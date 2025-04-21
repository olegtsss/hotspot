<?php

echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coffeecup - мы варим кофе!</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Добро пожаловать!</h1>
            <h2 align="center">coffeecuptogo.com</h2>
                <div class="form-group">
                    <form method="post">
                        <label for="exampleInputEmail1">Пользователь</label>
                        <input type="text" class="form-control" name="login" placeholder="Логин">
                        <label for="exampleInputPassword1">Пароль</label>
                        <input type="password" class="form-control" name="pass" placeholder="Пароль">
                        <br>
                        <button type="submit" class="btn btn-primary" name="submit" value="yes">Вход</button>
                    </form>
';


?>