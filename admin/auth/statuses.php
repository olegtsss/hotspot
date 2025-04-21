<?php

if (isset($_SESSION['user_data']['user_login'])) {

//Показать заголовок для таблицы
echo '<h5>Статусы пользователей:</h5>';
//Вывод всех статусов
$query_statutes = "SELECT * FROM status ORDER BY status.status_id ASC";
$result_statutes = mysqli_fetch_all(mysqli_query($connect, $query_statutes), MYSQLI_ASSOC);
$all_count_statutes = count($result_statutes);
include_once "content/statutes_print.php";

    //Редактировать статусы
    for($i = 0; $i < $all_count_statutes; $i++) {
        // Это не фильтруем, так как оно просто проверяется есть ли или нет 
        // $edit_status_decrypt_new = trim(htmlspecialchars($_POST["edit_status_decrypt_new_$i"]));
        $edit_status_decrypt = trim(htmlspecialchars($_POST["edit_status_decrypt_$i"]));
        $edit_status_comment = trim(htmlspecialchars($_POST["edit_status_comment_$i"]));

        if(isset($_POST["edit_status_decrypt_new_$i"]) && ($edit_status_decrypt === 'изменить')) {
            $new_status_decrypt = trim(htmlspecialchars($_POST["edit_status_decrypt_new_$i"]));
            $status_decrypt_for_change = $result_statutes[$i][status_id];        
            $update_status_decrypt = "UPDATE status SET status_status='$new_status_decrypt' WHERE status_id = '$status_decrypt_for_change'";
            mysqli_query($connect, $update_status_decrypt);
        }
        if(isset($_POST["edit_status_comment_new_$i"]) && ($edit_status_comment === 'изменить')) {
            $new_status_comment = trim(htmlspecialchars($_POST["edit_status_comment_new_$i"]));
            $status_comment_for_change = $result_statutes[$i][status_id];        
            $update_status_comment = "UPDATE status SET status_comment='$new_status_comment' WHERE status_id = '$status_comment_for_change'";
            mysqli_query($connect, $update_status_comment);
        }
    }


}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>