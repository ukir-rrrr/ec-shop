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

// 商品情報をメンズカテゴリから取得
$category_id = 1;
$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id = ?");
$stmt->execute([$category_id, $product_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<form action='product_edit_check.php' method='post' enctype='multipart/form-data'>";
echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
echo "<p>商品名：<input type='text' name='name' value='" . $row['name'] . "'></p>";
echo "<p>説明：<textarea name='description'>" . $row['description'] . "</textarea></p>";
echo "<p>価格：<input type='text' name='price' value='" . intval($row['price']) . "'></p>";
echo "<p>現在の画像：<img src='" . $row['image_path'] . "' alt='現在の画像'></p>";
echo "<p>新しい画像：<input type='file' name='new_image' accept='image/*'></p>";
echo "<p><input type='submit' value='修正'></p>";
echo "<a href='mens_list.php'>戻る</a>";

echo "</form>";
?>
