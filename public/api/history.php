<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Models/Database.php';

header('Content-Type: application/json');

try {

    $db = Database::getConnection();

    $stmt = $db->query("
        SELECT *

        FROM weather

        ORDER BY created_at DESC

        LIMIT 500
    ");

    echo json_encode(

        $stmt->fetchAll(PDO::FETCH_ASSOC),

        JSON_PRETTY_PRINT

    );

}
catch(Throwable $e){

    http_response_code(500);

    echo json_encode([

        'success'=>false,

        'message'=>$e->getMessage()

    ]);

}