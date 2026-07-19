<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../app/Models/WeatherModel.php';

try {

    $weather = new WeatherModel();

    $latest = $weather->getLatest();

    if ($latest === null) {

        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Nessuna rilevazione disponibile.'
        ]);

        exit;

    }

    echo json_encode([

        'success'     => true,

        'temperature' => $latest['temperature'],

        'humidity'    => $latest['humidity'],

        'pressure'    => $latest['pressure'],

        'wind'        => $latest['wind'],

        'gust'        => $latest['gust'],

        'winddir'     => $latest['winddir'],

        'rain'        => $latest['rain'],

        'uv'          => $latest['uv'],

        'timestamp'   => $latest['created_at']

    ], JSON_PRETTY_PRINT);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([

        'success' => false,

        'message' => $e->getMessage()

    ], JSON_PRETTY_PRINT);

}