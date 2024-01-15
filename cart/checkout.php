<?php
// http://localhost/EC-shop/cart/checkout.php
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
        echo "<div class='product'>";
        echo "<img src='" . $product["image_path"] . "' alt='" . $product["name"] . "'>";
        echo "<h3>" . $product["name"] . "</h3>";
        echo "<p>説明: " . $product["description"] . "</p>";
        echo "<p>価格: " . intval($product['price']) . "円</p>";

        // 合計金額に商品ごとの小計を加算
        $total_price += $_SESSION["cart_quantity"][$cart_product_id] * $product["price"];

        echo "</div>";
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

    // // お支払い方法の表示
    // echo "<p>お支払い方法: " . (isset($_SESSION['payment_method']) ? ($_SESSION['payment_method'] === 'cash_on_delivery' ? '代金引換' : 'クレジットカード') : '') . "</p>";

    // お支払い方法の表示
    $payment_method = isset($_SESSION['payment_method']) ? ($_SESSION['payment_method'] === 'cash_on_delivery' ? '代金引換' : 'クレジットカード') : '';
    echo "<p>お支払い方法: " . $payment_method . "</p>";


    // } else {
    //     echo "未選択";
    // }
    echo "</p>";

    // お支払い方法の変更ボタン
    echo "<form action='payment_addres.php' method='post'>";
    echo "<input type='submit' name='change_payment' value='お支払い方法選択'>";
    echo "</form>";

    // 戻るボタン
    echo "<br>";
    echo "<input type='button' value='戻る' onclick='history.back();'>";
    echo "<br><br>";

    // 注文確定ボタン
    if ($payment_method !== '') {
        echo "<form action='process_order.php' method='post'>";
        echo "<input type='submit' name='confirm_order' value='注文確定'>";
        echo "</form>";
    } else {
        echo "<input type='submit' name='confirm_order' value='注文確定'>";
        echo "<p style='color: black;'>お支払い方法を選択してください。</p>";
    }
}
?>
