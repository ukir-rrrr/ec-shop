<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION["login"]) === false) {
    echo "ログインしていません。<br><br>";
    echo "<a href='staff_login.php'>ログイン画面へ</a>";
    exit();
} else {
    echo $_SESSION["name"]."さんログイン中";
    echo "<br><br>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ユーザーIDとユーザーネームを取得
    $user_id = $_POST['user_id'];
    $user_name = $_POST['username'];
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー退会確認</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <h2>ユーザー退会確認</h2>
    <p>ユーザーID: <?php echo $user_id; ?></p>
    <p>ユーザーネーム: <?php echo $user_name; ?></p>
    <p>退会処理をします。よろしいですか？</p>

    <form action="deactivate_user.php" method="post">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <input type="hidden" name="user_name" value="<?php echo $user_name; ?>">
        <button type="submit">退会</button>
    </form>
</body>

</html>
<a href="./staff_login_top.php">管理画面TOPへ</a>
