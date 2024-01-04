<?php
// http://localhost/EC-shop/cart/add_to_cart.php
session_start();

// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

// 商品IDがPOSTで送られてきたか確認
if (isset($_POST["product_id"])) {
    $product_id = $_POST["product_id"];

    // カートがセッションに存在しない場合は初期化
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }

    // カートに商品を追加
    if (!in_array($product_id, $_SESSION["cart"])) {
        $_SESSION["cart"][] = $product_id;

        // 新しく追加された商品の数量を初期化
        $_SESSION["cart_quantity"][$product_id] = 1;

        echo "商品をカートに追加しました。";
    } else {
        // 商品がすでにカートに存在する場合
        if (isset($_POST["quantity"])) {
            // 商品数量を変更した場合
            $quantity = $_POST["quantity"];
            $_SESSION["cart_quantity"][$product_id] = $quantity;
            echo "商品数量を変更しました。";
        } else {
            // 商品がすでに存在している旨を表示
            echo "選択した商品はすでにカートに存在します。";
        }
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

} elseif (isset($_POST["remove"]) && isset($_POST["remove_product_id"])) {
    // 商品の削除が要求された場合
    $remove_product_id = $_POST["remove_product_id"];

    // カートから商品を削除
    if (($key = array_search($remove_product_id, $_SESSION["cart"])) !== false) {
        unset($_SESSION["cart"][$key]);
        unset($_SESSION["cart_quantity"][$remove_product_id]);
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
?>
