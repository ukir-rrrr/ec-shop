<?php
session_start();

// セッションが開始されている場合にのみログイン状態を確認
if (!isset($_SESSION['user_id'])) {
    header("Location: ./user/login.html");
    exit;
}

// データベース接続設定
$host = 'localhost';
$dbUser = 'ecshop_user';
$dbPass = 'ecshop_pass';
$dbName = 'ecshop_db';
$charset = 'utf8mb4';

// DSN（Data Source Name）の設定
$dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    // PDOインスタンスの作成
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

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
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <header>
        <nav>
            <!-- ナビゲーションメニュー -->
            <!-- ... -->
        </nav>
    </header>

    <div class="main-content">
        <h1>マイページ</h1>
        <p>ユーザー名: <?php echo htmlspecialchars($user['username']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user['Email']); ?></p>
        <p>住所: <?php echo htmlspecialchars($user['Address']); ?></p>
        <!-- 他のユーザー情報も表示する -->

        <p><a href="edit_profile.php">プロフィールを編集する</a></p>
        <p><a href="delete_account_confirm.php">退会する</a></p>
        <p><a href="../index.php">ホームへ戻る</a></p>
    </div>
</body>
</html>
