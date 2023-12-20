<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // ログインしていない場合はログインページにリダイレクト
    header("Location: login.html");
    exit;
}

// ログアウト確認ページの表示
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログアウト確認</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <div class="confirmation-container">
        <h2><?php echo $_SESSION['user_name']; ?>様、ログアウトしますか？</h2>
        <form action="logout.php" method="post">
            <button type="submit">ログアウトする</button>
        </form>
        <a href="../index.php">戻る</a>
    </div>
</body>
</html>
