<?php
if(!defined('SITE_LOADED')) {
	die();
}
echo'<div class="main-content">'."\r\n".'<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">'."\r\n".'<div class="container-fluid"><!-- Brand --><a class="h3 mb-0 text-white text-uppercase d-none d-lg-inline-block">';
if ($user_level == 'alpha') {

	echo "Alpha Reseller Dashboard";
}
elseif ($user_level == 'master') {
	echo "Master Reseller Dashboard";
}
echo'</a></div></nav>'."\r\n".'<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">'."\r\n".'<div class="container-fluid">'."\r\n".'<div class="header-body">'."\r\n".'<!-- Card stats --><div class="row">'."\r\n";
if ($user_level == 'alpha') {

echo '<div class="col-xl-3 col-lg-6">'."\r\n".'<div class="card card-stats mb-4 mb-xl-0">'."\r\n".'<div class="card-body">'."\r\n".'<div class="row">'."\r\n".'<div class="col">'."\r\n".'<h5 class="card-title text-uppercase text-muted mb-2">Number of Master Resellers</h5>'."\r\n".'<span class="h3 font-weight-bold text-uppercase mb-2">';$mrCount = 0;
	$data = getFile('alphas/' . $_ENV['REMOTE_USER']);
	$masters = array_filter(explode(',', $data['masters']));
	$mrCount = $mrCount + count($masters);
	echo ($mrCount - 1) . ' of ' . $data['mastersallowed'];
echo '</span>'."\r\n".'<br><a href="sellercp/index.cgi?modules=masterclients" class="btn btn-sm mt-2 btn-info">View Usage</a>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n";
}
echo '<div class="col-xl-3 col-lg-6"> <div class="card card-stats mb-4 mb-xl-0">'."\r\n".'<div class="card-body">'."\r\n".'<div class="row">'."\r\n".'<div class="col">'."\r\n".'<h5 class="card-title text-uppercase text-muted mb-2">Number of cPanel Resellers</h5>'."\r\n".'<span class="h3 font-weight-bold text-uppercase mb-2">';
if ($user_level == 'master') {
	$masters = array($_ENV['REMOTE_USER']);
}

$resellersCount = 0;
$resellers = array();

foreach ($masters as $master) {
	$data = getFile('masters/' . $master);
	$resellers = array_merge($resellers, array_filter(explode(',', $data['resellers'])));
}

$resellers = array_filter(array_unique($resellers));
echo count($resellers) - 1;

if ($user_level == 'master') {
	echo ' of ' . $data['resellersallowed'];
}
echo '</span>'."\r\n".'<br><a a href="sellercp/index.cgi?modules=resellerclients" class="btn btn-sm mt-2 btn-info">View Usage</a>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n";

if ($user_level == 'alpha') {
echo'<div class="col-xl-3 col-lg-6">'."\r\n".'<div class="card card-stats mb-4 mb-xl-0">'."\r\n".'<div class="card-body">'."\r\n".'<div class="row">'."\r\n".'<div class="col">'."\r\n".'<h5 class="card-title text-uppercase text-muted mb-2">Number of Domains</h5>'."\r\n".'<span class="h3 font-weight-bold text-uppercase mb-2">';
$domainsCount = 0;
	$acc = new accounts();

	foreach ($resellers as $reseller) {
		$whmParams = array();
		$whmParams['search'] = $reseller;
		$whmParams['searchtype'] = 'owner';
		$result = $acc->whmapi('listaccts', $whmParams);
		$domainsCount += count($result->acct);
	}

	echo $domainsCount;
echo '</span>'."\r\n".'<br><a href="../../scripts4/listaccts" class="btn btn-sm mt-2 btn-info">View Usage</a>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>';

echo'<div class="col-xl-3 col-lg-6">'."\r\n".'<div class="card card-stats mb-4 mb-xl-0">'."\r\n".'<div class="card-body">'."\r\n".'<div class="row">'."\r\n".'<div class="col">'."\r\n".'<h5 class="card-title text-uppercase text-muted mb-2">Domains Limit to Masters
</h5>'."\r\n".'<span class="h3 font-weight-bold text-uppercase mb-2">';
$acc = new accounts();
	$domainsAlloc = $acc->getdomains();

	if (is_numeric($domainsAlloc[0])) {
		echo $domainsAlloc[1] . ' of ' . $domainsAlloc[0];
	}
	else {
		echo '&#8734';
	}
echo '</span>'."\r\n".'<br><a href="sellercp/index.cgi" class="btn btn-sm mt-2 btn-info">View Usage</a></div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>';
}


