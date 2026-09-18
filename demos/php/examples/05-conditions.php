<?php
$score = 86;

echo "<h3>按分数判断等级</h3>";
echo "<p>分数：<strong>$score</strong></p>";

if ($score >= 90) {
    echo '<p style="color:#15803d">等级：优秀</p>';
} elseif ($score >= 60) {
    echo '<p style="color:#a16207">等级：及格</p>';
} else {
    echo '<p style="color:#b91c1c">等级：需要努力</p>';
}

// 逻辑运算符：&&（并且）、||（或者）、!（取反）
$age = 20;
$hasTicket = true;

echo "<h3>两个条件一起判断</h3>";
if ($age >= 18 && $hasTicket) {
    echo "<p>年满 18 岁，并且有票，可以入场。</p>";
} else {
    echo "<p>条件不满足，不能入场。</p>";
}

// 三元运算符：条件 ? 真时的值 : 假时的值
$label = $hasTicket ? "有票" : "没票";
echo "<p>三元运算符的结果：<strong>$label</strong></p>";

// 严格比较 === 除了值，还要求类型相同
echo "<h3>== 和 === 的区别</h3>";
echo "<p>0 == \"0\" ：" . var_export(0 == "0", true) . "（只比较值）</p>";
echo "<p>0 === \"0\" ：" . var_export(0 === "0", true) . "（值和类型都要相同）</p>";
