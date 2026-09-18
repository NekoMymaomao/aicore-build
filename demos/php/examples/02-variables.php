<?php
// 变量以 $ 开头，不用提前声明类型，赋什么值就是什么类型
$name      = "小明";   // 字符串
$age       = 18;       // 整数
$height    = 1.75;     // 浮点数
$isStudent = true;     // 布尔值

echo "<h3>var_dump 能看到变量的类型和值</h3>";
echo "<pre>";
var_dump($name);
var_dump($age);
var_dump($height);
var_dump($isStudent);
echo "</pre>";

echo "<h3>变量可以直接写进双引号字符串里</h3>";
echo "<p>$name 今年 $age 岁，身高 $height 米。</p>";
echo "<p>是否学生：" . ($isStudent ? "是" : "否") . "</p>";
