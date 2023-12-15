<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>アカウント作成確認 - ECサイト</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="register-container">
        <h2>アカウント作成確認</h2>
        <form action="register_process.php" method="post">
            <p>ユーザー名: <?php echo htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p>メールアドレス: <?php echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p>氏: <?php echo htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p>名: <?php echo htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p>セイ: <?php echo htmlspecialchars($_POST['last_name_kana'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p>メイ: <?php echo htmlspecialchars($_POST['first_name_kana'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p>郵便番号: <?php echo htmlspecialchars($_POST['zipcode'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p>住所: <?php echo htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p>電話番号: <?php echo htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8'); ?></p>

            <!-- 入力された値を隠しフィールドとして保持 -->
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="password" value="<?php echo $_POST['password']; ?>"> <!-- パスワードはそのまま送信 -->
            <input type="hidden" name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="last_name_kana" value="<?php echo htmlspecialchars($_POST['last_name_kana'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="first_name_kana" value="<?php echo htmlspecialchars($_POST['first_name_kana'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="zipcode" value="<?php echo htmlspecialchars($_POST['zipcode'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="address" value="<?php echo htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8'); ?>">

            <div class="form-footer">
                <input type="button" onclick="history.back()" value="修正する" class="back-btn">
                <input type="submit" value="アカウント作成" class="register-btn">
            </div>
        </form>
    </div>
</body>
</html>
