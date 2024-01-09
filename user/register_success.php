<?php

// register_success.php

// セッションを開始
session_start();

// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

// ログイン状態をセット
$_SESSION['user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$_SESSION['user_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>アカウント作成成功 - ECサイト</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="register-container">
        <h2>アカウント作成が完了しました</h2>
        <a href="../index.php" class="btn">ホームへ</a>
    </div>
</body>
</html>
