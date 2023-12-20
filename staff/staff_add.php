<?php
http://localhost/EC-shop/staff/staff_add.php
session_start();
session_regenerate_id(true);
if(isset($_SESSION["login"]) === false) {
   echo "ログインしていません。<br><br>";
   echo "<a href='staff_login.html'>ログイン画面へ</a>";
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
<title>スタッフ追加</title>
<link rel="stylesheet" href=" ">
</head>

<body>
<form action="staff_add_check.php" method="post">
スタッフ追加<br><br>
スタッフ名<br>
<input type="text" name="name">
<br><br>
パスワード<br>
<input type="password" name="pass">
<br><br>
パスワード再入力<br>
<input type="password" name="pass2">
<br><br>
<input type="button" onclick="history.back()" value="戻る">
<input type="submit" value="OK">
</form>

</body>
