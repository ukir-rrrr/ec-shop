<?php
session_start();

// セッションが開始されている場合にのみログイン状態を確認
if (!isset($_SESSION['user_id'])) {
    header("Location: ./user/login.html");
    exit;
}

// ユーザーIDをセッションから取得
$userID = $_SESSION['user_id'];

// データベース接続はmypage.phpと同じく
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
    <title>ファッションECサイト - プロフィール編集</title>
    <link rel="stylesheet" href="../style/user.css">
</head>
<body>
    <!-- ヘッダー部分はmypage.phpと同じ -->

    <div class="main-content">
        <h1>プロフィール編集</h1>
        <form action="update_profile.php" method="post">
            <label for="username">ユーザー名:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>

            <label for="address">住所:</label>
            <textarea id="address" name="address"><?php echo htmlspecialchars($user['Address']); ?></textarea>

            <!-- 他のユーザー情報の編集フォームも追加する -->

            <input type="submit" value="保存">
        </form>
    </div>
</body>
</html>
