<?php

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
<title>スタッフ追加実効</title>
<link rel="stylesheet" href="../style.css">
</head>

<body>

<?php
    try{

require_once("../Databaseclass/Databaseclass.php");

$post = sanitize($_POST);
$name = $post["name"];
$pass = $post["pass"];

$dsn = "mysql:host=localhost;dbname=ecshop_db;charset=utf8";
$user = "ecshop_user";
$password = "ecshop_pass";
$dbh = new PDO($dsn, $user, $password);
$dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "INSERT INTO staff(name, password) VALUES(?,?)";
$stmt = $dbh -> prepare($sql);
$data[] = $name;
$data[] = $pass;
$stmt -> execute($data);

$dbh = null;

echo "スタッフを追加しました。<br><br>";
echo "<a href='staff_list.php'>スタッフ一覧へ</a>";

}
catch(Exception $e) {
    echo "エラーが発生しました。詳細情報: " . $e->getMessage() . "<br>";
    echo "ファイル: " . $e->getFile() . "<br>";
    echo "行数: " . $e->getLine() . "<br>";
    echo "スタックトレース: <pre>" . $e->getTraceAsString() . "</pre><br>";
    echo "<a href='../staff_login/staff_login.html'>ログイン画面へ</a>";
    echo "只今障害が発生しております。<br><br>";
    echo "<a href='../staff_login/staff_login.html'>ログイン画面へ</a>";
}
?>

</body>
</html>
