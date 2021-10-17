<?php
define('SITE_LOADED',true);
echo '<!DOCTYPE html>' . "\r\n" . '<html lang="en">' . "\r\n" . '<head>' . "\r\n\t" . '<meta charset="utf-8">' . "\r\n\t" . '<meta http-equiv="X-UA-Compatible" content="IE=edge">' . "\r\n\t" . '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\r\n\t" . '<title>';
if ($fileName = basename($_SERVER['SCRIPT_FILENAME'], '.php'));
	if ($fileName == 'index') {
		echo 'WHMSELLER DASHBOARD';
	}
	elseif (in_array($fileName, array('alpha-clients', 'alpha-create', 'alpha-edit', 'alpha-move'))) {
		echo 'Alpha Reseller Dashboard';
	}
echo '</title>';
if ($user_level == 'alpha')  { 
echo'<base href="../" >';
}
elseif ($user_level == 'master')  { 
echo'<base href="../" >';
}
else{
}
echo '<link href="assets/css/argon.css" rel="stylesheet">' . "\r\n" . '<link href="assets/css/argon.min.css" rel="stylesheet">' . "\r\n" .'<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">'."\r\n".'<link href="assets/vendor/nucleo/css/nucleo.css" rel="stylesheet">'."\r\n".'  <link href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">'."\r\n".'</head>'."\r\n";
echo '<body>'."\r\n"; include 'files/menu.php';
echo "\r\n";
?>