<?php
//version 1.6
#####################################
//Создание подключения к базам данных
$connect = mysqli_connect('127.0.0.1', 'admin', '12345', 'coffeecuptogo') or die('Ошибка подключения: '.mysqli_connect_error());
$connect_brute = mysqli_connect('127.0.0.1', 'admin', '12345', 'brute') or die('Ошибка подключения: '.mysqli_connect_error());
//Часовой пояс
date_default_timezone_set("Asia/Bishkek");
// Запрещаем вывод предупреждений
//Error_Reporting(E_ALL & ~E_NOTICE);
            
/*
ВАЖНО сделать!!!!   
1) на сервере задать права: 	chown www-data:www-data /var/www/html/export_files
2) изменить значение $quantity=25 в файле auth/page.php
3) изменить значение $export_server_name = 'https...... в файле auth/export_registartions.php
4) изменить значение $export_server_name = 'https...... в файле auth/export_users.php
5) изменить ip адрес для баз данных ($connect и $connect_brute) на 127.0.0.1
*/

####################################

include_once "scripts/database_scripts.php";		//Скрипты по работе с базой данных
#include_once "scripts/database_registrations.php";	//Скрипты по работе с журналом регистраций
include_once "scripts/main_scripts.php";			//Скрипты по подклчению страницы после авторизации
include_once "scripts/bruteforce.php";				//Проверки на брутфорсы
require_once "Classes/PHPExcel.php";				//Класс для работы с Exel
require_once "Classes/PHPExcel/Writer/Excel5.php";	//Подключаем класс для вывода данных в формате excel

//Запуск сессии
session_start();
user_reg($connect_brute);
/*var_dump($_POST);
echo "<br>";
var_dump($_SESSION);
echo "<br>";
var_dump($_COOKIE);*/
ses_destroy();

//Подключение сценария подгрузки контента страниц
include_once get_path_to_page();

?>



