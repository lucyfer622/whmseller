<?php

if ('root' !== $_ENV['REMOTE_USER']) {
    exit();
}

include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';
if (isset($_POST['action']) && 'master-move' === $_POST['action'] && '' !== $_POST['master']) {
    $explode = explode('-', $_POST['master']);
    list($oldAlpha, $master) = $explode;
    $newAlpha = $_POST['alpha'];
    $acc = new accounts();
    if ('root' !== $oldAlpha) {
        if ($oldAlpha === $newAlpha) {
            $res['error'] = 'Old and New Alpha Reseller cannot be same';
            jsonOut($res);
        }

        $alpData = getFile('alphas/'.$oldAlpha);
        $alpData['masters'] = implode(',', array_filter(array_unique(explode(',', str_ireplace($master, '', $alpData['masters'])))));
        $res = $acc->whmseller_c($alpData['username'], $alpData);
    }

    $resData = getFile('alphas/'.$newAlpha);
    $data['username'] = $newAlpha;
    $masters = explode(',', $resData['masters']);
    $masters[] = $master;
    $data['masters'] = implode(',', array_filter(array_unique($masters)));
    $res = $acc->whmseller_c($newAlpha, $data);
    jsonOut($res);
}

include './files/header.php';
include './files/body.php';

echo '<div class="row mb-3">';
echo '<div class="col-xl-5 mt-2">
<div class="card card-stats">
<div class="card-body">';
echo '<div id="notifications">';if (isset($_GET['success'])) {
    echo '<div class="alert alert-success">'.$_GET['success'].'</div>';
}
echo'<form><div class="form-group "><label for="select">Choose Master you want to move:</label><div class="col-lg-12 mt-2"><select class="form-control input-sm" name="master">';
$masters = glob('masters/*');
$allMasters = array_map('cleanMaster', $masters);
$alphas = glob('alphas/*');
$allAlphas = array_map('cleanMaster', $alphas);
foreach ($alphas as $alpha) {
    $alpData = getFile($alpha);
    $masters = array_filter(explode(',', $alpData['masters']));
    $explode = explode('/', $alpha);
    echo '<optgroup label="'.$explode[1].'">';
    foreach ($masters as $master) {
        if ($explode[1] === $master) {
            continue;
        }

        echo '<option value="'.$explode[1].'-'.$master.'">'.$master.'</option>';
    }
    echo '</optgroup>';
    $allMasters = array_diff($allMasters, $masters);
}
echo '<optgroup label="root">';
$allMasters = array_diff($allMasters, $allAlphas);
foreach ($allMasters as $master) {
    echo '<option value="root-'.$master.'">'.$master.'</option>';
}
echo '</optgroup>                                    </select> </div> </div><div class="form-group text-center">
<label for="select">Choose the new owner (Alpha):</label>
<div class="col-lg-12 mt-2"> <select class="form-control input-sm" name="alpha">';
$alphas = glob('alphas/*');
foreach ($alphas as $alpha) {
    $explode = explode('/', $alpha);
    echo '<option value="'.$explode[1].'">'.$explode[1].'</option>';
}
echo'</select></div></div> <div class="form-group"> <div class="col-lg-12"><input type="hidden" name="action" value="master-move"><button type="submit" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#loading">Move Master</button></div>
</div>

</div>
 </div>
</div></div></form>
<div class="col-xl-7 mt-2">
<div class="card card-stats">
<div class="card-body"><p>Here you can move Master Reseller Accounts between Alpha Resellers. This action will not effect on username, password. It will only change ownership of Master account.</p><p>When You created a new Alpha reseller, it will automatically add in Master Reseller List.</p>
</div>
 
</div></div><div class="modal fade" id="loading">' . "\r\n" . '<div class="modal-dialog">' . "\r\n" . ' <div class="modal-content">' . "\r\n" . ' <div class="modal-body">' . "\r\n" . '<img src="process.gif"></div>' . "\r\n" . '</div>' . "\r\n" . '</div>' . "\r\n" . '</div>';

include './files/footer.php';
function cleanMaster($master)
{
    $explode = explode('/', $master);

    return $explode[1];
}
    
?>