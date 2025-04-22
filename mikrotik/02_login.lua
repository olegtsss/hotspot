:local INTERNETINTERFACE pppoe-out1;
:local MAILTO mikrotik@.ru;
:local APIKEY 1234;
:local LOGIN 1;
:local SITE coffeecuptogo.ru;
:local PORT 5000;

:local nas [/system identity get name];
:local today [/system clock get date];
:local time1 [/system clock get time ];
:local ipuser [/ip hotspot active get [find user=$user] address];
:local usermac [/ip hotspot active get [find user=$user] mac-address]
:local hour [:pick $time1 0 2]; 
:local min [:pick $time1 3 5]; 
:local sec [:pick $time1 6 8];
:set $time1 [:put ({hour} . {min} . {sec})] 
:local mac1 [:pick $usermac 0 2];
:local mac2 [:pick $usermac 3 5];
:local mac3 [:pick $usermac 6 8];
:local mac4 [:pick $usermac 9 11];
:local mac5 [:pick $usermac 12 14];
:local mac6 [:pick $usermac 15 17];
:local USERLONG "7$user";
:set $usermac [:put ({mac1} . {mac2} . {mac3} . {mac4} . {mac5} . {mac6})]

:local whiteip ([/tool fetch url="https://.../" output=user as-value]->"data");
:local grayip [/ip address get [find interface=$INTERNETINTERFACE] address];
:local grayipshort [:pick $grayip 0 [:find $grayip "/"]];
:put $grayipshort;

:foreach i in=[/ip dhcp-server lease print as-value where address=$ipuser] do={
	:if (($i->"address")=$ipuser) do={
		:set $host [($i->"host-name")];
	}
}
/ip firewall address-list add\
	list=$today\
	address="login---$time1---$user---$usermac---$ipuser---$grayipshort---$whiteip"
do {/tool e-mail send to=$MAILTO subject="Login number: $user on $nas" \
	body="Login number: $user\
	mac-address: $usermac\
	time: $time1\
	ip-address: $ipuser\
	gray-ip: $grayip\
	white-ip: $whiteip"}\
	on-error={};
do {/tool fetch url="https://$SITE:$PORT/\?api=$APIKEY&device=$nas\
	&tel=$USERLONG\
	&status=$LOGIN\
	&ipgray=$grayipshort\
	&ipnat=$ipuser\
	&mac=$usermac\
	&date=$today\
	&time=$time1\
	&host=$host"\
	keep-result=no} on-error={};	
