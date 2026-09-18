<?php
/**
 * 生成 PHP 课程页的预览快照，并把示例源码填进代码框。
 *
 * 用法： php build.php
 *
 * 做两件事：
 *   1. 对 demos/php/ 下每个课程页（*.html），执行它 textarea 上 data-source 指向的示例源码，
 *      把真实输出包上统一的基础样式，写入 iframe 上 data-preview 指向的快照文件。
 *   2. 把示例源码转义后写进该页的 <textarea>，保证课程页显示的代码和 examples/ 里的文件一字不差。
 *
 * 改了 examples/ 里的示例后，重新跑一次本脚本即可。
 */

$dir = __DIR__;
$baseStyle = <<<CSS
body{margin:0;padding:16px 18px;background:#fff;color:#1f2430;
     font:16px/1.7 system-ui,"PingFang SC","Microsoft YaHei",sans-serif}
h2{font-size:19px;margin:0 0 10px}
h3{font-size:16px;margin:18px 0 8px}
p{margin:8px 0}
ul,ol{padding-left:22px;margin:8px 0}
li{margin:3px 0}
code{background:#eef2ff;color:#3730a3;padding:1px 5px;border-radius:4px;font-size:14px}
pre{background:#f1f5f9;border-radius:8px;padding:10px 12px;overflow:auto;font-size:13px;line-height:1.5}
.box{background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px}
label{display:inline-block;margin:4px 0}
input,select,button{font:inherit;padding:5px 9px;border-radius:6px}
input[type=text],input[type=password]{border:1px solid #cbd5e1}
button{background:#2563eb;color:#fff;border:0;cursor:pointer}
CSS;

/** 执行一个示例文件，返回它的输出（标准输出 + 标准错误）。 */
function runExample(string $file, array $get): string
{
    $runner = '$_GET = ' . var_export($get, true) . ';'
        . '$_SERVER["REQUEST_METHOD"] = "GET";'
        . '$_SERVER["SERVER_SOFTWARE"] = "nginx";'
        . '$_SERVER["SCRIPT_NAME"] = ' . var_export('/' . basename($file), true) . ';'
        . 'include ' . var_export($file, true) . ';';

    $cmd = [PHP_BINARY, '-d', 'display_errors=1', '-d', 'error_reporting=E_ALL', '-r', $runner];
    $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    if (!is_resource($proc)) {
        return "（无法执行 $file）";
    }
    $out = stream_get_contents($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($proc);

    return $err === '' ? $out : $out . "\n<pre style=\"background:#fee2e2\">" . htmlspecialchars(trim($err)) . '</pre>';
}

$pages = glob($dir . '/*.html');
sort($pages);

$count = 0;
foreach ($pages as $page) {
    $html = file_get_contents($page);
    if (!preg_match('/<textarea[^>]*data-source="([^"]+)"[^>]*>.*?<\/textarea>/s', $html, $m)) {
        continue;
    }
    $source = $m[1];
    $absSource = $dir . '/' . $source;
    if (!is_file($absSource)) {
        echo "跳过 " . basename($page) . "：找不到 $source\n";
        continue;
    }

    // 有些示例需要模拟 URL 参数（例如表单那一课）
    $get = [];
    if (preg_match('/data-query="([^"]*)"/', $html, $q) && $q[1] !== '') {
        parse_str(html_entity_decode($q[1], ENT_QUOTES), $get);
    }

    // 1) 生成快照
    preg_match('/data-preview="([^"]+)"/', $html, $p);
    $preview = $dir . '/' . $p[1];
    $output = runExample($absSource, $get);
    $doc = "<!DOCTYPE html>\n<html lang=\"zh-CN\">\n<head>\n<meta charset=\"UTF-8\">\n"
        . "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n"
        . "<title>预览 · " . basename($source) . "</title>\n<style>\n$baseStyle\n</style>\n"
        . "</head>\n<body>\n" . trim($output) . "\n</body>\n</html>\n";
    file_put_contents($preview, $doc);

    // 2) 把源码写进代码框
    $code = htmlspecialchars(rtrim(file_get_contents($absSource), "\n"), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $html = preg_replace(
        '/<textarea([^>]*data-source="' . preg_quote($source, '/') . '"[^>]*)>.*?<\/textarea>/s',
        '<textarea$1>' . $code . '</textarea>',
        $html,
        1
    );
    file_put_contents($page, $html);

    echo sprintf("%-22s -> %-26s (%d 字节输出)\n", basename($source), basename($p[1]), strlen(trim($output)));
    $count++;
}

echo "\n完成，共处理 $count 节课。\n";
