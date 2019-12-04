<?php
session_start();
if (!$_SESSION['auth']) {
    header("Location: index.php");
    exit;
}
$title = "Комнаты";
require_once "head.php";
?>  
    <audio autoplay loop preload="auto" id="audio">
        <source src="music/audio_07.mp3" type="audio/mpeg">
    </audio>


    <div class="d-flex justify-content-center">
        <p style="margin-top: 20vh;"><span id='room'></span></p>
        <br>
    </div>
    <div class="d-flex justify-content-center" style="visibility: hidden">
        <div class="progress" style="height: 40px; width: 80vw;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" id="progress" role="progressbar" style="width: 20%;" aria-valuenow="1" aria-valuemin="0" aria-valuemax="5">1</div>
        </div>
    </div>
    <br>
    <div class="d-flex justify-content-center" id="drop" style="visibility: visible">
        <div class="dropdown">
            <button class="btn btn-danger dropdown-toggle" type="button" id="rooms" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Выберите комнату
            </button>
            <div class="dropdown-menu bg-danger" aria-labelledby="rooms" style="cursor: pointer">
                <a class="dropdown-item" onclick="conTo1()">1 Комната</a>
                <a class="dropdown-item" onclick="conTo2()">2 Комната</a>
                <a class="dropdown-item" onclick="conTo3()">3 Комната</a>
            </div>
        </div>
    </div>   
    <br>
    <div class="d-flex justify-content-sm-between" id="players" style="margin: 1vh;">
        
    </div>
    <div style="position: fixed; right: 1vw; top: 1vh;">
        <a id="reload" href="#"><span style="color: rgb(204, 31, 31); font-size: 2.8em" class="fa fa-repeat"></span></a>
        <a id="range" href="#"><span style="color: rgb(204, 31, 31); font-size: 3em" class="fa fa-volume-up"></span></a>
        <a href="actions/exit.php"><span style="color: rgb(204, 31, 31); font-size: 3em" class="fa fa-sign-out"></span></a>
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
    room = '<?php echo $_SESSION['room'];?>';
    function quit() {
        $.ajax({
            type: "POST",
            url: 'actions/quitRoom.php',
            data: { login: "<?php echo $_SESSION['login']; ?>"},
            success: function(response)
            {
                document.getElementById('room').innerHTML = 'Вы покинули комнату'; 
                var jsonData = JSON.parse(response);
                document.getElementById('progress').innerHTML =  jsonData.players;
                document.getElementById('progress').style.width = jsonData.players*20+"%";   
                document.getElementById('drop').innerHTML = ''; 
            }
        });         
        function hide() { document.getElementById('room').innerHTML = '';
                document.getElementById('progress').style.visibility = "hidden"; 
                location.reload();
                document.getElementById('drop').innerHTML = `
                <div class="dropdown">
                    <button class="btn btn-danger dropdown-toggle" type="button" id="rooms" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Выберите комнату
                    </button>
                    <div class="dropdown-menu bg-danger" aria-labelledby="rooms" style="cursor: pointer">
                        <a class="dropdown-item" onclick="conTo1()">1 Комната</a>
                        <a class="dropdown-item" onclick="conTo2()">2 Комната</a>
                        <a class="dropdown-item" onclick="conTo3()">3 Комната</a>
                    </div>
                </div>`;}
        setTimeout(hide, 1000);
    }
    
    function conTo1() {
        document.getElementById('room').innerHTML =  'Вы выбрали комнату №1';
        $.ajax({
            type: "POST",
            url: 'actions/inRoom.php',
            data: { login: "<?php echo $_SESSION['login']; ?>",
                    room: "room_1"},
            success: function(response)
            {
                document.getElementById('progress').style.visibility = "visible";
                document.getElementById('drop').innerHTML = '<button class="btn btn-danger" onclick="quit()" type="button" id="quit"> Покинуть игру </button>';
                var jsonData = JSON.parse(response);
            }
        });         
    }    
    if (room == 'room_1') {
        conTo1();
    }
    document.getElementById('reload').onclick = function(){
        window.location.reload();
    }
    function check() {
        if(audio.paused)
            audio.play();
        $.ajax({
            type: "POST",
            url: 'actions/checkRoom.php',
            success: function(response)
            {
                var jsonData = JSON.parse(response);
                document.getElementById('players').innerHTML =  jsonData.col;
                document.getElementById('progress').innerHTML =  jsonData.players+'/5';
                document.getElementById('progress').style.width = jsonData.players*20+"%";
                if (jsonData.players >= 5)
                    window.location.href = "game.php?room="+room;
            }
        });  
    }
    check();
    setInterval(check, 5000);
</script>