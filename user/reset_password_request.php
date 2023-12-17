<?php
// データベース接続設定
$host = 'localhost';
$dbname = 'your_database';
$user = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('データベースに接続できませんでした。');
}

// POSTデータからメールアドレスを取得
$email = isset($_POST['email']) ? $_POST['email'] : '';

// メールアドレスの存在確認
$stmt = $pdo->prepare('SELECT * FROM Users WHERE Email = :email');
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch();

if (!$user) {
    exit('このメールアドレスは登録されていません。');
}

// トークンの生成と保存
$token = bin2hex(random_bytes(32));

// トークンをデータベースに保存
$updateTokenStmt = $pdo->prepare('UPDATE Users SET ResetToken = :token, ResetTokenExpire = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE Email = :email');
$updateTokenStmt->bindParam(':token', $token, PDO::PARAM_STR);
$updateTokenStmt->bindParam(':email', $email, PDO::PARAM_STR);
$updateTokenStmt->execute();

// メールの送信
$to = $email;
$subject = 'パスワードリセットリクエスト';
$message = "以下のリンクをクリックしてパスワードをリセットしてください。\n\n";
$message .= "リセットリンク: http://yourdomain.com/reset_password.php?token=$token";
$headers = 'From: webmaster@yourdomain.com';

// メール送信
$mailSuccess = mail($to, $subject, $message, $headers);

if (!$mailSuccess) {
    exit('メールの送信に失敗しました。');
}

echo 'パスワードリセットリンクが送信されました。メールを確認してください。';
?>
