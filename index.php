<!-- http://localhost/EC-shop/index.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ファッションECサイト</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
    <header>
      <body>
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
                  <li><a href="./user/mypage.php">マイページ</a></li>
                  <?php
                        // セッションが開始されている場合にのみログイン状態を確認
                        session_start();
                        if (isset($_SESSION['user_id'])) {
                            echo '<li><a href="./user/logout_confirm.php
                            ">ログアウト</a></li>';
                        } else {
                            echo '<li><a href="./user/login.html">ログイン</a></li>';
                        }
                    ?>
                  <li><a href="favorites.html">お気に入り</a></li>
                  <li><a href="cart.html">カートを見る</a></li>
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
              <!-- 各製品ボックス、リンク先は例として # にしています -->
              <a href="mens.html" class="product-box">
                  <img src="./main-image/mens.jpg" alt="メンズ">
                  <h3>MEN'S</h3>
              </a>
              <a href="womens.html" class="product-box">
                  <img src="./main-image/womens.jpg" alt="ウィメンズ">
                  <h3>WOMEN'S</h3>
              </a>
              <a href="kids.html" class="product-box">
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
