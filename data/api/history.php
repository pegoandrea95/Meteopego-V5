<?php

require_once __DIR__ . '/../app/Database.php';

header('Content-Type: application/json');

try {

    $db = Database::connection();

    $stmt = $db->query("
        SELECT
            id,
            created_at,
            temperature,
            humidity,
            pressure,
            wind
        FROM weather
        ORDER BY created_at DESC
        LIMIT 100
    ");

    echo json_encode(
        $stmt->fetchAll(PDO::FETCH_ASSOC),
        JSON_PRETTY_PRINT
    );

} catch (Exception $e) {

    echo json_encode([
        "errore" => $e->getMessage()
    ]);
}