<?php
if(!defined('SITE_LOADED')) {
        die();
}
echo'<div class="main-content">'."\r\n".'<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">'."\r\n".'<div class="container-fluid"><!-- Brand --><a class="h3 mb-0 text-white text-uppercase d-none d-lg-inline-block">';
if ($fileName = basename($_SERVER['SCRIPT_FILENAME'], '.php'));
        if ($fileName == 'index') {
                echo 'WHMSELLER DASHBOARD';
        }

        elseif (in_array($fileName, array('alpha-clients'))) {
                echo 'Alpha Reseller Dashboard';
        }
        elseif (in_array($fileName, array('alpha-create'))) {
                echo 'Alpha Reseller Create accounts';
        }
        elseif (in_array($fileName, array('alpha-edit'))) {
                echo 'Alpha Reseller Edit';
        }
        elseif (in_array($fileName, array('alpha-move'))) {
                echo 'Alpha Reseller Move';
        }

        elseif (in_array($fileName, array('master-clients'))) {
                echo 'Master Reseller Dashboard';
        }
        elseif (in_array($fileName, array('master-create'))) {
                echo 'Master Reseller Create accounts';
        }
        elseif (in_array($fileName, array('master-edit'))) {
                echo 'Master Reseller Edit';
        }
        elseif (in_array($fileName, array('master-move'))) {
                echo 'Master Reseller Move';
        }
        elseif (in_array($fileName, array('reseller-move'))) {
                echo 'Reseller Move';
        }

        elseif (in_array($fileName, array('alpha-upgrade', 'master-upgrade'))) {
                echo 'Upgrade Reseller';
        }
        elseif (in_array($fileName, array('settings'))) {
                echo 'GENERAL Settings';
        }
                elseif (in_array($fileName, array('integration'))) {
                echo 'Billing Tools';
        }

echo'</a></div></nav>'."\r\n".'<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">'."\r\n".'<div class="container-fluid">'."\r\n".'<div class="header-body">'."\r\n".'<!-- Card stats --><div class="row">'."\r\n".'<div class="col-xl-3 col-lg-6">'."\r\n".'<div class="card card-stats mb-4 mb-xl-0">'."\r\n".'<div class="card-body">'."\r\n".'<div class="row">'."\r\n".'<div class="col">'."\r\n".'<h5 class="card-title text-uppercase text-muted mb-0">Number of Alpha Resellers</h5>'."\r\n".'<span class="h2 font-weight-bold mb-0">';$alphas = glob('alphas/*');echo $alphasCount = count($alphas);
echo '</span>'."\r\n".'<br><a href="alpha-clients.php?full_details=1" class="btn btn-sm btn-info">View Usage</a>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="col-xl-3 col-lg-6"> <div class="card card-stats mb-4 mb-xl-0">'."\r\n".'<div class="card-body">'."\r\n".'<div class="row">'."\r\n".'<div class="col">'."\r\n".'<h5 class="card-title text-uppercase text-muted mb-0">Number of Master Resellers</h5>'."\r\n".'<span class="h2 font-weight-bold mb-0">';
$masters = glob('masters/*');
echo $mastersCount = count($masters);
echo '</span>'."\r\n".'<br><a a href="master-clients.php?full_details=1" class="btn btn-sm btn-info">View Usage</a>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="col-xl-3 col-lg-6">'."\r\n".'<div class="card card-stats mb-4 mb-xl-0">'."\r\n".'<div class="card-body">'."\r\n".'<div class="row">'."\r\n".'<div class="col">'."\r\n".'<h5 class="card-title text-uppercase text-muted mb-0">Number of cPanel Resellers</h5>'."\r\n".'<span class="h2 font-weight-bold mb-0">';
$acc = new accounts();
$res = $acc->whmapi('listresellers', '');
echo count($res->reseller) - $mastersCount;
echo '</span>'."\r\n".'<br><a href="../../scripts2/statres" class="btn btn-sm btn-info">View Usage</a>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div><div class="col-xl-3 col-lg-6">'."\r\n".'<div class="card card-stats mb-4 mb-xl-0">'."\r\n".'<div class="card-body">'."\r\n".'<div class="row">'."\r\n".'<div class="col">'."\r\n".'<h5 class="card-title text-uppercase text-muted mb-0">WHMSELLER License</h5>'."\r\n".'<h5 class="card-title text-uppercase text-muted mb-0">Expire</h5>'."\r\n".'<span class="h2 font-weight-bold mb-0">';
$edate="No date";
echo "$edate";
echo '</span>'."\r\n".'<br><a href="https://github.com/lucyfer622/whmseller" class="btn btn-sm btn-info">Visit</a></div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<!-- table -->'."\r\n".'<div class="col-xl-6 col-lg-6 mt-2">'."\r\n".'<div class="card card-stats">'."\r\n".'<div class="card-body line">'."\r\n".'<div class="row">'."\r\n".'<div class="col-xl-6 col-lg-3 right">cPanel/WHM Version </div>'."\r\n".'<div class="col-xl-6 col-lg-3 left">';
print(file_get_contents('/usr/local/cpanel/version'));
echo '</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="col-xl-6 col-lg-6 mt-2">'."\r\n".'<div class="card card-stats">'."\r\n".'<div class="card-body line">'."\r\n".'<div class="row">'."\r\n".'<div class="col-xl-6 col-lg-3 right">Server Disk Used </div>'."\r\n".'<div class="col-xl-6 col-lg-3 left">';
echo substr(include 'includes/info.php');
echo  '</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<div class="col-xl-6 col-lg-6 mt-2">'."\r\n".'<div class="card card-stats">'."\r\n".'<div class="card-body line">'."\r\n".'<div class="row">'."\r\n".'<div class="col-xl-6 col-lg-3 right">Server Load Avarage</div>'."\r\n".'<div class="col-xl-6 col-lg-3 left">';
echo substr(file_get_contents('/proc/loadavg'), 0, 14);
echo '</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".' </div>'."\r\n".'</div> '."\r\n".'<div class="col-xl-6 col-lg-6 mt-2">'."\r\n".'<div class="card card-stats">'."\r\n".'<div class="card-body line">'."\r\n".' <div class="row">'."\r\n".'<div class="col-xl-6 col-lg-3 right">WHMSELLER version</div>'."\r\n".'<div class="col-xl-6 col-lg-3 left">';
echo trim(file_get_contents('includes/version'));
echo '</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'<!-- end -->'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n";
echo '<div class="container-fluid mt--7">'."\r\n";


?>
