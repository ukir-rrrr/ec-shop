<?php
session_start();

// セッションが開始されている場合にのみログイン状態を確認
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// POSTリクエストを確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // パスワードのハッシュ化（セキュリティ上の理由）
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // パスワードを確認してユーザーアカウントを削除

    $stmt = $pdo->prepare("DELETE FROM Users WHERE UserID = :user_id AND Password = :password");
    $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // ユーザーアカウントの削除に成功
        session_destroy(); // セッションを破棄してログアウト状態にする
        header("Location: deleted_successfully.php"); // 退会成功ページにリダイレクト
        exit;
    } else {
        // パスワードが一致しない場合
        header("Location: delete_account_confirm.php?error=password_mismatch");
        exit;
    }
} else {
    // 不正なアクセスを検知した場合
    header("Location: delete_account_confirm.php");
    exit;
}
?>
