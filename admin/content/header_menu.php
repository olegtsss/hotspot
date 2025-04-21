<?php

if (isset($_SESSION['user_data']['user_login'])) {

echo '
<div class="database_print">
    <div class="menu">
        <form method="post">
            <button type="submit" class="btn btn-light">Регистрации</button>
            <input type="hidden" name="link" value="registrations">
        </form>
    
        <form method="post">
            <button type="submit" class="btn btn-light">Пользователи</button>
            <input type="hidden" name="link" value="users">
        </form>
    
        <form method="post">
            <button type="submit" class="btn btn-light">Бары</button>
            <input type="hidden" name="link" value="coffeepoints">
        </form>
    
        <form method="post">
            <button type="submit" class="btn btn-light">Статусы</button>
            <input type="hidden" name="link" value="statuses">
        </form>

        <form method="post">
            <button type="submit" class="btn btn-light">Подключения</button>
            <input type="hidden" name="link" value="bans">
        </form>
    </div>
</div>
';

}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>
