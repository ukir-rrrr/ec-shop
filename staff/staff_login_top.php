<?php
// http://localhost/EC-shop/staff/staff_login_top.php
session_start();
session_regenerate_id(true);
if(isset($_SESSION["login"]) === false) {
    echo "ログインしていません。<br><br>";
    echo "<a href='staff_login.php'>ログイン画面へ</a>";
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
<title>管理画面TOP</title>
<link rel="stylesheet" href="../style.css">
</head>

<body>

管理画面TOP<br><br>
    <a href="staff_list.php">スタッフ管理</a>
    <br><br>
    <a href="../products/product_top.php">商品管理</a>
    <br><br>
    <a href="user_top.php">ユーザー管理</a>
    <br><br>
    <a href="XXX_top.php">売上管理</a>
    <br><br>
    <!-- <a href="staff_contact.php">問い合わせ管理</a>
    <br><br> -->
    <a href="staff_logout.php">ログアウト</a>
</body>
</html>
