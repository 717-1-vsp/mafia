<?php
$host = 'localhost';
$database = 'mafia';
$user = 'root';
$password = '1234567890';
$link = mysqli_connect($host, $user, $password, $database);
if (mysqli_connect_errno()) {
  echo "Ошибка ".mysqli_connect_errno()."!\n".mysqli_connect_error();
  exit;
}
?>
