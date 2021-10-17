<?php

if( preg_match( "/includes/" , $_SERVER["PHP_SELF"] ) ) { 
header('HTTP/1.0 404 Not Found');
exit;
}

$debug = 1;
ob_start();
ini_set('max_execution_time', 1200);
if (isset($_POST)) {
    foreach ($_POST as $key => $value) {
        if (!is_array($value)) {
            $_POST[$key] = trim($value);
        }
    }
}

if (isset($_GET)) {
    foreach ($_GET as $key => $value) {
        $_GET[$key] = trim($value);
    }
}

$res = [];
function buildFile($data)
{
    $content = '';
    foreach ($data as $key => $value) {
        $content .= $key.'='.$value;
        $content .= "\r\n";
    }

    return $content;
}

function getFile($path)
{
    $content = file_get_contents($path);
    if (!$content) {
        return false;
    }

    $lines = explode("\r\n", $content);
    $data = [];
    foreach ($lines as $line) {
        $explode = explode('=', $line);
        if (!empty($explode[0])) {
            $data[$explode[0]] = $explode[1];
        }
    }
    if (isset($data['resellers'])) {
        $data['resellers'] = trim($data['resellers']);
    }

    return $data;
}

function jsonOut($array)
{
    global $debug;
    $output = ob_get_contents();
    if ($debug) {
        $array['response'] .= $output;
        $array['error'] .= $output;
    }

    file_put_contents('error_log', $output, FILE_APPEND);
    ob_end_clean();
    echo json_encode($array);
    exit();
}

?>
