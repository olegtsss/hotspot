<?php 

if (isset($_SESSION['user_data']['user_login'])) {

echo
'<table>
    <tr>
        <th>№</th>
        <th>Дата</th>
        <th>Время</th>
        <th>Бар</th>
        <th>Статус</th>
        <th>Пользователь</th>
        <th>MAC</th>
        <th>Хост</th>
        <th>IP nat</th>
        <th>IP серый</th>
        <th>IP белый</th>
    </tr>';
	  
for($i = 0; $i < $all_count_registrations; $i++) {
  	echo
    "<tr>
        <td><div id=\"table_registrations_1\">".$result_registrations[$i][registrations_id]."</div></td>
        <td><div id=\"table_registrations_2\">".$result_registrations[$i][registrations_date]."</div></td>
        <td><div id=\"table_registrations_3\">"
            .substr($result_registrations[$i][registrations_time], 0, 2).":"
            .substr($result_registrations[$i][registrations_time], 2, 2).":"
            .substr($result_registrations[$i][registrations_time], 4, 2)."</div>
        </td>        
        <td><div id=\"table_registrations_4\">".$result_registrations[$i][coffeepoints_city]."<br>".$result_registrations[$i][coffeepoints_point_name]." ".$result_registrations[$i][coffeepoints_comment]."</div></td>
        <td><div id=\"table_registrations_5\">".$result_registrations[$i][status_status]."</div></td>
        <td><div id=\"table_registrations_6\">".$result_registrations[$i][registrations_users]."</div></td>
        <td><div id=\"table_registrations_7\">".$result_registrations[$i][registrations_mac]."</div></td>
        <td><div id=\"table_registrations_8\">".$result_registrations[$i][registrations_host_name]."</div></td>
        <td><div id=\"table_registrations_9\">".$result_registrations[$i][registrations_ip_nat]."</div></td>
        <td><div id=\"table_registrations_10\">".$result_registrations[$i][registrations_ip_gray]."</div></td>
        <td><div id=\"table_registrations_11\">".$result_registrations[$i][registrations_ip_white]."</div></td>
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