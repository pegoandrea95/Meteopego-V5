<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/Models/Database.php';
require_once __DIR__ . '/../app/Models/WeatherModel.php';

header('Content-Type: application/json');

try {

    $weather = new WeatherModel();

    $data = $weather->getCurrent();

    if (!$data['success']) {

        throw new Exception($data['message']);

    }

    $db = Database::getConnection();

    $stmt = $db->prepare("
        INSERT INTO weather (

            temperature,

            humidity,

            pressure,

            wind,

            gust,

            winddir,

            rain,

            uv

        )

        VALUES (

            :temperature,

            :humidity,

            :pressure,

            :wind,

            :gust,

            :winddir,

            :rain,

            :uv

        )
    ");

    $stmt->execute([

        ':temperature' => $data['temperature'],

        ':humidity' => $data['humidity'],

        ':pressure' => $data['pressure'],

        ':wind' => $data['wind'],

        ':gust' => $data['gust'],

        ':winddir' => $data['winddir'],

        ':rain' => $data['rain'],

        ':uv' => $data['uv']

    ]);

    echo json_encode([

        'success' => true,

        'message' => 'Misurazione salvata.'

    ], JSON_PRETTY_PRINT);

}
catch(Throwable $e){

    http_response_code(500);

    echo json_encode([

        'success' => false,

        'message' => $e->getMessage()

    ], JSON_PRETTY_PRINT);

}