<?php

if (isset($_SESSION['user_data']['user_login'])) {

echo '

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Личный кабинет</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h2 align="center">Биллинговая информация Hotspots</h2>
            <h3 align="center">
';

}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>