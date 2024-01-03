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

    // パスワードを確認してユーザーアカウントを削除
$stmt = $pdo->prepare("SELECT Password FROM Users WHERE UserID = :user_id");
$stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
$stmt->execute();

// パスワードの取得
$storedPassword = $stmt->fetchColumn();

// 入力されたパスワードとデータベースから取得したパスワードを照合
if (password_verify($_POST['password'], $storedPassword)) {
    // パスワードが一致する場合、ユーザーアカウントを削除
    $deleteStmt = $pdo->prepare("DELETE FROM Users WHERE UserID = :user_id");
    $deleteStmt->bindParam(':user_id', $userID, PDO::PARAM_INT);

    if ($deleteStmt->execute()) {
        // ユーザーアカウントの削除に成功
        session_destroy(); // セッションを破棄してログアウト状態にする
        header("Location: deleted_successfully.php"); // 退会成功ページにリダイレクト
        exit;
    } else {
        // データベースのエラー処理
        header("Location: delete_account_confirm.php?error=database_error");
        exit;
    }
    } else {
    // パスワードが一致しない場合
    header("Location: delete_account_confirm.php?error=password_mismatch");
    exit;
    }

  }
?>
