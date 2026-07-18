<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/Models/WeatherModel.php';

header('Content-Type: application/json; charset=utf-8');

try {

    $hours = isset($_GET['hours'])
        ? max(1, (int) $_GET['hours'])
        : 24;

    $weather = new WeatherModel();

    echo json_encode(

        $weather->getHistory($hours),

        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE

    );

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([

        'success' => false,

        'message' => $e->getMessage()

    ], JSON_PRETTY_PRINT);

}