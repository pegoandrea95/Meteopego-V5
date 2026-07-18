<?php

class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo === null) {

            $db = __DIR__ . '/../storage/database/meteopego.sqlite';

            self::$pdo = new PDO('sqlite:' . $db);

            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            self::$pdo->exec("
                CREATE TABLE IF NOT EXISTS weather (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    temperature REAL,
                    humidity REAL,
                    pressure REAL,
                    wind REAL
                );
            ");
        }

        return self::$pdo;
    }
}