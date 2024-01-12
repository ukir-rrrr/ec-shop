<?php
// // データベース接続
// require_once("../Databaseclass/Databaseclass.php");
// $pdo = connectToDatabase($host, $dbname, $username, $password);

// // POSTリクエストの場合のみ処理を実行
// if ($_SERVER["REQUEST_METHOD"] === "POST") {
//     // フォームからのデータを取得
//     $name = $_POST["name"];
//     $email = $_POST["email"];
//     $message = $_POST["message"];

//     // 入力データのバリデーション
//     $errors = [];
//     if (empty($name)) {
//         $errors["name"] = "お名前を入力してください。";
//     }
//     if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         $errors["email"] = "正しいメールアドレスを入力してください。";
//     }
//     if (empty($message)) {
//         $errors["message"] = "お問い合わせ内容を入力してください。";
//     }

//     // バリデーションエラーがない場合のみデータベースに登録
//     if (empty($errors)) {
//         try {
//             // SQL文を作成
//             $sql = "INSERT INTO inquiries (name, email, message) VALUES (?, ?, ?)";
//             // プリペアドステートメントを作成
//             $stmt = $pdo->prepare($sql);
//             // プリペアドステートメントに値をバインドして実行
//             $stmt->execute([$name, $email, $message]);

//             // 成功メッセージを表示
//             echo "お問い合わせが送信されました。";
//         } catch (PDOException $e) {
//             // エラーメッセージを表示
//             echo "エラーが発生しました：" . $e->getMessage();
//         }
//     } else {
//         // エラーメッセージを表示
//         echo "入力エラーがあります。";
//     }
// }
?>



<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせフォーム</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <form action="contact.php" method="POST">
      <h2 class="form-header">お問い合わせ</h2>
      <div>
          <label for="name" class="form-label">お名前：</label>
          <input type="text" id="name" name="name" value="<?php echo isset($original['name']) ? $original['name'] : null;?>" required/>
          <?php echo isset($flash['name']) ? $flash['name'] : null ?>
      </div>
      <div>
          <label for="email" class="form-label">メールアドレス：</label>
          <input type="email" id="email" name="email" value="<?php echo isset($original['email']) ? $original['email'] : null;?>" required/>
          <?php echo isset($flash['email']) ? $flash['email'] : null ?>
      </div>
      <div>
          <label for="message" class="form-label">お問い合わせ本文</label>
          <textarea id="message" name="message"><?php echo isset($original['message']) ? $original['message'] : null;?></textarea>
      </div>
      <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
      <button type="submit" class="form-button">送信</button>
    </form>
</body>

</html>
