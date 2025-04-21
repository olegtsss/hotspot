<?php

if (isset($_SESSION['user_data']['user_login'])) {

echo
'
<table>
    <tr>
        <th>№</th>
        <th>Город</th>
        <th>Бар</th>
        <th>Комментарий</th>
    </tr>';
      
for($i = 0; $i < $all_count_coffeepoints; $i++) {
    echo
    "<tr>
        <td><div id=\"table_coffeepoints_1\">".($i+1)."</div></td>
        <td><div id=\"table_coffeepoints_2\">".$result_coffeepoints[$i][coffeepoints_city]."</div>
            <form method=\"post\">
                <input class=\"myclass_input_coffepoints\" name=\"edit_coffeepoints_city_new_$i\" type=\"text\">
                <input class=\"myclass\" name=\"edit_coffeepoints_city_$i\" type=\"submit\" value=\"изменить\">
                <input type=\"hidden\" name=\"link\" value=\"coffeepoints\">
            </form>
        </td>
        <td><div id=\"table_coffeepoints_3\">".$result_coffeepoints[$i][coffeepoints_point_name]."</div>
            <form method=\"post\">
                <input class=\"myclass_input_coffepoints\" name=\"edit_coffeepoints_name_new_$i\" type=\"text\">
                <input class=\"myclass\" name=\"edit_coffeepoints_name_$i\" type=\"submit\" value=\"изменить\">
                <input type=\"hidden\" name=\"link\" value=\"coffeepoints\">
            </form>
        </td>
        <td><div id=\"table_coffeepoints_4\">".$result_coffeepoints[$i][coffeepoints_comment]."</div>
            <form method=\"post\">
                <input class=\"myclass_input\" name=\"edit_coffeepoints_comment_new_$i\" type=\"text\">
                <input class=\"myclass\" name=\"edit_coffeepoints_comment_$i\" type=\"submit\" value=\"изменить\">
                <input type=\"hidden\" name=\"link\" value=\"coffeepoints\">
            </form>

            <form method=\"post\">
                <input class=\"myclass_bar\" name=\"edit_coffeepoints_delete_$i\" type=\"submit\" value=\"удалить бар\">
                <input type=\"hidden\" name=\"link\" value=\"coffeepoints\">
            </form>
        </td>
    </tr>";
}

echo
'</table><br>';


}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}


?>