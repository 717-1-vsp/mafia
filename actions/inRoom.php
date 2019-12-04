<?php
session_start();
if (!$_SESSION['auth']) {
    header("Location: room.php");
    exit;
}

require_once "db/db.php";

$room = $_POST['room'];
$_SESSION['room'] = $_POST['room'];
$login = $_POST['login'];
$sql = "SELECT * FROM $room WHERE `login`='$login'";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
$row = mysqli_num_rows($result);
if ($row == 0) {
    $sql = "INSERT INTO `room_1`(`login`, `role`, `live`, `lastActive`, `img`) VALUES ('$login', '$role', 1, NOW(), '$img')";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    $sql = "INSERT INTO `game_1` (`login`, `text`, `time`, `role`) VALUES ('$login', 'Вошёл в игру...', NOW(), 1)";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
}

$sql = "SELECT * FROM $room WHERE 1";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
$rows = mysqli_num_rows($result);
echo json_encode(array('players' => $rows));
?>