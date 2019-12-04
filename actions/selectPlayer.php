<?php
session_start();
if (!$_SESSION['auth']) {
    header("Location: ../room.php");
    exit;
}

require_once "db/db.php";

$login = $_SESSION['login'];
$player_id = $_POST['player_id'];

$sql = "SELECT * FROM game_1 WHERE `login`='server' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
if (mysqli_fetch_array($result)['role'] == 101){
    $sql = "SELECT * FROM room_1 WHERE `login`='$login' AND who = 0";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    $row = mysqli_fetch_array($result);
    if ($row['role'] != 5 && $row['role'] != 4 && $row['live'] != 0) { 
        if ($row['role'] == 3) {    
            $sql2 = "SELECT * FROM room_1 WHERE id=$player_id AND role = 2";
            $result2 = mysqli_query($link, $sql2) or die("Ошибка " . mysqli_error($link));
            if (mysqli_num_rows($result2) > 0) {
                $row2 = mysqli_fetch_array($result2);
                $make = "Вы сделали выбор! ".$row2['login']." мафия!";
            }  else {
                $row2 = mysqli_fetch_array($result2);
                $make = "Вы сделали выбор! ".$row2['login']." не мафия!";
            }
            $query ="UPDATE `room_1` SET who=$player_id WHERE login = '$login' ";
        }
        if ($row['role'] == 2) {    
            $sql2 = "SELECT * FROM room_1 WHERE id=$player_id";
            $result2 = mysqli_query($link, $sql2) or die("Ошибка " . mysqli_error($link));
            $row2 = mysqli_fetch_array($result2);
            $make = "Вы сделали выбор! ".$row2['login']." убит!";
            $query ="UPDATE `room_1` SET who=$player_id WHERE login = '$login' ";
        }
        if ($row['role'] == 1) {    
            $sql2 = "SELECT * FROM room_1 WHERE id=$player_id";
            $result2 = mysqli_query($link, $sql2) or die("Ошибка " . mysqli_error($link));
            $row2 = mysqli_fetch_array($result2);
            $make = "Вы сделали выбор! ".$row2['login']." вылечен!";
            $query ="UPDATE `room_1` SET who=$player_id WHERE login = '$login' ";
        }
    }
}
$sql = "SELECT * FROM game_1 WHERE `login`='server' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
if (mysqli_fetch_array($result)['role'] == 102){
    $sql = "SELECT * FROM room_1 WHERE `login`='$login' AND who = 0";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    $row = mysqli_fetch_array($result);
    if ($row['live'] != 0) { 
        $sql2 = "SELECT * FROM room_1 WHERE id=$player_id";
        $result2 = mysqli_query($link, $sql2) or die("Ошибка " . mysqli_error($link));
        $row2 = mysqli_fetch_array($result2);
        $make = "Вы сделали выбор! ".$row2['login'];
        $query ="UPDATE `room_1` SET who=$player_id WHERE login = '$login' ";
    }
}
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
echo json_encode(array('role' => $role, 'make' => $make));


?>

