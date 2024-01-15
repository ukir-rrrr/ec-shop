<?php
session_start();

// セッションが開始されている場合にのみログイン状態を確認
if (!isset($_SESSION['user_id'])) {
    header("Location: ./user/login.html");
    exit;
}

// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

// ユーザーIDをセッションから取得
$userID = $_SESSION['user_id'];

// データベースからユーザー情報を取得
$stmt = $pdo->prepare("SELECT * FROM Users WHERE UserID = :user_id");
$stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ファッションECサイト - マイページ</title>
    <link rel="stylesheet" href="../style/user.css">
</head>
<body>
    <header>
        <nav>
        </nav>
    </header>

    <div class="main-content">
        <h1>マイページ</h1>
        <p>ユーザー名: <?php echo htmlspecialchars($user['username']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user['Email']); ?></p>
        <p>住所: <?php echo htmlspecialchars($user['Address']); ?></p>
        <!-- ユーザー情報 -->

        <p><a href="edit_profile.php">プロフィールを編集する</a></p>
        <p><a href="delete_account_confirm.php">退会する</a></p>
        <p><a href="../index.php">ホームへ戻る</a></p>
    </div>
</body>
</html>
