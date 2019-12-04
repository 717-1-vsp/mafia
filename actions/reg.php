<?php 
session_start();
if ($_SESSION['auth']) {
    header("Location: room.php");
    exit;
}
require_once "db/db.php";
require_once "salt.php";

$login = $_POST['login'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
    if (empty($password) || empty($login)) {
        echo json_encode(array('success' => 1));
                        exit();
    }

    if ($password != $password2) {
        echo json_encode(array('success' => 0)); 
                        exit();
    }

    $query = 'SELECT * FROM users WHERE login="'.$login.'"';
    $isLoginFree = mysqli_fetch_assoc(mysqli_query($link, $query));
    if (!empty($isLoginFree)) {
        echo json_encode(array('success' => 2));  
                        exit();
    }

    $salt = generateSalt();

    $pass = md5(md5($salt).md5($password));

    $sql = "INSERT INTO users (`login`, `password`, `salt`) VALUES('$login', '$pass', '$salt')";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    if ($result==true) {
        echo json_encode(array('success' => 200)); 
    } else {
        echo json_encode(array('success' => 3)); 
        exit();
    }
?>