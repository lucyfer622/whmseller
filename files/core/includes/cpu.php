<?php

function GetCoreInformation()
{
	$data = file('/proc/stat');
	$cores = array();

	foreach ($data as $line) {
		if (preg_match('/^cpu[0-9]/', $line)) {
			$info = explode(' ', $line);
			$cores[] = array('user' => $info[1], 'nice' => $info[2], 'sys' => $info[3], 'idle' => $info[4]);
		}
	}

	return $cores;
}

function GetCpuPercentages($stat1, $stat2)
{
	if (count($stat1) !== count($stat2)) {
		return NULL;
	}

	$cpus = array();
	$i = 0;

	for ($l = count($stat1); $i < $l; $i++) {
		$dif = array();
		$dif['user'] = $stat2[$i]['user'] - $stat1[$i]['user'];
		$dif['nice'] = $stat2[$i]['nice'] - $stat1[$i]['nice'];
		$dif['sys'] = $stat2[$i]['sys'] - $stat1[$i]['sys'];
		$dif['idle'] = $stat2[$i]['idle'] - $stat1[$i]['idle'];
		$total = array_sum($dif);
		$cpu = array();

		foreach ($dif as $x => $y) {
			$cpu[$x] = round(($y / $total) * 100, 1);
		}

		$cpus['cpu' . $i] = $cpu;
	}

	return $cpus;
}

function makeImageUrl($title, $data)
{
	$url = 'https://chart.apis.google.com/chart?chs=200x100&cht=bvs&chco=0062FF|50b432|ed561b|0e3e6e&chd=t:';
	$url .= $data['user'] . ',';
	$url .= $data['nice'] . ',';
	$url .= $data['sys'] . ',';
	$url .= $data['idle'];
	$url .= '&chdl=User|Nice|Sys|Idle&chdlp=b&chl=';
	$url .= $data['user'] . '%25|';
	$url .= $data['nice'] . '%25|';
	$url .= $data['sys'] . '%25|';
	$url .= $data['idle'] . '%25';
	$url .= '&chtt=Core+' . $title;
	return $url;
}

$stat1 = getcoreinformation();
sleep(1);
$stat2 = getcoreinformation();
$data = getcpupercentages($stat1, $stat2);

foreach ($data as $k => $v) {
	echo '<img class="my" src="' . makeimageurl($k, $v) . '" />';
}

?>
