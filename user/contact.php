<?php
session_start();
// ワンタイムトークンの一致を確認
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
  // トークンが一致しなかった場合
  die('お問い合わせの送信に失敗しました');;
}
//CSRF対策

// 必須項目の確認
if(empty($_POST['name'])) {
  $_SESSION['flash']['name'] = 'お名前は必須項目です';
}

$_SESSION['original']['name'] = $_POST['name']; // nameに入力があった場合、一旦セッションに保存

if(empty($_POST['name'])) {
  $_SESSION['flash']['email'] = 'メールアドレスは必須項目です';
}

$_SESSION['original']['email'] = $_POST['email']; // emailに入力があった場合、一旦セッションに保存
$_SESSION['original']['message'] = $_POST['message']; // messageに入力があった場合、一旦セッションに保存

// nameまたはemailのどちらかが入力されていなければ、contact.phpへリダイレクト
if (empty($_POST['name']) || empty($_POST['email'])) {
  header('Location: ./contactform.php');
}



mb_language("Japanese");
//↑マルチバイトの言語設定を日本語にします

mb_internal_encoding("UTF-8");
//↑マルチバイトの文字エンコーディングをUTF-8にします

if($_POST) {
  $to = 'youraddress@example.com';
  //↑このお問い合わせフォームに入力された内容を送る先のメールアドレス。
  //通常は、お問い合わせフォームがあるホームページを管理している人のメールアドレスです。

  $subject = 'お問い合わせがありました';
  //↑送信されるメールの題名です。

  //↓以下は、送信するメールの本文です。1行ずつ$messageに追記する形です。
  // \nは、改行の意味。
  $message = "お問い合わせがありました。\n";
  $message .= "\n";
  $message .= "入力された内容は以下の通りです。\n";
  $message .= "---\n";
  $message .= "\n";
  $message .= "お名前：\n";
  $message .= $_POST["name"]; // name属性がnameの内容が入ります
  $message .= "\n";
  $message .= "メールアドレス:\n";
  $message .= $_POST["email"]; // name属性がemailの内容が入ります
  $message .= "\n";
  $message .= "お問い合わせ本文:\n";
  $message .= $_POST["message"]; // name属性がmessageの内容が入ります

  //↓最後に、設定した内容でメールを送る命令です
  if(mb_send_mail($to,$subject,$message)) {
    echo "お問い合わせありがとうございます";
    echo "<br>";
    echo "メールが送信されました。";
  } else {
    echo "メールの送信に失敗しました";
  }
} else {
  echo "HTMLからのPOST送信受信に失敗しました";
}
