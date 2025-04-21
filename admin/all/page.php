<?php
include_once "content/all_header.php";
//Защита от брута, вывод информационного
    if (isset ($_SESSION['user_data']['bruteforce'])) {   
        include_once "content/alert-verification.php";
    }
include_once "content/all_header_continue.php";
?>
