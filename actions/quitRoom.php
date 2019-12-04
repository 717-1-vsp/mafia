<?php
session_start();
if (!$_SESSION['auth']) {
    header("Location: ../index.php");
    exit;
}
require_once "db/db.php";

$login = $_SESSION['login'];
if ($_SESSION['room']) {
    $sql = "SELECT * FROM ".$_SESSION['room']." WHERE `login`='$login'";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    $row = mysqli_num_rows($result);
    if ($row != 0) {
        $sql = "DELETE FROM ".$_SESSION['room']." where `login`='$login'";
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    }
    $sql = "INSERT INTO `game_1` (`login`, `text`, `time`, `role`) VALUES ('$login', 'Покинул игру...', NOW(), 2)";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));

    $sql = "SELECT * FROM ".$_SESSION['room']." WHERE 1";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    $rows = mysqli_num_rows($result);
    echo json_encode(array('players' => $rows));
    $_SESSION['room'] = NULL;
}

?>