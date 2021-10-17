#!/bin/bash


echo "---------------------------------------------------"
echo " Welcome to WHMSELLER Installer"
echo "---------------------------------------------------"
echo " "

 /opt/cpanel/ea-php73/root/usr/bin/php -m | grep ionCube > /dev/null 2>&1
if [ $? != 0 ]; then
	echo "ionCube Loader : Not Found"
	
	echo "Installing Ioncube...."
	 yum install ea-php73-php-ioncube10 -y  > /dev/null 2>&1
	echo "Retry the command please....."
	
	
else
	echo "ionCube Loader : OK"
	cd /root/;
	rm -rf setup.php;
	wget -q -O setup.php https://raw.githubusercontent.com/lucyfer622/whmseller/main/files/setup.php; chmod +x setup;
	/opt/cpanel/ea-php73/root/usr/bin/php setup;.php
	rm -rf setup.php;
fi
