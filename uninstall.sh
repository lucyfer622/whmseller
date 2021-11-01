#!/bin/bash
IP=$(hostname -i);
HOST=$(hostname);
echo "#####################################################################################";
echo "#####################################################################################";
echo "#####                                                                           #####";
echo "#####                            WHMSELLER Uninstall                            #####";
echo "#####                                                                           #####";
echo "##### Written and maintained by https://github.com/lucyfer622/whmseller/        #####";
echo "##### Email lucifer_622@hotmail.com for any questions regarding this module     #####";
echo "##### We are kind sad to see you leave, but please help the plugin grow.        #####";
echo "#####################################################################################";
echo "#####################################################################################";
echo "";
echo "#####################################################################################";
echo "|Website : https://github.com/lucyfer622/whmseller/";
echo "|Server Ip : $IP";
echo "|Hostname : $HOST";
echo "";
echo "Whmseller uninstalling....";
/usr/local/cpanel/bin/unregister_appconfig /usr/local/cpanel/whostmgr/docroot/cgi/whmseller/whmseller.conf;
rm -rf /usr/local/cpanel/whostmgr/docroot/cgi/addon_whmseller.cgi;
rm -rf /usr/local/cpanel/whostmgr/docroot/cgi/whmseller;
crontab -u root -l | grep -v 'whmseller' | crontab -u root -
/bin/systemctl restart cpanel.service > /dev/null 2>&1;
echo "Hope we see you in near future ;) You can allways count on our plugin to your needs";
rm -rf uninstall.sh;
