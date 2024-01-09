<?php
// セッションを開始
session_start();

// データベース接続設定
$dbHost = 'localhost';
$dbUser = 'ecshop_user';
$dbPass = 'ecshop_pass';
$dbName = 'ecshop_db';
$charset = 'utf8mb4';

// PDOインスタンスを作成
$dsn = "mysql:host={$dbHost};dbname={$dbName};charset={$charset}";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_PERSISTENT => false,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_general_ci'
];

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $e) {
    // エラーが発生した場合はメッセージを表示
    exit("データベース接続エラー: " . $e->getMessage());
}

// フォームデータの取得とサニタイズ
$username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$password = $_POST['password'];
$last_name = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');
$first_name = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
$last_name_kana = htmlspecialchars($_POST['last_name_kana'], ENT_QUOTES, 'UTF-8');
$first_name_kana = htmlspecialchars($_POST['first_name_kana'], ENT_QUOTES, 'UTF-8');
$zipcode = htmlspecialchars($_POST['zipcode'], ENT_QUOTES, 'UTF-8');
$address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
$phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');

// パスワードのハッシュ化
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// メールアドレスの形式チェック
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // メールアドレスが正しい形式でない場合
    $_SESSION['register_error'] = "正しいメールアドレスの形式で入力してください。";
    header('Location: register_failure.html');
    exit();
}

// データベースへのINSERT
$sql = "INSERT INTO Users (Username, Email, Password, Last_name, First_name, Last_name_kana, First_name_kana, Zipcode, Address, Phone) VALUES (:username, :email, :password, :last_name, :first_name, :last_name_kana, :first_name_kana, :zipcode, :address, :phone)";

$stmt = $pdo->prepare($sql);

// バインド処理
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':password', $hashed_password, PDO::PARAM_STR);
$stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);
$stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
$stmt->bindValue(':last_name_kana', $last_name_kana, PDO::PARAM_STR);
$stmt->bindValue(':first_name_kana', $first_name_kana, PDO::PARAM_STR);
$stmt->bindValue(':zipcode', $zipcode, PDO::PARAM_STR);
$stmt->bindValue(':address', $address, PDO::PARAM_STR);
$stmt->bindValue(':phone', $phone, PDO::PARAM_STR);

// 実行
try {
  $stmt->execute();

  // 新しいユーザーIDを取得
  $newUserId = $pdo->lastInsertId();

  // ログイン状態をセット
  $_SESSION['user_id'] = $newUserId;
  $_SESSION['user_name'] = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');

  // 登録成功のメッセージ
  $_SESSION['register_success'] = "アカウントの作成に成功しました。";
  header('Location: register_success.php');
} catch (PDOException $e) {
  // エラーが発生した場合はメッセージをセット
  $_SESSION['register_error'] = "アカウントの作成に失敗しました。" . $e->getMessage();
  header('Location: register_failure.html');
}
?>
// 実行
// try {
//     $stmt->execute();
//     // 登録成功のメッセージ
//     $_SESSION['register_success'] = "アカウントの作成に成功しました。";
//     header('Location: register_success.php');
// } catch (PDOException $e) {
//     // エラーが発生した場合はメッセージをセット
//     $_SESSION['register_error'] = "アカウントの作成に失敗しました。" . $e->getMessage();
//     header('Location: register_failure.html');
// }
// ?>
