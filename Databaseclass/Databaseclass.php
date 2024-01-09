<?php

// データベース接続情報
$host = 'localhost';
$dbname = 'ecshop_db';
$username = 'ecshop_user';
$password = 'ecshop_pass';

// データベースに接続する関数
function connectToDatabase($host, $dbname, $username, $password) {
    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $pdo = new PDO($dsn, $username, $password, $options);
        return $pdo;
    } catch (PDOException $e) {
        die("データベースに接続できません: " . $e->getMessage());
    }
}


function sanitize($before) {
    foreach($before as $key => $value) {
        $after[$key] = htmlspecialchars($value, ENT_QUOTES,"UTF-8");
    }
    return $after;
}

?>
