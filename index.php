<?php
session_start();
if ($_SESSION['auth']) {
    header("Location: actions/checkRoom.php?goto=room");
    exit;
}
$title = "Мафия";
require_once "head.php";
?>
    <div class="d-flex justify-content-center" style="margin-top: -10vh;">
      <button type="button" class="btn btn-start" data-toggle="modal" data-target="#start">
        НАЧАТЬ ИГРУ
      </button>
    </div>
    <div class="d-flex justify-content-center">
      <button onclick="reg()" class="btn btn-rules">
        Регистрация
      </button>&nbsp;
      <button type="button" class="btn btn-rules" data-toggle="modal" data-target="#rules">
        Правила
      </button>
    </div>

<!-- Modal -->
<div class="modal fade" id="rules" tabindex="-1" role="dialog" aria-labelledby="rulesTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-start">
      <div class="modal-header">
        <h5 class="modal-title" id="rulesTitle">Правила</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>В игре принимает участие 5 челове. Для того что бы начать игру необхоимо авторизироваться. Если у Вас ещё нет 
        аккаунта то просто <a href="reg.php" onclick="reg()">нажмите сюда</a> </p>
        <p>После автоизации вы можете выбрать поравившуюся комнату. Как только в команате наберется нужное количество игроков игра начнтся автоматически.</p>
        <p>В игре писутвуют: мафия, доктор, шериф и 2 мирных жителя.</p>
        <p>Мафия - ночью убивает одного из игроков, если её не помешает доктор.</p> 
        <p>Доктор - ночью может вылечить игрока в которого стреляла мафия.</p>
        <p>Шериф - ночью может попытаться найти мафию</p>
        <p>Мирный житель - просто хороший человек.</p>
        <p>Ночь продолжается в течении 2 минут. День длится 4 минуты. Днём игроки голосуют, пытаясь определить кто мафия, есть возможность обсуждения.</p>
        <p>Удачной игры!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
    <!-- Modal -->
    <div class="modal fade" id="start" tabindex="-1" role="dialog" aria-labelledby="startTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-start">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Авторизируйтесь</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" style="color:white;">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="login">Логин</label>
              <input type="text" class="form-control" id="login" placeholder="Логин">
            </div>
            <div class="form-group">
              <label for="inputAddress">Пароль</label>
              <input type="password" class="form-control" id="password" placeholder="Пароль">
            </div>            
            <div style="text-align: center" id="alert"></div>
          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button type="button" id="next" class="btn btn-next">Далее</button>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
<script>
  document.getElementById('next').onclick = function() { next(); };
  function reg() { window.location.href = "reg.php"; }
  function next() {
    login = document.getElementById('login').value;
    password = document.getElementById('password').value;
    $.ajax({
            type: "POST",
            url: 'actions/auth.php',
            data: { login: login,
                  password: password },
            success: function(response)
            {
                var jsonData = JSON.parse(response);
                if (jsonData.success == 0) {
                  document.getElementById('alert').innerHTML = '<text style="color: brown;">Такого пользователя не существует!</text><a href="reg.php?'+login+'">Регистрация</a>';
                } else if (jsonData.success == "1") {
                  document.getElementById('alert').innerHTML = '<text style="color: brown;">Данные введены не верно!</text>'; 
                } else if (jsonData.success == 0) {
                  document.getElementById('alert').innerHTML = '<text style="color: white;">Отсутствует соединение с сервером</text>';
                } else if (jsonData.success == 200) {                 
                  window.location.href="actions/checkRoom.php?goto=room";
                } else {
                  document.getElementById('alert').innerHTML = '<text style="color: white;">Неизвестная ошибка!</text<';
                }
           }
       }); 
  }
</script>