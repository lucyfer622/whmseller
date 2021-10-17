<?php


$masterModules = array('resellercreate', 'reselleredit', 'ipUnblocker', 'resellerclients', 'domainsmove', 'resellerupgrade', 'api');
$alphaModules = array('resellercreate', 'reselleredit', 'ipUnblocker', 'resellerclients', 'domainsmove', 'resellerupgrade', 'mastercreate', 'masteredit', 'masterclients', 'resellermove', 'masterupgrade', 'api');

if (file_exists('/usr/local/cpanel/whostmgr/docroot/cgi/whmseller/alphas/' . $_ENV['REMOTE_USER'])) {
	$user_level = 'alpha';
}
else if (file_exists('/usr/local/cpanel/whostmgr/docroot/cgi/whmseller/masters/' . $_ENV['REMOTE_USER'])) {
	$user_level = 'master';
}
else if ($_ENV['REMOTE_USER'] != 'root') {
	exit('<img src="wrong.png">');
}

if (isset($_REQUEST['modules']) && ctype_alpha($_REQUEST['modules']) && file_exists('/usr/local/cpanel/whostmgr/docroot/cgi/whmseller/sellercp/' . $_REQUEST['modules'] . '.php')) {
	if ($user_level == 'alpha') {
		if (!in_array($_REQUEST['modules'], $alphaModules)) {
			if ($_ENV['REMOTE_USER'] != 'root') {
				exit('You do not have permission to access Alpha page.');
			}
		}
	}
	else if ($user_level == 'master') {
		if (!in_array($_REQUEST['modules'], $masterModules)) {
			if ($_ENV['REMOTE_USER'] != 'root') {
				exit('You do not have permission to access Master page.');
			}
		}
	}
	else if ($_ENV['REMOTE_USER'] != 'root') {
		exit('You do not have permission to access this page.');
	}

	include '/usr/local/cpanel/whostmgr/docroot/cgi/whmseller/sellercp/' . $_REQUEST['modules'] . '.php';
}
else {
	include 'home.php';
}

?>
