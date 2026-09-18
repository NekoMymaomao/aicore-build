<?php
// 最简单的 PHP 输出：echo 一段文字
echo "<h2>你好，PHP！</h2>";
?>

<p>这一行是 PHP 标签外面的普通 HTML，服务器会原样发给浏览器。</p>

<?php
// 再回到 PHP 里
echo "<p>这一行是 echo 输出的。</p>";
?>

<p>两段内容最终会拼成一个完整的页面 —— 浏览器看不到 PHP 代码，只见到了它生成的结果。
这就是 PHP 和 HTML 最本质的区别：<strong>HTML 直接发给浏览器，PHP 先在服务器上执行一遍</strong>。</p>
