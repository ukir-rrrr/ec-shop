<?php
// http://localhost/EC-shop/products/product_top.php
session_start();
session_regenerate_id(true);
if(isset($_SESSION["login"]) === false) {
    echo "ログインしていません。<br><br>";
    echo "<a href='../staff/staff_login.php'>ログイン画面へ</a>";
    exit();
} else {
    echo $_SESSION["name"]."さんログイン中";
    echo "<br><br>";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>商品管理TOP</title>
<link rel="stylesheet" href="../style.css">
</head>

<body>

商品管理TOP<br><br>
    <a href="mens_list.php">メンズ</a>
    <br><br>
    <a href="womens.php">ウィメンズ</a>
    <br><br>
    <a href="kids.php">キッズ</a>
    <br><br>
    <a href="product_add.php">商品追加</a>
    <br><br>
    <a href="../staff/staff_login_top.php">管理画面TOPへ</a>
    <br><br>
    <a href="../staff/staff_logout.php">ログアウト</a>
</body>
</html>
