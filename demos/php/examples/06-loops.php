<?php
echo "<h3>for：已知要循环几次</h3>";
echo "<ul>";
for ($i = 1; $i <= 5; $i++) {
    echo "<li>第 $i 次循环</li>";
}
echo "</ul>";

echo "<h3>while：条件成立就一直循环</h3>";
$countdown = 3;
echo "<ul>";
while ($countdown > 0) {
    echo "<li>倒计时 $countdown</li>";
    $countdown--;
}
echo "</ul>";

echo "<h3>foreach：专门用来遍历数组</h3>";
$scores = [
    "语文" => 92,
    "数学" => 88,
    "英语" => 95,
];
echo "<ul>";
foreach ($scores as $subject => $score) {
    // 变量后面紧跟着中文时要写成 {$变量}，否则中文会被当成变量名的一部分
    echo "<li>{$subject}：{$score} 分</li>";
}
echo "</ul>";

echo "<h3>break 提前跳出循环</h3>";
echo "<ul>";
for ($i = 1; $i <= 10; $i++) {
    if ($i > 3) {
        break;   // 到 4 就跳出整个循环
    }
    echo "<li>$i</li>";
}
echo "</ul>";

echo "<h3>continue 跳过本次</h3>";
echo "<ul>";
for ($i = 1; $i <= 5; $i++) {
    if ($i === 3) {
        continue;   // 跳过 3，继续后面的
    }
    echo "<li>$i</li>";
}
echo "</ul>";
