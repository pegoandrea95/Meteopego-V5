<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/Models/Database.php';
require_once __DIR__ . '/../../app/Models/WeatherModel.php';

header('Content-Type: application/json');

$file = dirname(__DIR__, 2) . '/data/current.json';

if (!file_exists($file)) {

    http_response_code(404);

    echo json_encode([
        'success' => false,
        'message' => 'current.json non trovato'
    ]);

    exit;
}

$data = json_decode(file_get_contents($file), true);

if (!is_array($data)) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'JSON non valido'
    ]);

    exit;
}

$model = new WeatherModel();

$model->insert([

    'temperature' => $data['temperature'] ?? null,
    'humidity'    => $data['humidity'] ?? null,
    'pressure'    => $data['pressure'] ?? null,
    'wind'        => $data['wind'] ?? null,
    'gust'        => $data['gust'] ?? null,
    'winddir'     => $data['dir'] ?? null,
    'rain'        => $data['rain'] ?? null,
    'uv'          => $data['uv'] ?? null,

]);

echo json_encode([
    'success' => true
]);