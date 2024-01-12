<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // フォームから送信されたデータを取得
    $inquiryId = $_POST['inquiry_id'];
    $status = $_POST['status'];

    // データベース接続
    require_once("../Databaseclass/Databaseclass.php");
    $pdo = connectToDatabase($host, $dbname, $username, $password);

    try {
        // 対応状況を更新
        $stmt = $pdo->prepare("UPDATE inquiries SET status = ? WHERE id = ?");
        $stmt->execute([$status, $inquiryId]);

        // 更新が成功した場合は、元のページにリダイレクトするなどの処理を行う
        header("Location: staff_contact.php");
        exit();
    } catch (Exception $e) {
        // エラーが発生した場合はエラーメッセージを表示
        echo "更新中にエラーが発生しました：" . $e->getMessage();
    }
} else {
    // POST メソッド以外でアクセスされた場合の処理
    echo "Invalid request.";
}
?>
