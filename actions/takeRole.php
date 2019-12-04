<?php
session_start();
if (!$_SESSION['auth']) {
    header("Location: ../room.php");
    exit;
}

require_once "db/db.php";

$login = $_SESSION['login'];
$sql = "SELECT * FROM room_1 WHERE login = '$login' AND role <> 0 ";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
if (mysqli_num_rows($result) == 0) {
    $sql = "SELECT * FROM room_1 WHERE role=5";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    if(mysqli_num_rows($result) == 0)
        $query ="UPDATE `room_1` SET role=5 WHERE login = '$login' ";
    else {
        $sql = "SELECT * FROM room_1 WHERE role=3";
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
        if (mysqli_num_rows($result) == 0)
            $query ="UPDATE `room_1` SET role=3 WHERE login = '$login' ";
        else {
            $sql = "SELECT * FROM room_1 WHERE role=4";
            $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
            if (mysqli_num_rows($result) == 0)
                $query ="UPDATE `room_1` SET role=4 WHERE login = '$login' ";
            else {
                $sql = "SELECT * FROM room_1 WHERE role=2";
                $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
                if (mysqli_num_rows($result) == 0)
                    $query ="UPDATE `room_1` SET role=2 WHERE login = '$login' ";
                else {
                    $sql = "SELECT * FROM room_1 WHERE role=1";
                    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
                    if (mysqli_num_rows($result) == 0)
                        $query ="UPDATE `room_1` SET role=1 WHERE login = '$login' ";
                }
            }
        }
    }
    $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
}
?>

