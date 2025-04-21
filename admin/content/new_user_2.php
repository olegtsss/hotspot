<?php

if (isset($_SESSION['user_data']['user_login'])) {

echo '
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="inputPassword3" class="col-sm-2 col-form-label">Комментарий</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="inputPassword3" placeholder="Свободный комментарий для пользователя" name="new_user_comment">
        </div>
    </div>

<button type="submit" class="btn btn-primary mb-2" id="button_exit_add_user_2" name="link" value="users">Добавить</button>
    <input type="hidden" name="page" value="'.$_SESSION['user_data']['page_memory'].'">
</form>
';

}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>



