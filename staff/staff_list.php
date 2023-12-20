<?php

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
<title>スタッフ一覧</title>
<link rel="stylesheet" href="../style.css">
</head>

<body>

<?php
try{

$dsn = "mysql:host=localhost;dbname=ecshop_db;charset=utf8";
$user = "ecshop_user";
$password = "ecshop_pass";
$dbh = new PDO($dsn, $user, $password);
$dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT code,name FROM staff WHERE1";
$stmt = $dbh -> prepare($sql);
$stmt -> execute();

$dbh = null;

echo "スタッフ一覧<br><br>";
echo "<form action='staff_branch.php' method='post'>";

while(true) {
    $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
    if($rec === false) {
        break;
    }
    echo "<input type='radio' name='code' value='".$rec['code']."'>";
    echo $rec["name"];
    echo "<br>";
}
echo "<br>";
echo "<input type='submit' name='disp' value='詳細'>";
echo "<input type='submit' name='add' value='追加'>";
echo "<input type='submit' name='edit' value='修正'>";
echo "<input type='submit' name='delete' value='削除'>";
}
catch(Exception $e) {
    echo "只今障害が発生しております。<br><br>";
    echo "<a href='./staff_login.html'>ログイン画面へ</a>";
}
?>
<br><br>
<a href="./staff_login_top.php">管理画面TOPへ</a>

</body>
</echo
