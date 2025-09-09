<?php
$api = '12345';
$key_from_get = trim(htmlspecialchars($_GET['api']));
$dir = '/usr/share/asterisk/sounds/ru';
$silence = "$dir/silence/1";
$sip1 = "@siplink-74991111111";
$sip2 = trim(htmlspecialchars($_GET['sip']));
$fileWeb = 'file.txt';
$fileAsterisk = 'file2.txt';

//Проверка корректности ключа API, длинны номера, что в номере только цифры
if ( $key_from_get === $api AND strlen("$sip2") === 10 AND ctype_digit("$sip2") ) {
    $pas1d = random_int(0, 9);
    $pas2d = random_int(0, 9);
    $pas3d = random_int(0, 9);
    $pas4d = random_int(0, 9);
    echo "$pas1d$pas2d$pas3d$pas4d";
    $myfile = fopen("$fileWeb", "w");
    $txt = "Channel: PJSIP/+7$sip2$sip1\n";
    fwrite($myfile, $txt);
    $txt = "Application: Playback\n";
    fwrite($myfile, $txt);
    $txt = "Data: $silence&$dir/digits/$pas1d&$silence&$dir/digits/$pas2d&$silence&$dir/digits/$pas3d&$silence&$dir/digits/$pas4d\n";
    fwrite($myfile, $txt);
    fclose($myfile);
    rename("$fileWeb", "$fileAsterisk");
}
?>