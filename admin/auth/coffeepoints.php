<?php

if (isset($_SESSION['user_data']['user_login'])) {

//Показать заголовок для таблицы
echo '<h5>Кофебары:</h5>';
// Форма добавления нового бара
include_once "content/new_coffeepoint.php";    
$new_coffeepoint_city = trim(htmlspecialchars($_POST['new_coffeepoint_city']));
$new_coffeepoint_name = trim(htmlspecialchars($_POST['new_coffeepoint_name']));
$new_coffeepoint_comment = trim(htmlspecialchars($_POST['new_coffeepoint_comment']));
    if(($new_coffeepoint_city != null) && ($new_coffeepoint_name != null)) {
        //Проверка, есть ли уже в базе данных такой бар
        $exist_coffeepoint = "SELECT * FROM coffeepoints WHERE coffeepoints_city = '$new_coffeepoint_city' AND coffeepoints_point_name = '$new_coffeepoint_name'";
        $check_coffeepoint_exist =  mysqli_fetch_assoc(mysqli_query($connect, $exist_coffeepoint));
            if (!isset($check_coffeepoint_exist)) {
                $new_coffeepoint_id = uniqid();
                $new_coffeepoint_device_name = 'addme_'.$new_coffeepoint_id;
                $add_new_coffeepoint_query = "INSERT INTO coffeepoints (coffeepoints_id, coffeepoints_device, coffeepoints_city, coffeepoints_point_name, coffeepoints_comment) VALUES ('$new_coffeepoint_id', '$new_coffeepoint_device_name', '$new_coffeepoint_city', '$new_coffeepoint_name', '$new_coffeepoint_comment')";
                mysqli_query($connect, $add_new_coffeepoint_query);
                success_window('бар');
            }
            else {
                allert_window('бар');
            }
    }
//Вывод всех баров
$result_coffeepoints = bd_all_coffeepoints($connect);
$all_count_coffeepoints = count($result_coffeepoints);
include_once "content/coffeepoints_print.php";
    //Редактировать бары
    for($i = 0; $i < $all_count_coffeepoints; $i++) {
        // Это не фильтруем, так как оно просто проверяется есть ли или нет 
        // $edit_coffeepoints_city_new = trim(htmlspecialchars($_POST["edit_coffeepoints_city_new_$i"]));
        $edit_coffeepoints_city = trim(htmlspecialchars($_POST["edit_coffeepoints_city_$i"]));
        $edit_coffeepoints_name = trim(htmlspecialchars($_POST["edit_coffeepoints_name_$i"]));
        $edit_coffeepoints_comment = trim(htmlspecialchars($_POST["edit_coffeepoints_comment_$i"]));
        $edit_coffeepoints_delete = trim(htmlspecialchars($_POST["edit_coffeepoints_delete_$i"]));

        if(isset($_POST["edit_coffeepoints_city_new_$i"]) && ($edit_coffeepoints_city === 'изменить')) {
            $new_coffeepoints_city = trim(htmlspecialchars($_POST["edit_coffeepoints_city_new_$i"]));
            $coffeepoints_id_for_change = $result_coffeepoints[$i][coffeepoints_id];        
            $update_coffeepoints_city = "UPDATE coffeepoints SET coffeepoints_city='$new_coffeepoints_city' WHERE coffeepoints_id = '$coffeepoints_id_for_change'";
            mysqli_query($connect, $update_coffeepoints_city);
        }
        if(isset($_POST["edit_coffeepoints_name_new_$i"]) && ($edit_coffeepoints_name === 'изменить')) {
            $new_coffeepoints_name = trim(htmlspecialchars($_POST["edit_coffeepoints_name_new_$i"]));
            $coffeepoints_id_for_change = $result_coffeepoints[$i][coffeepoints_id];        
            $update_coffeepoints_name = "UPDATE coffeepoints SET coffeepoints_point_name='$new_coffeepoints_name' WHERE coffeepoints_id = '$coffeepoints_id_for_change'";
            mysqli_query($connect, $update_coffeepoints_name);
        }
        if(isset($_POST["edit_coffeepoints_comment_new_$i"]) && ($edit_coffeepoints_comment === 'изменить')) {
            $new_coffeepoints_comment = trim(htmlspecialchars($_POST["edit_coffeepoints_comment_new_$i"]));
            $coffeepoints_id_for_change = $result_coffeepoints[$i][coffeepoints_id];        
            $update_coffeepoints_comment = "UPDATE coffeepoints SET coffeepoints_comment='$new_coffeepoints_comment' WHERE coffeepoints_id = '$coffeepoints_id_for_change'";
            mysqli_query($connect, $update_coffeepoints_comment);
        }
        if($edit_coffeepoints_delete === 'удалить бар') {
            $coffeepoints_for_delete = $result_coffeepoints[$i][coffeepoints_id];
            $delete_coffeepoints_query = "DELETE FROM coffeepoints WHERE coffeepoints_id = '$coffeepoints_for_delete'";
                if (!mysqli_query($connect, $delete_coffeepoints_query)) {
                    allert_window_foreign_key();
                }
        }
    }


}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>