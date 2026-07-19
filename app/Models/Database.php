<?php

declare(strict_types=1);

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {

            self::$connection = new PDO(
                "mysql:host=localhost;dbname=meteopego;charset=utf8mb4",
                "meteopego",
                "Tornado25!",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            self::$connection->exec("
                CREATE TABLE IF NOT EXISTS weather (

                    id INT AUTO_INCREMENT PRIMARY KEY,

                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                    temperature DOUBLE NULL,

                    humidity DOUBLE NULL,

                    pressure DOUBLE NULL,

                    wind DOUBLE NULL,

                    gust DOUBLE NULL,

                    winddir VARCHAR(10) NULL,

                    rain DOUBLE NULL,

                    uv DOUBLE NULL

                ) ENGINE=InnoDB
                DEFAULT CHARSET=utf8mb4;
            ");
        }

        return self::$connection;
    }
}