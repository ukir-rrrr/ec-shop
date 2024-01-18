<?php
session_start();
session_regenerate_id(true);

// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

// 商品情報を取得
$stmt = $pdo->query("SELECT id, category_id, name, price, stock, created_at FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// CSV出力
if (isset($_POST['export_csv'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="product_list.csv"');

    $output = fopen('php://output', 'w');

    // ヘッダーを書き込む
    fputcsv($output, array_keys($products[0]));

    // データを書き込む
    foreach ($products as $product) {
        fputcsv($output, $product);
    }

    fclose($output);
    exit();
}
?>
