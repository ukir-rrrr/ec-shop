<?php
session_start();

// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

// 月の選択肢が選択された場合
if (isset($_POST['selected_month'])) {
    $selectedMonth = $_POST['selected_month'];

    // 選択された月のデータを取得するSQLクエリ
    $stmt = $pdo->prepare("SELECT * FROM Sales WHERE MONTH(OrderDate) = ?");
    $stmt->execute([$selectedMonth]);

    // 取得したデータを表示
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // 月が選択されていない場合は全データを表示
    $stmt = $pdo->query("SELECT * FROM Sales");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 月の選択肢を表示
$months = range(1, 12);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>売上管理</title>
</head>
<body>

<h1>売上管理</h1>

<!-- 月の選択肢 -->
<form action="" method="post">
    <label for="selected_month">月を選択:</label>
    <select name="selected_month" id="selected_month">
        <option value="" selected>全ての月</option>
        <?php foreach ($months as $month): ?>
            <option value="<?= $month ?>"><?= $month ?>月</option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="表示">
</form>

<!-- データを表示するテーブル -->
<table border="1">
    <thead>
        <tr>
            <th>注文ID</th>
            <th>ユーザID</th>
            <th>注文日時</th>
            <th>合計金額</th>
            <th>姓</th>
            <th>名</th>
            <th>郵便番号</th>
            <th>住所</th>
            <th>支払方法</th>
            <th>商品ID</th>
            <th>数量</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= $row['OrderID'] ?></td>
                <td><?= $row['UserID'] ?></td>
                <td><?= $row['OrderDate'] ?></td>
                <td><?= $row['TotalAmount'] ?></td>
                <td><?= $row['Last_name'] ?></td>
                <td><?= $row['First_name'] ?></td>
                <td><?= $row['Zipcode'] ?></td>
                <td><?= $row['Address'] ?></td>
                <td><?= $row['PaymentMethod'] ?></td>
                <td><?= $row['ProductID'] ?></td>
                <td><?= $row['Quantity'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>
<a href="staff_login_top.php">管理画面TOPへ</a>

</body>
</html>
