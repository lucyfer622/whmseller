<?php

if ($_ENV['REMOTE_USER'] != 'root') {
	exit();
}


include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';


include './files/header.php';
include './files/body.php';

echo '<div class="row">'."\r\n".'<div class="col-xl-';if (isset($_GET['full_details'])) {
    echo'12';
}else{
echo '8';   }
echo ' mb-5 mb-xl-0">'."\r\n".'<div class="card shadow">'."\r\n".'<div class="card-header border-0">'."\r\n".'<div class="row align-items-center">'."\r\n".'<div class="col">'."\r\n".'<h3 class="mb-0">Alpha Reseller Dashboard</h3>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="table-responsive">'."\r\n".'<!-- Projects table -->'."\r\n".'<table class="table align-items-center table-flush">'."\r\n".'<thead class="thead-light">';
echo'<tr>';
echo'<th scope="col">#</th>'."\r\n".'<th scope="col">Domain/(username)</th>'."\r\n".'<th scope="col">Masters / Limit</th>';
 if (isset($_GET['full_details'])) {
	echo '<th>Resellers</th>'."\r\n".'<th>Domains</th>'."\r\n".'<th>Space & Bandwidth Used</th>'."\r\n";
}echo '<th></th>'."\r\n".'<th></th>'."\r\n".'</tr></thead><tbody>'."\r\n";
$alphas = glob('alphas/*');
$serialNo = 1;
$acc = new accounts();
foreach ($alphas as $alpha) {
	$alpData = getFile($alpha);
	$masters = array_filter(explode(',', $alpData['masters']));
	$mastersCount = count($masters);
	echo '<tr>' . "\r\n" . '<td>' . $serialNo . '</td>' . "\r\n" . '<td>' . $alpData['domainname'] . ' (' . $alpData['username'] . ')</td>' . "\r\n" . '<td><span>' . $mastersCount . ' of ';
	if (is_numeric($alpData['mastersallowed'])) {
		echo $alpData['mastersallowed'];
	}
	else {
		echo '&infin;';
	}
	echo '</span></td>';
	if (isset($_GET['full_details'])) {
		echo '<td>';
		$resellersCount = 0;
		foreach ($masters as $master) {
			$data = getFile('masters/' . $master);
			$resellersCount += count(array_filter(explode(',', $data['resellers'])));
		}
		echo $resellersCount;
		echo '</td><td>';
		$space = $bw = 0;
		$domainsCount = 0;
		foreach ($masters as $master) {
			$data = getFile('masters/' . $master);
			$resellers = array_filter(explode(',', $data['resellers']));

			foreach ($resellers as $reseller) {
				$whmParams = array();
				$whmParams['reseller'] = $reseller;
				$result = $acc->whmapi('resellerstats', $whmParams);
				$new_space = 0;
				$whmParams2 = array();
				$whmParams2['user'] = $reseller;
				$result2 = $acc->whmapi('accountsummary', $whmParams2);
				$new_space = str_replace('M', '', $result2->acct[0]->diskused);
				$space += $new_space;
				$space += $result->result->diskused;
				$bw += $result->result->totalbwused;
				$whmParams = array();
				$whmParams['user'] = $reseller;
				$result = $acc->whmapi('acctcounts', $whmParams);
				$domainsCount += $result->reseller->suspended + $result->reseller->active;
			}
		}

		echo $domainsCount . '</td><td>';
		echo $space . ' MB / ' . $bw . ' MB';
		echo '</td>';
	}

	echo '<td style="width:100px"><a href="alpha-edit.php?alpha=' . $alpData['username'] . '" class="btn btn-sm btn-primary">EDIT</a>';
	++$serialNo;
}
echo "\r\n" . ' </td>'."\r\n".'</tbody>'."\r\n".'</tr></table>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="col-xl-';
if (isset($_GET['full_details'])) {
    echo'5 offset-xl-3 mt-2';
}else{
echo '4';   }
    
echo '">'."\r\n".'<div class="card shadow">'."\r\n".'<div class="card-header border-0">'."\r\n".'<div class="row align-items-center">'."\r\n".'<div class="col">'."\r\n".'<h3 class="mb-0">Alpha Seller Panel</h3>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".' <div class="table-responsive">'."\r\n".''."\r\n".'<table class="table align-items-center table-flush">'."\r\n".'<thead class="thead-light">'."\r\n".'<tr>'."\r\n".'<th scope="col">&nbsp;</th>'."\r\n".'</tr>'."\r\n".'</thead>'."\r\n".'<tbody><td><a href="alpha-create.php" class="btn btn-sm btn-success">CREATE ALPHA RESELLER</a></td><tr><td><a href="alpha-clients.php?full_details=1" class="btn btn-sm btn-warning">APLHA USAGES IN DETAILS</a></td></tr>'."\r\n".'<tr><td><a href="../../scripts2/editres" class="btn btn-sm btn-danger">RESELLERS ACL EDIT</a></td></tr>'."\r\n".'<tr><td><a href="alpha-upgrade.php" class="btn btn-sm btn-dark">UPGRADE MASTER TO ALPHA</a></td></tr>'."\r\n".'</tbody>'."\r\n".'</table>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>';
include './files/footer.php';

    
?>