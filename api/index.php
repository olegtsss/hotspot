<?php
// head /dev/urandom | tr -dc a-zA-Z0-9 | head -c 25
$api = '12345';
$key_from_get = trim(htmlspecialchars($_GET['api']));

//Проверка корректности ключа API
if ( $key_from_get === $api ) {
	####################################
	//Создание подключения к базе данных
	$connect = mysqli_connect('127.0.0.1', 'admin', '12345', 'coffeecuptogo') or die('Sorry! Try later!');
	//Часовой пояс
	date_default_timezone_set("Asia/Moscow");
	####################################
	
	//Принимаем запрос
	$ip_gray = trim(htmlspecialchars($_GET['ipgray']));
	$ip_nat = trim(htmlspecialchars($_GET['ipnat']));
	$mac = trim(htmlspecialchars($_GET['mac']));
	$date = trim(htmlspecialchars($_GET['date']));
	$time = trim(htmlspecialchars($_GET['time']));
	$status_code = trim(htmlspecialchars($_GET['status']));
	$password = trim(htmlspecialchars($_GET['pass']));
	$device = trim(htmlspecialchars($_GET['device']));
	$telefon = trim(htmlspecialchars($_GET['tel']));
	$host = trim(htmlspecialchars($_GET['host']));

	//Сохраняем запрос в базу данных
	//Получаем информацию о баре
	$check_bar = "SELECT * FROM coffeepoints WHERE coffeepoints_device = '$device'";
	$result_bar_arr = mysqli_fetch_assoc(mysqli_query($connect, $check_bar));
		if (isset($result_bar_arr)) {
			//Получаем информацию о пользователе
			$check_user = "SELECT * FROM users WHERE users_telefon = '$telefon'";
			$result_user_arr = mysqli_fetch_assoc(mysqli_query($connect, $check_user));
				if (!isset($result_user_arr)) {
					//Новый пользователь, добавляем его
					$first_reg = $result_bar_arr['coffeepoints_id'];
					$add_user = "INSERT INTO users (users_telefon, users_first_reg) VALUES ('$telefon', '$first_reg')";
					mysqli_query($connect, $add_user);
					$result_user_arr = mysqli_fetch_assoc(mysqli_query($connect, $check_user));
				}
			//Получаем информацию о статусе
			$check_status = "SELECT * FROM status WHERE status_id = '$status_code'";
			$result_status_arr = mysqli_fetch_assoc(mysqli_query($connect, $check_status));
				if (!isset($result_status_arr)) {
					echo "Unknown status code $status_code";
				}

			//Вносим информацию в журнал регистрации
			$id_users = $result_user_arr['users_telefon'];
			$id_coffeepoints = $result_bar_arr['coffeepoints_id'];
			$ip_white = $_SERVER['REMOTE_ADDR'];
			$add_registration = "INSERT INTO registration (registrations_users, registrations_id_coffeepoints, registrations_mac, registrations_host_name, registrations_date, registrations_time, registrations_ip_nat, registrations_ip_gray, registrations_ip_white, registrations_status_code, registrations_password) VALUES ('$telefon', '$id_coffeepoints', '$mac', '$host', '$date', '$time', '$ip_nat', '$ip_gray', '$ip_white', '$status_code', '$password')";
			mysqli_query($connect, $add_registration);
		}
		else {
			echo "Unknown device $device";
		}
}
else {
	//Выводим просто временный пароль
	$pas1d = random_int (0, 9);
	$pas2d = random_int (0, 9);
	$pas3d = random_int (0, 9);
	$pas4d = random_int (0, 9);
	$password = "$pas1d$pas2d$pas3d$pas4d";
	echo $password;
	//die();
}


//https://test.ru:5000?api=1234&device=coffeecuptogo_N_Novgorod&tel=7999123&status=1&ipgray=192.168.1.54&ipnat=192.168.2.253&mac=AAAAAAAAAA&date=20201104&time=104613&pass=0945&host=Iphone

?>
