#EMPTY FIREWALL RULES TABLE
iptables -F


#DROP NULL PACKET
iptables -A INPUT -p tcp --tcp-flags ALL NONE -j DROP

#DROP XMAS ATTACK PACKETS
iptables -A INPUT -p tcp --tcp-flags ALL ALL -j DROP

#DROP SYN ATTACK PACKETS
iptables -A INPUT -p tcp ! --syn -m state --state NEW -j DROP

#ALLOW ESTABLISHED CONNECTION TO KEEP GOING IF INTERNAL CONNECTIONS CHANGE CONFIGURATION
iptables -A INPUT -m conntrack --ctstate ESTABLISHED,RELATED -j ACCEPT

# ALLOW LAN CONNECTION FOR GLUSTER
sudo iptables -A INPUT -s 192.168.0.0/24 -j ACCEPT
sudo iptables -A OUTPUT -s 192.168.0.0/24 -j ACCEPT




#ACCEPT INPUT FROM THE INTERNET OVER LOCAL NETWORK INTERFACE (lo)
iptables -A INPUT -i lo -j ACCEPT

#SSH
iptables -A INPUT -p tcp --dport 1377 -j ACCEPT

#MySQL --- PLEASE CHANGE ME !!
iptables -A INPUT -p tcp --dport 3306 -j ACCEPT

#Git
iptables -A INPUT -p tcp --dport 9418 -j ACCEPT

#REDIS CLUSTER
iptables -A INPUT -p tcp --dport 6379 -j ACCEPT
iptables -A INPUT -p tcp --dport 16379 -j ACCEPT


#HTTP
iptables -A INPUT -p tcp --dport 80 -j ACCEPT

#HTTP Alternate Port --- TURN ON FOR DEV DEPLOY ONLY
#iptables -A INPUT -p tcp --dport 8080 -j ACCEPT

#HTTPS
iptables -A INPUT -p tcp --dport 443 -j ACCEPT

#DROP EVERYTHING ELSE
iptables -A INPUT -p tcp -j DROP
iptables -A INPUT -p udp -j DROP
