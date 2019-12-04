<?php

if ($_GET['key']!='50454e4953')
    header('Location: index.php');

require_once "head.php";

?>

        <span id="message"></span>
    </body>
</html>
<script>
    
    
    //Проверка на присутствие игроков в сети
    function check() {
        $.ajax({
            type: "POST",
            url: 'actions/checker.php',
            success: function(response)
            {
                var jsonData = JSON.parse(response);
                if (jsonData.clear)
                    document.getElementById('message').innerHTML = "";
                if (jsonData.message)
                    document.getElementById('message').innerHTML += jsonData.message;
            }
        }); 
    } 
    check(); 
    setInterval(check, 3000);
</script>