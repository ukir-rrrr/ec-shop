<?php
session_start();

// データベース接続設定
$dbHost = 'localhost';
$dbUser = 'ecshop_user';
$dbPass = 'ecshop_pass';
$dbName = 'ecshop_db';
$charset = 'utf8mb4';

// DSN（Data Source Name）の設定
$dsn = "mysql:host=$dbHost;dbname=$dbName;charset=$charset";

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

// ユーザー入力を取得
$email = $_POST['email'];
$password = $_POST['password'];

// SQLインジェクションを防ぐためにプリペアドステートメントを使用
$stmt = $pdo->prepare("SELECT * FROM Users WHERE Email = :email");

// パラメータをバインド
$stmt->bindParam(':email', $email, PDO::PARAM_STR);

// クエリ実行
$stmt->execute();

// 結果を取得
$user = $stmt->fetch();

if ($user) {
    // パスワードを確認（password_verifyでハッシュ化されたパスワードを照合）
    if (password_verify($password, $user['password'])) {
        // ログイン成功
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        header("Location: ../index.php"); // ログイン後のページにリダイレクト
    } else {
        // ログイン失敗
        header("Location: login.html?error=invalid_credentials");
    }
} else {
    // ユーザーが見つからない
    header("Location: login.html?error=user_not_found");
}
?>
