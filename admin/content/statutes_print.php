<?php 

if (isset($_SESSION['user_data']['user_login'])) {

echo
'
<form method="post">
    <button type="submit" class="btn btn-primary" id="button_update_page_2" name="link" value="statuses">Обновить</button>
</form>
<br>

<table>
    <tr>
        <th>Код</th>
        <th>Расшифровка</th>
        <th>Комментарий</th>
    </tr>';
      
for($i = 0; $i < $all_count_statutes; $i++) {
    echo
    "<tr>
        <td><div id=\"table_statutes_1\">".$result_statutes[$i][status_id]."</div></td>
        <td><div id=\"table_statutes_2\">".$result_statutes[$i][status_status]."</div>
            <form method=\"post\">
                <input class=\"myclass_input\" name=\"edit_status_decrypt_new_$i\" type=\"text\">
                <input class=\"myclass\" name=\"edit_status_decrypt_$i\" type=\"submit\" value=\"изменить\">
                <input type=\"hidden\" name=\"link\" value=\"statuses\">
            </form>
        </td>
        <td><div id=\"table_statutes_3\">".$result_statutes[$i][status_comment]."</div>
            <form method=\"post\">
                <input class=\"myclass_input\" name=\"edit_status_comment_new_$i\" type=\"text\">
                <input class=\"myclass\" name=\"edit_status_comment_$i\" type=\"submit\" value=\"изменить\">
                <input type=\"hidden\" name=\"link\" value=\"statuses\">
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