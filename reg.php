<?php
session_start();
$title = "Мафия";
require_once "head.php";
if ($_SESSION['auth']) {
    header("Location: room.php");
    exit;
}
?>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-start">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Регистарция</h5>
                    <button type="button" onclick="toIndex()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color:white;">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                    <label for="login">Логин</label>
                    <input type="text" class="form-control" id="login" name="login" placeholder="Логин">
                    </div>
                    <div class="form-group">
                    <label for="inputAddress">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Пароль">
                    </div>    
                    <div class="form-group">
                    <label for="inputAddress">Повторите пароль</label>
                    <input type="password" class="form-control" id="password2" name="password2" placeholder="Повторите пароль">
                    </div>         
                    <div style="text-align: center" id="alert"></div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <input type="button" value="Завершить" id="reg" class="btn btn-success">
                </div>
                </div>
            </div>
    </body>
</html>
<script>
    function toIndex() { window.location.href = "index.php" }
    document.getElementById('login').value=window.location.search.replace( '?', '');
    document.getElementById('reg').onclick = function() { next(); };
    function next() {
        login = document.getElementById('login').value;
        password = document.getElementById('password').value;
        password2 = document.getElementById('password2').value;
        $.ajax({
                type: "POST",
                url: 'actions/reg.php',
                data: {login: login,
                       password: password,
                       password2: password2},
                success: function(response)
                {
                    var jsonData = JSON.parse(response);
                    if (jsonData.success == 2) {
                        document.getElementById('alert').innerHTML = '<text style="color: white;">Такой пользователь уже существует!</text><a href="index.php">Авторизация</a>';
                    } else if (jsonData.success == 1) {
                        document.getElementById('alert').innerHTML = '<text style="color: white;">Данные введены не верно!</text>'; 
                    } else if (jsonData.success == 0) {
                        document.getElementById('alert').innerHTML = '<text style="color: white;">Пароли не совпадают</text>';
                    } else if (jsonData.success == 200) {
                        window.location.href="index.php";
                    } else {
                    document.getElementById('alert').innerHTML = '<text style="color: white;">Неизвестная ошибка!</text<';
                    }
            }
        }); 
    }
</script>