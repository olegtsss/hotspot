<?php

if (isset($_SESSION['user_data']['user_login'])) {

##############################
// Устанавливаем количество записей, которые будут выводиться на одной странице
$quantity=50;
// Ограничиваем количество ссылок, которые будут выводиться перед и после текущей страницы
$limit=5;
##############################

include_once "content/header.php";
echo 'выполнен вход пользователя '.$_SESSION['user_data']['user_login'];
include_once "content/header_form.php";

//Фильтруем форму post для смены пароля ($_POST['new_password_for_change'] не надо, так как просто проверяется isset-ом, а фильтруется уже в теле функции)
$confirm_change_password = trim(htmlspecialchars($_POST['confirm_change_password']));
    if(isset($_POST['new_password_for_change']) && ($confirm_change_password === 'yes')) {
        bd_data_user_pass_set($connect_brute);
    }

include_once "content/header_menu.php";
//Фильтруем форму post для кнопок меню
$link = trim(htmlspecialchars($_POST['link']));
    
    ###########################
    # Журнал регистраций:
    ###########################
    if ((!isset($link)) || ($link === 'registrations') || ($link == null)) {
        include_once "auth/registrations.php";
        # Экспорт в Exel:
        include_once "export_registrations.php";
    }
    
    ##########################
    # Пользователи:
    ##########################
    elseif ($link === 'users') {
        include_once "auth/users.php";
        # Экспорт в Exel:
        include_once "export_users.php";
    }  

    ##########################
    # Бары:
    ##########################
    elseif ($link === 'coffeepoints') {
        include_once "auth/coffeepoints.php";
    }
    
    ###########################
    # Статусы:
    ###########################
    elseif ($link === 'statuses') {
        include_once "auth/statuses.php";
    }
    
    ###########################
    # Подключения к сайту:
    ###########################
    elseif ($link === 'bans') {
        include_once "auth/bans.php"; 
    }

include_once "content/footer.php";



}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>
