<?php
// http://localhost/EC-shop/cart/process_order.php
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
    try {
        // トランザクション開始
        $pdo->beginTransaction();

        // ユーザーがログインしている場合
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            // ユーザー情報取得
            $user_stmt = $pdo->prepare("SELECT Last_name, First_name, Email, Address, Zipcode FROM Users WHERE UserID = ?");
            $user_stmt->execute([$user_id]);
            $user_info = $user_stmt->fetch(PDO::FETCH_ASSOC);

            if ($user_info) {
                $last_name = $user_info['Last_name'];
                $first_name = $user_info['First_name'];
                $email = $user_info['Email'];
                $zipcode = $user_info['Zipcode'];
                $address = $user_info['Address'];
            } else {
                throw new Exception("ユーザー情報が取得できませんでした。");
            }
        } else {
            throw new Exception("ユーザーがログインしていません。");
        }

        // 注文情報を Sales テーブルに挿入
        $total_price = 0;

        foreach ($_SESSION["cart"] as $cart_product_id) {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$cart_product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            // 合計金額に商品ごとの小計を加算
            $total_price += $_SESSION["cart_quantity"][$cart_product_id] * $product["price"];

            // 注文情報を Sales テーブルに挿入
            $insert_stmt = $pdo->prepare("
                INSERT INTO Sales (UserID, OrderDate, TotalAmount, Last_name, First_name, Zipcode, Address, PaymentMethod, ProductID, Quantity)
                VALUES (?, NOW(), ?, ?, ?, ?, ?, 'クレジットカード', ?, ?)
            ");
            $insert_stmt->execute([$user_id, $total_price, $last_name, $first_name, $zipcode, $address, $cart_product_id, $_SESSION["cart_quantity"][$cart_product_id]]);
        }

        // トランザクションコミット
        $pdo->commit();

        // カートを空にする
        $_SESSION["cart"] = [];
        $_SESSION["cart_quantity"] = [];

        // 注文完了メッセージを表示
        echo "<h2>" . $last_name . " " . $first_name . "様、ご注文ありがとうございました</h2>";
        echo "<p> . $email . にメールを送りましたのでご確認ください。</p>";
        echo "<p>商品は以下の住所に発送させていただきます。</p>";
        echo "<p>〒" . $zipcode . "</p>";
        echo "<p>" . $address . "</p>";
        echo "<p>ご注文内容</p>";

        // 注文された商品の表示
        echo "<ul>";
        foreach ($_SESSION["cart"] as $cart_product_id) {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$cart_product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<li>";
            echo $product["name"] . " - Quantity: " . $_SESSION["cart_quantity"][$cart_product_id];
            echo "</li>";
        }
        echo "</ul>";

        // 合計金額の表示
        echo "<p>合計: " . $total_price . "円（送料は無料です）</p>";

    } catch (Exception $e) {
        // エラーが発生した場合はロールバック
        $pdo->rollBack();
        echo "注文確定中にエラーが発生しました：" . $e->getMessage();
    }
}
?>
