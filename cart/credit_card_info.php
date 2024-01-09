<?php
// credit_card_info.php

// クレジットカード情報のセッションを開始
session_start();

// 初期化
$error_message = '';

// 続行ボタンが押された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['continue'])) {
    // クレジットカード番号、有効期限、セキュリティコードが入力されているかチェック
    if (!empty($_POST['card_number']) && !empty($_POST['expiration_date']) && !empty($_POST['security_code'])) {
        // 入力されたクレジットカード情報をセッションに保存
        $_SESSION['card_number'] = $_POST['card_number'];
        $_SESSION['expiration_date'] = $_POST['expiration_date'];
        $_SESSION['security_code'] = $_POST['security_code'];

        // 支払い・お届け先情報入力画面にリダイレクト
        header("Location: payment_addres.php");
        exit();
    } else {
        // エラーメッセージを表示
        $error_message = "クレジットカード情報を正しく入力してください。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>クレジットカード情報入力</title>
    <link rel="stylesheet" href="../style/style.css">
    <style>
        /* 必要なスタイルを追加 */
        label {
            display: block;
            margin-bottom: 10px;
        }
        .form-container {
            margin-top: 20px;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>クレジットカード情報入力</h2>

    <!-- エラーメッセージ表示 -->
    <?php if (!empty($error_message)) : ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- クレジットカード情報入力フォーム -->
    <form action="credit_card_info.php" method="post" class="form-container">
        <label for="card_number">クレジットカード番号:</label>
        <input type="text" name="card_number" placeholder="クレジットカード番号" required>
        <br>
        <label for="expiration_date">有効期限(MM/YY):</label>
        <input type="text" name="expiration_date" placeholder="MM/YY" required>
        <br>
        <label for="security_code">セキュリティコード:</label>
        <input type="text" name="security_code" placeholder="セキュリティコード" required>
        <br>
        <!-- 続行ボタン -->
        <input type="submit" name="continue" value="続行">
    </form>
</body>
</html>
