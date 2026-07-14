<?php

header('Content-Type: application/json');

$file = dirname(__DIR__) . '/data/current.json';

if (!file_exists($file)) {
    echo json_encode([
        "ok" => false,
        "error" => "current.json non trovato"
    ]);
    exit;
}

echo file_get_contents($file);