<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION["login"]) === false) {
    echo "ログインしていません。<br><br>";
    echo "<a href='../staff/staff_login.php'>ログイン画面へ</a>";
    exit();
}

if (isset($_POST["add"]) === true) {
    header("Location:product_add.php");
    exit();
}

if (isset($_POST["disp"]) === true) {
    if (isset($_POST["id"]) === false) {
        header("Location:product_ng.php");
        exit();
    }
    $code = $_POST["id"];
    header("Location:product_disp_kids.php?code=".$code);
    exit();
}

if(isset($_POST["edit"]) === true) {
    if(isset($_POST["id"]) === false) {
        header("Location:product_ng.php");
        exit();
    }
    $code = $_POST["id"];
    header("Location:product_edit_kids.php?code=".$code);
    exit();
}

if(isset($_POST["delete"]) === true) {
    if(isset($_POST["id"]) === false) {
        header("Location:product_ng.php");
        exit();
    }
    $code = $_POST["id"];
    header("Location:product_delete_kids.php?code=".$code);
    exit();
}
?>
