<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/Models/Database.php';
require_once __DIR__ . '/../../app/Models/WeatherModel.php';

header('Content-Type: application/json');

/*
|--------------------------------------------------------------------------
| Meteobridge URL
|--------------------------------------------------------------------------
*/

define('METEOBRIDGE_URL', 'http://192.168.1.234');

$template =
    '[th0temp-act]|' .
    '[th0hum-act]|' .
    '[thb0seapress-act]|' .
    '[wind0wind-act]|' .
    '[wind0gust-act]|' .
    '[wind0dir-act]|' .
    '[rain0total-daysum]|' .
    '[uv0index-act]';

$url = METEOBRIDGE_URL .
    '/cgi-bin/template.cgi?template=' .
    urlencode($template);

/*
|--------------------------------------------------------------------------
| Lettura dati Meteobridge
|--------------------------------------------------------------------------
*/

$response = @file_get_contents($url);

if ($response === false) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Impossibile leggere i dati da Meteobridge'
    ], JSON_PRETTY_PRINT);

    exit;
}

/*
|--------------------------------------------------------------------------
| Parsing dati
|--------------------------------------------------------------------------
*/

$values = explode('|', trim($response));

if (count($values) !== 8) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Risposta Meteobridge non valida',
        'response' => $response
    ], JSON_PRETTY_PRINT);

    exit;
}

$data = [

    'temperature' => (float)$values[0],
    'humidity'    => (float)$values[1],
    'pressure'    => (float)$values[2],
    'wind'        => (float)$values[3],
    'gust'        => (float)$values[4],
    'winddir'     => (float)$values[5],
    'rain'        => (float)$values[6],
    'uv'          => (float)$values[7]

];

/*
|--------------------------------------------------------------------------
| Salvataggio nel database
|--------------------------------------------------------------------------
*/

$model = new WeatherModel();

if (!$model->insert($data)) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Errore durante il salvataggio nel database'
    ], JSON_PRETTY_PRINT);

    exit;
}

/*
|--------------------------------------------------------------------------
| Log import
|--------------------------------------------------------------------------
*/

$logDir = dirname(__DIR__, 2) . '/storage/logs';

if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

$log = sprintf(
    "[%s] T=%.1f°C H=%.1f%% P=%.1f hPa W=%.1f km/h G=%.1f km/h D=%d° R=%.1f mm UV=%.1f\n",
    date('Y-m-d H:i:s'),
    $data['temperature'],
    $data['humidity'],
    $data['pressure'],
    $data['wind'],
    $data['gust'],
    (int)$data['winddir'],
    $data['rain'],
    $data['uv']
);

file_put_contents(
    $logDir . '/import.log',
    $log,
    FILE_APPEND
);

/*
|--------------------------------------------------------------------------
| Risposta JSON
|--------------------------------------------------------------------------
*/

echo json_encode([
    'success'     => true,
    'source'      => 'Meteobridge',
    'temperature' => $data['temperature'],
    'humidity'    => $data['humidity'],
    'pressure'    => $data['pressure'],
    'wind'        => $data['wind'],
    'gust'        => $data['gust'],
    'winddir'     => $data['winddir'],
    'rain'        => $data['rain'],
    'uv'          => $data['uv'],
    'timestamp'   => date('Y-m-d H:i:s')
], JSON_PRETTY_PRINT);