<?php
session_start();
if ($_SESSION['auth']) {
    header("Location: ../room.php");
    exit;
}

require_once "db/db.php";
require_once "salt.php";

$login = $_POST['login'];
$password = $_POST['password'];
 

if (empty($password) || empty($login)) {
    echo json_encode(array('success' => 1));
                    exit();
}

$sql = "SELECT * FROM users WHERE login='$login'";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
$myrow = mysqli_fetch_array($result);
if (empty($myrow['id'])) {
    echo json_encode(array('success' => 0));
    exit();
}

if ($myrow['password']==md5(md5($myrow['salt']).md5($password))) {
    $_SESSION['login'] = $myrow['login'];
    $_SESSION['id'] = $myrow['id'];
    $_SESSION['auth'] = true;
    $_SESSION['music'] = 1;
    $sql = "UPDATE users SET lastAuth = NOW() WHERE login='$login'";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    echo json_encode(array('success' => 200));
    exit();
} else {
    echo json_encode(array('success' => 2));
    exit();
} 

?>