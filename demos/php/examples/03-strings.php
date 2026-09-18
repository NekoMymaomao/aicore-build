<?php
// 点号 . 用来把字符串拼起来
$first = "Hello";
$last  = "World";

echo "<h3>" . $first . ", " . $last . "!</h3>";

// 双引号里的变量会被替换成它的值，单引号不会
$lang = "PHP";
echo '<p>单引号：$lang 就是四个字母加一个符号。</p>';
echo "<p>双引号：$lang 会被替换成 PHP。</p>";

$raw = "  hello php  ";

echo "<h3>常用的字符串函数</h3>";
echo "<ul>";
echo "<li>原字符串 <code>" . $raw . "</code>，长度 " . strlen($raw) . "</li>";
echo "<li>trim 去掉首尾空格后长度 " . strlen(trim($raw)) . "，内容 <code>" . trim($raw) . "</code></li>";
echo "<li>strtoupper 转成大写：<code>" . strtoupper(trim($raw)) . "</code></li>";
echo "<li>ucfirst 首字母大写：<code>" . ucfirst(trim($raw)) . "</code></li>";
echo "<li>str_replace 替换：<code>" . str_replace("php", "PHP", trim($raw)) . "</code></li>";
echo "<li>str_repeat 重复三遍：<code>" . str_repeat("ab", 3) . "</code></li>";
echo "</ul>";
