/ip hotspot profile
set [ find default=yes ] dns-name=coffeecuptogo.com hotspot-address=\
    192.168.2.1 html-directory=flash/hotspot http-cookie-lifetime=4h name=\
    coffeecup

/ip hotspot user profile
set [ find default=yes ] keepalive-timeout=5m

/ip hotspot
add address-pool=pool_guest addresses-per-mac=1 disabled=no idle-timeout=none \
    interface=bridge_guest name=hotspot_coffeecup

/system logging action
add name=hotspot target=memory

/system logging
add action=hotspot topics=hotspot,debug,info,!account
