<?php

echo '<br>
<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Ошибка!</h4>
    <p>Авторизация не пройдена. Попытка '.$_SESSION['user_data']['bruteforce'].'</p>
    <hr>
    <p class="mb-0">Попробуйте снова!</p>
</div>
<br>';


?>