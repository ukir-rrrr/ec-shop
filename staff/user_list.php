<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION["login"]) === false) {
    echo "ログインしていません。<br><br>";
    echo "<a href='staff_login.php'>ログイン画面へ</a>";
    exit();
} else {
    echo $_SESSION["name"]."さんログイン中";
}

// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

// // ユーザー情報を取得
// $stmt = $pdo->query("SELECT * FROM Users");
// $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ユーザー情報を取得
$stmt = $pdo->query("SELECT UserID, username, Email, Address, Phone, Last_name, First_name, Last_name_kana, First_name_kana, Zipcode, status FROM Users"); 
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);


// CSV出力
if (isset($_POST['export_csv'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="user_list.csv"');

    $output = fopen('php://output', 'w');

    // ヘッダーを書き込む
    fputcsv($output, array_keys($users[0]));

    // データを書き込む
    foreach ($users as $user) {
        fputcsv($output, $user);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー一覧</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <h2>ユーザー一覧</h2>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>ﾕｰｻﾞｰﾈｰﾑ</th>
                <th>ﾒｰﾙ</th>
                <th>住所</th>
                <th>電話番号</th>
                <th>氏</th>
                <th>名</th>
                <th>(氏)ヨミ</th>
                <th>(名)ヨミ</th>
                <th>郵便番号</th>
                <th>状態</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['UserID']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['Email']; ?></td>
                    <td><?php echo $user['Address']; ?></td>
                    <td><?php echo $user['Phone']; ?></td>
                    <td><?php echo $user['Last_name']; ?></td>
                    <td><?php echo $user['First_name']; ?></td>
                    <td><?php echo $user['Last_name_kana']; ?></td>
                    <td><?php echo $user['First_name_kana']; ?></td>
                    <td><?php echo $user['Zipcode']; ?></td>
                    <td><?php echo $user['status']; ?></td>
                    <td>
                    <form action="confirm_deactivate_user.php"  method="post">
                        <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>">
                        <input type="hidden" name="username" value="<?php echo $user['username']; ?>">
                        <button type="submit">退会</button>
                    </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>
<br>
<!-- CSV出力ボタン -->
<form action="" method="post">
    <input type="submit" name="export_csv" value="CSV出力">
</form>

<br>
<a href="./staff_login_top.php">管理画面TOPへ</a>
