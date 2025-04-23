# Описание проекта Hotspot:

Проект для организации hotspot технологии под ключ, созданный в интересах компании `Coffeecuptogo`. Позволяет организовать гостевой доступ в интернет по технологии `hotspot` на оборудовании `Mikrotik` в соответствии с требованиями регуляторов. Подробно освещен на Хабре (`https://habr.com/ru/articles/534936/` и `https://habr.com/ru/companies/ruvds/articles/695066/`). Backend нативный без применения каких-либо frameworks. Разработан web application firewall (`WAF`) для защиты от bruteforce. Скрипты и фронтенд для роутеров частично заимствованы из различных источников. Проект находился в коммерческой эксплуатации в период с 2019-2025 года.

### Используемые технологии:

Php, Javascript, Lua, Mysql

### Архитектура проекта:
- `admin` - панель администратора.
- `api` - backend для регистрации информации от hotspot маршрутизаторов.
- `mobile` - frontend для страницы hotspot на роутерах.
- `mikrotik` - обвязка со стороны роутеров.
- `pbx` - сервис для передачи гостям временного пароля посредством IP телефонии.

На роутерах подняты сервисы `Hotspot` (функционал, предоставляемый `RouterOS`). При регистрации хоста в гостевой сети пользователь посредством `Firewall Filter` перенаправляется на страницу авторизации `mobile`, лежащую в памяти роутера. Гость в ней указывает свой номер телефона и нажимает на кнопку `Далее`. Lua скриптом в `RouterOS` выполняется `http get` на сервис `api` для получения случайного пароля доступа. Далее пароль направляется на сервис `pbx`, который его сообщает гостю посредством звонка с `Asterisk` и озвучивания сгенерированного пароля. Дополнительно в операционной системе роутера создается пользователь `Hotspot` с нужным паролем. Frontend переводит пользователя на страницу ввода пароля и, в случае его корректности, отображает рекламный баннер и далее пропускает гостевой трафик в интернет. Администратор сервиса подключает бары к системе и смотрит на результаты его работы посредством сервиса `admin`.

### Подготовка базы данных:

```
mysql
CREATE SCHEMA `coffeecuptogo` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
use coffeecuptogo;
CREATE USER 'admin'@'%' IDENTIFIED BY '12345';
FLUSH PRIVILEGES;
GRANT USAGE ON coffeecuptogo.* TO 'admin'@'%' IDENTIFIED BY '12345';
GRANT SELECT ON coffeecuptogo.* TO 'admin'@'%';
GRANT ALL ON coffeecuptogo.* TO 'admin'@'%';

CREATE SCHEMA `brute` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
CREATE USER 'brute_user'@'%' IDENTIFIED BY '12345';
GRANT ALL ON brute.* TO 'brute_user'@'%';
FLUSH PRIVILEGES;

ALTER TABLE registration DROP registrations_id;
ALTER TABLE registration AUTO_INCREMENT = 1;
ALTER TABLE registration ADD registrations_id int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

mysql coffeecuptogo < coffeecuptogo.sql
mysql bruteforce < bruteforce.sql
```

### Запись биллинга для выполнения требований регулятора:

```
apt install flow-tools
nano /etc/flow-tools/flow-capture.conf

-w /var/log/flow -n 275 -N 3 127.0.0.1/0/1234

mkdir /var/log/flow
systemctl restart flow-capture
```

### Вспомогательне скрипты:

* Работа WAF (cron/bruteforce_database_clean.sh):

