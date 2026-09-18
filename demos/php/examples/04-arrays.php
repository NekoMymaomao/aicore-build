<?php
// 索引数组：键是从 0 开始的数字
$fruits = ["苹果", "香蕉", "橙子"];

echo "<h3>索引数组</h3>";
echo "<p>第 2 个元素是 <strong>" . $fruits[1] . "</strong>，一共 " . count($fruits) . " 个元素。</p>";
echo "<ul>";
foreach ($fruits as $fruit) {
    echo "<li>$fruit</li>";
}
echo "</ul>";

// 关联数组：键是自己起的名字
$user = [
    "name" => "小明",
    "age"  => 18,
    "city" => "杭州",
];

echo "<h3>关联数组</h3>";
echo "<p>用键取值：" . $user["name"] . "，来自" . $user["city"] . "，今年 " . $user["age"] . " 岁。</p>";
echo "<ul>";
foreach ($user as $key => $value) {
    echo "<li><code>$key</code> => $value</li>";
}
echo "</ul>";

// 往数组里追加
$fruits[] = "葡萄";
echo "<h3>追加一个元素</h3>";
echo "<p>现在是：" . implode("、", $fruits) . "</p>";
