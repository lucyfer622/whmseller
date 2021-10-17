<?php


chdir('../');
include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';


if (isset($_POST['action']) && ($_POST['action'] == 'resellerupgrade')) {
	$acc = new accounts();
	$res = $acc->upgradereseller($_POST['username'], $_POST['domains']);
	
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
echo'<form><div class="form-group "><label for="select">Choose cPanel to Upgrade:</label><div class="col-lg-12 mt-2"><select class="form-control input-sm" name="username">';
$master = $_ENV['REMOTE_USER'];
$data = getFile('masters/' . $master);
$resellers = array_filter(explode(',', $data['resellers']));

foreach ($resellers as $reseller) {
	$acc = new accounts();
	$whmParams = array();
	$whmParams['search'] = $reseller;
	$whmParams['searchtype'] = 'owner';
	$result = $acc->whmapi('listaccts', $whmParams);
	$resellers = $acc->whmapi('listresellers', '');

	foreach ($result->acct as $accounts) {
		if (!in_array($accounts->user, $resellers->reseller)) {
			echo '<option value="' . $accounts->user . '">' . $accounts->domain . '</option>';
		}
	}
}
echo'</select></div>
<div class="form-group">
<label for="inputResellers" class="col-12 col-form-label">Domain Limit:</label> 
<div class="col-12">
<input id="inputResellers" name="domains" placeholder="Add Number or UNLIMITED" type="text" class="form-control here">
</div>
<div class="form-group"> <div class="col-lg-12 mt-3"><input type="hidden" name="action" value="resellerupgrade"><button type="submit" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#loading">Upgrade to Reseller</button></div>
</div>

</div>
 </div>
</form></div></div></div></div>
<div class="col-xl-7 mt-2">
<div class="card card-stats">
<div class="card-body"><p>Here you can Upgrade <i>cPanel Users</i> to Resellers.This action will not affect username, password etc. It will only Upgrade Accounts.
</div>
 
</div></div><div class="modal fade" id="loading">' . "\r\n" . '<div class="modal-dialog">' . "\r\n" . ' <div class="modal-content">' . "\r\n" . ' <div class="modal-body">' . "\r\n" . '<img src="process.gif"></div>' . "\r\n" . '</div>' . "\r\n" . '</div>' . "\r\n" . '</div>';

include './files/footer.php';

    
?>
