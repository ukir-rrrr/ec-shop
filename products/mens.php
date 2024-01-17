<?php
// http://localhost/EC-shop/products/mens.php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>メンズ商品</title>
    <link rel="stylesheet" href="../style/products.css">
</head>
    <header>
        <nav>
            <div class="left-nav">
                <ul>
                    <li><a href="../index.php">ホーム</a></li>
                    <li><a href="new_arrival.php">新着商品</a></li>
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

        // ページネーションの設定
        $productsPerPage = 4;
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $offset = ($page - 1) * $productsPerPage;


        // 商品情報をメンズカテゴリから取得（LIMITとOFFSETを使用）
        $category_id = 1;
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? LIMIT ? OFFSET ?");
        $stmt->execute([$category_id, $productsPerPage, $offset]);

        // 商品情報を表示
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='product'>";
            echo "<img src='" . $row['image_path'] . "' alt='" . $row['name'] . "'>";
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>価格: " . intval($row['price']) . "円</p>";
            echo "<p>商品説明: " . $row['description'] . "</p>";
            echo "<form action='../cart/add_to_cart.php' method='post'>";
            echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
            echo "<input type='submit' value='カートに追加'>";
            echo "</form>";
            echo "</div>";
        }

        // ページネーションの表示
        $totalProducts = $pdo->query("SELECT COUNT(*) FROM products WHERE category_id = $category_id")->fetchColumn();
        $totalPages = ceil($totalProducts / $productsPerPage);

        echo "<div class='pagination'>";
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='?page=$i'>$i</a>";
            if ($i < $totalPages) {
                echo "・";
            }
        }
        echo "</div>";
?>

    <footer>
  <p>&copy; 2023 ファッションECサイト</p>
</footer>
