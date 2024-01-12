<?php
// http://localhost/EC-shop/cart/payment_addres.php
session_start();

// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

// 初期化
$error_message = '';

// 続行ボタンが押された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['continue'])) {
  // お支払い方法が選択されている場合
  if (isset($_POST['payment_method'])) {
      // セッションにお支払い方法を保存
      $_SESSION['payment_method'] = $_POST['payment_method'];

      // デバッグ用の var_dump を挿入
      var_dump($_SESSION);


      // 続行ボタンが押されたら注文確定画面へリダイレクト
      header("Location: checkout.php");
      exit();
  } else {
      // お支払い方法が選択されていない場合はエラーメッセージを表示
      $error_message = "お支払い方法を選択してください。";
  }
}



?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お支払い方法選択</title>
    <link rel="stylesheet" href="../style/style.css">

</head>
<body>
    <h2>お支払い・お届け先情報</h2>

    <!-- エラーメッセージ表示 -->
    <?php if (!empty($error_message)) : ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- お支払い方法の選択 -->
    <form action="payment_addres.php" method="post" class="form-container payment-method-container">
      <label for="payment_method">お支払い方法:</label>
      <input type="radio" name="payment_method" value="cash_on_delivery" <?php echo (isset($_SESSION['payment_method']) && $_SESSION['payment_method'] === 'cash_on_delivery') ? 'checked' : ''; ?>> 代金引換
      <input type="radio" name="payment_method" value="credit_card" <?php echo (isset($_SESSION['payment_method']) && $_SESSION['payment_method'] === 'credit_card') ? 'checked' : ''; ?>> クレジットカード
      <input type="submit" name="continue" value="続行">
    </form>


    <!-- カード情報入力ボタン -->
    <form action="credit_card_info.php" method="post" class="form-container">
        <input type="submit" name="card_info" value="カード情報入力">
    </form>

</body>
</html>
