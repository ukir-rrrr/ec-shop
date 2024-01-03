<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION["login"]) === false) {
    print "ログインしていません。<br><br>";
    print "<a href='../staff/staff_login.php'>ログイン画面へ</a>";
    exit();
} else {
    print $_SESSION["name"]."さんログイン中";
    print "<br><br>";
}

// データベース接続
require_once("../Databaseclass/Databaseclass.php");
$pdo = connectToDatabase($host, $dbname, $username, $password);

// 商品情報をメンズカテゴリから取得
$category_id = 1;
$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ?");
$stmt->execute([$category_id]);

echo "商品一覧<br><br>";
echo "<form action='product_branch.php' method='post'>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  echo "<input type='radio' name='id' value='".$row['id']."'>";
  echo $row['name'] . " --- 価格: " . intval($row['price']) . "円";
  echo "<br>";
}

echo "<br>";
echo "<input type='submit' name='disp' value='詳細'>";
echo "<input type='submit' name='add' value='追加'>";
echo "<input type='submit' name='edit' value='修正'>";
echo "<input type='submit' name='delete' value='削除'>";
echo "<br><br>";
echo "<a href='product_top.php'>戻る</a>";
echo "</form>";
