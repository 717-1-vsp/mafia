<?php
session_start();
if ($_GET['on']==1)
    $_SESSION['music'] = 0;
else    
    $_SESSION['music'] = 1;
?>