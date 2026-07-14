<?php

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$username = "meteobridge";
$password = "7365sdw25";

$url = "http://192.168.1.234/cgi-bin/template.cgi?template=[th0temp-act]";

$opts = [
    "http" => [
        "header" => "Authorization: Basic " . base64_encode($username . ":" . $password)
    ]
];

$context = stream_context_create($opts);

$temp = @file_get_contents($url, false, $context);

if ($temp === false) {
    echo json_encode([
        "errore" => "Impossibile collegarsi al Meteobridge"
    ]);
    exit;
}

echo json_encode([
    "temperatura" => trim($temp)
]);