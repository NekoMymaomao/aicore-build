<?php
// include 会把另一个文件的内容「插」到这一行来执行
$config = include __DIR__ . "/parts/config.php";

echo "<h3>用 include 引入配置文件</h3>";
echo "<p>站点名：" . $config["site"] . "，作者：" . $config["author"] . "，建于 " . $config["since"] . " 年。</p>";
echo "<p>把头部、底部、配置这些到处都要用的部分拆成单独文件，再用 include 拼装，"
   . "就不用每个页面都复制一遍。</p>";

echo "<h3>超全局变量 \$_SERVER</h3>";
echo "<ul>";
echo "<li>PHP 版本：" . PHP_VERSION . "</li>";
echo "<li>当前脚本文件名：" . basename(__FILE__) . "</li>";
$method = $_SERVER["REQUEST_METHOD"] ?? "命令行";
echo "<li>请求方式：<code>$method</code></li>";
echo "<li>服务器软件：" . ($_SERVER["SERVER_SOFTWARE"] ?? "命令行环境") . "</li>";
echo "</ul>";

echo "<h3>还有几个常用的超全局变量</h3>";
echo "<ul>";
echo "<li><code>\$_GET</code> / <code>\$_POST</code>：表单和地址栏传过来的数据（见第 8 课）</li>";
echo "<li><code>\$_SERVER</code>：服务器和执行环境的信息</li>";
echo "<li><code>\$_COOKIE</code> / <code>\$_SESSION</code>：存在浏览器端 / 服务器端的用户状态</li>";
echo "</ul>";
