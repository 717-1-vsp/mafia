<?php
session_start();
if (!$_SESSION['auth']) {
    header("Location: ../room.php");
    exit;
}

require_once "db/db.php";

$clear = false;

$sql = "SELECT * FROM game_1 WHERE `role`= 2 AND TIME(NOW()) - `time` < 3";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $mess .= "<p>- Игрок ".$row['login']." покинул игру...<i><small>".$row['time']."</small></i></p>";  
}

$sql = "SELECT * FROM game_1 WHERE `role`= 1 AND TIME(NOW()) - `time` < 3";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $mess .= "<p>- Игрок ".$row['login']." вошёл в игру...<i><small>".$row['time']."</small></i></p>";   

}

$sql = "SELECT * FROM room_1 WHERE NOW() - `lastActive` > 60";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
while ($row = mysqli_fetch_array($result)) {
    //дата
    $time = mysqli_query($link, "SELECT TIME(NOW()) as time") or die("Ошибка " . mysqli_error($link));
    $mess .= "<p>- Удалён игрок ".$row['login']."<i><small>".mysqli_fetch_array($result)['time']."</small></i></p>";
    $sql = "DELETE FROM `room_1` where `id`=".$row['id'];
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
}

$sql = "SELECT * FROM game_1 WHERE TIME(NOW()) - `time` > 120 ORDER BY id DESC LIMIT 1";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
while (mysqli_fetch_array($result)['role'] == 101) {
    $sql = "SELECT * FROM room_1 WHERE who = 0 AND role >= 1 AND role <=3 AND live <> 0";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    while ($row = mysqli_fetch_array($result)) {
        //дата
        $time = mysqli_query($link, "SELECT TIME(NOW()) as time") or die("Ошибка " . mysqli_error($link));
        $mess .= "<p>- Удалён игрок ".$row['login']."<i><small>".mysqli_fetch_array($result)['time']."</small></i></p>";
        $sql = "DELETE FROM `room_1` where `id`=".$row['id'];
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    }
}

$sql = "SELECT * FROM game_1 WHERE TIME(NOW()) - `time` > 240 ORDER BY id DESC LIMIT 1";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
while (mysqli_fetch_array($result)['role'] == 102) {
    $sql = "SELECT * FROM room_1 WHERE who = 0 AND live <> 0";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    while ($row = mysqli_fetch_array($result)) {
        //дата
        $time = mysqli_query($link, "SELECT TIME(NOW()) as time") or die("Ошибка " . mysqli_error($link));
        $mess .= "<p>- Удалён игрок ".$row['login']."<i><small>".mysqli_fetch_array($result)['time']."</small></i></p>";
        $sql = "DELETE FROM `room_1` where `id`=".$row['id'];
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    }
}

$sql = "SELECT * FROM game_1 WHERE role = 11 OR role = 100";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
if (mysqli_num_rows($result) == 1) {
    //дата
    $result = mysqli_query($link, "SELECT TIME(NOW()) as time") or die("Ошибка " . mysqli_error($link));
    $mess .= "<p>- Время пошло <i><small>".mysqli_fetch_array($result)['time']."</small></i></p>";
    $sql = "INSERT INTO `game_1` (`login`, `text`, `time`, `role`, `action`) VALUES ('server', 'Время пошло!', NOW(), 100, 'time')";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
}

