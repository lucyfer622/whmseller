<?php
if ($_ENV['REMOTE_USER'] != 'root') {
	exit();
}


include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';
$config = getFile('includes/config');


include './files/header.php';
include './files/body.php';

echo '<div class="row">'."\r\n".'<div class="col-xl-';if (isset($_GET['full_details'])) {
    echo'12';
}else{
echo '8';   }
echo ' mb-5 mb-xl-0">'."\r\n".'<div class="card shadow">'."\r\n".'<div class="card-header border-0">'."\r\n".'<div class="row align-items-center">'."\r\n".'<div class="col">'."\r\n".'<h3 class="mb-0">Masters Reseller Dashboard</h3>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="table-responsive">'."\r\n".'<!-- Projects table -->'."\r\n".'<table class="table align-items-center table-flush">'."\r\n".'<thead class="thead-light">';
echo'<tr>';
echo'<th scope="col">#</th>'."\r\n".'<th scope="col">Domain/(username)</th>'."\r\n".'<th scope="col">Resellers / Limit</th>';
 if (isset($_GET['full_details'])) {
	echo '<th>Space & Bandwidth Used</th>';
}echo '<th></th>'."\r\n".'<th></th>'."\r\n".'</tr></thead><tbody>'."\r\n";
$masters = glob('masters/*');
$serialNo = 1;
$acc = new accounts();

foreach ($masters as $master) {
	$data = getFile($master);

	if (file_exists('alphas/' . $data['username'])) {
		continue;
	}

	$resellers = array_filter(explode(',', $data['resellers']));
	$resellersCount = count($resellers);
	echo '<tr>' . "\r\n\t\t\t\t\t\t\t\t\t\t" . '<td>' . $serialNo . '</td>' . "\r\n\t\t\t\t\t\t\t\t\t\t" . '<td>' . $data['domainname'] . ' (' . $data['username'] . ')';
	echo '</td>' . "\r\n\t\t\t\t\t\t\t\t\t\t" . '<td><span class="badge ">' . $resellersCount . ' of ';

	if (is_numeric($data['resellersallowed'])) {
		echo $data['resellersallowed'];
	}
	else {
		echo '&infin;';
	}

	echo '</span></td>';

	if (isset($_GET['full_details'])) {
		echo '<td>';
		$space = $bw = 0;

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
		}

		echo $space . ' MB / ' . $bw . ' MB';
		echo '</td>';
	}

	echo '<td style="width:100px"><a href="master-edit.php?master=' . $data['username'] . '" class="btn btn-sm btn-info">EDIT</a></td>' . "\r\n\t\t\t\t\t\t\t\t\t" . '</tr>';
	++$serialNo;
}

echo "\r\n" .'</tbody>'."\r\n".'</table>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="col-xl-';
if (isset($_GET['full_details'])) {
    echo'5 offset-xl-3 mt-2';
}else{
echo '4';   }
    
echo '">'."\r\n".'<div class="card shadow">'."\r\n".'<div class="card-header border-0">'."\r\n".'<div class="row align-items-center">'."\r\n".'<div class="col">'."\r\n".'<h3 class="mb-0">Masters Seller Panel</h3>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".' <div class="table-responsive">'."\r\n".''."\r\n".'<table class="table align-items-center table-flush">'."\r\n".'<thead class="thead-light">'."\r\n".'<tr>'."\r\n".'<th scope="col">&nbsp;</th>'."\r\n".'</tr>'."\r\n".'</thead>'."\r\n".'<tbody><td><a href="master-create.php" class="btn  btn-sm btn-success">CREATE MASTER RESELLER</a></td><tr><td><a href="master-clients.php?full_details=1" class="btn  btn-sm btn-warning">MASTER USAGES IN DETAILS</a></td></tr>'."\r\n".'<tr><td><a href="../../scripts2/editres" class="btn  btn-sm btn-danger">RESELLERS ACL EDIT</a></td></tr>'."\r\n".'<tr><td><a href="master-upgrade.php" class="btn  btn-sm btn-dark">UPGRADE RESELLERS TO MASTER</a></td></tr>'."\r\n".'</tbody>'."\r\n".'</table>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>';
include './files/footer.php';

    
?>