```
#/bin/bash

# Записи, более старые по таймауту, пойдут под удаление - Week
BETWEEN_FOR_DELETE=604800
MYSQL_SERVER=127.0.0.1
MYSQL_USER=brute_user
MYSQL_PASS=12345
MYSQL_DB=bruteforce

TIME_NOW=`date +%s`
TIME_IN_RECORD=0
BETWEEN=0
I=0

for I in `mysql -u $MYSQL_USER -p$MYSQL_PASS -h $MYSQL_SERVER \
		--database $MYSQL_DB -B --skip-column-names\
		-e "SELECT id FROM ip_addresses"`; do
	TIME_IN_RECORD=$(mysql -u $MYSQL_USER -p$MYSQL_PASS -h $MYSQL_SERVER \
		--database $MYSQL_DB -B --skip-column-names\
		-e "SELECT time_last_connect FROM ip_addresses WHERE id = \"$I\"")
	BETWEEN=$(expr $TIME_NOW - $TIME_IN_RECORD)
		if [ $BETWEEN -gt $BETWEEN_FOR_DELETE ]; then
			mysql -u $MYSQL_USER -p$MYSQL_PASS -h $MYSQL_SERVER \
				--database $MYSQL_DB -B --skip-column-names\
				-e "DELETE FROM ip_addresses WHERE id = \"$I\""
		fi
done
```

* Работа WAF (cron/bruteforce_exportfiles_clean.sh):

```
#/bin/bash
# Записи, более старые по таймауту, пойдут под удаление - 3 Minute
BETWEEN_FOR_DELETE=180
MYSQL_SERVER=127.0.0.1
MYSQL_USER=brute_user
MYSQL_PASS=12345
MYSQL_DB=bruteforce
DIR_WITH_SITE='/var/www/https/coffeecuptogo_admin.ru/'

TIME_NOW=`date +%s`
TIME_IN_RECORD=0
BETWEEN=0
I=0
FILE_NAME_FOR_DELETE=0
FILE_DIR_FOR_DELETE=0

for I in `mysql -u $MYSQL_USER -p$MYSQL_PASS -h $MYSQL_SERVER \
		--database $MYSQL_DB -B --skip-column-names\
		-e "SELECT id FROM export_files"`; do
	TIME_IN_RECORD=$(mysql -u $MYSQL_USER -p$MYSQL_PASS -h $MYSQL_SERVER \
		--database $MYSQL_DB -B --skip-column-names\
		-e "SELECT time_create FROM export_files WHERE id = \"$I\"")
	BETWEEN=$(expr $TIME_NOW - $TIME_IN_RECORD)
		if [ $BETWEEN -gt $BETWEEN_FOR_DELETE ]; then
			
			FILE_NAME_FOR_DELETE=$(mysql -u $MYSQL_USER -p$MYSQL_PASS -h $MYSQL_SERVER \
				--database $MYSQL_DB -B --skip-column-names\
				-e "SELECT file_name FROM export_files WHERE id = \"$I\"")
			
			FILE_DIR_FOR_DELETE=$(mysql -u $MYSQL_USER -p$MYSQL_PASS -h $MYSQL_SERVER \
				--database $MYSQL_DB -B --skip-column-names\
				-e "SELECT file_path FROM export_files WHERE id = \"$I\"")
						
			mysql -u $MYSQL_USER -p$MYSQL_PASS -h $MYSQL_SERVER \
				--database $MYSQL_DB -B --skip-column-names\
				-e "DELETE FROM export_files WHERE id = \"$I\""
				#echo "Record with id $I"
				
			rm $DIR_WITH_SITE/$FILE_DIR_FOR_DELETE/$FILE_NAME_FOR_DELETE
			rmdir $DIR_WITH_SITE/$FILE_DIR_FOR_DELETE
		fi
done
```

* Работа c резервными копиями (cron/database_backup.sh):

```
#!/bin/sh
DIR_FOR_DUMPS=/home/test/database_backup
NEW_DATE=`date +%Y-%m-%d-%H-%M-%S`
FILE_FOR_DUMP=dump_coffeecuptogo_$NEW_DATE.sql

/usr/bin/mysqldump coffeecuptogo > $DIR_FOR_DUMPS/$FILE_FOR_DUMP
```

### Работа с веб серверами:

