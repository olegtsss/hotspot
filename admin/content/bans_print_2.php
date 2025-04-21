<?php 

if (isset($_SESSION['user_data']['user_login'])) {

echo
'
<table>
    <tr>
        <th>Время<br>подключения</th>
        <th>IP</th>
        <th>Количество<br>попыток</th>
        <th>Время последнего<br>удачного подключения</th>
        <th>Количество<br>удачных<br>подключений</th>
        <th>Бан</th>
        <th>Комментарий</th>
    </tr>';
      
for($i = 0; $i < $all_count_bans; $i++) {
    echo
    "<tr>
        <td><div id=\"table_ban_2\">".date('Y-m-d H:i:s', $result_bans[$i][time_last_connect])."</div></td>
        <td><div id=\"table_ban_3\">".$result_bans[$i][ip]."</div></td>
        <td><div id=\"table_ban_4\">".$result_bans[$i][count_connect]."</div></td>
        <td><div id=\"table_ban_5\">".$result_bans[$i][time_last_success]."</div></td>
        <td><div id=\"table_ban_6\">".$result_bans[$i][count_success]."</div></td>
        <td><div id=\"table_ban_7\">".$result_bans[$i][ban]."</div></td>
        <td><div id=\"table_ban_8\">".$result_bans[$i][comment]."</div>
            <form method=\"post\">
                <input class=\"myclass_input\" name=\"edit_bans_comment_new_$i\" type=\"text\">
                <input class=\"myclass\" name=\"edit_bans_comment_$i\" type=\"submit\" value=\"изменить\">
                <input type=\"hidden\" name=\"link\" value=\"bans\">
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