<?php
// http://localhost/EC-shop/cart/payment_addres.php
session_start();

// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

// 住所の取得
$user_id = $_SESSION['user_id'];
$address_stmt = $pdo->prepare("SELECT Last_name, First_name, Address, Zipcode FROM Users WHERE UserID = ?");
$address_stmt->execute([$user_id]);
$user_address = $address_stmt->fetch(PDO::FETCH_ASSOC);

// 初期化
$error_message = '';

if (isset($_POST['payment_method']) && $_POST['payment_method'] === 'cash_on_delivery') {
  if (empty($_POST['zipcode']) || empty($_POST['address'])) {
      $error_message = "郵便番号と住所は必須です。";
  } else {
      // セッションにお届け先情報を保存
      $_SESSION['user_address'] = [
          'Last_name' => $user_address['Last_name'],
          'First_name' => $user_address['First_name'],
          'Zipcode' => $_POST['zipcode'],
          'Address' => $_POST['address'],
      ];

      // セッションにお支払い方法を保存
      $_SESSION['payment_method'] = $_POST['payment_method'];

      // 続行ボタンが押されたら注文確定画面へリダイレクト
      header("Location: checkout.php");
      exit();
  }
} else {
  $error_message = "お支払い方法を選択してください。";
}

// // 続行ボタンが押された場合の処理
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['continue'])) {
//   // 代金引換が選択されている場合
//   if (isset($_POST['payment_method']) && $_POST['payment_method'] === 'cash_on_delivery') {
//       // 郵便番号または住所が未入力の場合エラーメッセージを表示
//       if (empty($_POST['zipcode']) || empty($_POST['address'])) {
//           $error_message = "郵便番号と住所は必須です。";
//       } else {
//           // セッションにお届け先情報を保存
//           $_SESSION['user_address'] = [
//               'Zipcode' => $_POST['zipcode'],
//               'Address' => $_POST['address'],
//           ];

//           // セッションにお支払い方法を保存
//           $_SESSION['payment_method'] = $_POST['payment_method'];

//           // 続行ボタンが押されたら注文確定画面へリダイレクト
//           header("Location: checkout.php");
//           exit();
//       }
//   } else {
//       // その他の場合はエラーメッセージを表示
//       $error_message = "お支払い方法を選択してください。";
//   }
// }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お支払い・お届け先情報</title>
    <link rel="stylesheet" href="../style/style.css">
    <style>
        /* 必要なスタイルを追加 */
        label {
            display: block;
            margin-bottom: 10px;
        }
        #credit_card_info {
            margin-top: 10px;
        }
        .form-container {
            margin-top: 20px;
        }
        .payment-method-container {
            display: flex;
            flex-direction: column;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>お支払い・お届け先情報</h2>

    <!-- エラーメッセージ表示 -->
    <?php if (!empty($error_message)) : ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- 住所の表示と編集フォーム -->
    <form action="payment_addres.php" method="post" class="form-container">
        <label for="zipcode">〒郵便番号:</label>
        <input type="text" name="zipcode" value="<?php echo $user_address['Zipcode']; ?>">
        <br>
        <label for="address">お届け先住所:</label>
        <textarea name="address" rows="4" cols="50"><?php echo $user_address['Address']; ?></textarea>
        <br>
    </form>

    <!-- お支払い方法の選択 -->
    <form action="payment_addres.php" method="post" class="form-container payment-method-container">
        <label for="payment_method">お支払い方法:</label>
        <input type="radio" name="payment_method" value="cash_on_delivery" <?php echo (isset($_SESSION['payment_method']) && $_SESSION['payment_method'] === 'cash_on_delivery') ? 'checked' : ''; ?>> 代金引換
        <input type="radio" name="payment_method" value="credit_card" <?php echo (isset($_SESSION['payment_method']) && $_SESSION['payment_method'] === 'credit_card') ? 'checked' : ''; ?>> クレジットカード
    </form>

    <!-- カード情報入力ボタン -->
    <form action="credit_card_info.php" method="post" class="form-container">
        <input type="submit" name="card_info" value="カード情報入力">
    </form>

    <!-- 続行ボタン -->
    <form action="checkout.php" method="post" class="form-container">
        <input type="submit" name="continue" value="続行">
    </form>
</body>
</html>
