:local INTERNETINTERFACE pppoe-out1;
:local MAILTO mikrotik@.ru;
:local APIKEY 1234;
:local LOGIN 2;
:local SITE coffeecuptogo.ru;
:local PORT 5000;
:local nas [/system identity get name];
:local today [/system clock get date];
:local time1 [/system clock get time ];
:local hour [:pick $time1 0 2]; 
:local min [:pick $time1 3 5];
:local sec [:pick $time1 6 8];
:set $time1 [:put ({hour} . {min} . {sec})] 
:local USERLONG "7$user";

:local whiteip ([/tool fetch url="https://.../" output=user as-value]->"data");
:local grayip [/ip address get [find interface=$INTERNETINTERFACE] address];
:local grayipshort [:pick $grayip 0 [:find $grayip "/"]];
:put $grayipshort;

/ip firewall address-list add list=$today \
	address="logout---$time1---$user---$grayipshort---$whiteip"
do {/tool e-mail send to=$MAILTO subject="Logout number: $user on $nas" \
	body="Logout number: $user time: $time1 gray-ip: $grayip white-ip: $whiteip"} \
	on-error={};
	
do {/tool fetch url="https://$SITE:$PORT/\?api=$APIKEY&device=$nas\
	&tel=$USERLONG\
	&status=$LOGIN\
	&ipgray=$grayipshort\
	&date=$today\
	&time=$time1"\
	keep-result=no} on-error={};
