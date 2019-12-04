<?php
  session_start();

  if(!$_SESSION['auth'])
    header('Location: ../index.php');
  require_once "quitRoom.php";
  

  $_SESSION['user_login'] = NULL;
  $_SESSION['user_id'] = NULL;
  $_SESSION['auth'] = false;

  header('Location: ../index.php');
  exit;

?>
