<?php
// 表单用 method="get" 提交时，数据会出现在 $_GET 里；method="post" 则用 $_POST。
// ?? 是「取不到就用后面的默认值」，避免直接访问不存在的键而报错。
$name = $_GET["name"] ?? "";

echo "<h3>一个最简单的表单</h3>";
?>
<form method="get">
    <label>你的名字：
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
    </label>
    <button type="submit">提交</button>
</form>
<?php
if ($name === "") {
    echo "<p>在上面填个名字，点「提交」，页面会带着 <code>?name=...</code> 重新加载一次。</p>";
} else {
    echo "<p>你好，<strong>" . htmlspecialchars($name) . "</strong>！"
       . "这句话就是读取 <code>\$_GET['name']</code> 之后输出的。</p>";
}
?>

<p class="box">把用户输入的内容打印到页面前，一定要先用 <code>htmlspecialchars()</code> 转义。
否则别人可以往输入框里填一段 &lt;script&gt; 之类的代码，让它在别人的浏览器里执行。</p>
