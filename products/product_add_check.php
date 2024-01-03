<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION["login"]) === false) {
    echo "ログインしていません。<br><br>";
    echo "<a href='../staff/staff_login.html'>ログイン画面へ</a>";
    exit();
} else {
    echo $_SESSION["name"]."さんログイン中";
    echo "<br><br>";
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // POST メソッド以外でアクセスされた場合の処理
    header("Location: error_page.php");
    exit();
}

// フォームから送信された商品情報を取得
$category_id = htmlspecialchars($_POST["category_id"]);
$name = htmlspecialchars($_POST["name"]);
$description = htmlspecialchars($_POST["description"]);
$price = htmlspecialchars($_POST["price"]);
$stock = htmlspecialchars($_POST["stock"]);
$image_path = htmlspecialchars($_POST["image_path"]);

// TODO: バリデーションやエスケープ処理を追加

// データベースに商品情報を追加するクエリを実行
// PDOを使用
try {
    $pdo = new PDO("mysql:host=localhost;dbname=ecshop_db;charset=utf8", "ecshop_user", "ecshop_pass");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO products (category_id, name, description, price, stock, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$category_id, $name, $description, $price, $stock, $image_path]);

    // 最後に挿入された行のIDを取得
    $newlyAddedProductId = $pdo->lastInsertId();

    // データベース接続を閉じる
    $pdo = null;

    // リダイレクト
    header("Location: product_detail.php?product_id=" . $newlyAddedProductId);
    exit();
} catch (PDOException $e) {
    // データベースエラーの場合の処理
    echo "データベースエラー: " . $e->getMessage();
    // 必要に応じてエラーページにリダイレクトするなどの処理を追加することができます。
}
?>
