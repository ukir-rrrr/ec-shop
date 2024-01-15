<?php
// http://localhost/EC-shop/cart/add_to_cart.php
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

// ホーム画面から「カートを見る」がクリックされたかを確認
if (isset($_GET["view_cart"])) {
    // カート内の商品情報を表示
    echo "<h2>カート内の商品</h2>";
    echo "<ul>";

    // カートが存在し、配列であることを確認
    if (isset($_SESSION["cart"]) && is_array($_SESSION["cart"])) {
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

            // 個数を変更できるプルダウンメニュー（onchangeで自動的にフォームをサブミット）
            echo "<form action='add_to_cart.php' method='post' id='form_$cart_product_id'>";
            echo "<input type='hidden' name='product_id' value='" . $cart_product_id . "'>";
            echo "<label for='quantity'>個数:</label>";
            echo "<select name='quantity' id='quantity_$cart_product_id' onchange='document.getElementById(\"form_$cart_product_id\").submit();'>";
            for ($i = 1; $i <= 10; $i++) {
                $selected = ($_SESSION["cart_quantity"][$cart_product_id] == $i) ? "selected" : ""; // 現在の個数を選択
                echo "<option value='" . $i . "' $selected>" . $i . "</option>";
            }
            echo "</select>";
            echo "</form>";

            // カートから商品を削除するボタン
            echo "<form action='add_to_cart.php' method='post'>";
            echo "<input type='hidden' name='remove_product_id' value='" . $cart_product_id . "'>";
            echo "<input type='submit' name='remove' value='削除'>";
            echo "</form>";

            // 合計金額に商品ごとの小計を加算
            $total_price += $_SESSION["cart_quantity"][$cart_product_id] * $product["price"];

            echo "</div>";
        }

        echo "</ul>";

        // カート合計金額を表示
        echo "<p>カート合計金額: " . $total_price . "円";

        // 買い物を続けるボタン
        echo "<form action='../index.php' method='post'>";
        echo "<input type='submit' value='買い物を続ける'>";
        echo "</form>";

        // ログイン状態によって表示を変更
        if (isset($_SESSION['user_id'])) {
            // ログインしている場合
            echo "<form action='checkout.php' method='post'>";
            echo "<input type='submit' value='レジへ進む'>";
            echo "</form>";
        } else {
            // ログインしていない場合
            echo "<form action='../user/login.php' method='post'>";
            echo "<input type='submit' value='ログインしてレジへ進む'>";
            echo "</form>";
        }

        // ビューが終了したらexitで終了
        exit();
    } else {
        echo "<br><br>";
        echo "<form action='../index.php' method='post'>";
        echo "<input type='submit' value='買い物を続ける'>";
        echo "</form>";

    }
} elseif (isset($_POST["remove"]) && isset($_POST["remove_product_id"])) {
    // 商品の削除が要求された場合
    $remove_product_id = $_POST["remove_product_id"];

    // カートから商品を削除
    if (($key = array_search($remove_product_id, $_SESSION["cart"])) !== false) {
        unset($_SESSION["cart"][$key]);
        unset($_SESSION["cart_quantity"][$remove_product_id]);
        echo "商品をカートから削除しました。";
    }

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

        // 個数を変更できるプルダウンメニュー（onchangeで自動的にフォームをサブミット）
        echo "<form action='add_to_cart.php' method='post' id='form_$cart_product_id'>";
        echo "<input type='hidden' name='product_id' value='" . $cart_product_id . "'>";
        echo "<label for='quantity'>個数:</label>";
        echo "<select name='quantity' id='quantity_$cart_product_id' onchange='document.getElementById(\"form_$cart_product_id\").submit();'>";
        for ($i = 1; $i <= 10; $i++) {
            $selected = ($_SESSION["cart_quantity"][$cart_product_id] == $i) ? "selected" : ""; // 現在の個数を選択
            echo "<option value='" . $i . "' $selected>" . $i . "</option>";
        }
        echo "</select>";
        echo "</form>";

        // カートから商品を削除するボタン
        echo "<form action='add_to_cart.php' method='post'>";
        echo "<input type='hidden' name='remove_product_id' value='" . $cart_product_id . "'>";
        echo "<input type='submit' name='remove' value='削除'>";
        echo "</form>";

        // 合計金額に商品ごとの小計を加算
        $total_price += $_SESSION["cart_quantity"][$cart_product_id] * $product["price"];

        echo "</li>";
    }

    echo "</ul>";

    // カート合計金額を表示
    echo "<p>カート合計金額: " . $total_price . "円</p>";

    // 買い物を続けるボタン
    echo "<form action='../index.php' method='post'>";
    echo "<input type='submit' value='買い物を続ける'>";
    echo "</form>";

} else {
    // 商品IDが指定されていない場合のエラーメッセージ
    echo "商品が指定されていません。";
}

// ログイン状態によって表示を変更
if (isset($_SESSION['user_id'])) {
    // ログインしている場合
    echo "<form action='checkout.php' method='post'>";
    echo "<input type='submit' value='レジへ進む'>";
    echo "</form>";
} else {
    // ログインしていない場合
    echo "<form action='../user/login.php' method='post'>";
    echo "<input type='submit' value='ログインしてレジへ進む'>";
    echo "</form>";
}
?>
