<?php

chdir('../');
include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';
$config = getFile('includes/config');


include './files/header.php';
include './files/body2.php';
echo '<div class="row">'."\r\n".'<div class="col-xl-12 mb-5 mb-xl-0">'."\r\n".'<div class="card shadow">'."\r\n".'<div class="card-header border-0">'."\r\n".'<div class="row align-items-center">';
echo '<div class="col">'."\r\n".'<h3 class="mb-0">CPU Usage</h3>'."\r\n".'</div>'."\r\n".'</div>'."\r\n".'</div>';
echo '<div class="row mb-5 pl-2">'."\r\n".'<div>'."\r\n".'<table>'."\r\n".'<tbody><tr><td class="text-center">';
echo substr(include 'includes/cpu.php');
echo '</td></tr></tbody></table></div></div>';
echo '</div>'."\r\n".'</div>'."\r\n";
include './files/footer.php';

    
?>
