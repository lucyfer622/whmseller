<?php

$stat['hdd_free'] = round(disk_free_space('/') / 1024 / 1024 / 1024, 2);
$stat['hdd_total'] = round(disk_total_space('/') / 1024 / 1024 / 1024, 2);
$stat['hdd_used'] = $stat['hdd_total'] - $stat['hdd_free'];
$stat['hdd_percent'] = round(sprintf('%.2f', ($stat['hdd_used'] / $stat['hdd_total']) * 100), 2);
echo $stat['hdd_percent'] . '% ' . '';

?>
