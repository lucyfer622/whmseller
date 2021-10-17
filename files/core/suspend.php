<?php

function getBandwidthLimit($user)
{
	$acc = new accounts();
	$whmParams = array();
	$whmParams['user'] = $user;
	$whmParams['searchtype'] = 'owner';
	$whmParams['search'] = $user;
	$result = $acc->whmapi('showbw', $whmParams);

	foreach ($result->bandwidth as $bw) {
		foreach ($bw->acct as $j) {
			return $j->limit;
		}
	}
}

function getMasterUsage($user)
{
	global $bw_used;
	$data = getFile('masters/' . $user);

	if (file_exists('alphas/' . $data['username'])) {
		return 0;
	}

	$resellers = array_filter(explode(',', $data['resellers']));
	$resellersCount = count($resellers);
	$space = 0;
	$bw = 0;
	$acc = new accounts();

	foreach ($resellers as $reseller) {
		$whmParams = array();
		$whmParams['reseller'] = $reseller;
		$result = $acc->whmapi('resellerstats', $whmParams);
		$space += $result->result->diskused;
		$bw += $result->result->totalbwused;
	}

	$bw_used = $bw;
	return $space;
}

function getAlphaUsage($user)
{
	global $bw_used;
	$serialNo = 1;
	$acc = new accounts();
	$alpData = getFile('alphas/' . $user);
	$masters = array_filter(explode(',', $alpData['masters']));
	$mastersCount = count($masters);
	$resellersCount = 0;

	foreach ($masters as $master) {
		$data = getFile('masters/' . $master);
		$resellersCount += count(array_filter(explode(',', $data['resellers'])));
	}

	$space = $bw = 0;
	$domainsCount = 0;

	foreach ($masters as $master) {
		$data = getFile('masters/' . $master);
		$resellers = array_filter(explode(',', $data['resellers']));

		foreach ($resellers as $reseller) {
			$whmParams = array();
			$whmParams['reseller'] = $reseller;
			$result = $acc->whmapi('resellerstats', $whmParams);
			$space += $result->result->diskused;
			$bw += $result->result->totalbwused;
			$whmParams = array();
			$whmParams['user'] = $reseller;
			$result = $acc->whmapi('acctcounts', $whmParams);
			$domainsCount += $result->reseller->suspended + $result->reseller->active;
		}
	}

	$bw_used = $bw;
	return $space;
}

function deleteAlphaAccount($user, $disklimit)
{
	global $bw_used;
	$alpData = getFile('alphas/' . $user);

	if ($alpData != '') {
		$disk_used = getalphausage($user);
		$bw_limit = getbandwidthlimit($user) / (1024 * 1024);
		if (((str_replace('M', '', $disklimit) <= $disk_used) && (str_replace('M', '', $disklimit) != 'unlimited')) || (($bw_limit <= $bw_used) && ($bw_limit != 'unlimited'))) {
			$acc = new accounts();
			$res = $acc->whmseller_h($user, 'suspend');
			echo $user;
			print_r($res);
		}
	}
}

function deleteMasterAccount($user, $disklimit)
{
	if (file_exists('alphas/' . $user)) {
		return 0;
	}

	global $bw_used;
	$masterData = getFile('masters/' . $user);

	if ($masterData != '') {
		$masterUsage = getmasterusage($user);
		$bw_limit = getbandwidthlimit($user) / (1024 * 1024);
		if (((str_replace('M', '', $disklimit) <= $masterUsage) && (str_replace('M', '', $disklimit) != 'unlimited')) || (($bw_limit <= $bw_used) && ($bw_limit != 'unlimited'))) {
			$acc = new accounts();
			$res = $acc->whmseller_f($user, 'suspend');
			echo $user;
			print_r($res);
		}
	}
}

include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';
$acc = new accounts();
$whmParams = array();
$whmParams[1] = '1';
$result = $acc->whmapi('listaccts', $whmParams);
$bw_used = 0;

foreach ($result->acct as $account) {
	if ($account->suspended == 0) {
		deletealphaaccount($account->user, $account->disklimit);
		deletemasteraccount($account->user, $account->disklimit);
	}
}

?>
