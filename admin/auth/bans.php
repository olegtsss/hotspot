<?php

if (isset($_SESSION['user_data']['user_login'])) {

//Показать заголовок для таблицы
echo '<h5>Журнал подключений к сайту:</h5>';
include_once "content/bans_print_1.php";  
//Фильтруем пользовательский ввод параметров фильтра
$filter_parameter_bans = trim(htmlspecialchars($_POST['filter_parameter_bans']));
$submit_input = trim(htmlspecialchars($_POST['submit']));
    //Корректируем ввод на списке типов бана
    if (($filter_parameter_bans !== 'yes') && ($filter_parameter_bans !== 'no') && ($filter_parameter_bans !== 'update')) {            
        $filter_parameter_bans = null;
        $_SESSION['user_data']['filter_3'] = 'WHERE ip_addresses.ban IS NOT NULL';  
    }

    //Если нажали кнопку Обновить в фильтрах, готовим запрос фильтра для sql 
    if (($submit_input === 'yes') && ($filter_parameter_bans != null)) {
        $_SESSION['user_data']['filter_3'] = 'WHERE ip_addresses.ban = "'.$filter_parameter_bans.'"';  
    }

//var_dump($_SESSION['user_data']['filter_3']);
//То, что будет отображаться внизу
$_SESSION['user_data']['bans_print_in_filter'] = $filter_parameter_bans;     
//Итоговый фильтр в SQL запрос                     
$filter_query_3 = $_SESSION['user_data']['filter_3'];
################################

//Подсчет вообще всех записей (обязательно указан в нем итоговый фильтр)
$query_ip_all_count = "SELECT * FROM ip_addresses $filter_query_3";
$result_ip_all_count = mysqli_fetch_all(mysqli_query($connect_brute, $query_ip_all_count), MYSQLI_ASSOC);
$all_count_ip_all_count = count($result_ip_all_count);
//Универсализация параметров
$all_count_universal = $all_count_ip_all_count;        
$menu = 'bans';

####################
#Постраничный вывод:
####################
include_once "scripts/pages_button_1.php";
//Вывод всех подключения к сайту
$query_bans = "SELECT * FROM ip_addresses
    $filter_query_3
    ORDER BY ip_addresses.time_last_connect ASC LIMIT $quantity OFFSET $list";
$result_bans = mysqli_fetch_all(mysqli_query($connect_brute, $query_bans), MYSQLI_ASSOC);
$all_count_bans = count($result_bans);
    if ($result_bans != null) {
        include_once "content/bans_print_2.php";
        // Вывод кнопок перемоток на странице
        include_once "scripts/pages_button_2.php";
    }
    else {
        allert_warning();
    }

//Ввводился ли фильтр (кнопки снизу)
    if (($_SESSION['user_data']['filter_3'] != 'WHERE ip_addresses.ban IS NOT NULL') && ($_SESSION['user_data']['filter_3'] != null)) {
        //Функция вывода на экран кнопок-напоминалок, какой фильтр задан
        filter_button_current_print ($_SESSION['user_data']['bans_print_in_filter'], null, null);
    }

//Редактировать комментарии журнала подключений
    for($i = 0; $i < $all_count_bans; $i++) {
        // Это не фильтруем, так как оно просто проверяется есть ли или нет 
        // $edit_bans_comment_new = trim(htmlspecialchars($_POST["edit_bans_comment_new_$i"]));
        $edit_bans_comment = trim(htmlspecialchars($_POST["edit_bans_comment_$i"]));

        if(isset($_POST["edit_bans_comment_new_$i"]) && ($edit_bans_comment === 'изменить')) {
            $new_bans_comment = trim(htmlspecialchars($_POST["edit_bans_comment_new_$i"]));
            $bans_comment_for_change = $result_bans[$i][id];        
            $update_bans_comment = "UPDATE ip_addresses SET comment='$new_bans_comment' WHERE id = '$bans_comment_for_change'";
            mysqli_query($connect_brute, $update_bans_comment);
        }
    }
echo '<br><br>';    //Расстягивает серый контейнер за кнопки страниц


}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>