$sql = "SELECT * FROM game_1 WHERE 1 ORDER BY id DESC LIMIT 1 ";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
$row = mysqli_fetch_array($result);
$sql2 = "SELECT * FROM room_1 WHERE role >= 1 AND role <=5  AND live <> 0";
$result2 = mysqli_query($link, $sql2) or die("Ошибка " . mysqli_error($link));
$sql3 = "SELECT * FROM room_1 WHERE who <> 0 AND live <> 0";
$result3 = mysqli_query($link, $sql3) or die("Ошибка " . mysqli_error($link));
if (mysqli_num_rows($result3) == mysqli_num_rows($result2) && $row['role']==102) {
    $say = "";
    $sql = "SELECT who, COUNT(who) as count FROM `room_1` WHERE who <> 0 GROUP BY who ORDER BY count DESC";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    $row = mysqli_fetch_array($result);          
    $sql = "SELECT * FROM room_1 WHERE id = ".$row['who'];
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    $row = mysqli_fetch_array($result); 
    $sql = "UPDATE room_1 SET live=0 WHERE id=".$row['id'];
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    $sql2 = "SELECT * FROM room_1 WHERE live=1";
        $result2 = mysqli_query($link, $sql2) or die("Ошибка " . mysqli_error($link));             
        if (mysqli_num_rows($result2) < 3)
            while ($row = mysqli_fetch_array($result2))
                if ($row['role'] == 2) {
                    $end = "Мафия победила!";
                    $_SESSION['end'] = true;
                } else {
                    $end = "Мафия проиграла!";
                    $_SESSION['end'] = true;
                }
    $say = "Днём убили ".$row['login']."! ".$end;
    $result = mysqli_query($link, "SELECT TIME(NOW()) as time") or die("Ошибка " . mysqli_error($link));
    $mess .= "<p>- Город засыпает <i><small>".mysqli_fetch_array($result)['time']."</small></i></p>";
    $sql = "INSERT INTO `game_1` (`login`, `text`, `time`, `role`, `action`) VALUES ('server', 'Город засыпает!', NOW(), 101, 'time')";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    $sql = "UPDATE room_1 SET who=0";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
}

$sql = "SELECT * FROM room_1 WHERE role = 2 AND live = 0";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
if (mysqli_num_rows($result)>0) {
    $_SESSION['end']=true;
    $sql = "INSERT INTO `game_1` (`login`, `text`, `time`, `role`, `action`) VALUES ('server', 'Мафия проиграла!', NOW(), 105, 'time')";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
}

$sql = "SELECT * FROM game_1 WHERE 1 ORDER BY id DESC LIMIT 1 ";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
$row = mysqli_fetch_array($result);
$sql2 = "SELECT * FROM room_1 WHERE role >= 1 AND role <=3 AND live = 1";
$result2 = mysqli_query($link, $sql2) or die("Ошибка " . mysqli_error($link));
$sql3 = "SELECT * FROM room_1 WHERE who <> 0  AND live = 1";
$result3 = mysqli_query($link, $sql3) or die("Ошибка " . mysqli_error($link));
if (mysqli_num_rows($result3) == mysqli_num_rows($result2) && $row['role']==101) {
    //дата        
    $say = "";
    $sql = "SELECT * FROM room_1 WHERE role >= 2 AND role <= 3";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    $row = mysqli_fetch_array($result);
    $row2 = mysqli_fetch_array($result);
    if ($row['who'] == $row2['who'])
        $say = "Ночью никого не убили!";
    else {
        $end = "";
        $sql = "SELECT * FROM room_1 WHERE role = 2";
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
        $row = mysqli_fetch_array($result);        
        $sql = "SELECT * FROM room_1 WHERE id = ".$row['who'];
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
        $row = mysqli_fetch_array($result);
        $sql = "UPDATE room_1 SET live=0 WHERE id=".$row['id'];   
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));        
        $sql2 = "SELECT * FROM room_1 WHERE live=1";
        $result2 = mysqli_query($link, $sql2) or die("Ошибка " . mysqli_error($link));             
        if (mysqli_num_rows($result2) < 3)
            while ($row = mysqli_fetch_array($result2))
                if ($row['role'] == 2) {
                    $end = "Мафия победила!";
                    $_SESSION['end'] = true;
                } else {
                    $end = "Мафия проиграла!";
                    $_SESSION['end'] = true;
                }
        $say = "Ночью убили ".$row['login']."! ".$end;
    }
    $result = mysqli_query($link, "SELECT TIME(NOW()) as time") or die("Ошибка " . mysqli_error($link));
    $mess .= "<p>- Город просыпается <i><small>".mysqli_fetch_array($result)['time']."</small></i></p>";
    $sql = "INSERT INTO `game_1` (`login`, `text`, `time`, `role`, `action`) VALUES ('server', '$say', NOW(), 102, 'time')";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));    
    $sql = "UPDATE room_1 SET who=0";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
}

