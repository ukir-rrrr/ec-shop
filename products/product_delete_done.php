<?php

session_start();
session_regenerate_id(true);

if (isset($_SESSION["login"]) === false) {
    echo "ログインしていません。<br><br>";
    echo "<a href='../staff/staff_login.php'>ログイン画面へ</a>";
    exit();
} else {
    echo $_SESSION["name"]."さんログイン中";
    echo "<br><br>";
}

require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = isset($_POST["code"]) ? $_POST["code"] : null;
    $image_path = isset($_POST["gazou"]) ? $_POST["gazou"] : null;

    // 商品を削除する処理
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);

    // 画像も削除（削除する必要がある場合）
    if (!empty($image_path) && file_exists($image_path)) {
        unlink($image_path);
    }

    echo "商品を削除しました。<br><br>";
    echo "<a href='mens_list.php'>商品一覧へ</a>";
} else {
    echo "不正なアクセスです。<br><br>";
}

?>
