<?php
// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

// POSTリクエストの場合のみ処理を実行
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // フォームからのデータを取得
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    // 入力データのバリデーション（適切なバリデーションが必要に応じて追加してください）
    $errors = [];
    if (empty($name)) {
        $errors["name"] = "お名前を入力してください。";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "正しいメールアドレスを入力してください。";
    }
    if (empty($message)) {
        $errors["message"] = "お問い合わせ内容を入力してください。";
    }

    // バリデーションエラーがない場合のみデータベースに登録
    if (empty($errors)) {
        try {
            // SQL文を作成
            $sql = "INSERT INTO inquiries (name, email, message) VALUES (?, ?, ?)";
            // プリペアドステートメントを作成
            $stmt = $pdo->prepare($sql);
            // プリペアドステートメントに値をバインドして実行
            $stmt->execute([$name, $email, $message]);

            // 成功メッセージを表示（あるいはリダイレクトなど適切な処理を追加してください）
            echo "お問い合わせが送信されました。";
        } catch (PDOException $e) {
            // エラーメッセージを表示（あるいはログに記録など適切な処理を追加してください）
            echo "エラーが発生しました：" . $e->getMessage();
        }
    } else {
        // エラーメッセージを表示（あるいはエラーメッセージをセッションに保存して遷移など適切な処理を追加してください）
        echo "入力エラーがあります。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせ完了</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <p>お問い合わせありがとうございます。3営業日以内に担当よりご連絡いたしますので、今しばらくお待ちくださいませ。</p>
    <a href="../index.php">ホームに戻る</a>
</body>

</html>
