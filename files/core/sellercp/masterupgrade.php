<?php
if (($user_level) != 'alpha') {
	exit('<img src="wrong.png">');
}
chdir('../');
include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';

if (isset($_POST['action']) && ($_POST['action'] == 'masterupgrade')) {
	$explode = explode('-', $_POST['username']);
	$_POST['username'] = $explode[1];
	$alpData = getFile('alphas/' . $_ENV['REMOTE_USER']);
	$masters = explode(',', $alpData['masters']);

	if (in_array($explode[0], $masters)) {
		$masData = getFile('masters/' . $explode[0]);
		$resellers = array_filter(explode(',', $masData['resellers']));

		if (in_array($_POST['username'], $resellers)) {
			$acc = new accounts();
			$res = $acc->upgrademaster($_POST['username'], $_POST['resellers'], $_POST['maxdomains'], 1);
			if (!isset($res['error']) || ($res['error'] == '')) {
				$masters[] = $_POST['username'];
				$alpData['masters'] = implode(',', array_filter(array_unique($masters)));
				$acc->whmseller_c($alpData['username'], $alpData);
			}
		}
		else {
			$res['error'] = 'Reseller not Found';
		}
	}
	else {
		$res['error'] = 'Master not owned by you';
	}

	jsonOut($res);
}

include './files/header.php';
include './files/body2.php';

echo '<div class="row mb-3">';
echo '<div class="col-xl-5 mt-2">
<div class="card card-stats">
<div class="card-body">';
echo '<div id="notifications">';if (isset($_GET['success'])) {
	echo '<div class="alert alert-success">' . $_GET['success'] . '</div>';
}
echo'<form><div class="form-group "><label for="select">Choose Reseller to Upgrade:</label><div class="col-lg-12 mt-2"><select class="form-control input-sm" name="username">';
$alpha = $_ENV['REMOTE_USER'];
$alpData = getFile('alphas/' . $alpha);
$masters = array_filter(explode(',', $alpData['masters']));

foreach ($masters as $master) {
	$masData = getFile('masters/' . $master);
	$resellers = array_filter(explode(',', $masData['resellers']));

	foreach ($resellers as $reseller) {
		if ($reseller != $master) {
			echo '<option value="' . $master . '-' . $reseller . '">' . $reseller . '</option>';
		}
	}
}
echo'</select></div>
<div class="form-group">
<label for="inputResellers" class="col-12 col-form-label">Resellers Limit:</label> 
<div class="col-12">
<input id="inputResellers" name="resellers" placeholder="Add Number or UNLIMITED" type="text" class="form-control here">
</div>
<div class="form-group">
<label for="inputResellers" class="col-12 col-form-label">Domain Limit:</label> 
<div class="col-12">
<input id="inputResellers" name="maxdomains" placeholder="Add Number or UNLIMITED" type="text" class="form-control here">
</div>
<div class="form-group"> <div class="col-lg-12 mt-3"><input type="hidden" name="action" value="masterupgrade"><button type="submit" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#loading">Upgrade to Master</button></div>
</div>

</div>
 </div>
</form></div></div></div></div></div>
<div class="col-xl-7 mt-2">
<div class="card card-stats">
<div class="card-body"><p>Here you can upgrade Resellers to Master Resellers. This action will not effect on username, password. It will only upgrade Level for Reselling.</p>
</div>
 
</div></div><div class="modal fade" id="loading">' . "\r\n" . '<div class="modal-dialog">' . "\r\n" . ' <div class="modal-content">' . "\r\n" . ' <div class="modal-body">' . "\r\n" . '<img src="process.gif"></div>' . "\r\n" . '</div>' . "\r\n" . '</div>' . "\r\n" . '</div>';

include './files/footer.php';

    
?>
