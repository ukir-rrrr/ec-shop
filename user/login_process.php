<!-- http://localhost/EC-shop/user/login_process.php -->
<?php
session_start();

// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);


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



 var_dump($user);

if ($user) {
    // ユーザーが見つかった場合

    // ステータスがactiveであるか確認
    if ($user['status'] === 'active') {
        // パスワードを確認（password_verifyでハッシュ化されたパスワードを照合）
        if (password_verify($password, $user['Password'])) {
            // ログイン成功
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['email'] = $user['Email'];
            $_SESSION['user_name'] = $user['username'];
            header("Location: ../index.php"); // ログイン後のページにリダイレクト
        } else {
            // ログイン失敗
            header("Location: login.php?error=invalid_credentials");
        }
    } else {
        // ステータスがinactiveの場合
        header("Location: login.php?error=user_not_found");
    }
} else {
    // ユーザーが見つからない
    header("Location: login.php?error=user_not_found");
}
?>
