<?php

if (isset($_SESSION['user_data']['user_login'])) {

//Показать заголовок для таблицы
echo '<h5>Журнал регистраций:</h5>';
###########################
# Фильтр:
###########################
include_once "content/filter_registrations_1.php";
//Вывод всех баров списком на кнопке
$result_coffeepoints = bd_all_coffeepoints($connect);
$all_count_coffeepoints = count($result_coffeepoints);
//Сам список внутри кнопки
    for($i = 0; $i < $all_count_coffeepoints; $i++) {
        echo '<option value="'.$result_coffeepoints[$i][coffeepoints_id].'">'.$result_coffeepoints[$i][coffeepoints_city]." ".$result_coffeepoints[$i][coffeepoints_point_name]." ".$result_coffeepoints[$i][coffeepoints_comment].'</option>';
    }
include_once "content/filter_registrations_2.php";
//Фильтруем пользовательский ввод параметров фильтра
$filter_parameter_date_input = trim(htmlspecialchars($_POST['filter_parameter_date_input']));
$filter_parameter_user_input = trim(htmlspecialchars($_POST['filter_parameter_user_input']));
$filter_parameter_coffeepoint_input = trim(htmlspecialchars($_POST['filter_parameter_coffeepoint_input']));  
$submit_input = trim(htmlspecialchars($_POST['submit']));
    //Корректируем ввод на списке баров
    if ($filter_parameter_coffeepoint_input === 'Вывести на экран все бары') {
        $filter_parameter_coffeepoint_input = null;
        $_SESSION['user_data']['coffeepoint_print_in_filter'] = null;
    }
        //Если нажали кнопку Обновить в фильтрах, готовим запрос фильтра для sql 
        if ($submit_input === 'yes') {
            $_SESSION['user_data']['coffeepoint_print_in_filter_normal'] = null;
            //Проверка вводилась ли дата
            if ($filter_parameter_date_input !== '') {
                $filter_parameter_date_query = 'registration.registrations_date = "'.$filter_parameter_date_input.'"';
                $filter_parameter_date_query_all = ' AND '.$filter_parameter_date_query;    
            }
            else {
                $filter_parameter_date_query_all = null;   
            }
            //Проверка вводился ли бар
            //Обязательно != , так как это одновременно и null и ''
            if ($filter_parameter_coffeepoint_input != null) {
                $filter_parameter_coffeepoint_query = 'registration.registrations_id_coffeepoints = "'.$filter_parameter_coffeepoint_input.'"';
                $filter_parameter_coffeepoint_query_all = ' AND '.$filter_parameter_coffeepoint_query;    
            }
            else {
                $filter_parameter_coffeepoint_query_all = null;
            }
            //Проверка вводился ли номер
            if ($filter_parameter_user_input !== '') {
                $filter_parameter_user_query = 'registration.registrations_users = "'.$filter_parameter_user_input.'"';
                $filter_parameter_user_query_all = ' AND '.$filter_parameter_user_query;    
            }
            else {
                $filter_parameter_user_query_all = null;
            }
                //Запоминаем фильтр
                $_SESSION['user_data']['filter'] = 'WHERE registration.registrations_id IS NOT NULL'.$filter_parameter_date_query_all.$filter_parameter_coffeepoint_query_all.$filter_parameter_user_query_all;
                //То, что будет отображаться внизу
                $_SESSION['user_data']['date_print_in_filter'] = $filter_parameter_date_input;
                $_SESSION['user_data']['user_print_in_filter'] = $filter_parameter_user_input;
                //Пересчитать id бара в его нормальное имя
                if ($filter_parameter_coffeepoint_input != null) {
                    $_SESSION['user_data']['coffeepoint_print_in_filter'] = $filter_parameter_coffeepoint_input;
                    $query_name_again_coffeepoints = "SELECT * FROM coffeepoints WHERE coffeepoints_id = '$filter_parameter_coffeepoint_input'";
                    $result_coffeepoints_normal = mysqli_fetch_assoc(mysqli_query($connect, $query_name_again_coffeepoints));
                        if (isset ($result_coffeepoints_normal)) $_SESSION['user_data']['coffeepoint_print_in_filter_normal'] = $result_coffeepoints_normal['coffeepoints_city'].' '.$result_coffeepoints_normal['coffeepoints_point_name'].' '.$result_coffeepoints_normal['coffeepoints_comment'];  
                }                    
        }
             
$filter_query = $_SESSION['user_data']['filter'];
//echo $filter_query;
################################

//Подсчет вообще всех записей
$query_registrations_all_count = "SELECT * FROM registration $filter_query";
$result_registrations_all_count = mysqli_fetch_all(mysqli_query($connect, $query_registrations_all_count), MYSQLI_ASSOC);
$all_count_registrations_all_count = count($result_registrations_all_count);

//Универсализация параметров
$all_count_universal = $all_count_registrations_all_count;
$menu = 'registrations';
####################
#Постраничный вывод:
####################
include_once "scripts/pages_button_1.php";
//Вывод журнала регистраций
$query_registrations = "SELECT * FROM registration 
    INNER JOIN coffeepoints on registration.registrations_id_coffeepoints = coffeepoints.coffeepoints_id
    INNER JOIN status on registration.registrations_status_code = status.status_id
    $filter_query
    ORDER BY registration.registrations_id DESC LIMIT $quantity OFFSET $list";
$result_registrations = mysqli_fetch_all(mysqli_query($connect, $query_registrations), MYSQLI_ASSOC);
$all_count_registrations = count($result_registrations);
    if ($result_registrations != null) {
        include_once "content/registartions_print.php";
        // Вывод кнопок перемоток на странице
        include_once "scripts/pages_button_2.php";        
    }
    else {
        allert_warning();
    }
    //Ввводился ли фильтр
    if ($_SESSION['user_data']['filter'] != 'WHERE registration.registrations_id IS NOT NULL') {
        //Функция вывода на экран кнопок-напоминалок, какой фильтр задан
        filter_button_current_print ($_SESSION['user_data']['date_print_in_filter'], $_SESSION['user_data']['user_print_in_filter'], $_SESSION['user_data']['coffeepoint_print_in_filter_normal']);
    }

echo '<br><br>';    //Расстягивает серый контейнер за кнопки страниц



}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>