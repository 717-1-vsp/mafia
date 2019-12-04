<?php
session_start();
if (!$_SESSION['auth']) {
    header("Location: index.php");
    exit;
}
if (!$_GET['room'] || !$_SESSION['room']) {
    header("Location: ../room.php");
    exit;
}
$title = "Мафия: 1 комната";
require_once "head.php";
?>  

        <audio autoplay loop preload="auto" id="audio">
            <source src="music/audio_05.mp3" type="audio/mpeg">
        </audio>
        <div style="position: fixed; right: 1vw; top: 1vh; z-index: 100;">
            <a id="reload" href="#"><span style="color: rgb(204, 31, 31); font-size: 2.8em" class="fa fa-repeat"></span></a>
            <a id="range" href="#"><span style="color: rgb(204, 31, 31); font-size: 3em" class="fa fa-volume-up"></span></a>
            <a href=""><span onclick="quit()" style="color: rgb(204, 31, 31); font-size: 3em" class="fa fa-stop-circle"></span></a>
            <a href="actions/exit.php"><span style="color: rgb(204, 31, 31); font-size: 3em" class="fa fa-sign-out"></span></a>
        </div>
        <div class="container">
            <div class="justify-content-sm-between">
                <div class="w-auto p-3" style="padding-top: 20px; min-width: 100%">
                    <div class="card">
                        <h5 class="card-header"><p>ВАША РОЛЬ: <small id="message"></small></p><p id="time"></p><p id="do"></p></h5>
                        <div class="card-body" style="overflow-y: scroll; height: 30vh;" id="action"> </div>
                    </div> 
                </div>   
            </div>
            <div class="d-flex justify-content-sm-between" id="card"></div>
            <div class="d-flex justify-content-sm-between" id="players"></div>
            <div class="col">
                <div id="history" style="text-align: center; margin: 10vh 0vh 0vh 0vh;"></div> 
            </div>
        </div>
    </body>
</html>
<script>
    var audio = document.getElementById('audio');
    var range = document.getElementById('range');
    audio.volume = <?php echo $_SESSION['music']?>;
    if (audio.volume == 0){
        range.innerHTML = '<span style="color: rgb(204, 31, 31); font-size: 3em" class="fa fa-volume-off"></span>';
    }
    else {
        range.innerHTML = '<span style="color: rgb(204, 31, 31); font-size: 3em" class="fa fa-volume-up"></span>';
    }
    range.onclick = function(){
        if (audio.volume == 1){
            this.innerHTML = '<span style="color: rgb(204, 31, 31); font-size: 3em" class="fa fa-volume-off"></span>';
            $.post('actions/music.php?on=1');
            audio.volume = 0;
        }
        else {
            this.innerHTML = '<span style="color: rgb(204, 31, 31); font-size: 3em" class="fa fa-volume-up"></span>';
            $.post('actions/music.php?on=0');
            audio.play();
            audio.volume = 1;
        }
    }
    document.getElementById('reload').onclick = function(){
        window.location.reload();
    }
    load = true;
    function quit() {
        $.ajax({
            type: "POST",
            url: 'actions/quitRoom.php'
        }); 
        window.location.href="room.php";
    }
    actionIs = false
    function check() {
        if(audio.paused)
            audio.play();
        $.ajax({
            type: "POST",
            url: 'actions/checkRoom.php',
            success: function(response)
            {
                var jsonData = JSON.parse(response);
                if (jsonData.players < 5 || !jsonData.players)
                    window.location.href="room.php";
                document.getElementById('players').innerHTML = jsonData.col;
            }
        });  
    }
    check();
    setInterval(check, 5000);


    takeRole = false;
    sleep = true;
    function check_time() {
        $.ajax({
        type: "POST",
        url: 'actions/checkTime.php',
        success: function(response)
        {
            var jsonData = JSON.parse(response);
            time = jsonData.time;
            action = jsonData.action;
            text = jsonData.text;
            role = jsonData.role;
            if (role=='100' && Number(time) <= 120 && !takeRole)
                start(time)
            if (role=='100' && Number(time) > 120 && !takeRole)
                take_role()
            if (role=='101' && Number(time) <= 120)
                start_night(time)
            if (role=='101' && Number(time) > 120)
                stop_night()
            if (role=='102' && Number(time) <= 240)
                start_day(time)
            if (role=='102' && Number(time) > 240)
                stop_day()
                
        }
        }); 
    }
    my_role = '';
    setInterval(check_time, 1000);
    function check_role() {
        $.ajax({
        type: "POST",
        url: 'actions/checkRole.php',
        success: function(response)
        {
            var jsonData = JSON.parse(response);
            if (jsonData.role) {
                document.getElementById('message').innerHTML = jsonData.role;
                my_role = jsonData.role;
            }
        }
        }); 
    }
    check_role();
    setInterval(check_role, 5000);
    
    function take_role() { 
        $.ajax({ type: "POST", url: 'actions/takeRole.php' }); 
        takeRole = true; 
        check_role();
        document.getElementById('card').remove();
    }
    function select_player(player_id) {
        $.ajax({
        type: "POST",
        url: 'actions/selectPlayer.php',
        data: { player_id : player_id },
        success: function(response)
        {
            var jsonData = JSON.parse(response);
            if (jsonData.make) {
                document.getElementById('action').innerHTML += jsonData.make;
            }
        }
        }); 
    }
    function start_night(time) {
        if (document.getElementById('card')) document.getElementById('card').remove();
        document.getElementById('time').innerHTML = 'Город спит. Игроки делаютт свой выбор. Осталось ' + (120 - Number(time));
    }

    function stop_night() {
        document.getElementById('do').innerHTML = '<h6>Ночь закончилась.</h6>';
    }

    function start_day(time) {
        $.ajax({
        type: "POST",
        url: 'actions/night.php',
        success: function(response)
        {
            var jsonData = JSON.parse(response);
            document.getElementById('do').innerHTML = jsonData.message;
        }
        }); 
        document.getElementById('time').innerHTML = 'Город проснулся. Игроки начинаю голосование. Осталось ' + (240 - Number(time));
    }

    function stop_day() {
        document.getElementById('do').innerHTML = '<h6>День заершился</h6>';
    }

    function start(time) {
        document.getElementById('time').innerHTML = "У вас осталось " + (120 - Number(time));
        document.getElementById('card').innerHTML = `<div class="w-auto p-3"><div class="card card-play" style="margin-left: auto; margin-right: auto;"><img src="img/back.jpg" onclick="take_role()" id="card1" class="play-card"></div></div>
            <div class="w-auto p-3"><div class="card card-play" style="margin-left: auto; margin-right: auto;"><img src="img/back.jpg" id="card2" onclick="take_role()" class="play-card"></div></div>
            <div class="w-auto p-3"><div class="card card-play" style="margin-left: auto; margin-right: auto;"><img src="img/back.jpg" id="card3" onclick="take_role()" class="play-card"></div></div>
            <div class="w-auto p-3"><div class="card card-play" style="margin-left: auto; margin-right: auto;"><img src="img/back.jpg" id="card4" onclick="take_role()" class="play-card"></div></div>
            <div class="w-auto p-3"><div class="card card-play" style="margin-left: auto; margin-right: auto;"><img src="img/back.jpg" id="card5" onclick="take_role()" class="play-card"></div></div>`;
    }
</script>