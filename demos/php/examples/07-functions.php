<?php
// function 定义函数：给它参数，它用 return 交回结果
function greet($name, $greeting = "你好")
{
    // 变量后面紧跟着中文标点，要用 {$变量} 包起来，否则标点会被当成变量名的一部分
    return "{$greeting}，{$name}！";
}

// 调用函数
echo "<h3>自定义函数</h3>";
echo "<p>" . greet("小明") . "</p>";
echo "<p>" . greet("小红", "早上好") . "</p>";

// 参数可以参与运算
function add($a, $b)
{
    return $a + $b;
}

echo "<p>add(3, 5) = <strong>" . add(3, 5) . "</strong></p>";

// 函数内部定义变量不会影响外面，这叫作用域
$counter = 100;
function tryToChange()
{
    $counter = 0;   // 这个 $counter 只活在函数里面
    return "函数里的 \$counter = {$counter}";
}
echo "<p>" . tryToChange() . "，外面的 \$counter 仍然是 {$counter}。</p>";

echo "<h3>内置函数也是同样的用法</h3>";
$nums = [4, 9, 16, 25];
echo "<ul>";
echo "<li>最大值 max：" . max($nums) . "</li>";
echo "<li>最小值 min：" . min($nums) . "</li>";
echo "<li>求和 array_sum：" . array_sum($nums) . "</li>";
echo "<li>平方根 sqrt(81)：" . sqrt(81) . "</li>";
echo "<li>四舍五入 round(3.14159, 2)：" . round(3.14159, 2) . "</li>";
echo "</ul>";
