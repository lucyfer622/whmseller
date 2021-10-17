<?php


chdir('../');
include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';


if (isset($_POST['action']) && ($_POST['action'] == 'domainsmove')) {
	$master = $_ENV['REMOTE_USER'];
	$data = getFile('masters/' . $master);
	$resellers = array_filter(explode(',', $data['resellers']));
	$acc = new accounts();
	$whmParams['search'] = $_POST['domain'];
	$whmParams['searchtype'] = 'user';
	$result = $acc->whmapi('listaccts', $whmParams);

	if (!in_array($result->acct[0]->owner, $resellers)) {
		$response['error'] = 'Domain not owned by any your resellers.';
		jsonOut($response);
	}

	if (!in_array($_POST['reseller'], $resellers)) {
		$response['error'] = 'End reseller is not under you';
		jsonOut($response);
	}

	$whmParams = array();
	$whmParams['user'] = $_POST['domain'];
	$whmParams['owner'] = $_POST['reseller'];
	$result = $acc->whmapi('modifyacct', $whmParams);
	$result = $result->result[0];

	if ($result->status != 1) {
		$response['error'] = 'Domain Move Failed<br />' . $result->statusmsg;
		jsonOut($response);
	}
	else {
		$response['response'] = 'Domain moved successfully';
		jsonOut($response);
	}
}

include './files/header.php';
include './files/body2.php';

echo '<div class="row mb-3">';
echo '<div class="col-xl-5 mt-2">
<div class="card card-stats">
<div class="card-body">';
echo '<div id="notifications">';if (isset($_GET['success'])) {
    echo '<div class="alert alert-success">'.$_GET['success'].'</div>';
}
echo'<form><div class="form-group "><label for="select">Choose Domain you want to move:</label><div class="col-lg-12 mt-2"><select class="form-control input-sm" name="domain">';
$master = $_ENV['REMOTE_USER'];
$data = getFile('masters/' . $master);
$resellers = array_filter(explode(',', $data['resellers']));

foreach ($resellers as $reseller) {
	$acc = new accounts();
	$whmParams = array();
	$whmParams['search'] = $reseller;
	$whmParams['searchtype'] = 'owner';
	$result = $acc->whmapi('listaccts', $whmParams);

	foreach ($result->acct as $accounts) {
		if (!in_array($accounts->user, $resellers)) {
			echo '<option value="' . $accounts->user . '">' . $accounts->domain . '</option>';
		}
	}
}

echo '  </select> </div> </div><div class="form-group text-center">
<label for="select">Choose the new Domain owner:</label>
<div class="col-lg-12 mt-2"> <select class="form-control input-sm" name="reseller">';
foreach ($resellers as $reseller) {
	echo '<option value="' . $reseller . '">' . $reseller . '</option>';
}
echo'</select></div></div> <div class="form-group"> <div class="col-lg-12"><input type="hidden" name="action" value="domainsmove"><button type="submit" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#loading">Move Domain</button></div>
</div>

</div>
 </div>
</div></div></form>
<div class="col-xl-7 mt-2">
<div class="card card-stats">
<div class="card-body"><p>In this section, you can move Domain Names between Resellers. This action will not affect username, password etc. It will only change ownership of Domains.<br><b>Domain Names</b> that shows in the list but the ownership can not be changed are the MAIN DOMAINS of Resellers..
</div>
 
</div></div><div class="modal fade" id="loading">' . "\r\n" . '<div class="modal-dialog">' . "\r\n" . ' <div class="modal-content">' . "\r\n" . ' <div class="modal-body">' . "\r\n" . '<img src="process.gif"></div>' . "\r\n" . '</div>' . "\r\n" . '</div>' . "\r\n" . '</div>';

include './files/footer.php';

?>	