<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION["login"]) === false) {
    echo "ログインしていません。<br><br>";
    echo "<a href='staff_login.php'>ログイン画面へ</a>";
    exit();
} else {
    echo $_SESSION["name"]."さんログイン中";
    echo "<br><br>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // データベース接続
        require_once("../Databaseclass/Databaseclass.php");
        $pdo = connectToDatabase($host, $dbname, $username, $password);

        // POSTデータからユーザーIDとユーザーネームを取得
        $user_id = $_POST['user_id'];
        $user_name = $_POST['user_name'];

        // ユーザーを非アクティブに変更
        $stmt = $pdo->prepare("UPDATE Users SET status = 'inactive' WHERE UserID = ?");

        $stmt->execute([$user_id]);
        echo "ユーザー「{$user_name}」を退会させました。";
        echo "<br><br>";
        echo "<a href='./staff_login_top.php'>管理画面TOPへ</a>";

    } catch (Exception $e) {
        // エラーが発生した場合の処理を追加
        echo "エラーが発生しました：" . $e->getMessage();
        echo "<br><br>";
        echo "<a href='./staff_login_top.php'>管理画面TOPへ</a>";
    } finally {
        // データベース接続を閉じる
        $pdo = null;
    }
} else {
    // POSTでない場合はエラー処理やリダイレクトを行うなど
}
?>
