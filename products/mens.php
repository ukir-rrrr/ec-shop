<?php
// http://localhost/EC-shop/products/mens.php
session_start();
// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

// 商品情報をメンズカテゴリから取得
$category_id = 1;
$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ?");
$stmt->execute([$category_id]);

// 商品情報を表示
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<div class='product'>";
    echo "<img src='" . $row['image_path'] . "' alt='" . $row['name'] . "'>";
    echo "<h3>" . $row['name'] . "</h3>";
    echo "<p>価格: " . intval($row['price']) . "円</p>";
    echo "<p>詳細: " . $row['description'] . "</p>";

    // カートに追加ボタン
    echo "<form action='../cart/add_to_cart.php' method='post'>";
    echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
    echo "<input type='submit' value='カートに追加'>";
    echo "</form>";
    echo "</div>";
}
?>
