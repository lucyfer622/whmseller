<?php


$installedVersion = trim(file_get_contents('includes/version'));
$currentVersion = trim(file_get_contents('https://raw.githubusercontent.com/lucyfer622/whmseller/main/version'));

if ($installedVersion < $currentVersion) {
	$output = shell_exec('rm whmseller.zip -f; wget https://www.whmseller.com/update/v2/whmseller.zip; unzip -o whmseller.zip -d /usr/local/cpanel/whostmgr/docroot/cgi/whmseller; rm whmseller.zip -f; dos2unix /usr/local/cpanel/whostmgr/docroot/cgi/whmseller/sellercp/index.cgi; chmod +x /usr/local/cpanel/whostmgr/docroot/cgi/whmseller/sellercp/index.cgi');
	include 'afterupdate.php';
}

?>
