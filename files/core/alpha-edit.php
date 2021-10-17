<?php


echo '﻿';

if ($_ENV['REMOTE_USER'] != 'root') {
	exit();
}


include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';

if (isset($_GET['alpha'])) {
	if (!ctype_alnum($_GET['alpha'])) {
		exit('Invalid Alpha');
	}

	if (!file_exists('alphas/' . $_GET['alpha'])) {
		exit('Alpha not found');
	}
}
else if ($_POST['username']) {
	if (!ctype_alnum($_POST['username'])) {
		exit('Invalid Alpha');
	}

	if (!file_exists('alphas/' . $_POST['username'])) {
		exit('Alphas not found');
	}
}

if (isset($_POST['action']) && !empty($_POST['username'])) {
	$acc = new accounts();

	if ($_POST['action'] == 'update-alpha') {
		if ($_POST['password'] != '') {
			$res = $acc->changepwd($_POST['username'], $_POST['password']);
		}

		if (!empty($_POST['mastersallowed'])) {
			if (!is_numeric($_POST['mastersallowed'])) {
				$_POST['mastersallowed'] = 'unlimited';
			}

			$res = $acc->whmseller_c($_POST['username'], array('mastersallowed' => $_POST['mastersallowed']));
		}

		if (!empty($_POST['domainsallowed'])) {
			if (!is_numeric($_POST['domainsallowed'])) {
				$_POST['domainsallowed'] = 'unlimited';
			}

			$res = $acc->whmseller_c($_POST['username'], array('domainsallowed' => $_POST['domainsallowed']));
		}
	}
	else {
		if (($_POST['action'] == 'suspend') || ($_POST['action'] == 'unsuspend') || ($_POST['action'] == 'terminate') || ($_POST['action'] == 'removeal')) {
			if (empty($_POST['username']) || !is_file('alphas/' . $_POST['username'])) {
				$res['error'] = 'Invalid Attempt';
				jsonOut($res);
			}

			$res = $acc->whmseller_h($_POST['username'], $_POST['action']);
		}
	}

	jsonOut($res);
}
else {
	if (empty($_GET['alpha']) || !is_file('alphas/' . $_GET['alpha'])) {
		exit('Invalid Attempt');
	}
}

include './files/header.php';
    include './files/body.php';


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
<label for="inputResellers" class="col-4 col-form-label">Masters Limit</label> 
<div class="col-8">
<input id="inputResellers" name="mastersallowed" placeholder="Add Number or UNLIMITED" type="text" class="form-control here">
<small class="pr-5">Current Limit:';
$data = getFile('alphas/' . $_GET['alpha']);
echo $data['mastersallowed'];
echo '</small>
</div>
</div>
<div class="form-group row">
<label for="inputDomains" class="col-4 col-form-label">Domains Limit</label> 
<div class="col-8">
<input id="inputDomains" name="domainsallowed" placeholder="Add Number or UNLIMITED" type="text" class="form-control here">
<small class="pr-5">Current Limit:';
echo $data['domainsallowed'];
echo ' </small>
</div>
</div>
<div class="form-group"> <div class="col-lg-12"><input type="hidden" name="username" value="';
echo $_GET['alpha'];
echo'"><input type="hidden" name="action" value="update-alpha"><button type="submit" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#loading">Update</button></div>
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
                           <center><button class="btn bta btn-warning" data-rs-action="';

if ($data['status'] == 'active') {
	echo 'suspend';
}
else {
	echo 'unsuspend';
}

echo '" data-rs="' . $_GET['alpha'] . '">';

if ($data['status'] == 'active') {
	echo 'Suspend';
}
else {
	echo 'UnSuspend';
}

echo ' Alpha Reseller</button></center><p></p>
                        <p></p><center><button class="btn bta btn-danger" data-rs-action="terminate" data-rs="';
echo $_GET['alpha'];
echo '">Terminate Alpha Reseller</button></center><p></p>
                    	<p></p><center><button class="btn bta btn-info" data-rs-action="removeal" data-rs="';
echo $_GET['alpha'];
echo '">Downgrade to Master Reseller </button><p></p></center><p>"<b>Please note that Suspending or Terminating an Alpha Reseller Will Suspend or Terminate all users that are under this Alpha reseller ownership.</b>"</p>
                    </div></div>
  </div>
 
</div></div></div>';

echo '<div class="row">'."\r\n".'<div class="col-xl-12 mb-5 mb-xl-0">'."\r\n".'<div class="card shadow">'."\r\n".'<div class="card-header border-0">'."\r\n".'<div class="row align-items-center">'."\r\n".'<div class="col">'."\r\n".'<h3 class="mb-0">Master Reseller List</h3>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="table-responsive">'."\r\n".'<!-- Projects table -->'."\r\n".'<table class="table align-items-center table-flush">'."\r\n".'<thead class="thead-light">';
echo'<tr>';
echo'<th scope="col">#</th>'."\r\n".'<th scope="col">Domain</th>'."\r\n".'<th scope="col">Username</th>'."\r\n".'<th scope="col">Status</th>'."\r\n".'<th scope="col">No of Resellers</th>'."\r\n".'<th scope="col">Options</th>'."\r\n";
 if (isset($_GET['full_details'])) {
	echo '<th>Resellers</th>'."\r\n".'<th>Domains</th>'."\r\n".'<th>Space & Bandwidth Used</th>'."\r\n";
}echo '<th></th>'."\r\n".'<th></th>'."\r\n".'</tr></thead><tbody>'."\r\n";
$serialNo = 1;
$masters = array_filter(explode(',', $data['masters']));
$acc = new accounts();

