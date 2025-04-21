<?php

if (isset($_SESSION['user_data']['user_login'])) {

//Показать заголовок для таблицы
echo '<h5>Пользователи Hotspot:</h5>';
// Форма добавления нового пользователя
include_once "content/new_user_1.php";
//Вывод всех баров списком на кнопке
$result_coffeepoints = bd_all_coffeepoints($connect);
$all_count_coffeepoints = count($result_coffeepoints);
//Сам список внутри кнопки
    for($i = 0; $i < $all_count_coffeepoints; $i++) {
        echo '<option value="'.$result_coffeepoints[$i][coffeepoints_id].'">'.$result_coffeepoints[$i][coffeepoints_city]." ".$result_coffeepoints[$i][coffeepoints_point_name]." ".$result_coffeepoints[$i][coffeepoints_comment].'</option>';
    }
include_once "content/new_user_2.php";
$new_user = trim(htmlspecialchars($_POST['new_user']));
$new_user_city = trim(htmlspecialchars($_POST['new_user_city']));
$new_user_comment = trim(htmlspecialchars($_POST['new_user_comment']));
    if(($new_user != null) && ($new_user_city != null)) {
        //Проверка, есть ли уже в базе данных такой телефон
        $exist_user = "SELECT * FROM users WHERE users_telefon = '$new_user'";
        $check_user =  mysqli_fetch_assoc(mysqli_query($connect, $exist_user));
            if (!isset($check_user)) {
                $add_new_user_query = "INSERT INTO users (users_telefon, users_first_reg, users_comment) VALUES ('$new_user', '$new_user_city', '$new_user_comment')";
                mysqli_query($connect, $add_new_user_query);
                success_window('телефонный номер');
            }
            else {
                allert_window('телефонный номер');
            }
    }

###########################
# Фильтр:
###########################
include_once "content/new_user_3.php";
//Вывод всех баров списком на кнопке для фильтра
//Сам список внутри кнопки
    for($i = 0; $i < $all_count_coffeepoints; $i++) {
        echo '<option value="'.$result_coffeepoints[$i][coffeepoints_id].'">'.$result_coffeepoints[$i][coffeepoints_city]." ".$result_coffeepoints[$i][coffeepoints_point_name]." ".$result_coffeepoints[$i][coffeepoints_comment].'</option>';
    }
include_once "content/new_user_4.php";
//Фильтруем пользовательский ввод параметров фильтра
$filter_parameter_user_input = trim(htmlspecialchars($_POST['filter_parameter_user_input']));
$filter_parameter_coffeepoint_input = trim(htmlspecialchars($_POST['filter_parameter_coffeepoint_input']));
/*echo '$filter_parameter_coffeepoint_input = '; var_dump($filter_parameter_coffeepoint_input); echo '<br>';*/
$submit_input = trim(htmlspecialchars($_POST['submit']));
    //Корректируем ввод на списке баров
    if ($filter_parameter_coffeepoint_input === 'Вывести на экран все бары') {
        $filter_parameter_coffeepoint_input = null;
        $_SESSION['user_data']['coffeepoint_print_in_filter_2'] = null;
/*echo '$filter_parameter_coffeepoint_input = '; var_dump($filter_parameter_coffeepoint_input); echo '<br>';*/
    }
        //Если нажали кнопку Обновить в фильтрах, готовим запрос фильтра для sql 
        if ($submit_input === 'yes') {
            //Обнуляем куку для нижней кнопки, с введенным значением фильтра
            $_SESSION['user_data']['coffeepoint_print_in_filter_2_normal'] = null;
            //Проверка вводился ли бар
            //Обязательно != , так как это одновременно и null и ''
            if ($filter_parameter_coffeepoint_input != null) {
                $filter_parameter_coffeepoint_query = 'users.users_first_reg = "'.$filter_parameter_coffeepoint_input.'"';
                $filter_parameter_coffeepoint_query_all = ' AND '.$filter_parameter_coffeepoint_query;
/*echo '$filter_parameter_coffeepoint_query_all = '; var_dump($filter_parameter_coffeepoint_query_all);  echo '<br>';*/
            }
            else {
                $filter_parameter_coffeepoint_query_all = null;
            }
            //Проверка вводился ли номер
            if ($filter_parameter_user_input !== '') {
                $filter_parameter_user_query = 'users.users_telefon = "'.$filter_parameter_user_input.'"';
                $filter_parameter_user_query_all = ' AND '.$filter_parameter_user_query;    
            }
            else {
                $filter_parameter_user_query_all = null;
            }
            
            //Запоминаем фильтр
            $_SESSION['user_data']['filter_2'] = 'WHERE users.users_telefon IS NOT NULL'.$filter_parameter_coffeepoint_query_all.$filter_parameter_user_query_all;
            //То, что будет отображаться внизу
            $_SESSION['user_data']['user_print_in_filter_2'] = $filter_parameter_user_input;
            //Пересчитать id бара в его нормальное имя
                if ($filter_parameter_coffeepoint_input != null) {
                    $_SESSION['user_data']['coffeepoint_print_in_filter_2'] = $filter_parameter_coffeepoint_input;
                    $filter_parameter_coffeepoint_input = $_SESSION['user_data']['coffeepoint_print_in_filter_2'];
                    $query_name_again_coffeepoints = "SELECT * FROM coffeepoints WHERE coffeepoints_id = '$filter_parameter_coffeepoint_input'";
                    $result_coffeepoints_normal = mysqli_fetch_assoc(mysqli_query($connect, $query_name_again_coffeepoints));
                        if (isset ($result_coffeepoints_normal)) $_SESSION['user_data']['coffeepoint_print_in_filter_2_normal'] = $result_coffeepoints_normal['coffeepoints_city'].' '.$result_coffeepoints_normal['coffeepoints_point_name'].' '.$result_coffeepoints_normal['coffeepoints_comment'];                      
                }
    }

//Итоговый фильтр в SQL запрос                     
$filter_query_2 = $_SESSION['user_data']['filter_2'];
################################

//Подсчет вообще всех записей (обязательно указан в нем итоговый фильтр)
$query_users_all_count = "SELECT * FROM users $filter_query_2";
$result_users_all_count = mysqli_fetch_all(mysqli_query($connect, $query_users_all_count), MYSQLI_ASSOC);
$all_count_users_all_count = count($result_users_all_count);
//Универсализация параметров
$all_count_universal = $all_count_users_all_count;        
$menu = 'users';

####################
#Постраничный вывод:
####################
include_once "scripts/pages_button_1.php";
//Вывод всех пользователей Hotspot
$query_users = "SELECT * FROM users 
    INNER JOIN coffeepoints on users.users_first_reg = coffeepoints.coffeepoints_id
    $filter_query_2
    ORDER BY users.users_telefon ASC LIMIT $quantity OFFSET $list";
/*echo '$_SESSION[user_data][filter_2] = '; var_dump($_SESSION['user_data']['filter_2']); echo '<br>';
echo '$_SESSION[user_data][user_print_in_filter_2] = '; var_dump($_SESSION['user_data']['user_print_in_filter_2']); echo '<br>';
echo '$_SESSION[user_data][coffeepoint_print_in_filter_2] = '; var_dump($_SESSION['user_data']['coffeepoint_print_in_filter_2']); echo '<br>';
echo '$_SESSION[user_data][coffeepoint_print_in_filter_2_normal] = '; var_dump($_SESSION['user_data']['coffeepoint_print_in_filter_2_normal']); echo '<br>';*/
$result_users = mysqli_fetch_all(mysqli_query($connect, $query_users), MYSQLI_ASSOC);
$all_count_users = count($result_users);
if ($result_users != null) {
    include_once "content/users_print.php";
    // Вывод кнопок перемоток на странице
    include_once "scripts/pages_button_2.php";
}
else {
    allert_warning();
}

//Ввводился ли фильтр (кнопки снизу)
    if (($_SESSION['user_data']['filter_2'] != 'WHERE users.users_telefon IS NOT NULL') && ($_SESSION['user_data']['filter_2'] != null)) {
        //Функция вывода на экран кнопок-напоминалок, какой фильтр задан
        filter_button_current_print ($_SESSION['user_data']['user_print_in_filter_2'], $_SESSION['user_data']['coffeepoint_print_in_filter_2_normal'], null);
    }
    #############################
    # Редактировать комментарии:
    #############################
    for($i = 0; $i < $all_count_users; $i++) {
        // Это не фильтруем, так как оно просто проверяется есть ли или нет 
        // $edit_users_comment_new = trim(htmlspecialchars($_POST["edit_users_comment_new_$i"]));
        $edit_users_comment = trim(htmlspecialchars($_POST["edit_users_comment_$i"]));
        $edit_users_delete = trim(htmlspecialchars($_POST["edit_users_delete_$i"]));

        if(isset($_POST["edit_users_comment_new_$i"]) && ($edit_users_comment === 'изменить')) {
            $new_users_comment = trim(htmlspecialchars($_POST["edit_users_comment_new_$i"]));
            $users_telefony_for_change = $result_users[$i][users_telefon];        
            $update_users_comment = "UPDATE users SET users_comment='$new_users_comment' WHERE users_telefon = '$users_telefony_for_change'";
            mysqli_query($connect, $update_users_comment);
        }
        if($edit_users_delete === 'удалить') {
            $users_for_delete = $result_users[$i][users_telefon];
            $delete_user_query = "DELETE FROM users WHERE users_telefon = '$users_for_delete'";
                if (!mysqli_query($connect, $delete_user_query)) {
                    echo '<br>';
                    allert_window_foreign_key();
                }
        }
    }
    echo '<br><br>';    //Расстягивает серый контейнер за кнопки страниц



}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}
?>