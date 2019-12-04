<?php
session_start();
if (!$_SESSION['auth']) {
    header("Location: ../room.php");
    exit;
}

require_once "db/db.php";

$login = $_SESSION['login'];

$sql = "SELECT * FROM game_1 WHERE `login`='server' AND role = 102 ORDER BY id DESC LIMIT 1";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $text = $row['text'];
    $time = $row['time'];
    $action = $row['action'];
    $message = "<p>$text</p>";
}
echo json_encode(array('message' => $message, 'action' => $action));
?>