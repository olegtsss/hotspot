#/bin/sh/
FILE_WEB=/var/www/https/pbx.ru/file2.txt
FILE_ASTERISK=/var/spool/asterisk/outgoing/test.call
i=1
while [ i ]; do 
	if [ -f $FILE_WEB ]; then
		mv $FILE_WEB $FILE_ASTERISK
	fi
done
