<?php
session_start();

// セッションが開始されていない場合はログインページにリダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
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

// POSTリクエストを確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ユーザーIDをセッションから取得
    $userID = $_SESSION['user_id'];

    // フォームから送信されたデータを取得
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // データベースを更新
    $stmt = $pdo->prepare("UPDATE Users SET Username = :username, Email = :email, Address = :address WHERE UserID = :user_id");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // 更新成功の処理（例: マイページにリダイレクト）
        header("Location: mypage.php");
        exit;
    } else {
        // 更新失敗の処理
        // ここにエラー処理を追加してください
    }
} else {
    // 不正なアクセスを検知した場合
    header("Location: mypage.php");
    exit;
}
?>
