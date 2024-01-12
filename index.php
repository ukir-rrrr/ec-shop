<!-- http://localhost/EC-shop/index.php -->

<?php
session_start();


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ファッションECサイト</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<header>
        <nav>
            <div class="left-nav">
                <ul>
                    <li><a href="index.php">ホーム</a></li>
                    <li><a href="#">新着商品</a></li>
                    <li><a href="./user/contactform.php">お問い合わせ</a></li>
                </ul>
            </div>
            <div class="right-nav">
                <ul>
                    <?php
                    // ユーザーがログインしている場合の表示
                    if (isset($_SESSION['user_id'])) {
                        echo '<li>' . htmlspecialchars($_SESSION['user_name'], ENT_QUOTES) . '様 ログイン中</li>';
                        echo '<li><a href="./user/mypage.php">マイページ</a></li>';
                        echo '<li><a href="./user/logout_confirm.php">ログアウト</a></li>';
                    } else {
                        echo '<li><a href="./user/login.php">ログイン</a></li>';
                    }
                    ?>
                    <li><a href="./cart/shopping_cart.php?view_cart=true">カートを見る</a></li>
                </ul>
                <form action="/search" method="get" class="search-form">
                    <input type="text" name="q" placeholder="検索...">
                    <button type="submit">検索</button>
                </form>
            </div>
        </nav>
    </header>

    <main>
      <!-- メインイメージ -->
      <div class="main-image-container">
          <img src="./main-image/main-image.jpg" alt="メインイメージ">
      </div>

          <!-- 製品セクション -->
      <section class="products-section">
          <h2>PRODUCTS</h2>
          <div class="products-container">
              <!-- メンズ -->
              <a href="./products/mens.php" class="product-box">
                  <img src="./main-image/mens.jpg" alt="メンズ">
                  <h3>MEN'S</h3>
              </a>
              <a href="womens.php" class="product-box">
                  <img src="./main-image/womens.jpg" alt="ウィメンズ">
                  <h3>WOMEN'S</h3>
              </a>
              <a href="kids.php" class="product-box">
                  <img src="./main-image/kids.jpg" alt="キッズ">
                  <h3>KID'S</h3>
              </a>
          </div>
      </section>
    </main>

<footer>
  <p>&copy; 2023 ファッションECサイト</p>
</footer>
</body>
