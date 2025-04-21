<?php

if (isset($_SESSION['user_data']['user_login'])) {

echo '
<br>
</p>
<form class="form-inline" method="post">
  <div class="form-group mb-2">
    <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="Изменить пароль:">
  </div>

  <div class="form-group mx-sm-3 mb-2">
    <input type="password" class="form-control" id="inputPassword2" name="new_password_for_change" placeholder="Новый пароль">
  </div>

  <button type="submit" class="btn btn-primary mb-2" name="confirm_change_password" value="yes">Подтвердить</button>
</form>

<div class="form-group">
  <form action="" method="post">
    <button type="submit" class="btn btn-primary" id="button_exit" name="submit" value="exit">Выйти</button>
  </form>
</div>
';

}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>
