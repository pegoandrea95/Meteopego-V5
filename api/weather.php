<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Models/WeatherModel.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

try {

    $weather = new WeatherModel();

    $data = $weather->getCurrent();

    if (!$data['success']) {

        http_response_code(500);

        echo json_encode([
            'success' => false,
            'message' => $data['message']
        ], JSON_PRETTY_PRINT);

        exit;
    }

    echo json_encode([

        'success'     => true,

        'station'     => 'Meteopego',

        'location'    => 'Marghera (VE)',

        'temperature' => $data['temperature'],

        'humidity'    => $data['humidity'],

        'pressure'    => $data['pressure'],

        'wind'        => $data['wind'],

        'gust'        => $data['gust'],

        'winddir'     => $data['winddir'],

        'rain'        => $data['rain'],

        'uv'          => $data['uv'],

        'timestamp'   => $data['timestamp']

    ], JSON_PRETTY_PRINT);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);

}