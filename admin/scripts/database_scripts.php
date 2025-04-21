<?php

//Функция авторизации пользователя
function user_reg($connect_brute) {
    $new_user_login = trim(htmlspecialchars($_POST['login']));
    $new_user_pass = trim(htmlspecialchars($_POST['pass']));
    //Ничего не фильтруем, так как работает просто isset
if((isset($_POST['login'])) && (isset($_POST['pass'])) && (isset($_POST['submit']))) {     
        $check_query = "SELECT * FROM users WHERE name = '$new_user_login'";
        $result_arr = mysqli_fetch_assoc(mysqli_query($connect_brute, $check_query));
        //Проверки на брутфорсы
        bruteforce_ip ($connect_brute, $_SERVER['REMOTE_ADDR']);
        bruteforce_session ();
        //Проверка логина и пароля
        ########################################
        # Вычисление хеша от введенного пароля
        ########################################
        if((isset($result_arr['name'])) && (password_verify($new_user_pass, $result_arr['password']))) {
        //if((isset($result_arr['name'])) && ($new_user_pass === $result_arr['password'])) {
            //Удаляем переменные, отвечающие за брутфорс
            $_SESSION['user_data']['bruteforce'] = null;
            //Присваиваем данные для входа
            $_SESSION['user_data']['user_id'] = $result_arr['id'];
            $_SESSION['user_data']['user_login'] = $result_arr['name'];
            //Добавляем информацию об успешной авторизации в базу брута
            $try_loging_time_success = date("Y-m-d H:i:s");            
            bd_bruteforce_success($connect_brute, $_SERVER['REMOTE_ADDR'], $try_loging_time_success);
            //Решает проблему с возвратом НАЗАД в браузере (возвращаем назад и функция
            //определит авторизовн ли пользователь или нет)
            header('Location: /');
        }
    }
}

//Функция изменения пароля в БД
function bd_data_user_pass_set($connect) {
    $user_login = $_SESSION['user_data']['user_login'];
    ########################################
    # Вычисление хеша от введенного пароля
    # (varchar(90))
    ########################################
    $new_password = password_hash(trim(htmlspecialchars($_POST['new_password_for_change'])), PASSWORD_DEFAULT);
    #$new_password = trim(htmlspecialchars($_POST['new_password_for_change']));
    $update_pass_query = "UPDATE users SET password='$new_password' WHERE name = '$user_login'";
    mysqli_query($connect, $update_pass_query);
}

//Функция получения информации о всех барах
function bd_all_coffeepoints ($connect) {
    $query_coffeepoints = "SELECT * FROM coffeepoints ORDER BY coffeepoints.coffeepoints_id ASC";
    $result_coffeepoints = mysqli_fetch_all(mysqli_query($connect, $query_coffeepoints), MYSQLI_ASSOC);
return $result_coffeepoints;
}

//Функция вывода на экран ошибки
function allert_window($type) {
    echo '
    <br>
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Ошибка!</h4>
        <p>База данных содержит указанный '.$type.'</p>
        <hr>
        <p class="mb-0">Пробуй другой '.$type.'</p>
    </div>
    <br>';
}

//Функция вывода на экран успеха
function success_window($type) {
    echo '
    <br>
    <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Отлично!</h4>
            <p>Новый '.$type.' добавлен в базу данных</p>
    </div>
    <br>';
}

//Функция вывода на экран ошибки удаления из базы данных
function allert_window_foreign_key() {
    echo '
    <br>
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Ошибка!</h4>
        <p>Невозможно удалить запись</p>
        <hr>
        <p class="mb-0">Журнал регистраций содержит информацию о ней</p>
    </div>
    <br>';
}

//Функция вывода на экран предупреждения, что в базе нет информации
function allert_warning() {
    echo '
    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">Пусто</h4>
        <p>Получен пустой вывод</p>
        <hr>
        <p class="mb-0">Пробуй другие параметры для фильтра</p>
    </div>
    ';
}

//Функция вывода на экран кнопок для вывода страниц
function page_button_print ($page_value, $button_value, $menu) {
    echo '
    <form method="post">
        <input type="hidden" name="page" value="'.$page_value.'">
        <input type="hidden" name="link" value="'.$menu.'">
        <input type="submit" class="myclass_page" value="'.$button_value.'">
    </form>';
}

//Функция вывода на экран кнопок для вывода страниц (текущая страница)
function page_button_print_current ($page_value, $button_value, $menu) {
    echo '
    <form method="post">
        <input type="hidden" name="page" value="'.$page_value.'">
        <input type="hidden" name="link" value="'.$menu.'">
        <input type="submit" class="myclass_page_current" value="'.$button_value.'">
    </form>';
}

//Функция вывода на экран кнопок-напоминалок, какой фильтр задан
function filter_button_current_print ($var1, $var2, $var3) {
    echo '
    <br><br>
    <p>&nbsp Указаны фильтры:</p>
    <span class="badge badge-secondary id="filter_output">'.$var1.'</span>
    <span class="badge badge-secondary id="filter_output">'.$var2.'</span>
    <span class="badge badge-secondary id="filter_output">'.$var3.'</span>
    ';
}

//Функция экспорта в exel
function export_exel_button ($link) {
    echo '
    <form method="post">
        <input type="submit" class="btn btn-primary" id="button_update_page_3" value="Экспорт в Exel">
        <input type="hidden" name="export" value="yes">
        <input type="hidden" name="link" value="'.$link.'">
        <input type="hidden" name="page" value="'.$_SESSION['user_data']['page_memory'].'">
    </form>';
}

//Функция вывода на экран успеха экспорта
function success_export_window($file) {
    echo '
    <br>
    <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Отлично!</h4>
            <p>Экспорт выполнен. Чтобы скачать файл нажмите на '.$file.'.</p>
    </div>
    <br>';
}

?>


