<?php
try {

require_once("../Databaseclass/Databaseclass.php");

$post = sanitize($_POST);
$code = $post["code"];
$pass = $post["pass"];

$pass = md5($pass);

$dsn = "mysql:host=localhost;dbname=ecshop_db;charset=utf8";
$user = "ecshop_user";
$password = "ecshop_pass";
$dbh = new PDO($dsn, $user, $password);
$dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT name FROM staff WHERE code=? AND password=?";
$stmt = $dbh -> prepare($sql);
$data[] = $code;
$data[] = $pass;
$stmt -> execute($data);

$dbh = null;

$rec = $stmt -> fetch(PDO::FETCH_ASSOC);

if(empty($rec["name"]) === true) {
    echo "入力が間違っています。<br><br>";
    echo "<a href='staff_login.html'>戻る</a>";
    exit();
} else {
    session_start();
    $_SESSION["login"] = 1;
    $_SESSION["name"] = $rec["name"];
    $_SESSION["code"] = $code;
    header("Location:staff_login_top.php");
    exit();
}
}
catch(Exception $e) {
    echo "只今障害が発生しております。<br><br>";
    echo "<a href='staff_login.html'>戻る</a>";
}
?>
