<?php
// class 定义一类东西的模板：它有哪些数据（属性）、能做哪些事（方法）
class Book
{
    public $title;
    public $author;
    public $price;

    // 构造函数：用 new 创建对象时会自动执行，常用来给属性赋初值
    public function __construct($title, $author, $price)
    {
        $this->title  = $title;
        $this->author = $author;
        $this->price  = $price;
    }

    // 方法：对象能做的事，用 $this 访问自己的属性
    public function info()
    {
        return "《" . $this->title . "》 " . $this->author . " 著，￥" . $this->price;
    }

    public function discounted($percent)
    {
        return $this->price * (1 - $percent / 100);
    }
}

// 用 new 创建对象（实例）
$book1 = new Book("PHP 入门", "张三", 59);
$book2 = new Book("HTML 基础", "李四", 39);

echo "<h3>创建了两个对象</h3>";
echo "<ul>";
echo "<li>" . $book1->info() . "</li>";
echo "<li>" . $book2->info() . "</li>";
echo "</ul>";

echo "<h3>调用对象的方法</h3>";
echo "<p>《" . $book1->title . "》打八折后是 " . $book1->discounted(20) . " 元。</p>";
echo "<p>《" . $book2->title . "》打八折后是 " . $book2->discounted(20) . " 元。</p>";

echo "<h3>两个对象互不影响</h3>";
$book2->price = 29;
echo "<p>把 book2 的价格改成 29，book1 仍然是 " . $book1->price . " 元。</p>";
