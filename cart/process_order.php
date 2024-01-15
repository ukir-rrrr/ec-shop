<?php
// http://localhost/EC-shop/cart/process_order.php
session_start();
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ファッションECサイト</title>
    <link rel="stylesheet" href="../style/products.css">
</head>
    <header>
        <nav>
            <div class="left-nav">
                <ul>
                    <li><a href="../index.php">ホーム</a></li>
                    <li><a href="../products/new_arrival.php">新着商品</a></li>
                    <li><a href="../user/contactform.php">お問い合わせ</a></li>
                </ul>
            </div>
            <div class="right-nav">
                <ul>
                    <?php
                    // ユーザーがログインしている場合の表示
                    if (isset($_SESSION['user_id'])) {
                        echo '<li>' . htmlspecialchars($_SESSION['user_name'], ENT_QUOTES) . '様 ログイン中</li>';
                        echo '<li><a href="../user/mypage.php">マイページ</a></li>';
                        echo '<li><a href="../user/logout_confirm.php">ログアウト</a></li>';
                    } else {
                        echo '<li><a href="../user/login.php">ログイン</a></li>';
                    }
                    ?>
                    <li><a href="../cart/shopping_cart.php?view_cart=true">カートを見る</a></li>
                </ul>
                <form action="../products/search_products.php" method="get" class="search-form">
                    <input type="text" name="q" placeholder="検索...">
                    <button type="submit">検索</button>
                </form>
            </div>
        </nav>
    </header>


<?php

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
        // 注文確定処理
        $total_price = 0;

        // ユーザー情報を取得
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $address_stmt = $pdo->prepare("SELECT Last_name, First_name, Address, Zipcode, Email FROM Users WHERE UserID = ?");
            $address_stmt->execute([$user_id]);
            $user_address = $address_stmt->fetch(PDO::FETCH_ASSOC);
        }

        // トランザクション開始
        $pdo->beginTransaction();

        // 注文情報をSalesテーブルに挿入
        foreach ($_SESSION["cart"] as $cart_product_id) {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$cart_product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            // 合計金額を計算
            $total_price += $_SESSION["cart_quantity"][$cart_product_id] * $product["price"];

            // 注文情報を挿入
            $stmt = $pdo->prepare("INSERT INTO Sales (UserID, TotalAmount, Last_name, First_name, Zipcode, Address, PaymentMethod, ProductID, Quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $user_id,
                $total_price,
                $user_address['Last_name'],
                $user_address['First_name'],
                $user_address['Zipcode'],
                $user_address['Address'],
                $_SESSION["payment_method"],
                $cart_product_id,
                $_SESSION["cart_quantity"][$cart_product_id]
            ]);
        }

        // コミット
        $pdo->commit();

        // 注文完了メッセージを表示
        echo "<h2>" . $user_address['Last_name'] . " " . $user_address['First_name'] . "様、ご注文ありがとうございました</h2>";
        echo "<p>" . $user_address['Email'] . " にメールを送りましたのでご確認ください。</p>";
        echo "<p>商品は以下の住所に発送させていただきます。</p>";
        echo "<p>〒" . $user_address['Zipcode'] . "</p>";
        echo "<p>" . $user_address['Address'] . "</p>";
        echo "<p>ご注文内容</p>";

        // 注文された商品の表示
        echo "<ul>";
        foreach ($_SESSION["cart"] as $cart_product_id) {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$cart_product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<li>";
            echo $product["name"] . "  ✖️ " . $_SESSION["cart_quantity"][$cart_product_id];
            echo "</li>";
        }
        echo "</ul>";

        // 合計金額の表示
        echo "<p>合計: " . $total_price . "円（送料は無料です）</p>";

        echo "<form action='../index.php' method='post'>";
        echo "<input type='submit' value='ホームへ戻る'>";
        echo "</form>";

        // セッションデータをクリア
        unset($_SESSION["cart"]);
        unset($_SESSION["cart_quantity"]);
        unset($_SESSION["payment_method"]);

    } catch (Exception $e) {
        // エラーが発生した場合はロールバック
        $pdo->rollBack();
        echo "注文確定中にエラーが発生しました：" . $e->getMessage();
    }
}
?>
