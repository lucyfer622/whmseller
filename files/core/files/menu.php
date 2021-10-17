<?php
if(!defined('SITE_LOADED')) {
    die();
}
echo '<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">'."\r\n".'<div class="container-fluid">'."\r\n".'<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">'."\r\n".'<span class="navbar-toggler-icon"></span>'."\r\n".'</button>'."\r\n";
echo'<a class="navbar-brand pt-0" href="">';
if ($user_level == 'alpha') {    
echo'<img src="./assets/img/brand/alphacp.png" class="navbar-brand-img"></a>';
}
elseif ($user_level == 'master') {
  echo'<img src="./assets/img/brand/mastercp.png" class="navbar-brand-img"></a>';
}
else{
    echo'<img src="./assets/img/brand/blue.png" class="navbar-brand-img"></a>';
}
echo'<ul class="nav align-items-center d-md-none"></ul>';
echo'<div class="collapse navbar-collapse" id="sidenav-collapse-main">'. "\r\n";
echo'<div class="navbar-collapse-header d-md-none">'."\r\n".'<div class="row">'."\r\n".'<div class="col-6 collapse-brand">'."\r\n".'<a href="">'."\r\n";
if ($user_level == 'alpha') {    
echo'<img src="./assets/img/brand/alphacp.png"></a>';
}
elseif ($user_level == 'master') {
  echo'<img src="./assets/img/brand/mastercp.png"></a>';
}
else{
    echo'<img src="./assets/img/brand/blue.png"></a>';
}
echo'</div>'."\r\n".'<div class="col-6 collapse-close">'.'
<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">'."\r\n".'<span></span>'.'<span></span>'."\r\n".'</button>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>'."\r\n";
echo'<ul class="navbar-nav">'."\r\n";
if ($_ENV['REMOTE_USER'] == 'root') {
    echo '<li class="nav-item"><a class="nav-link" href="./index.php"> <i class="ni ni-tv-2 text-primary"></i> Dashboard</a></li>'."\r\n";
    echo'<a class="nav-link collapsed" href="#alpha" data-toggle="collapse" data-target="#alpha"><i class="ni ni-app text-primary"></i>Alpha Reseller</a>'."\r\n".'
                    <div class="collapse" id="alpha" aria-expanded="false">'."\r\n".'
                        <ul class="flex-column pl-2 nav">'."\r\n".'
                            <li class="nav-item"><a class="nav-link py-0" href="alpha-clients.php"><i class="ni ni-ui-04 text-primary"></i>List Alpha Resellers</a></li>'."\r\n".'
                            <li class="nav-item"><a class="nav-link py-0" href="alpha-create.php"><i class="ni ni-ui-04 text-primary"></i>Create Alpha Reseller</a></li>'."\r\n".'
                        <li class="nav-item"><a class="nav-link py-0" href="master-move.php"><i class="ni ni-ui-04 text-primary"></i>Move Masters</a></li>'."\r\n".'
                    </ul>
                    </div>'."\r\n"; 
echo'<a class="nav-link collapsed" href="#master" data-toggle="collapse" data-target="#master"><i class="ni ni-app text-primary"></i>Master Reseller</a>'."\r\n".'
                    <div class="collapse" id="master" aria-expanded="false">'."\r\n".'
                        <ul class="flex-column pl-2 nav">'."\r\n".'
                            <li class="nav-item"><a class="nav-link py-0" href="master-clients.php"><i class="ni ni-ui-04 text-primary"></i>List Master Resellers</a></li>'."\r\n".'
                            <li class="nav-item"><a class="nav-link py-0" href="master-create.php"><i class="ni ni-ui-04 text-primary"></i>Create Master Reseller</a></li>'."\r\n".'
                        <li class="nav-item"><a class="nav-link py-0" href="reseller-move.php"><i class="ni ni-ui-04 text-primary"></i>Move Resellers</a></li>'."\r\n".'
                    </ul>
                    </div>'."\r\n"; 
echo'<a class="nav-link collapsed" href="#upgrade" data-toggle="collapse" data-target="#upgrade"><i class="ni ni-app text-primary"></i>Upgrade</a>'."\r\n".'
                    <div class="collapse" id="upgrade" aria-expanded="false">'."\r\n".'
                        <ul class="flex-column pl-2 nav">'."\r\n".'
                            <li class="nav-item"><a class="nav-link py-0" href="alpha-upgrade.php"><i class="ni ni-ui-04 text-primary"></i>Master to Alpha</a></li>'."\r\n".'
                            <li class="nav-item"><a class="nav-link py-0" href="master-upgrade.php"><i class="ni ni-ui-04 text-primary"></i>Reseller to Master</a></li>'."\r\n".'
                            </ul>
                    </div>'."\r\n"; 
    echo'<a class="nav-link collapsed" href="#seller" data-toggle="collapse" data-target="#seller"><i class="ni ni-settings-gear-65 text-primary"></i>WSeller Tools</a>'."\r\n".'
                    <div class="collapse" id="seller" aria-expanded="false">'."\r\n".'
                        <ul class="flex-column pl-2 nav">'."\r\n".'
                            <li class="nav-item"><a class="nav-link py-0" href="settings.php"> <i class="ni ni-settings text-primary"></i>Settings</a></li>'."\r\n".'
                        <li class="nav-item"><a class="nav-link py-0" href="integration.php"><i class="ni ni-single-copy-04 text-primary"></i> Billing Tool </a></li>'."\r\n".'
                    </ul>
                    </div>'."\r\n";
}
else {
    echo '<li class="nav-item"><a class="nav-link" href="sellercp/index.cgi"> <i class="ni ni-tv-2 text-primary"></i> Dashboard</a></li>'."\r\n";
    if ($user_level == 'alpha') {    
    echo'<a class="nav-link collapsed" href="#master1" data-toggle="collapse" data-target="#master1"><i class="ni ni-app text-primary"></i>Master Resellers</a>'."\r\n".'<div class="collapse" id="master1" aria-expanded="false">'.'
                        <ul class="flex-column pl-2 nav">'."\r\n".'
                            <li class="nav-item"><a class="nav-link py-0" href="sellercp/index.cgi?modules=masterclients"><i class="ni ni-ui-04 text-primary"></i>List Master Resellers</a></li>'."\r\n".'
                            <li class="nav-item"><a class="nav-link py-0" href="sellercp/index.cgi?modules=mastercreate"><i class="ni ni-ui-04 text-primary"></i>Create Master Reseller</a></li>'."\r\n".'
                        <li class="nav-item"><a class="nav-link py-0" href="sellercp/index.cgi?modules=resellermove"><i class="ni ni-ui-04 text-primary"></i>Move Resellers</a></li>'."\r\n".'
                    </ul>
                    </div>'."\r\n"; 
    } 
echo'<a class="nav-link collapsed" href="#reseller1" data-toggle="collapse" data-target="#reseller1"><i class="ni ni-app text-primary"></i>Resellers</a>'."\r\n".'<div class="collapse" id="reseller1" aria-expanded="false">'."\r\n".'<ul class="flex-column pl-2 nav">'."\r\n".'<li class="nav-item"><a class="nav-link py-0" href="sellercp/index.cgi?modules=resellerclients"><i class="ni ni-ui-04 text-primary"></i>List cPanel Resellers</a></li>'."\r\n".'<li class="nav-item"><a class="nav-link py-0" href="sellercp/index.cgi?modules=resellercreate"><i class="ni ni-ui-04 text-primary"></i>Create cPanel Reseller</a></li>'."\r\n".'<li class="nav-item"><a class="nav-link py-0" href="sellercp/index.cgi?modules=domainsmove"><i class="ni ni-ui-04 text-primary"></i>Move Domains</a></li>'."\r\n".'</ul></div>'."\r\n"; 
    $config = getFile('includes/config');
    if ($config['ipunblocker']) {
    echo "\r\n";
    if ($user_level == 'master') {
    $config = getFile('includes/config');
    if ($config['ipunblocker']) {
    echo '<li  class="nav-item"><a class="nav-link" href="sellercp/index.cgi?modules=resellerupgrade"><i class="ni ni-app text-primary"></i>Upgrade cPanel to Reseller</a></li>' . "\r\n";} }
    echo "\r\n"; 
    if ($user_level == 'master') {
            $config = getFile('includes/config');
            if ($config['ipunblocker']) {
            echo '<li class="nav-item"><a class="nav-link" href="https://www.whmseller.com/billingtools/whmsellermaster.zip" target="_blank"><i class="ni ni-app text-primary"></i>Download WHMCS Module</a></li>' . "\r\n";}}
        echo"\r\n"; 
     if ($user_level == 'alpha') {
         
         echo'<a class="nav-link collapsed" href="#alpha1" data-toggle="collapse" data-target="#alpha1"><i class="ni ni-app text-primary"></i>Upgrade</a>'."\r\n".'
                    <div class="collapse" id="alpha1" aria-expanded="false">'."\r\n".'
                        <ul class="flex-column pl-2 nav">'."\r\n".'
                            <li class="nav-item"><a class="nav-link py-0" href="sellercp/index.cgi?modules=masterupgrade"><i class="ni ni-ui-04 text-primary"></i>Upgrade Reseller to Master</a></li>'."\r\n".'
                            <li class="nav-item"><a class="nav-link py-0" href="sellercp/index.cgi?modules=resellerupgrade"><i class="ni ni-ui-04 text-primary"></i>Upgrade cPanel to Reseller</a></li>'."\r\n".'
                    </ul></div>'."\r\n";
     }
        echo "\r\n\r\n\r\n\r\n";
        if ($user_level == 'alpha') {
            $config = getFile('includes/config');
            if ($config['ipunblocker']) {
                echo '<li class="nav-item"><a class="nav-link" href="https://www.whmseller.com/billingtool/whmsellermaster.zip" target="_blank"><i class="ni ni-app text-primary"></i>Download WHMCS Module</a></li>' . "\r\n";
            }
        }
        echo "\r\n";
        
    }


}



echo'</ul>'.'</div>'."\r\n".'</div>'."\r\n".'</nav>';

?>