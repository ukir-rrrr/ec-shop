<?php
session_start();

// セッションが開始されている場合にのみログイン状態を確認
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ファッションECサイト - 退会確認</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <!-- ヘッダー部分はmypage.phpと同じ -->

    <div class="main-content">
        <h1>本当に退会しますか？</h1>
        <p>アカウントを削除すると、全ての情報が失われます。この操作は取り消せません。</p>
        <form action="delete_account.php" method="post">
            <label for="password">パスワード:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="退会する">
        </form>
    </div>
</body>
</html>