$sql = "SELECT * FROM room_1 WHERE role <> 0";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
$sql2 = "SELECT * FROM game_1 WHERE role = 101";
$result2 = mysqli_query($link, $sql2) or die("Ошибка " . mysqli_error($link));
if ( mysqli_num_rows($result) == 5 && mysqli_num_rows($result2) == 0)  {
    //дата
    $sql = "SELECT * FROM room_1 WHERE role = 2";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    if (mysqli_num_rows($result) == 0) {
        $sql = "SELECT * FROM room_1 WHERE 1";
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
        $row = mysqli_fetch_array($result);
        $query ="UPDATE `room_1` SET role=2 WHERE login = '".$row['login']."'";
        mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
    }
    $sql = "SELECT * FROM room_1 WHERE role = 1";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    if (mysqli_num_rows($result) > 2) {
        $row = mysqli_fetch_array($result);
        $query ="UPDATE `room_1` SET role=5 WHERE login = '".$row['login']."'";
        mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
    }
    $sql = "SELECT * FROM room_1 WHERE role = 3";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    if (mysqli_num_rows($result) > 2) {
        $row = mysqli_fetch_array($result);
        $query ="UPDATE `room_1` SET role=5 WHERE login = '".$row['login']."'";
        mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
    }
    $sql = "UPDATE room_1 SET who=0";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    $result = mysqli_query($link, "SELECT TIME(NOW()) as time") or die("Ошибка " . mysqli_error($link));
    $mess .= "<p>- Город засыпает <i><small>".mysqli_fetch_array($result)['time']."</small></i></p>";
    $sql = "INSERT INTO `game_1` (`login`, `text`, `time`, `role`, `action`) VALUES ('server', 'Город засыпает!', NOW(), 101, 'time')";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
}

$sql = "SELECT * FROM room_1 WHERE 1";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
$sqlr = "SELECT * FROM game_1 WHERE `role`= 11";
$resultr = mysqli_query($link, $sqlr) or die("Ошибка " . mysqli_error($link));
if (mysqli_num_rows($result) >= 5 && mysqli_num_rows($resultr) == 0) {
    $sql = "INSERT INTO `game_1` (`login`, `text`, `time`, `role`, `action`) VALUES ('server', 'Игра началась!', NOW(), 11, 'start')";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    //дата
    $result = mysqli_query($link, "SELECT TIME(NOW()) as time") or die("Ошибка " . mysqli_error($link));
    $mess .= "<p>- Игра началась! <i><small>".mysqli_fetch_array($result)['time']."</small></i></p>";   
}  elseif ((mysqli_num_rows($result) < 5  && mysqli_num_rows($resultr) > 0) || $_SESSION['end']) {
    sleep(10);
    $_SESSION['end'] = false;    
    $sql = "DELETE FROM `game_1` WHERE 1";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    $sql = "INSERT INTO `game_1` (`login`, `text`, `time`, `role`) VALUES ('server', 'Игра окончена!', NOW(), 404)";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    $sql = "UPDATE room_1 SET role=0, who=0, live=1";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    //дата
    $result = mysqli_query($link, "SELECT TIME(NOW()) as time") or die("Ошибка " . mysqli_error($link));
    $mess .= "<p>- Игра окончена! <i><small>".mysqli_fetch_array($result)['time']."</small></i></p>";
    $clear = true;
}
    echo json_encode(array('message' => $mess, 'clear' => $clear));
?>