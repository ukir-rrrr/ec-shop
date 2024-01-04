<?php
// http://localhost/EC-shop/products/product_add.php

session_start();
session_regenerate_id(true);
if(isset($_SESSION["login"]) === false) {
    echo "ログインしていません。<br><br>";
    echo "<a href='../staff/staff_login.php'>ログイン画面へ</a>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>商品追加</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>商品追加</h1>

<?php

// データベース接続
require_once("../Databaseclass/Databaseclass.php"); // データベース接続関数を含むファイルのパスを指定
$pdo = connectToDatabase($host, $dbname, $username, $password);


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // フォームが送信された場合の処理

    // フォームから送信された商品情報を取得
    // フォームから送信された商品情報を取得
    $category_id = isset($_POST["category_id"]) ? htmlspecialchars($_POST["category_id"]) : null;
    $name = isset($_POST["name"]) ? htmlspecialchars($_POST["name"]) : null;
    $description = isset($_POST["description"]) ? htmlspecialchars($_POST["description"]) : null;
    $price = isset($_POST["price"]) ? htmlspecialchars($_POST["price"]) : null;
    $stock = isset($_POST["stock"]) ? htmlspecialchars($_POST["stock"]) : null;

    // 画像アップロード
    $image_path = ''; // 画像の保存先の初期化

    // ディレクトリが存在しない場合は作成
    $uploadDirectory = '../main-image/';
    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // アップロードされたファイルのファイル名を生成
        $uploadedFileName = basename($_FILES['image']['name']);

        // 画像の保存先のファイルパス
        $image_path = $uploadDirectory . $uploadedFileName;

        // ファイルを指定ディレクトリに移動
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    // データベースに商品情報を追加するクエリを実行
    $stmt = $pdo->prepare("INSERT INTO products (category_id, name, description, price, stock, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$category_id, $name, $description, $price, $stock, $image_path]);

    echo "商品を追加しました。<br>";
    echo "<a href='../staff/staff_login_top.php'>管理画面TOPへ</a>";
} else {
    // フォームがまだ送信されていない場合、フォームを表示
    ?>

    <form action="product_add.php" method="post" enctype="multipart/form-data">
        カテゴリID: <input type="text" name="category_id" required><br>
        商品名: <input type="text" name="name" required><br>
        説明: <textarea name="description"></textarea><br>
        価格: <input type="text" name="price" required><br>
        在庫数: <input type="text" name="stock" required><br>
        画像アップロード: <input type="file" name="image" accept="image/*" required><br>
        <input type="submit" value="商品追加">
    </form>

    <?php
}
?>

</body>
</html>
