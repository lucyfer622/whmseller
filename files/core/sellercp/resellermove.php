<?php
chdir('../');

include 'includes/Kb_inc.php';
include 'includes/Main_Kb_inc.php';

if (isset($_POST['action']) && ($_POST['action'] == 'move-reseller')) {
    $user = $_POST['reseller'];
    $acc = new accounts();
    $alpData = getFile('alphas/' . $_ENV['REMOTE_USER']);
    $masters = array_filter(explode(',', $alpData['masters']));

    if (in_array($_POST['master'], $masters)) {
        foreach ($masters as $master) {
            $masData = getFile('masters/' . $master);
            $resellers = array_filter(explode(',', $masData['resellers']));

            $masData['resellers'] = implode(',', array_filter(array_unique(explode(',', str_ireplace($user, '', $masData['resellers'])))));
            $res = $acc->updatemaster($masData['username'], $masData);
            $is_removed = 1;
            break;
        }

        if ($is_removed) {
            $masData = getFile('masters/' . $_POST['master']);
            $resellers = array_filter(explode(',', $masData['resellers']));
            $resellers[] = $user;
            $masData['resellers'] = implode(',', array_unique(array_filter($resellers)));
            $acc->updatemaster($masData['username'], $masData);
            $response['response'] = 'Reseller moved successfully';
            jsonOut($response);
        }
        else {
            $response['error'] = 'Reseller is not owned by you or your Masters';
            jsonOut($response);
        }
    }
    else {
        $response['error'] = 'You do not own Target Master';
        jsonOut($response);
    }
}


include './files/header.php';
include './files/body2.php';

echo '<div class="row mb-3">';
echo '<div class="col-xl-5 mt-2">
<div class="card card-stats">
<div class="card-body">';
echo '<div id="notifications">';
if (isset($_GET['success'])) {
    echo '<div class="alert alert-success">'.$_GET['success'].'</div>';
}
echo'<form><div class="form-group "><label for="select">Choose Reseller you want to move:</label><div class="col-lg-12 mt-2"><select class="form-control input-sm" name="reseller">';
$alpha = $_ENV['REMOTE_USER'];
$alpData = getFile('alphas/' . $alpha);
$masters = array_filter(explode(',', $alpData['masters']));

foreach ($masters as $master) {
    $masData = getFile('masters/' . $master);
    $resellers = array_filter(explode(',', $masData['resellers']));
    echo '<optgroup label="' . $master . '">';

    foreach ($resellers as $reseller) {
        if ($reseller != $master) {
            echo '<option value="' . $reseller . '">' . $reseller . '</option>';
        }
    }

    echo '</optgroup>';
}

echo '                                    </select></div> </div><div class="form-group text-center">
<label for="select">Choose the new owner (Master):</label>
<div class="col-lg-12 mt-2"> <select class="form-control input-sm" name="master">';
foreach ($masters as $master) {
    echo '<option value="' . $master . '">' . $master . '</option>';
}


echo'</select></div></div> <div class="form-group"> <div class="col-lg-12"><input type="hidden" name="action" value="move-reseller"><button type="submit" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#loading">Move Reseller</button></div>
</div>

</div>
 </div>
</div></div></form>
<div class="col-xl-7 mt-2">
<div class="card card-stats">
<div class="card-body"><p>Here you can move <i>Reseller Accounts</i> between Master Resellers. This action will not effect username, password etc. It will only change ownership of Resellers. </p><p><b>Reseller Accounts</b> that shows in the list but can not be selected are Alphas or Masters. Alphas and Masters are added automatically also as Reseller Accounts in the system.
</div>
 
</div></div><div class="modal fade" id="loading">' . "\r\n" . '<div class="modal-dialog">' . "\r\n" . ' <div class="modal-content">' . "\r\n" . ' <div class="modal-body">' . "\r\n" . '<img src="process.gif"></div>' . "\r\n" . '</div>' . "\r\n" . '</div>' . "\r\n" . '</div>';

include './files/footer.php';

?>