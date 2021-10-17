<?php


echo '﻿';
chdir('../');
include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';
$acc = new accounts();
$whmParams = array();
$result_accounts = $acc->whmapi('listpkgs');

if (isset($_GET['reseller'])) {
	$data = getFile('masters/' . $_ENV['REMOTE_USER']);
	$resellers = array_filter(explode(',', $data['resellers']));

	if (!in_array($_GET['reseller'], $resellers)) {
		exit();
	}
}

if (isset($_POST['action']) && !empty($_POST['username'])) {
	$acc = new accounts();

	if ($_POST['action'] == 'resellerupdate') {
		if ($_POST['password'] != '') {
			$res = $acc->changepwd($_POST['username'], $_POST['password']);
		}

		if (($_POST['domainsallowed'] != '') || ($_POST['domainsallowed'] != '') || ($_POST['diskspace'] != '') || ($_POST['bandwidth'] != '') || ($_POST['dsoversell'] != '') || ($_POST['bwoversell'] != '')) {
			$res = $acc->whmseller_d($_POST['username'], $_POST['domainsallowed'], $_POST['diskspace'], $_POST['bandwidth'], $_POST['dsoversell'], $_POST['bwoversell']);

			if ($res['error'] == '') {
				$res['response'] = 'Reseller Limits has been updated';
			}
		}
	}

	if (($_POST['action'] == 'suspend') || ($_POST['action'] == 'unsuspend') || ($_POST['action'] == 'terminate') || ($_POST['action'] == 'removers')) {
		$res = $acc->whmseller_b($_POST['username'], $_POST['action']);
	}

	jsonOut($res);
}
else if (empty($_GET['reseller'])) {
	exit('Invalid Attempt');
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
echo '<div id="account-update">';
echo'<form id="updateinfo"><div class="form-group row" >
<label for="inputPassword" class="col-4 col-form-label">Password</label> 
<div class="col-8">
<input id="inputPassword" name="password" placeholder="Enter New Password" type="text" class="form-control here">
</div>
</div>
<div class="form-group row">
<label for="inputResellers" class="col-4 col-form-label">cPanel Accounts Limit</label> 
<div class="col-8">
<input id="inputResellers" name="domainsallowed" placeholder="Add Number or UNLIMITED" type="text" class="form-control here">
<small class="pr-5">Current Limit:';
$acc = new accounts();
$whmParams = array();
$whmParams['user'] = $_GET['reseller'];
$result = $acc->whmapi('acctcounts', $whmParams);
echo $result->reseller->limit;
echo '</small>
</div>
</div>
<div class="form-group row">'."\r\n".'<label for="diskspace" class="col-4 col-form-label">Disk Space</label>'."\r\n".'<div class="col-8">'."\r\n".'<div class="input-group">'."\r\n".'<input id="diskspace" name="diskspace" placeholder="Add Number or UNLIMITED" type="text" class="form-control here"> '."\r\n".'<div class="input-group-addon append">MB</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div> '."\r\n".'<div class="form-group row">'."\r\n".'<label class="col-4">Overselling</label> '."\r\n".'<div class="col-8 text-left">'."\r\n".'<label class="">'."\r\n".'<input type="checkbox" name="dsoversell" id="inputdsos" value="1">'."\r\n".'<span class="custom-control-indicator"></span> '."\r\n".'<span class="custom-control-description">Can oversell Disk Space.</span>'."\r\n".' </label>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="form-group row">'."\r\n".'
    <label for="diskspace" class="col-4 col-form-label">Bandwidth</label> 
    <div class="col-8">
      <div class="input-group">
        <input id="inputbw" name="bandwidth" placeholder="Add Number or UNLIMITED" type="text" class="form-control here"> 
        <div class="input-group-addon append">MB</div>
      </div>
    </div>
  </div><div class="form-group row">'."\r\n".'<label class="col-4">Overselling</label> '."\r\n".'<div class="col-8 text-left">'."\r\n".'<label class="">'."\r\n".' <input type="checkbox" name="bwoversell" value="1" id="bwos">'."\r\n".' <span class="custom-control-indicator"></span> '."\r\n".'<span class="custom-control-description">Can oversell Bandwidth.</span>'."\r\n".'</label>'."\r\n".' </div></div>';
echo'<div class="form-group"> <div class="col-lg-12"><input type="hidden" name="username" value="';
echo $_GET['reseller'];
echo'"><input type="hidden" name="action" value="resellerupdate"><button type="submit" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#loading">Update</button></div>
</div>
</div>
 </div>
</div></div></form></div>
<div class="col-xl-7 mt-2">
<div class="card card-stats">
<div class="card-body">
<div class="card bg-dark text-white line">
    <div class="card-body">Account Functions</div>
  </div>
  <div id="account-update">
  <div class="mt-3" id="account-functions">
                           <center><button class="btn bta btn-warning" data-rs-action=';
$acc = new accounts();
$whmParams = array();
$whmParams['user'] = $_GET['reseller'];
$result = $acc->whmapi('accountsummary', $whmParams);

if ($result->acct[0]->suspended) {
	echo '"unsuspend" data-rs="' . $_GET['reseller'] . '">Un';
}
else {
	echo '"suspend" data-rs="' . $_GET['reseller'] . '">';
}

echo ' Suspend Reseller</button></center>
                        <p></p><center><button class="btn bta btn-danger" data-rs-action="terminate" data-rs="';
echo $_GET['reseller'];
echo '">Terminate Reseller</button></center><p></p>
                    	<p></p><center><button class="btn bta btn-info" data-rs-action="removers" data-rs="';
echo $_GET['reseller'];
echo '">Remove Reseller Permission</button><p></p></center><p>"<b>Please note that Suspending or Terminating an Reseller will Suspend or Terminate all users that are under this Reseller ownership.</b>"</p>
                    </div></div>
  </div>
 
</div></div></div>';


echo '<div class="row mt-3">'."\r\n".'<div class="col-xl-12 mb-5 mb-xl-0">'."\r\n".'<div class="card shadow">'."\r\n".'<div class="card-header border-0">'."\r\n".'<div class="row align-items-center">'."\r\n".'<div class="col">'."\r\n".'<h3 class="mb-0">Domain List</h3>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="table-responsive">'."\r\n".'<!-- Projects table -->'."\r\n".'<table class="table align-items-center table-flush">'."\r\n".'<thead class="thead-light">';
echo'<tr>';
echo'<th scope="col">#</th>'."\r\n".'<th scope="col">Domain Name</th>'."\r\n".'<th scope="col">Username</th>'."\r\n".'<th scope="col">Status</th>'."\r\n".'<th scope="col">Disk Space</th>'."\r\n".'<th scope="col">Created on</th>'."\r\n";
$serialNo = 1;
$acc = new accounts();
$whmParams = array();
$whmParams['search'] = $_GET['reseller'];
$whmParams['searchtype'] = 'owner';
$result = $acc->whmapi('listaccts', $whmParams);

foreach ($result->acct as $accounts) {
	echo '<tr>' . "\r\n\t\t\t\t\t\t\t\t" . '<td>' . $serialNo . '</td>' . "\r\n\t\t\t\t\t\t\t\t" . '<td>' . $accounts->domain . '</td>' . "\r\n\t\t\t\t\t\t\t\t" . '<td>' . $accounts->user . '</td>' . "\r\n\t\t\t\t\t\t\t\t" . '<td>';

	if ($accounts->suspended) {
		echo '<span class="label label-warning">Suspended</span>';
	}
	else {
		echo '<span class="label label-success">Active</span>';
	}

	echo '</td>' . "\r\n\t\t\t\t\t\t\t\t" . '<td>';

	if ($accounts->diskused == 'none') {
		echo '0 M';
	}
	else {
		echo $accounts->diskused;
	}

	echo 'B</td>' . "\r\n\t\t\t\t\t\t\t\t" . '<td>';
	$date = explode(' ', $accounts->startdate);
	echo $date[2] . '-' . $date[1] . '-' . $date[0];
	echo '</td>' . "\r\n\t\t\t\t\t\t\t" . '</tr>';
}

echo '</tbody>'."\r\n".'</tr></table>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>';
include './files/footer.php';
  
?>
