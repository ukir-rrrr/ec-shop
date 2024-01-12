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
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スタッフ管理問い合わせ一覧</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <h2>問い合わせ一覧</h2>
    <?php
    // データベース接続
    require_once("../Databaseclass/Databaseclass.php");
    $pdo = connectToDatabase($host, $dbname, $username, $password);

    // 以下、inquiriesテーブルからデータを取得するクエリを実行
    $stmt = $pdo->query("SELECT * FROM inquiries");
    $inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>お名前</th>
                <th>メールアドレス</th>
                <th>お問い合わせ内容</th>
                <th>送信日時</th>
                <th>対応状況</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inquiries as $inquiry) : ?>
                <tr>
                    <td><?php echo $inquiry['id']; ?></td>
                    <td><?php echo $inquiry['name']; ?></td>
                    <td><?php echo $inquiry['email']; ?></td>
                    <td><?php echo $inquiry['message']; ?></td>
                    <td><?php echo $inquiry['created_at']; ?></td>
                    <td>
                        <form action="contact_status.php" method="post">
                            <input type="hidden" name="inquiry_id" value="<?php echo $inquiry['id']; ?>">
                            <label><input type="radio" name="status" value="対応済み" <?php echo (isset($inquiry['status']) && $inquiry['status'] === '対応済み') ? 'checked' : ''; ?>> 対応済み</label>
                            <label><input type="radio" name="status" value="未対応" <?php echo (isset($inquiry['status']) && $inquiry['status'] === '未対応') ? 'checked' : ''; ?>> 未対応</label>
                            <button type="submit">更新</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>
<br><br>
<a href="./staff_login_top.php">管理画面TOPへ</a>
