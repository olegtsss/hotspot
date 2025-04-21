<?php

//Функция уничтожения сессии
function ses_destroy() {
	$submit_exit = trim(htmlspecialchars($_POST['submit']));
	if($submit_exit === 'exit') {
		$_SESSION = null;
		// Очищаем POST в два этапа, чтобы исключитьь ошибки с отображением
		$_POST['link'] = null;
		$_POST = null;
		unset($_COOKIE[session_name()]);
		session_destroy();
		//Переадресовываем пользователя на начальную страницу
		//Автоматом это будет index.php
		header('Location: /');
	}
}

//Функция проверки пользователя на предмет авторизации
function verify_user($user_data) {
	if(!isset($user_data)) {
		$fold_path = 'all/';
	} else
		$fold_path = 'auth/';
return $fold_path;
}

//Функция подключения контента страницы в зависимости от выбранного пути
function get_path_to_page() {
	$folder = verify_user($_SESSION['user_data']['user_login']);
	$path_to_page = $folder.'/page.php';
return $path_to_page;
}


?>