<?php
session_start();

// セッションを破棄
session_destroy();

// ログアウト後、ログアウト完了ページにリダイレクト
header("Location: logout_complete.php");
exit;
?>
