<?php
session_start();

// フォームからのデータを受け取り、サニタイズ
$username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = $_POST['password']; // パスワードは後でハッシュ化
$last_name = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');
$first_name = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
$last_name_kana = htmlspecialchars($_POST['last_name_kana'], ENT_QUOTES, 'UTF-8');
$first_name_kana = htmlspecialchars($_POST['first_name_kana'], ENT_QUOTES, 'UTF-8');
$zipcode = htmlspecialchars($_POST['zipcode'], ENT_QUOTES, 'UTF-8');
$address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
$phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');

// バリデーション（簡単な例）
$errors = [];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "メールアドレスの形式が不正です。";
}
if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
    $errors[] = "ユーザー名に不正な文字が含まれています。";
}
if (!preg_match("/^[ぁ-んァ-ン一-龥]+$/u", $last_name)) {
    $errors[] = "氏名（氏）に不正な文字が含まれています。";
}
// 他のフィールドに対するバリデーションも同様に行う

// エラーがあれば元のページへリダイレクト
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: register.html');
    exit;
}

// エラーがなければセッションに保存して確認画面へ
$_SESSION['register_data'] = [
    'username' => $username,
    'email' => $email,
    'password' => $password,
    'last_name' => $last_name,
    'first_name' => $first_name,
    'last_name_kana' => $last_name_kana,
    'first_name_kana' => $first_name_kana,
    'zipcode' => $zipcode,
    'address' => $address,
    'phone' => $phone
];
?>

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
        <?php foreach ($errors as $error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endforeach; ?>
        <p>ユーザー名: <?php echo $username; ?></p>
        <p>メールアドレス: <?php echo $email; ?></p>
        <p>氏: <?php echo $last_name; ?></p>
        <p>名: <?php echo $first_name; ?></p>
        <p>セイ: <?php echo $last_name_kana; ?></p>
        <p>メイ: <?php echo $first_name_kana; ?></p>
        <p>郵便番号: <?php echo $zipcode; ?></p>
        <p>住所: <?php echo $address; ?></p>
        <p>電話番号: <?php echo $phone; ?></p>
        <form action="register_process.php" method="post">
            <div class="form-footer">
                <input type="button" onclick="history.back()" value="修正する" class="back-btn">
                <input type="submit" value="アカウント作成" class="register-btn">
            </div>
        </form>
    </div>
</body>
</html>
