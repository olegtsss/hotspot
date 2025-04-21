<?php

if (isset($_SESSION['user_data']['user_login'])) {

echo '
<br>
<form method="post">
    <div class="form-group row">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Новый кофебар</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="inputEmail3" placeholder="Город" name="new_coffeepoint_city">
        </div>
    </div>

    <div class="form-group row">
        <label for="inputEmail3" class="col-sm-2 col-form-label"></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="inputEmail3" placeholder="Бар" name="new_coffeepoint_name">
        </div>
    </div>

    <div class="form-group row">
        <label for="inputPassword3" class="col-sm-2 col-form-label"></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="inputPassword3" placeholder="Свободный комментарий для бара" name="new_coffeepoint_comment">
        </div>
    </div>

    <button type="submit" class="btn btn-primary mb-2" id="button_exit_add_user" name="link" value="coffeepoints">Добавить</button>
</form>

<form method="post">
    <button type="submit" class="btn btn-primary" id="button_update_page" name="link" value="coffeepoints">Обновить</button>
</form>
<br>
';

}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>
