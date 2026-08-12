<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/Models/Database.php';

header('Content-Type: application/json');

try {

    $db = Database::getConnection();

    $stmt = $db->query("
        SELECT
            temperature,
            humidity,
            pressure,
            wind,
            gust,
            winddir,
            rain,
            uv,
            created_at
        FROM weather
        ORDER BY created_at DESC
        LIMIT 1
    ");

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        throw new RuntimeException('Nessun dato disponibile.');
    }

    echo json_encode([

        'temperature' => (float) $data['temperature'],
        'humidity'    => (int) $data['humidity'],
        'pressure'    => (float) $data['pressure'],
        'wind'        => (float) $data['wind'],
        'gust'        => (float) $data['gust'],
        'winddir'     => (float) $data['winddir'],
        'rain'        => (float) $data['rain'],
        'uv'          => (float) $data['uv'],
        'timestamp'   => $data['created_at']

    ], JSON_PRETTY_PRINT);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'error' => $e->getMessage()
    ]);

}