<?php
if ($_ENV['REMOTE_USER'] != 'root') {
	exit();
}


include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';


include './files/header.php';
include './files/body.php';

echo '<div class="row">'."\r\n".'<div class="col-xl-8 mb-5 mb-xl-0">'."\r\n".'<div class="card shadow">'."\r\n".'<div class="card-header border-0">'."\r\n".'<div class="row align-items-center">'."\r\n".'<div class="col">'."\r\n".'<h3 class="mb-0">Intergration Modules</h3>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="table-responsive">'."\r\n".'<!-- Projects table -->'."\r\n".'<table class="table align-items-center table-flush">'."\r\n".'<thead class="thead-light">';
echo'<tr>';
echo'<th scope="col">Billing System</th>'."\r\n".'<th scope="col">File Name</th>'."\r\n".'<th scope="col">Download Link</th>';
echo '<tbody><tr><td><a href="#"><img src="assets/img/brand/whmcs.png" height="32" width="130"></a></td><td>whmcsmodule.zip</td><td><a href="https://www.whmseller.com/billingtool/whmsellerroot.zip" target="_blank" class="btn bta btn-sm btn-primary">DOWNLOAD WHMCS ROOT MODULE</a></td></tr>	</tbody>';

 
echo "\r\n" . ' </td>'."\r\n".'</tbody>'."\r\n".'</tr></table>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="col-xl-4 mt-0"><div class="card shadow">'."\r\n".'<div class="card-header border-0">'."\r\n".'<div class="row align-items-center">'."\r\n".'<div class="col">'."\r\n".'<h3 class="mb-0">Installation Guide</h3>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>
<div class="card card-stats">
<div class="card-body"><b>1.</b>You need to be download whmcsmoudle.zip<br><b>2.</b>Upload zip file on whmcs root directory and extract.<br><b>3.</b> Create Server from WHMCS Admin Panel. Under Server details, choose WHMSELLER.<br><b>4.</b> Under Setup > Products/Services, Create a Reseller Package. In Module Settings Tab, Choose WHMSELLER as Module Name.<br><b>5.</b> Complete the form and choose the option for Account to Create.<br>If you need anykind of help, Open a support ticket<b><a href="https://www.hostqweb.com"> #Needsupport</b></a>
</div>
 
</div></div></div>';
include './files/footer.php';

    
?>