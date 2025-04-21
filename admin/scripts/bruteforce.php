<?php

function bruteforce_ip($connect_brute, $try_loging_ip) {
##########################################
//Блокировка по IP
//Создание подключения к базе данных анализа IP
$brute_force_between = 30;          //Разрешенное время между запросами при обнаружении атаки
$brute_force_between_hard = 300;    //Разрешенное время между запросами при обнаружении атаки, без успешной авторизации
$brute_force_count = 10;            //Разрешенное количество ошибочных подключений с одного ip
$brute_force_timeout = 30;          //Таймаут при обнаружении брутфорса
$brute_force_timeout_hard = 300;    //Таймаут при обнаружении брутфорса под ip, без успешных авторизаций
###########################################

$try_loging_time = time(); // Получаем время
$try_id = uniqid();
//Проверка, есть ли ip в в базе
$exist_query = "SELECT * FROM ip_addresses WHERE ip = '$try_loging_ip'";
$result =  mysqli_fetch_assoc(mysqli_query($connect_brute, $exist_query));
    if (!isset($result)) {
        //ip  базе нет, добавляем новую запись
        $add = "INSERT INTO ip_addresses (id, ip, time_last_connect, count_connect, count_success, ban) VALUES ('$try_id', '$try_loging_ip', '$try_loging_time', 1, 0, 'no')";
        mysqli_query($connect_brute, $add);
	}
    else {
        //ip в базе есть
        $update = "UPDATE ip_addresses SET time_last_connect='$try_loging_time' WHERE ip = '$try_loging_ip'";
        mysqli_query($connect_brute, $update);
        //Обновить количество подключений с этого ip
        $result['count_connect']++;
        $update_count_connect = $result['count_connect'];
            //Превышен ли лимит с этого ip
            if ( $update_count_connect < $brute_force_count ) {
            	//Лимит не превышен
                $update = "UPDATE ip_addresses SET count_connect='$update_count_connect', time_last_connect='$try_loging_time' WHERE ip = '$try_loging_ip'";
                mysqli_query($connect_brute, $update);
            }
            else
            {
            	//Лимит превышен
                //Разница во времени между запросами
                   	//Были ли с этого ip успешные входы
                   	if ($result['count_success'] > 0) {
                   	    $brute_force_between_test = $brute_force_between;
                   	} 
                    	else {
                   	    $brute_force_between_test = $brute_force_between_hard;
                   	}
                //Attack detect
                if (($try_loging_time - $result['time_last_connect']) < $brute_force_between_test ) {
                    $update = "UPDATE ip_addresses SET count_connect='$update_count_connect', time_last_connect='$try_loging_time', ban='yes' WHERE ip = '$try_loging_ip'";
                    mysqli_query($connect_brute, $update);
                    	//Были ли с этого ip успешные входы
                    	if ($result['count_success'] > 0) {
                    	    sleep($brute_force_timeout);
                    	} 
                    	else {
                    	    sleep($brute_force_timeout_hard);
                    	}
                }
                else
                // No attack
                {
                    $update = "UPDATE ip_addresses SET count_connect=1, time_last_connect='$try_loging_time', ban='update' WHERE ip = '$try_loging_ip'";
                    mysqli_query($connect_brute, $update);
                }
            }    
    }
}

function bruteforce_session () {
################################
$brute_force_count_people = 4;      //Разрешенное количество ошибочных подключений для одной сессии
$brute_force_timeout = 30;          //Таймаут при обнаружении брутфорса
###############################
//Защита от брута-человека
	if (!isset ($_SESSION['user_data']['bruteforce'])) {   
    	$_SESSION['user_data']['bruteforce'] = 0;
	}
$_SESSION['user_data']['bruteforce']++;
	//Блок после 5 попыток для одного PHPSESSION    
	if ($_SESSION['user_data']['bruteforce'] > $brute_force_count_people) {
        sleep($brute_force_timeout);
    	//die();
    	$_SESSION = null;
    	$_POST = null;
    	$_COOKIE = null;
    	session_destroy();
	}
}

//Функция добавления в базу брута информации об удачной авторизации
function bd_bruteforce_success($connect_brute, $ip, $time) {
    $select = "SELECT * FROM ip_addresses WHERE ip = '$ip'";
    $result =  mysqli_fetch_assoc(mysqli_query($connect_brute, $select));
    $result_count_success = $result['count_success'];
    $result_count_success++;
    $update = "UPDATE ip_addresses SET time_last_success='$time', count_success='$result_count_success' WHERE ip = '$ip'";
    mysqli_query($connect_brute, $update);
}


?>