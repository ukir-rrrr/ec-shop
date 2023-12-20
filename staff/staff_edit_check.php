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
<title>スタッフ修正チェック</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php

require_once("../Databaseclass/Databaseclass.php");

$post = sanitize($_POST);
$code = $post["code"];
$name = $post["name"];
$pass = $post["pass"];
$pass2 = $post["pass2"];

echo "スタッフコード<br>";
echo $code;
echo " の情報を修正します。";
echo "<br><br>";

if(empty($name) === true) {
    echo "名前が入力されていません。<br><br>";
} else {
    echo "スタッフ名:";
    echo $name;
    echo "<br><br>";
}

if(empty($pass) === true) {
    echo "パスワードが入力されていません。<br><br>";
}

if($pass != $pass2) {
    echo "パスワードが異なります。<br><br>";
}

if(empty($name) or empty($pass) or $pass != $pass2) {
    echo "<form>";
    echo "<input type='button' onclick='history.back()' value='戻る'>";
    echo "</form>";
} else {
    $pass = md5($pass);
    echo "上記の通り修正しますか？<br><br>";
    echo "<form action='staff_edit_done.php' method='post'>";
    echo "<input type='hidden' name='name' value='".$name."'>";
    echo "<input type='hidden' name='pass' value='".$pass."'>";
    echo "<input type='hidden' name='code' value='".$code."'>";
    echo "<input type='button' onclick='history.back()' value='戻る'>";
    echo "<input type='submit' value='OK'>";
    echo "</form>";
}
?>
</body>
</html>
