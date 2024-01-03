<?php

session_start();
session_regenerate_id(true);
if(isset($_SESSION["login"]) === false) {
    print "ログインしていません。<br><br>";
    print "<a href='staff_login.html'>ログイン画面へ</a>";
    exit();
} else {
    print $_SESSION["name"]."さんログイン中";
    print "<br><br>";
}

// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

// フォームから送信された商品情報をサニタイズして取得
$sanitizedPost = sanitize($_POST);
$id = isset($sanitizedPost["id"]) ? $sanitizedPost["id"] : null;
$name = isset($sanitizedPost["name"]) ? $sanitizedPost["name"] : null;
$description = isset($sanitizedPost["description"]) ? $sanitizedPost["description"] : null;
$price = isset($sanitizedPost["price"]) ? $sanitizedPost["price"] : null;


// 新しい画像がアップロードされたかどうかを確認
$newImageUploaded = isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK;

// ファイルがアップロードされている場合、適切な処理を行う
if ($newImageUploaded) {
    // 画像のアップロード処理
    $uploadDirectory = 'EC-shop/main-image/'; // 実際の保存先に変更する
    $uploadedFileName = basename($_FILES['new_image']['name']);
    $newImagePath = $uploadDirectory . $uploadedFileName;
    move_uploaded_file($_FILES['new_image']['tmp_name'], $newImagePath);
} else {
    // 新しい画像がアップロードされていない場合は、現在の画像のパスをそのまま利用
    $newImagePath = $row['image_path'];
}


// 必要なチェックを行う
if (empty($name) || empty($description) || empty($price) || !is_numeric($price) || intval($price) < 0) {
    // 必要な情報が不足している場合や価格が正の整数でない場合、エラーメッセージを表示して元のページに戻る
    echo "入力に誤りがあります。";
    echo "<a href='product_edit.php?id=$id'>戻る</a>";
    exit();
}

// ここまでエラーがなければ、データベースを更新
$stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, image_path=? WHERE id=?");
$stmt->execute([$name, $description, $price, $newImagePath, $id]);

echo "商品を修正しました。";
echo "<a href='mens_list.php'>商品一覧へ</a>";

?>
