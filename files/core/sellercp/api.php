<?php

chdir('../');
include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';

if (isset($_GET['action'])) {
	$acc = new accounts();

	if (($_GET['action'] == 'terminatealpha') && ($user_level != 'alpha') && ($user_level != 'master')) {
		$res = $acc->whmseller_h($_GET['username'], 'terminate');
	}

	if (($_GET['action'] == 'createalpha') && ($user_level != 'alpha') && ($user_level != 'master')) {
		error_log('package 5 : ' . $package, 3, 'alpha_whmcs2.log');
		$res = $acc->whmseller_g($_GET['domain'], $_GET['username'], $_GET['password'], $_GET['email'], $_GET['domainsallowed'], $_GET['maxmasters'], $_GET['diskspace'], $_GET['bandwidth'], $_GET['diskoversell'], $_GET['bwoversell'], '0', $_GET['package']);
		jsonOut($res);
	}

	if ($_GET['action'] == 'createreseller') {
		$res = $acc->createreseller($_GET['domain'], $_GET['username'], $_GET['password'], $_GET['email'], $_GET['domainsallowed'], $_GET['diskspace'], $_GET['bandwidth'], $_GET['dsoversell'], $_GET['bwoversell'], $_ENV['REMOTE_USER'], 0, $_GET['package'], 1);
		jsonOut($res);
	}

	if ($_GET['action'] == 'suspendreseller') {
		$res = $acc->whmseller_b($_GET['username'], 'suspend');
		jsonOut($res);
	}

	if ($_GET['action'] == 'unsuspendreseller') {
		$res = $acc->whmseller_b($_GET['username'], 'unsuspend');
		jsonOut($res);
	}

	if ($_GET['action'] == 'terminatereseller') {
		$res = $acc->whmseller_b($_GET['username'], 'terminate');
		jsonOut($res);
	}

	if ($_GET['action'] == 'changepwd') {
		$res = $acc->changepwd($_GET['username'], $_GET['password']);
		jsonOut($res);
	}

	if ($_GET['action'] == 'createmaster') {
		if (($user_level != 'master') && ($user_level != 'reseller')) {
			$res = $acc->createmaster($_GET['domain'], $_GET['username'], $_GET['password'], $_GET['email'], $_GET['domainsallowed'], $_GET['resellersallowed'], $_GET['diskspace'], $_GET['bandwidth'], $_GET['dsoversell'], $_GET['bwoversell'], 0, 'null', $_GET['package']);
			jsonOut($res);
		}
	}

	if ($_GET['action'] == 'suspendmaster') {
		$res = $acc->whmseller_f($_GET['username'], 'suspend');
		jsonOut($res);
	}

	if ($_GET['action'] == 'unsuspendmaster') {
		$res = $acc->whmseller_f($_GET['username'], 'unsuspend');
		jsonOut($res);
	}

	if ($_GET['action'] == 'terminatemaster') {
		$res = $acc->whmseller_f($_GET['username'], 'terminate');
		jsonOut($res);
	}
}

?>