if ($user_level == 'master') {
echo'<div class="col-xl-3 col-lg-6">'."\r\n".'<div class="card card-stats mb-4 mb-xl-0">'."\r\n".'<div class="card-body">'."\r\n".'<div class="row">'."\r\n".'<div class="col">'."\r\n".'<h5 class="card-title text-uppercase text-muted mb-2">Number of Domains</h5>'."\r\n".'<span class="h3 font-weight-bold text-uppercase mb-2">';
$domainsCount = 0;
	$acc = new accounts();

	foreach ($resellers as $reseller) {
		$whmParams = array();
		$whmParams['search'] = $reseller;
		$whmParams['searchtype'] = 'owner';
		$result = $acc->whmapi('listaccts', $whmParams);
		$domainsCount += count($result->acct);
	}

	echo $domainsCount;
echo '</span>'."\r\n".'<br><a href="../../scripts4/listaccts" class="btn btn-sm mt-2 btn-info">View Usage</a>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>';

echo'<div class="col-xl-3 col-lg-6">'."\r\n".'<div class="card card-stats mb-4 mb-xl-0">'."\r\n".'<div class="card-body">'."\r\n".'<div class="row">'."\r\n".'<div class="col">'."\r\n".'<h5 class="card-title text-uppercase text-muted mb-2">Domains Limit to Reseller
</h5>'."\r\n".'<span class="h3 font-weight-bold text-uppercase mb-2">';
$acc = new accounts();
	$domainsAlloc = $acc->getdomains();

	if (is_numeric($domainsAlloc[0])) {
		echo $domainsAlloc[1] . ' of ' . $domainsAlloc[0];
	}
	else {
		echo '&#8734';
	}
echo '</span>'."\r\n".'<br><a href="sellercp/index.cgi" class="btn btn-sm mt-2 btn-info">View Usage</a></div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>';
}




echo "\r\n".'<!-- table -->'."\r\n".'<div class="col-xl-6 col-lg-6 mt-2">'."\r\n".'<div class="card card-stats">'."\r\n".'<div class="card-body line">'."\r\n".'<div class="row">'."\r\n".'<div class="col-xl-6 col-lg-3 right">Total Space Usages</div>'."\r\n".'<div class="col-xl-6 col-lg-3 left">';
$spaceCount = 0;
$bwCount = 0;

foreach ($resellers as $reseller) {
	$whmParams = array();
	$whmParams['reseller'] = $reseller;
	$result = $acc->whmapi('resellerstats', $whmParams);
	$whmParams2 = array();
	$whmParams2['user'] = $reseller;
	$result2 = $acc->whmapi('accountsummary', $whmParams2);
	$spaceCount += str_replace('M', '', $result2->acct[0]->diskused);
	echo $account_space = $result2->acct->diskused;
	$spaceCount += $result->result->diskused;
	$bwCount += $result->result->totalbwused;
}

echo $spaceCount . ' MB';
echo '</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="col-xl-6 col-lg-6 mt-2">'."\r\n".'<div class="card card-stats">'."\r\n".'<div class="card-body line">'."\r\n".'<div class="row">'."\r\n".'<div class="col-xl-6 col-lg-3 right">Total Bandwidth Usages </div>'."\r\n".'<div class="col-xl-6 col-lg-3 left">';
echo $bwCount . ' MB';
echo  '</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="col-xl-6 col-lg-6 mt-2">'."\r\n".'<div class="card card-stats">'."\r\n".'<div class="card-body line">'."\r\n".'<div class="row">'."\r\n".'<div class="col-xl-6 col-lg-3 right">Server Disk Used </div>'."\r\n".'<div class="col-xl-6 col-lg-3 left">';
echo substr(include 'includes/info.php');
echo '</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".' </div>'."\r\n".'</div> '."\r\n".'<div class="col-xl-6 col-lg-6 mt-2">'."\r\n".'<div class="card card-stats">'."\r\n".'<div class="card-body line">'."\r\n".' <div class="row">'."\r\n".'<div class="col-xl-6 col-lg-3 right">cPanel/WHM Version </div>'."\r\n".'<div class="col-xl-6 col-lg-3 left">';
print(file_get_contents('/usr/local/cpanel/version'));
echo '</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<!-- end -->'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n";
echo '<div class="container-fluid mt--7">'."\r\n";


?>