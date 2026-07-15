<?php

require_once __DIR__ . '/../app/Database.php';

$db = Database::connection();

$stmt = $db->query("
SELECT *
FROM weather
ORDER BY created_at DESC
LIMIT 20
");

header('Content-Type: application/json');

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC),
    JSON_PRETTY_PRINT
);