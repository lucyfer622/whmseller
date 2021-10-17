<?php
function cleanMaster($master)
{
	$explode = explode('/', $master);
	return $explode[1];
}

if ($_ENV['REMOTE_USER'] != 'root') {
	exit();
}
include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';


if (isset($_POST['action']) && ($_POST['action'] == 'alpha-upgrade')) {
	$acc = new accounts();
	$masters = glob('masters/*');
	$masters = array_map('cleanMaster', $masters);
	$alphas = glob('alphas/*');
	$alphas = array_map('cleanMaster', $alphas);
	$masters = array_diff($masters, $alphas);

	if (in_array($_POST['username'], $masters)) {
		$res = $acc->whmseller_e($_POST['username'], $_POST['masters'], $_POST['maxdomains']);
	}
	else {
		$res['error'] = 'Master not found';
	}

	jsonOut($res);
}

include './files/header.php';
include './files/body.php';

echo '<div class="row mb-3">';
echo '<div class="col-xl-5 mt-2">
<div class="card card-stats">
<div class="card-body">';
echo '<div id="notifications">';if (isset($_GET['success'])) {
	echo '<div class="alert alert-success">' . $_GET['success'] . '</div>';
}
echo'<form><div class="form-group "><label for="select">Choose Master to Upgrade:</label><div class="col-lg-12 mt-2"><select class="form-control input-sm" name="username">';
$masters = glob('masters/*');
$masters = array_map('cleanMaster', $masters);
$alphas = glob('alphas/*');
$alphas = array_map('cleanMaster', $alphas);
$masters = array_diff($masters, $alphas);

foreach ($masters as $master) {
	echo '<option value="' . $master . '">' . $master . '</option>';
}

echo'</select></div>
<div class="form-group">
<label for="inputResellers" class="col-12 col-form-label">Masters Limit:</label> 
<div class="col-12">
<input id="inputResellers" name="masters" placeholder="Add Number or UNLIMITED" type="text" class="form-control here">
</div>
<div class="form-group">
<label for="inputResellers" class="col-12 col-form-label">Domain Limit:</label> 
<div class="col-12">
<input id="inputResellers" name="maxdomains" placeholder="Add Number or UNLIMITED" type="text" class="form-control here">
</div>
<div class="form-group"> <div class="col-lg-12 mt-3"><input type="hidden" name="action" value="alpha-upgrade"><button type="submit" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#loading">Upgrade to Master</button></div>
</div>

</div>
 </div>
</form></div></div></div></div></div>
<div class="col-xl-7 mt-2">
<div class="card card-stats">
<div class="card-body"><p>Here you can upgrade Master Resellers to Alpha Resellers. This action will not effect on username, password. It will  upgrade only level for Reselling.
</p>
</div>
 
</div></div><div class="modal fade" id="loading">' . "\r\n" . '<div class="modal-dialog">' . "\r\n" . ' <div class="modal-content">' . "\r\n" . ' <div class="modal-body">' . "\r\n" . '<img src="process.gif"></div>' . "\r\n" . '</div>' . "\r\n" . '</div>' . "\r\n" . '</div>';

include './files/footer.php';

    
?>