* Прокси на api (/etc/apache2/sites-available/coffeecuptogo.ru.conf):

```
<VirtualHost coffeecuptogo.ru:5000>
        ServerAdmin admin@olegtsss.ru
        ServerName coffeecuptogo.ru
        ServerAlias www.coffeecuptogo.ru
        DocumentRoot /var/www/https/coffeecuptogo.ru/
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
        SSLEngine on
        SSLCertificateFile /etc/apache2/ssl_keys_for_apache/coffeecuptogo.ru.crt
        SSLCertificateKeyFile /etc/apache2/ssl_keys_for_apache/coffeecuptogo.ru.key
</VirtualHost>
```

* Прокси на админку (/etc/apache2/sites-available/coffeecuptogo_admin.ru.conf):

```
<VirtualHost coffeecuptogo.ru:443>
        ServerAdmin admin@olegtsss.ru
        ServerName coffeecuptogo.ru
        ServerAlias www.coffeecuptogo.ru
        DocumentRoot /var/www/https/coffeecuptogo_admin.ru/
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
        SSLEngine on
        SSLCertificateFile /etc/apache2/ssl_keys_for_apache/coffeecuptogo.ru.crt
        SSLCertificateKeyFile /etc/apache2/ssl_keys_for_apache/coffeecuptogo.ru.key
</VirtualHost>
```

* Прокси на Nginx (/etc/apache2/sites-available/coffeecuptogo_admin.ru.conf):

```
server {
    server_tokens off;
    server_name coffeecuptogo.ru;
    location / {
        include proxy_params;
        proxy_pass http://127.0.0.1:2005;
    }
}

server {
    server_tokens off;
    listen 5000 ssl;

    server_name coffeecuptogo.ru;
    location / {
        include proxy_params;
        proxy_pass http://127.0.0.1:2006;
    }
    ssl_certificate /etc/letsencrypt/live/coffeecuptogo.ru/cert.pem;
    ssl_certificate_key /etc/letsencrypt/live/coffeecuptogo.ru/privkey.pem;
}

server {
    server_tokens off;
    listen 443 ssl;

    server_name coffeecuptogo.ru;
    location / {
        include proxy_params;
        proxy_pass http://127.0.0.1:2007;
    }
    ssl_certificate /etc/letsencrypt/live/coffeecuptogo.ru/cert.pem;
    ssl_certificate_key /etc/letsencrypt/live/coffeecuptogo.ru/privkey.pem;
}
```

* Api для IP телефонии (index.php):

```
<?php
$api = '12345';
$key_from_get = trim(htmlspecialchars($_GET['api']));
$dir = '/usr/share/asterisk/sounds/ru';
$silence = "$dir/silence/1";
$sip1 = "@siplink-7499...";
$sip2 = trim(htmlspecialchars($_GET['sip']));
$fileWeb = 'file.txt';
$fileAsterisk = 'file2.txt';

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
```

* Api для IP телефонии (asterisk_call.sh):

```
#/bin/sh/
FILE_WEB=/var/www/https/file2.txt
FILE_ASTERISK=/var/spool/asterisk/outgoing/test.call
i=1
while [ i ]; do 
	if [ -f $FILE_WEB ]; then
		mv $FILE_WEB $FILE_ASTERISK
	fi
done

# https://pbx...ru:5000/?api=12345&sip=999123123
```

* Настройки для IP телефонии (extensions.conf):

```
[test-record]
exten => _1000,1,Wait(2)
exten => _1000,n,Record(/tmp/sound${EXTEN:2}:wav)
exten => _1000,n,Wait(1)
exten => _1000,n,Playback(/tmp/sound${EXTEN:2})
exten => _1000,n,Wait(2)
exten -> _1000,n,Hangup()
```

* Настройки для IP телефонии (pjsip.conf):

```
[3002]
type=endpoint
context=test-record
```

## Авторы:

[olegtsss](https://github.com/olegtsss)
