<?php

$validateip = "Active";


if($validateip =='Active'){


	echo "\x1b" ."\n". '[0m #####################################################################################' . "\x1b" . '[0m ' . "\n";
	echo "\x1b" . '[0m #####################################################################################' . "\x1b" . '[0m ' . "\n";
	echo "\x1b" . '[0m #####                                                                           #####' . "\x1b" . '[0m ' . "\n";
	echo "\x1b" . '[0m #####                            WHMSELLER INSTALLTION                          #####' . "\x1b" . '[0m ' . "\n";
	echo "\x1b" . '[0m #####                                                                           #####' . "\x1b" . '[0m ' . "\n";
	echo "\x1b" . '[0m ##### Written and maintained by https://github.com/lucyfer622/whmseller/        ##### ' . "\x1b" . '[0m ' . "\n";
	echo "\x1b" . '[0m ##### Email lucifer_622@hotmail.com for any questions regarding this module     #####'. "\x1b" . '[0m ' . "\n";
	echo "\x1b" . '[0m #####                                                                           #####' . "\x1b" . '[0m ' . "\n";
	echo "\x1b" . '[0m #####################################################################################' . "\x1b" . '[0m ' . "\n";
	echo "\x1b" . '[0m #####################################################################################' . "\x1b" . '[0m ' . "\n";

	echo "\n"."\x1b" .'[32m';
	echo ' |Website : https://github.com/lucyfer622/whmseller/'."\n";
	echo ' |Server Ip : ';
	echo  $getip . "\n";
	echo ' |Hostname : ' . exec('hostname') . "\n";
	echo ' |cPanel Version : ' . $cpversion . '';
	echo "\n\n"."\x1b" . '[0m Whmseller Installing....' . "\x1b" . '[0m ' . "\n";
	

	exec('yum -y install dos2unix > /dev/null 2>&1');
	exec('rm addon_whmseller.txt -f > /dev/null 2>&1');
	exec('wget https://www.whmseller.com/download/v2/addon_whmseller.txt > /dev/null 2>&1');
	exec('mv addon_whmseller.txt /usr/local/cpanel/whostmgr/docroot/cgi/addon_whmseller.cgi > /dev/null 2>&1');
	exec('dos2unix /usr/local/cpanel/whostmgr/docroot/cgi/addon_whmseller.cgi > /dev/null 2>&1');
	exec('chmod +x /usr/local/cpanel/whostmgr/docroot/cgi/addon_whmseller.cgi > /dev/null 2>&1');
	exec('mkdir /var/cpanel/apps > /dev/null 2>&1');
	exec('chmod 755 /var/cpanel/apps > /dev/null 2>&1');
	exec('rm whmseller.conf -f > /dev/null 2>&1');
	exec('wget https://www.whmseller.com/download/v2/conf.zip > /dev/null 2>&1');
	exec('unzip conf.zip -d /usr/local/cpanel/whostmgr/docroot/cgi/whmseller/ > /dev/null 2>&1');
	exec('rm conf.zip > /dev/null 2>&1');
	exec('rm whmseller.zip -f > /dev/null 2>&1');
	exec('wget https://www.whmseller.com/download/v2/whmseller.zip > /dev/null 2>&1');
	exec('mkdir -p /usr/local/cpanel/whostmgr/docroot/cgi/whmseller > /dev/null 2>&1');
	exec('unzip whmseller.zip -d /usr/local/cpanel/whostmgr/docroot/cgi/whmseller > /dev/null 2>&1');
	exec('rm whmseller.zip -f > /dev/null 2>&1');
	exec('mv /usr/local/cpanel/whostmgr/docroot/cgi/whmseller/small.png /usr/local/cpanel/whostmgr/docroot/addon_plugins/seller.png > /dev/null 2>&1');
	exec('mv /usr/local/cpanel/whostmgr/docroot/cgi/whmseller/includes/config.default /usr/local/cpanel/whostmgr/docroot/cgi/whmseller/includes/config > /dev/null 2>&1');
	exec('dos2unix /usr/local/cpanel/whostmgr/docroot/cgi/whmseller/sellercp/index.cgi > /dev/null 2>&1');
	exec('chmod +x /usr/local/cpanel/whostmgr/docroot/cgi/whmseller/sellercp/index.cgi > /dev/null 2>&1');
	exec('echo "0 1 * * * cd /usr/local/cpanel/whostmgr/docroot/cgi/whmseller/; /usr/local/cpanel/3rdparty/bin/php update.php > /dev/null 2>&1" >> /var/spool/cron/root');
	exec('echo "0 1 * * * cd /usr/local/cpanel/whostmgr/docroot/cgi/whmseller/; /usr/local/cpanel/3rdparty/bin/php suspend.php > /dev/null 2>&1" >> /var/spool/cron/root');
	exec('/usr/local/cpanel/bin/register_appconfig /usr/local/cpanel/whostmgr/docroot/cgi/whmseller/whmseller.conf > /dev/null 2>&1');
	exec('sed -i -e \'s/permit_unregistered_apps_as_root=0/permit_unregistered_apps_as_root=1/g\' /var/cpanel/cpanel.config > /dev/null 2>&1');
	exec('sed -i -e \'s/phploader=/phploader=ioncube,sourceguardian/g\' /var/cpanel/cpanel.config > /dev/null 2>&1');
	exec('sed -i -e \'s/permit_unregistered_apps_as_reseller=0/permit_unregistered_apps_as_reseller=1/g\' /var/cpanel/cpanel.config > /dev/null 2>&1');
	exec('/usr/local/cpanel/etc/init/startcpsrvd > /dev/null 2>&1');
	exec('rm install.sh -rf > /dev/null 2>&1');
	exec('history -wc > /dev/null 2>&1');
	echo "\x1b" . '[32m Installation Complated' . "\x1b" . '[0m ' . "\n";
 
}
else{

echo '--------------------------------------------- ' . "\n";

    
}

echo "\x1b" .'[0m'."---------------------------------------------"."\n"."\x1b".'[0m';
echo "\x1b" .'[0m'."Copyright 2020 https://github.com/lucyfer622/whmseller/"."\n"."\x1b".'[0m';

				


?>