foreach ($masters as $master) {
	echo '<tr>' . "\r\n\t\t\t\t\t\t\t\t\t" . '<td>' . $serialNo . '</td>' . "\r\n\t\t\t\t\t\t\t\t\t" . '<td>';
	$masData = getFile('masters/' . $master);
	echo $masData['domainname'];
	echo '</td>' . "\r\n\r\n\t\t\t\t\t\t\t\t\t" . '<td>' . $master . '</td>' . "\r\n\t\t\t\t\t\t\t\t\t" . '<td>';

	if ($masData['status'] != 'active') {
		echo '<span class="btn btn-sm btn-danger text-white">Suspended</span>';
	}
	else {
		echo '<span class="btn btn-sm btn-success text-white">Active</span>';
	}

	echo '</td>' . "\r\n\t\t\t\t\t\t\t\t\t" . '<td><span>';
	echo count(array_filter(explode(',', $masData['resellers'])));
	echo '</span></td>' . "\r\n\t\t\t\t\t\t\t\t\t" . '<td><a href="javascript:passwd(\'' . $master . '\')" target="_blank" class="btn bta btn-sm btn-primary">Change Passwd</a> <a href="master-edit.php?master=' . $master . '" target="_blank" class="btn bta btn-sm btn-primary">More Options</a></td>' . "\r\n\t\t\t\t\t\t\t\t" . '</tr>';
	++$serialNo;
}
echo "\r\n" . ' </td>'."\r\n".'</tbody>'."\r\n".'</tr></table>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div></div>';

echo '<div class="row mt-3">'."\r\n".'<div class="col-xl-12 mb-5 mb-xl-0">'."\r\n".'<div class="card shadow">'."\r\n".'<div class="card-header border-0">'."\r\n".'<div class="row align-items-center">'."\r\n".'<div class="col">'."\r\n".'<h3 class="mb-0">Reseller List</h3>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="table-responsive">'."\r\n".'<!-- Projects table -->'."\r\n".'<table class="table align-items-center table-flush">'."\r\n".'<thead class="thead-light">';
echo'<tr>';
echo'<th scope="col">#</th>'."\r\n".'<th scope="col">Domain</th>'."\r\n".'<th scope="col">Username</th>'."\r\n".'<th scope="col">Status</th>'."\r\n".'<th scope="col">No of Resellers</th>'."\r\n".'<th scope="col">Options</th>'."\r\n";
 if (isset($_GET['full_details'])) {
	echo '<th>Resellers</th>'."\r\n".'<th>Domains</th>'."\r\n".'<th>Space & Bandwidth Used</th>'."\r\n";
}echo '<th></th>'."\r\n".'<th></th>'."\r\n".'</tr></thead><tbody>'."\r\n";
$serialNo = 1;
$data = getFile('masters/' . $_GET['alpha']);
$resellers = array_filter(explode(',', $data['resellers']));

foreach ($resellers as $reseller) {
	echo '<tr>' . "\r\n\t\t\t\t\t\t\t\t\t" . '<td>' . $serialNo . '</td>' . "\r\n\t\t\t\t\t\t\t\t\t" . '<td>';
	$acc = new accounts();
	$whmParams = array();
	$whmParams['user'] = $reseller;
	$result = $acc->whmapi('accountsummary', $whmParams);
	echo $result->acct[0]->domain;
	echo '</td>' . "\r\n\r\n\t\t\t\t\t\t\t\t\t" . '<td>' . $reseller . '</td>' . "\r\n\t\t\t\t\t\t\t\t\t" . '<td>';

	if ($result->acct[0]->suspended) {
		echo '<span class="btn btn-sm btn-danger text-white">Suspended</span>';
	}
	else {
		echo '<span class="btn btn-sm btn-success text-white">Active</span>';
	}

	echo '</td>' . "\r\n\t\t\t\t\t\t\t\t\t" . '<td><span>';
	$whmParams = array();
	$whmParams['user'] = $reseller;
	$result = $acc->whmapi('acctcounts', $whmParams);
	$domains = $result->reseller->suspended + $result->reseller->active;

	if ($domains == 0) {
		++$domains;
	}

	echo $domains;
	echo '</span></td>' . "\r\n\t\t\t\t\t\t\t\t\t" . '<td><a href="javascript:passwd(\'' . $reseller . '\')" target="_blank" class="btn bta btn-sm btn-primary">Change Passwd</a> <a href="../../scripts2/statres?res=' . $reseller . '" target="_blank" class="btn bta btn-sm btn-primary">More Options</a></td>' . "\r\n\t\t\t\t\t\t\t\t" . '</tr>';
	++$serialNo;
}

echo '</tbody>'."\r\n".'</tr></table>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>';
include './files/footer.php';
  
?>
