<?php

require_once __DIR__ . '/../app/Database.php';

$file = __DIR__ . '/../storage/current.json';

if (!file_exists($file)) {
    exit("current.json non trovato");
}

$data = json_decode(file_get_contents($file), true);

$db = Database::connection();

$stmt = $db->prepare("
INSERT INTO weather
(temperature, humidity, pressure, wind)
VALUES (?, ?, ?, ?)
");

$stmt->execute([
    $data['temperature'],
    $data['humidity'],
    $data['pressure'],
    $data['wind']
]);

echo "OK";