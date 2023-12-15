<?php

session_start();

// フォームからのデータを取得
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password']; // パスワードはハッシュ化する前の状態で取得
$last_name = $_POST['last_name'];
$first_name = $_POST['first_name'];
$last_name_kana = $_POST['last_name_kana'];
$first_name_kana = $_POST['first_name_kana'];
$zipcode = $_POST['zipcode'];
$address = $_POST['address'];
$phone = $_POST['phone'];

// エラーメッセージ用の変数
$errors = [];

// 各フィールドの検証
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "メールアドレスの形式が正しくありません。";
}

if (!preg_match("/^[ぁ-んァ-ン一-龥]/", $last_name)) {
    $errors[] = "氏に不適切な文字が含まれています。";
}

if (!preg_match("/^[ぁ-んァ-ン一-龥]/", $first_name)) {
    $errors[] = "名に不適切な文字が含まれています。";
}

if (!preg_match("/^[ァ-ヶー]+$/u", $last_name_kana)) {
    $errors[] = "セイに不適切な文字が含まれています。";
}

if (!preg_match("/^[ァ-ヶー]+$/u", $first_name_kana)) {
    $errors[] = "メイに不適切な文字が含まれています。";
}

if (!preg_match("/^\d{7}$/", $zipcode)) {
    $errors[] = "郵便番号の形式が正しくありません。";
}

if (!preg_match("/^\d{10,11}$/", $phone)) {
    $errors[] = "電話番号の形式が正しくありません。";
}

// エラーがある場合はフォームに戻る
if (count($errors) > 0) {
  // エラーメッセージの処理
  $_SESSION['errors'] = $errors;
  header('Location: register.html');
  exit;
}

// エラーがなければ、セッション変数にデータを保存
$_SESSION['form_data'] = $_POST;

// 確認画面へリダイレクト
header('Location: register_confirm.html');
exit;
?>
