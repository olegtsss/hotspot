<?php

if (isset($_SESSION['user_data']['user_login'])) {

echo '
<br>
<form method="post">
    <div class="form-group row">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Новый пользователь</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="inputEmail3" placeholder="Номер телефона" name="new_user">
        </div>
    </div>

    <div class="form-group row">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Откуда пришел</label>
        <div class="col-sm-10">
            <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref" name="new_user_city">
                <option selected>Город, бар, комментарий для бара</option>
';

}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>