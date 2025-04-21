<?php

if (isset($_SESSION['user_data']['user_login'])) {

echo
'
<table>
    <tr>
        <th>Пользователь</th>
        <th>Откуда пришел</th>
        <th>Комментарий</th>
    </tr>';
      
for($i = 0; $i < $all_count_users; $i++) {
    echo
    "<tr>
        <td><div id=\"table_users_2\">".$result_users[$i][users_telefon]."</div></td>
        <td><div id=\"table_users_3\">".$result_users[$i][coffeepoints_city]." ".$result_users[$i][coffeepoints_point_name]." ".$result_users[$i][coffeepoints_comment]."</div></td>
        <td><div id=\"table_users_4\">".$result_users[$i][users_comment]."</div>
            <form method=\"post\">
                <input class=\"myclass_input\" name=\"edit_users_comment_new_$i\" type=\"text\">
                <input class=\"myclass\" name=\"edit_users_comment_$i\" type=\"submit\" value=\"изменить\">
                <input type=\"hidden\" name=\"link\" value=\"users\">
                <input type=\"hidden\" name=\"page\" value=\"".$_SESSION['user_data']['page_memory']."\">
            </form>

            <form method=\"post\">
                <input class=\"myclass\" name=\"edit_users_delete_$i\" type=\"submit\" value=\"удалить\">
                <input type=\"hidden\" name=\"link\" value=\"users\">
                <input type=\"hidden\" name=\"page\" value=\"".$_SESSION['user_data']['page_memory']."\">
            </form>
        </td>
    </tr>";
}

echo
'</table>
<br>
<p>&nbsp Все страницы:</p>';

}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>