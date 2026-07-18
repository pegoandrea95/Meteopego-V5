<?php

declare(strict_types=1);

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {

            $database = __DIR__ . '/../../storage/database/meteopego.sqlite';

            self::$connection = new PDO(
                'sqlite:' . $database
            );

            self::$connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            self::$connection->exec("
                CREATE TABLE IF NOT EXISTS weather (

                    id INTEGER PRIMARY KEY AUTOINCREMENT,

                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

                    temperature REAL,

                    humidity REAL,

                    pressure REAL,

                    wind REAL,

                    gust REAL,

                    winddir TEXT,

                    rain REAL,

                    uv REAL

                )
            ");

        }

        return self::$connection;
    }
}