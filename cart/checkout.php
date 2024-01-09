<?php
// http://localhost/EC-shop/cart/checkout.php
session_start();

// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

// カートがセッションに存在しない場合は初期化
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

// カートが空の場合はメッセージを表示
if (empty($_SESSION["cart"])) {
    echo "カートが空です。";
} else {
    // カート内の商品情報を表示
    echo "<h2>カート内の商品</h2>";
    echo "<ul>";

    // カート内の商品情報を取得
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");

    $total_price = 0; // カート合計金額を初期化

    foreach ($_SESSION["cart"] as $cart_product_id) {
        // 商品情報を取得
        $stmt->execute([$cart_product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // 商品情報を表示
        echo "<li>";
        echo "<img src='" . $product["image_path"] . "' alt='" . $product["name"] . "'>";
        echo "<h3>" . $product["name"] . "</h3>";
        echo "<p>説明: " . $product["description"] . "</p>";
        echo "<p>価格: " . intval($product['price']) . "円</p>";

        // 合計金額に商品ごとの小計を加算
        $total_price += $_SESSION["cart_quantity"][$cart_product_id] * $product["price"];

        echo "</li>";
    }

    echo "</ul>";

    // カート合計金額を表示
    echo "<p>お支払い合計金額: " . $total_price . "円</p>";

    // お届け先情報を表示
    if (isset($_SESSION['user_id'])) {
        // ユーザーがログインしている場合
        $user_id = $_SESSION['user_id'];
        $address_stmt = $pdo->prepare("SELECT Last_name, First_name, Address, Zipcode FROM Users WHERE UserID = ?");
        $address_stmt->execute([$user_id]);
        $user_address = $address_stmt->fetch(PDO::FETCH_ASSOC);
        if ($user_address) {
            echo "<p>お届け先: " . $user_address['Last_name'] . " " . $user_address['First_name'] . " 様</p>";
            echo "<p>〒" . $user_address['Zipcode'] . "</p>";
            echo "<p>" . $user_address['Address'] . "</p>";
        }
    }

    // お支払い方法の表示
    echo "<p>お支払い方法: " . (isset($_SESSION['payment_method']) ? ($_SESSION['payment_method'] === 'cash_on_delivery' ? '代金引換' : 'クレジットカード') : '') . "</p>";

    // デバッグ用
    echo "<p>デバッグ: "; var_dump($_SESSION); echo "</p>";

    // } else {
    //     echo "未選択";
    // }
    echo "</p>";

    // お支払い方法の変更ボタン
    echo "<form action='payment_addres.php' method='post'>";
    echo "<input type='submit' name='change_payment' value='お支払い方法選択'>";
    echo "</form>";

    // 戻るボタン
    echo "<input type='button' value='戻る' onclick='history.back();'>";
    echo "<br><br>";

    // 注文確定ボタン
    echo "<form action='process_order.php' method='post'>";
    echo "<input type='submit' name='confirm_order' value='注文確定'>";
    echo "</form>";
}
?>
