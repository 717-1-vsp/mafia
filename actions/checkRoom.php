<?php
session_start();
if (!$_SESSION['auth']) {
    header("Location: ../room.php");
    exit;
}

require_once "db/db.php";

if ($_GET['goto'] == 'room') {header("Location: ../room.php"); exit;}
$login = $_SESSION['login'];
$sql = "SELECT * FROM room_1 WHERE `login`='$login'";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
$row = mysqli_fetch_array($result);
if ($row['id']) {
    $_SESSION['room'] = 'room_1';
    $id = (int)$row['id']%10;
    $img = 'img/avatar_00.png';
    $sql = "UPDATE `room_1` SET `lastActive`=NOW(), `img`='$img' WHERE `login`='$login'";
    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
} else $_SESSION['room'] = NULL;
$sql = "SELECT * FROM ".$_SESSION['room']." WHERE 1";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
$rows = mysqli_num_rows($result);
while ($row = mysqli_fetch_array($result)){
    if ($row['live'] == 0)
        $opacity = "opacity: 0.5; ";
    else
        $opacity = "opacity: 1; ";
    $col .= '
        <div class="w-auto p-3">
            <div class="card" id="'.$row['id'].'" onclick="select_player(this.id)" style="'.$opacity.' margin-left: auto; margin-right: auto; margin-top: 1vh;">
                <img src="'.$row['img'].'" style="hieght: 17vh; width: 17vh;  margin-left: auto; margin-right: auto;">
                    <div class="card-body">
                    <small class="card-title">'.$row['login'].'</small>
                </div>
            </div>
        </div>
    ';
}
$col .= '
        <div class="w-auto p-3">
            <div class="card" style="opacity: 0.5; margin-left: auto; margin-right: auto; margin-top: 1vh;">
                <div class="card-body">
                    <img src="img/avatar_11.jfif" style="hieght: 17vh; width: 17vh;  margin-left: auto; margin-right: auto;">
                    <small>Ведущий</small>
                </div>
            </div>
        </div>
    ';
$sql = "SELECT * FROM game_1 WHERE `login`='server' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $text = $row['text'];
    $time = $row['time'];
    $action = $row['action'];
    $message = "<p>$text</p>";
}
echo json_encode(array('players' => $rows, 'col' => $col, 'message' => $message, 'action' => $action));
?>
