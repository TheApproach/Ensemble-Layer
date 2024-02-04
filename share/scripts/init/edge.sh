#!/bin/bash

hare="/usr/share/suitespace"
me=$(sh /usr/share/suitespace/scripts/get_me.sh)

echo "Initializing new edge server.. "

#Add repos

#Install stuff
dnf install nginx php-fpm -y
dnf install php-mysqlnd -y

# DO USER AND GROUP CREATION SCRIPT HERE WITH ADVANED PERMISSIONS

sudo mkdir /srv
sudo chown -R nginx:servers /srv
sudo cp -rf "$hare"/conf/nginx /etc/nginx
sudo cp -rf "$hare"/pkgs/Approach /srv/Approach
sudo cp -rf "$hare"/project/suiteux.com /srv/suiteux.com

# Project setup script. Define symlinks, reconfigure hosts/IPs in configuration files, Run composer and other generators, Register edge with project core, etc..
if test -f /srv/suiteux.com/setup.sh; then
	source /srv/suiteux.com/setup.sh
fi

# Detirmine if connections to Database, Caches, Load balancer, etc are clear. Prove all critical services are running with standard config. Report resource usage
if test -f /srv/suiteux.com/healthcheck.sh; then
	sh /srv/suiteux.com/healthcheck.sh > "$hare"/stats/init/"$me".status
if

if test -f /srv/suiteux.com/cronjobs.sh; then
	source /srve/suiteux.com/crongjobs.sh
fi

#this line probably must change
sudo touch /var/run/php-fpm/suiteux.com.sock 
chmod 660 /var/run/php-fpm/suiteux.com.sock

sudo "$hare"/scripts/edge/firewall.sh
sudo systemctl start php-pm
sudo systemctl start nginx
