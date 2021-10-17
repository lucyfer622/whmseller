<?php
chdir('../');
include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';
$config = getFile('includes/config');


include './files/header.php';
include './files/body2.php';

echo '<div class="row">'."\r\n".'<div class="col-xl-';if (isset($_GET['full_details'])) {
    echo'12';
}else{
echo '8';   }
echo ' mb-5 mb-xl-0">'."\r\n".'<div class="card shadow">'."\r\n".'<div class="card-header border-0">'."\r\n".'<div class="row align-items-center">'."\r\n".'<div class="col">'."\r\n".'<h3 class="mb-0">Reseller Dashboard</h3>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="table-responsive">'."\r\n".'<!-- Projects table -->'."\r\n";
$serialNo = 1;
$data = getFile('masters/' . $_ENV['REMOTE_USER']);
$resellers = array_filter(explode(',', $data['resellers']));
echo'<table class="table align-items-center table-flush">'."\r\n".'<thead class="thead-light">';
echo'<tr>';
echo'<th scope="col">#</th>'."\r\n".'<th scope="col">Domain/(username)</th>'."\r\n".'<th scope="col">Status</th><th>Domains</th>';
 if (isset($_GET['full_details'])) {
	echo '<th>Space & Bandwidth Used</th>';
}echo '<th></th>'."\r\n".'<th></th>'."\r\n".'</tr></thead><tbody>'."\r\n";
foreach ($resellers as $reseller) {
	if ($_ENV['REMOTE_USER'] != $reseller) {
		echo '<tr>' . "\r\n" . '                                        <td>' . $serialNo . '</td>' . "\r\n" . '                                        <td>';
		$acc = new accounts();
		$whmParams = array();
		$whmParams['user'] = $reseller;
		$result = $acc->whmapi('accountsummary', $whmParams);
		echo $result->acct[0]->domain;
		echo ' (' . $reseller . ')</td><td>';

		if ($result->acct[0]->suspended) {
			echo '<span class="label label-warning">Suspended</span>';
		}
		else {
			echo '<span class="label label-success">Active</span>';
		}

		echo '</td>' . "\r\n" . '                                        <td><span class="badge">';
		$whmParams = array();
		$whmParams['user'] = $reseller;
		$result = $acc->whmapi('acctcounts', $whmParams);
		$domains = $result->reseller->suspended + $result->reseller->active;

		if ($domains == 0) {
			++$domains;
		}

		echo $domains . '</span></td>';

		if (isset($_GET['full_details'])) {
			echo '<td>';
			$whmParams = array();
			$whmParams['reseller'] = $reseller;
			$result = $acc->whmapi('resellerstats', $whmParams);
			$new_space = 0;

			if ($_ENV['REMOTE_USER'] != $reseller) {
				$whmParams2 = array();
				$whmParams2['user'] = $reseller;
				$result2 = $acc->whmapi('accountsummary', $whmParams2);
				$new_space = str_replace('M', '', $result2->acct[0]->diskused);
			}

			echo ($result->result->diskused + $new_space) . ' MB / ' . $result->result->totalbwused . ' MB';
			echo '</td>';
		}

		echo '<td><a href="sellercp/index.cgi?modules=reselleredit&reseller=' . $reseller . '" class="btn btn-sm btn-info">EDIT</a></td>' . "\r\n" . '                                        </tr>';
		++$serialNo;
	}
}

echo "\r\n" .'</tbody>'."\r\n".'</table>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="col-xl-';
if (isset($_GET['full_details'])) {
    echo'5 offset-xl-3 mt-2';
}else{
echo '4';   }
    
echo '">'."\r\n".'<div class="card shadow">'."\r\n".'<div class="card-header border-0">'."\r\n".'<div class="row align-items-center">'."\r\n".'<div class="col">'."\r\n".'<h3 class="mb-0">cPanel Reseller Panel</h3>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".' <div class="table-responsive">'."\r\n".''."\r\n".'<table class="table align-items-center table-flush">'."\r\n".'<thead class="thead-light">'."\r\n".'<tr>'."\r\n".'<th scope="col">&nbsp;</th>'."\r\n".'</tr>'."\r\n".'</thead>'."\r\n".'<tbody><td><a href="sellercp/index.cgi?modules=resellercreate" class="btn  btn-sm btn-success">CREATE cPANEL RESELLER</a></td><tr><td><a href="sellercp/index.cgi?modules=resellerclients&full_details=1" class="btn  btn-sm btn-warning">cPANEL RESELLER USAGES IN DETAILS</a></td></tr>'."\r\n".'<tr><td><a href="sellercp/index.cgi?modules=resellerupgrade" class="btn  btn-sm btn-dark">UPGRADE cPANEL TO RESELLER</a></td></tr>'."\r\n".'</tbody>'."\r\n".'</table>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>';
include './files/footer.php';

    
?>