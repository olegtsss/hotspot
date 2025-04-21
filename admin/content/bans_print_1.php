<?php

if (isset($_SESSION['user_data']['user_login'])) {

echo '
<form method="post">
    <div class="form-group row">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Указать тип</label>
        <div class="col-sm-10">
            <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref" name="filter_parameter_bans">
                <option selected>Вывести на экран все типы бана</option>
                <option>yes</option>
                <option>no</option>
                <option>update</option>
            </select>
        </div>
    </div>

    <button type="submit" class="btn btn-primary" id="button_update_page_2" name="link" value="bans">Обновить</button>
    <input type="hidden" name="page" value="'.--$_SESSION['user_data']['page_memory'].'">
    <input type="hidden" name="submit" value="yes">
</form>
<br>
';

}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>