<?php

declare(strict_types=1);

header('Content-Type: application/json');

$file = dirname(__DIR__, 2) . '/data/current.json';

if (!is_file($file)) {

    http_response_code(404);

    echo json_encode([
        'error' => 'current.json non trovato'
    ]);

    exit;

}

readfile($file);