<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$config = require dirname(__DIR__, 2) . '/bootstrap.php';

require_once dirname(__DIR__, 2) . '/app/Services/RainViewerService.php';

use App\Services\RainViewerService;

try {

    $service = new RainViewerService($config);

    $radar = $service->getRadarData();

    echo json_encode(
        [
            'status' => 'ok',

            'provider' => 'RainViewer',

            'cache' => $service->getCacheMinutes(),

            'data' => $radar
        ],
        JSON_PRETTY_PRINT |
        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE |
        JSON_THROW_ON_ERROR
    );

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode(
        [
            'status' => 'error',

            'provider' => 'RainViewer',

            'error' => $e->getMessage()
        ],
        JSON_PRETTY_PRINT |
        JSON_UNESCAPED_UNICODE
    );
}