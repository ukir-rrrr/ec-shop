<?php
session_start();

// ログアウト後の完了ページの表示
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログアウト完了</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <div class="completion-container">
        <h2>ログアウトしました</h2>
        <form action="../index.php" method="post">
            <button type="submit">ホームへ戻る</button>
        </form>
    </div>
</body>
</html>
