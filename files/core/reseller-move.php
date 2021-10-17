<?php
if ($_ENV['REMOTE_USER'] != 'root') {
    exit();
}

include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';

if (isset($_POST['action']) && 'reseller-move' === $_POST['action'] && '' !== $_POST['reseller']) {
    $explode = explode('-', $_POST['reseller']);
    list($oldMaster, $reseller) = $explode;
    $newMaster = $_POST['master'];
    $acc = new accounts();
    if ('root' !== $oldMaster) {
        if ($oldMaster === $newMaster) {
            $res['error'] = 'Old and New master cannot be same';
            jsonOut($res);
        }

        $resData = getFile('masters/'.$oldMaster);
        $resellers = explode(',', $resData['resellers']);
        $keyIndex = array_search($reseller, $resellers, true);
        if (false !== $keyIndex) {
            unset($resellers[$keyIndex]);
        }

        $data['username'] = $oldMaster;
        $data['resellers'] = implode(',', $resellers);
        $res = $acc->updatemaster($oldMaster, $data);
    }

    $resData = getFile('masters/'.$newMaster);
    $data['username'] = $newMaster;
    $data['resellers'] = $resData['resellers'].','.$reseller;
    $res = $acc->updatemaster($newMaster, $data);
    jsonOut($res);
}


include './files/header.php';
include './files/body.php';

echo '<div class="row mb-3">';
echo '<div class="col-xl-5 mt-2">
<div class="card card-stats">
<div class="card-body">';
echo '<div id="notifications">';
if (isset($_GET['success'])) {
    echo '<div class="alert alert-success">'.$_GET['success'].'</div>';
}
echo'<form><div class="form-group "><label for="select">Choose Reseller you want to move:</label><div class="col-lg-12 mt-2"><select class="form-control input-sm" name="reseller">';
$acc = new accounts();
$res = $acc->whmapi('listresellers', '');
$allResellers = $res->reseller;
$masters = glob('masters/*');
foreach ($masters as $master) {
    $data = getFile($master);
    $resellers = array_filter(explode(',', $data['resellers']));
    $allResellers = array_diff($allResellers, $resellers);
    $explode = explode('/', $master);
    echo '<optgroup label="'.$explode[1].'">';
    foreach ($resellers as $reseller) {
        if ($explode[1] === $reseller) {
            continue;
        }

        echo '<option value="'.$explode[1].'-'.$reseller.'">'.$reseller.'</option>';
    }
    echo '</optgroup>';
}
echo '<optgroup label="root">';
foreach ($allResellers as $reseller) {
    echo '<option value="root-'.$reseller.'">'.$reseller.'</option>';
}
echo '</optgroup>                                    </select> </div> </div><div class="form-group text-center">
<label for="select">Choose the new owner (Master):</label>
<div class="col-lg-12 mt-2"> <select class="form-control input-sm" name="master">';
$masters = glob('masters/*');
foreach ($masters as $master) {
    $explode = explode('/', $master);
    echo '<option value="'.$explode[1].'">'.$explode[1].'</option>';
}


echo'</select></div></div> <div class="form-group"> <div class="col-lg-12"><input type="hidden" name="action" value="reseller-move"><button type="submit" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#loading">Move Reseller</button></div>
</div>

</div>
 </div>
</div></div></form>
<div class="col-xl-7 mt-2">
<div class="card card-stats">
<div class="card-body"><p>Here you can move Reseller Accounts between Master Resellers. This action will not effect on username, password. It will only change ownership of Master account.</p><p>When You created new Master reseller, it will automatically add in Reseller List.</p>
</div>
 
</div></div><div class="modal fade" id="loading">' . "\r\n" . '<div class="modal-dialog">' . "\r\n" . ' <div class="modal-content">' . "\r\n" . ' <div class="modal-body">' . "\r\n" . '<img src="process.gif"></div>' . "\r\n" . '</div>' . "\r\n" . '</div>' . "\r\n" . '</div>';

include './files/footer.php';

?>