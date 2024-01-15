<?php
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
                    <li><a href="#">新着商品</a></li>
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

// 検索ボックスから送信されたキーワードを取得
if (isset($_GET['q'])) {
    $search_keyword = $_GET['q'];

    // 商品テーブルからキーワードで検索
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
    $stmt->execute(["%" . $search_keyword . "%", "%" . $search_keyword . "%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 検索結果を表示
    if ($results) {
        echo "<h2>検索結果</h2>";
        foreach ($results as $result) {
            echo "<div class='product'>";
            echo "<img src='" . $result['image_path'] . "' alt='" . $result['name'] . "'>";
            echo "<h3>" . $result['name'] . "</h3>";
            echo "<p>価格: " . intval($result['price']) . "円</p>";
            echo "<p>詳細: " . $result['description'] . "</p>";
            echo "<form action='../cart/add_to_cart.php' method='post'>";
            echo "<input type='hidden' name='product_id' value='" . $result['id'] . "'>";
            echo "<input type='submit' value='カートに追加'>";
            echo "</form>";
            echo "</div>";
        }


    } else {
        echo "<p>検索結果が見つかりませんでした。</p>";
        echo "<form action='../index.php' method='post'>";
        echo "<input type='submit' value='ホームに戻る'>";
        echo "</form>";
    }

}
