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
$config = getFile('includes/config');

if (trim($config['acl']) == '') {
	$acc = new accounts();
	$acls = $acc->whmapi('listacls', '');

	if (!isset($acls->acls->whmseller)) {
		$whmParams = array();
		$whmParams['acllist'] = 'whmseller';
		$whmParams['acl-acct-summary'] = 1;
		$whmParams['acl-basic-system-info'] = 1;
		$whmParams['acl-basic-whm-functions'] = 1;
		$whmParams['acl-cors-proxy-get'] = 1;
		$whmParams['acl-cpanel-api'] = 1;
		$whmParams['acl-cpanel-integration'] = 1;
		$whmParams['acl-create-user-session'] = 1;
		$whmParams['acl-digest-auth'] = 1;
		$whmParams['acl-generate-email-config'] = 1;
		$whmParams['acl-list-pkgs'] = 1;
		$whmParams['acl-manage-api-tokens'] = 1;
		$whmParams['acl-manage-dns-records'] = 1;
		$whmParams['acl-manage-oidc'] = 1;
		$whmParams['acl-manage-styles'] = 1;
		$whmParams['acl-mysql-info'] = 1;
		$whmParams['acl-ns-config'] = 1;
		$whmParams['acl-public-contact'] = 1;
		$whmParams['acl-ssl-info'] = 1;


		$whmParams['acl-list-accts'] = 1;
		$whmParams['acl-show-bandwidth'] = 1;

		$whmParams['acl-create-acct'] = 1;
		$whmParams['acl-kill-acct'] = 1;
		$whmParams['acl-suspend-acct'] = 1;
		$whmParams['acl-upgrade-account'] = 1;
		$whmParams['acl-ssl'] = 1;
		$whmParams['acl-ssl-buy'] = 1;
		$whmParams['acl-ssl-gencrt'] = 1;
		$whmParams['acl-edit-mx'] = 1;
		$whmParams['acl-passwd'] = 1;
		
		
		$whmParams['acl-create-dns'] = 1;
		$whmParams['acl-edit-dns'] = 1;
		$whmParams['acl-park-dns'] = 1;
		$whmParams['acl-kill-dns'] = 1;

		$whmParams['acl-add-pkg'] = 1;
		$whmParams['acl-edit-pkg'] = 1;

		$whmParams['acl-thirdparty']=1;
		
		$whmParams['acl-mailcheck']= 1;
		
		$whmParams['acl-news'] = 1;

		$whmParams['acl-allow-shell'] = 0;



		$whmParams['acl-allow-addoncreate'] = 1;
		$whmParams['acl-allow-parkedcreate'] = 1;
		$whmParams['acl-add-pkg-ip'] = 1;
		$whmParams['acl-add-pkg-shell'] = 0;
		$whmParams['acl-allow-unlimited-pkgs'] = 1;
		$whmParams['acl-allow-emaillimits-pkgs'] =1;
		$whmParams['acl-allow-unlimited-disk-pkgs'] = 1;
		$whmParams['acl-allow-unlimited-bw-pkgs'] = 1;
		$whmParams['acl-limit-bandwidth']= 1;
		$whmParams['acl-quota'] = 1;


		$acls = $acc->whmapi('saveacllist', $whmParams);
	}

	$config['acl'] = 'whmseller';
	file_put_contents('includes/config', buildFile($config));
}

if (isset($_POST['action'])) {
	if ($_POST['action'] == 'update-acl') {
		$acc = new accounts();
		$acls = $acc->whmapi('listacls', '');

		if (isset($acls->acls->{$_POST['acl']})) {
			$config['acl'] = $_POST['acl'];
			file_put_contents('includes/config', buildFile($config));
			$res['response'] = 'ACL has been updated';
			jsonOut($res);
		}
	}

	if ($_POST['action'] == 'update-ipunblocker') {
		if (($_POST['ipunblocker'] != 0) && ($_POST['ipunblocker'] != 1)) {
			$_POST['ipunblocker'] = 0;
		}
		else {
			$_POST['ipunblocker'] = 1;
		}

		$config['ipunblocker'] = $_POST['ipunblocker'];
		file_put_contents('includes/config', buildFile($config));
		$res['response'] = 'Ip unblocker settings has been updated';
		jsonOut($res);
	}
}

include './files/header.php';
include './files/body.php';
echo '<div id="notifications"></div>';
echo '<div class="row mb-3">';
echo '<div class="col-xl-4 mt-2">
<div class="card card-stats">
<div class="card-body">';
echo '<div class="col">
<h3 class="mb-3">Reseller ACL</h3>
</div>';

$config = getFile('includes/config');
echo'<form>
<div class="form-group row mt-2">
    <label for="text" class="col-6 col-form-label">Current ACL:</label> 
    <div class="col-6">
      <div class="input-group">
       
    <label for="text" class="btn btn-warning col-form-label">';
    echo $config['acl'];
    echo '</label>       </div>
    </div>
  </div> 
<div class="form-group row">
    <label for="select" class="col-6 col-form-label">Available ACL:</label> 
    <div class="col-6">
      <select id="acl" name="acl" class="custom-select">';
$acc = new accounts();
$acls = $acc->whmapi('listacls', '');

foreach ($acls->acls as $acl => $aclval) {
	echo '<option value="' . $acl . '">' . $acl . '</option>';
}

echo '  </select>
       
      </select>
    </div>
  </div> 
 <div class="form-group mt-3"> <div class="col-lg-12"><input type="hidden" name="action" value="update-acl"><button type="submit" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#loading">UPDATE</button></div>
</div>

</div>
 </div>
</div></form>
<div class="col-xl-4 mt-2 id="ipunblocker">
<div class="card card-stats">
<div class="card-body">';
echo '<div class="col">
<h3 class="mb-3">Enable IP Unblocker</h3>
</div>';
echo'<form>
<div class="form-group row mt-3"><label class="col-xl-12 control-label"> <input type="checkbox" name="ipunblocker" value="1" ';

if ($config['ipunblocker']) {
	echo ' checked ';
}

echo '></label></div>
<div class="form-group"><div class="col-xl-12"> <input type="hidden" name="action" value="update-ipunblocker">                                        <button type="submit" class="btn btn-primary">UPDATE</button></div></div></div></div></div></form>




<div class="modal fade" id="loading">' . "\r\n" . '<div class="modal-dialog">' . "\r\n" . ' <div class="modal-content">' . "\r\n" . ' <div class="modal-body">' . "\r\n" . '<img src="process.gif"></div>' . "\r\n" . '</div>' . "\r\n" . '</div>' . "\r\n" . '</div>';

include './files/footer.php';
?>
