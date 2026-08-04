<?php

declare(strict_types=1);

header('Content-Type: application/json');

$config = require dirname(__DIR__, 2) . '/bootstrap.php';

require_once dirname(__DIR__, 2) . '/app/Services/RainViewerService.php';

use App\Services\RainViewerService;

$service = new RainViewerService($config);

echo json_encode([
    'status' => 'ok',
    'provider' => 'RainViewer',
    'api_url' => $service->getApiUrl(),
    'cache' => $service->getCacheMinutes()
], JSON_PRETTY_PRINT);