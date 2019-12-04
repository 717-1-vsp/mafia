<?php
session_start();
if (!$_SESSION['auth']) {
    header("Location: ../room.php");
    exit;
}

require_once "db/db.php";

$login = $_SESSION['login'];

$sql = "SELECT * FROM room_1 WHERE `login`='$login' AND role <> 0";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
if (mysqli_num_rows($result) > 0) {    
    $row = mysqli_fetch_array($result);
    $role = $row['role'];  
    if ($role == 5 || $role == 4) {
        $role = "Мирный житель";
    }
    elseif ($role == 3) {
        $role = "Комиссар";
    }
    elseif ($role == 2) {
        $role = "Мафия";
    }
    elseif ($role == 1) {
        $role = "Доктор";
    } else {
        header('Location: takeRole.php');
    }
}
echo json_encode(array('role' => $role));


?>

