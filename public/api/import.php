<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/Models/Database.php';
require_once __DIR__ . '/../../app/Models/WeatherModel.php';
require_once __DIR__ . '/../../app/Services/MeteobridgeService.php';

header('Content-Type: application/json');

$logDir = dirname(__DIR__, 2) . '/storage/logs';

if (!is_dir($logDir)) {
    mkdir($logDir, 0775, true);
}

$logFile = $logDir . '/import.log';

try {

    $service = new MeteobridgeService();

    $data = $service->fetch();

    $model = new WeatherModel();

    if (!$model->insert($data)) {

        throw new RuntimeException(
            'Errore durante il salvataggio nel database.'
        );
    }

    $log = sprintf(

        "[%s] IMPORT OK | T=%.1f°C H=%d%% P=%.1f hPa W=%.1f km/h G=%.1f km/h D=%d° R=%.1f mm UV=%.1f SOL=%.1f W/m² DEW=%.1f°C FEELS=%.1f°C\n",

        date('Y-m-d H:i:s'),

        $data['temperature'],
        $data['humidity'],
        $data['pressure'],
        $data['wind'],
        $data['gust'],
        $data['winddir'],
        $data['rain'],
        $data['uv'],
        $data['solar'],
        $data['dew'],
        $data['feels']

    );

    file_put_contents(
        $logFile,
        $log,
        FILE_APPEND
    );

    echo json_encode([

        'success' => true,

        'message' => 'Import completato con successo.',

        'timestamp' => date('Y-m-d H:i:s'),

        'data' => $data

    ], JSON_PRETTY_PRINT);

} catch (Throwable $e) {

    $log = sprintf(

        "[%s] IMPORT ERROR | %s\n",

        date('Y-m-d H:i:s'),

        $e->getMessage()

    );

    file_put_contents(
        $logFile,
        $log,
        FILE_APPEND
    );

    http_response_code(500);

    echo json_encode([

        'success' => false,

        'message' => $e->getMessage(),

        'timestamp' => date('Y-m-d H:i:s')

    ], JSON_PRETTY_PRINT);
}