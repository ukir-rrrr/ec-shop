<?php

session_start();
session_regenerate_id(true);
if(isset($_SESSION["login"]) === false) {
    echo "ログインしていません。<br><br>";
    echo "<a href='staff_login.html'>ログイン画面へ</a>";
    exit();
} else {
    echo $_SESSION["name"]."さんログイン中";
    echo "<br><br>";
}

// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

// 選択された商品の ID を取得
$product_id = isset($_GET["code"]) ? $_GET["code"] : null;

// 商品情報をレディスカテゴリから取得
$category_id = 2;
$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id = ?");
$stmt->execute([$category_id, $product_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo "商品詳細<br><br>";
echo "商品コード : " . $row['id'];
echo "<br><br>";
echo "商品名 : " . $row['name'];
echo "<br><br>";
echo "説明 : ". $row['description'];
echo "<br><br>";
echo "価格 : " . intval($row['price']) . "円";
echo "<br><br>";
echo "現在の画像 : <img src='" . $row['image_path'] . "' alt='現在の画像'>";
echo "<br><br>";
echo "上記商品を削除しますか?";
echo "<form action='product_delete_done.php' method='post'>";
echo "<input type='hidden' name='code' value='" . $row['id'] . "'>";
echo "<input type='hidden' name='gazou' value='" . $row['image_path'] . "'>";
echo "<input type='button' onclick='history.back()' value='戻る'>";
echo "<input type='submit' value='OK'>";
echo "</form>";
