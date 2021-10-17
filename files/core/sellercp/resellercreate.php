<?php



chdir('../');
include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';

if (isset($_POST['action']) && ($_POST['action'] == 'resellercreate')) {
	$acc = new accounts();
	$res = $acc->createreseller($_POST['domain'], $_POST['username'], $_POST['password'], $_POST['email'], $_POST['domainsallowed'], $_POST['diskspace'], $_POST['bandwidth'], $_POST['dsoversell'], $_POST['bwoversell'], $_ENV['REMOTE_USER'], 0, $_POST['package'], 1);
	jsonOut($res);
}

include './files/header.php';
include './files/body2.php';
$acc = new accounts();
$whmParams = array();
$result = $acc->whmapi('listpkgs');
echo '<div id="notifications"></div><form>'."\r\n".' <div class="row">'."\r\n".'<div class="col-md-6" style="padding=0.5em;">'."\r\n".'<div class="card"><div class="col mt-2"><h3 class="mb-0 text-center">Resellers will able to create cPanel Accounts.</h3></div>'."\r\n".'<div class="card-body mt-3">'."\r\n".'<div class="form-group row">'."\r\n".'<label for="inputDomain" class="col-4 col-form-label">Domain Name</label> '."\r\n".'<div class="col-8">'."\r\n".'<input id="inputDomain" name="domain" placeholder="ex. domain.com" type="text" class="form-control here">'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="form-group row">'."\r\n".''."\r\n".'<label for="inputUsername" class="col-4 col-form-label">Username</label>'."\r\n".'<div class="col-8">'."\r\n".'<input id="inputUsername" name="username" placeholder="Reseller Username" type="text" class="form-control here">'."\r\n".'</div>'."\r\n".'</div><div class="form-group row">'."\r\n".'<label for="inputPassword" class="col-4 col-form-label">Password</label> '."\r\n".'<div class="col-8">'."\r\n".'<input id="inputPassword" name="password" placeholder="Reseller Password" type="text" class="form-control here">'."\r\n".'</div>'."\r\n".'</div>';
  echo'<div class="form-group row">'."\r\n".'<label for="inputDomains" class="col-4 col-form-label">cPanel Accounts Limit</label> '."\r\n".'<div class="col-8">'."\r\n".'<input id="inputDomains" name="domainsallowed" placeholder="Add Number or UNLIMITED" type="text" class="form-control here">'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="form-group row">'."\r\n".'<label for="package" class="col-4 col-form-label">Package</label> '."\r\n".'<div class="col-8">'."\r\n".'  <select id="package" class="form-control input-sm" name="package">'."\r\n";
foreach ($result->data->pkg as $package) {
	echo '<option value="' . $package->name . '">' . $package->name . '</option>';
}
echo "\t\t\t" . '</select>';
echo '</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="col-md-6">'."\r\n".'<div class="card">'."\r\n".'<div class="card-body">'."\r\n".'<div class="form-group row">'."\r\n".'<label for="inputEmail" class="col-4 col-form-label">E-mail</label> '."\r\n".' <div class="col-8">'."\r\n".'<input id="inputEmail" name="email" placeholder="abc@gmail.com" type="text" class="form-control here">'."\r\n".'</div>'."\r\n".' </div>'."\r\n".'<div class="form-group row">'."\r\n".' <label for="inputdisk" class="col-4 col-form-label">Disk Space</label> '."\r\n".'<div class="col-8">'."\r\n".'<input id="inputdisk" name="diskspace" placeholder="Add Number or UNLIMITED" type="text" class="form-control here">'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="form-group row">'."\r\n".'<label class="col-4">Overselling</label> '."\r\n".'<div class="col-8 text-left">'."\r\n".'<label class="">'."\r\n".'<input type="checkbox" name="diskoversell" id="inputdsos" value="1">'."\r\n".'<span class="custom-control-indicator"></span> '."\r\n".'<span class="custom-control-description">Can oversell Disk Space.</span>'."\r\n".' </label>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="form-group row">'."\r\n".'<label for="inputbw" class="col-4 col-form-label">Bandwidth</label> '."\r\n".'<div class="col-8">'."\r\n".'<input id="inputbw" name="inputbw" placeholder="Add Number or UNLIMITED" type="text" class="form-control here">'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="form-group row">'."\r\n".'<label class="col-4">Overselling</label> '."\r\n".'<div class="col-8 text-left">'."\r\n".'<label class="">'."\r\n".' <input type="checkbox" name="bwoversell" value="1" id="bwos">'."\r\n".' <span class="custom-control-indicator"></span> '."\r\n".'<span class="custom-control-description">Can oversell Bandwidth.</span>'."\r\n".'</label>'."\r\n".' </div>'."\r\n".'</div> '."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="form-row text-center mt-3">'."\r\n".'<div class="col-12">'."\r\n".'<input type="hidden" name="action" value="resellercreate" ><button type="submit" class="btn sz btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#loading">Create cPanel Reseller</button>'."\r\n".' </div>'."\r\n".'</div>'."\r\n".' </form>'."\r\n".'<div class="modal fade" id="loading">' . "\r\n" . '<div class="modal-dialog">' . "\r\n" . ' <div class="modal-content">' . "\r\n" . ' <div class="modal-body">' . "\r\n" . '<img src="process.gif"></div>' . "\r\n" . '</div>' . "\r\n" . '</div>' . "\r\n";



include './files/footer.php';

    
?>
