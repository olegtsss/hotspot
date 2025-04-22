:local MAILTO mikrotik@mail...ru;
:local SITE pbx....ru;
:local PORT 5000;
:local APIKEY 12345;
:foreach line in=[/log find buffer=hotspot message~"login failed"] do={
	:do {:local content [/log get $line message];
		:local pos1 [:find $content " (" 0];
			:if ($pos1 != " ") do={
				:local uname ""; 
				:set uname [:pick $content ($pos1-10) ($pos1-0)];
				:local unameforsms "7$uname";
				:local sendtest yes;
					:foreach i in=[/ip firewall address-list print as-value where list=spam_cheks_list] do={
						:if (($i->"address")=$uname) do={
							:set $sendtest no;
						}
					}
					:if ($sendtest=yes) do={
						/ip firewall address-list add list=spam_cheks_list address=$uname timeout=00:05:00;
						local pass ([/tool fetch url="https://$SITE:$PORT/\?api=$APIKEY&sip=$uname" output=user as-value]->"data")		
						do {/ip hotspot user add name=$uname} on-error={};
						do {/ip hotspot user set password=$pass numbers=[find name=$uname]} on-error={};
						do {/tool e-mail send to=$MAILTO subject="Login $uname password $pass" body="Login $uname password $pass"} on-error={};
						:delay 1;
					}
			}
	}
}
/system logging action set hotspot memory-lines=1;
/system logging action set hotspot memory-lines=1000;
