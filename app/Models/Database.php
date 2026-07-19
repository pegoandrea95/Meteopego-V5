<?php

declare(strict_types=1);

class Database
{
    private static ?PDO $connection = null;

    /**
     * Restituisce la connessione PDO (Singleton)
     */
    public static function getConnection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = require __DIR__ . '/../../config.php';

        $db = $config['database'];

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $db['host'],
            $db['port'],
            $db['database'],
            $db['charset']
        );

        try {

            self::$connection = new PDO(
                $dsn,
                $db['username'],
                $db['password'],
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );

            self::createTables();

        } catch (PDOException $e) {

            die('Errore connessione database: ' . $e->getMessage());

        }

        return self::$connection;
    }

    /**
     * Crea automaticamente la tabella weather
     */
    private static function createTables(): void
    {
        $sql = <<<SQL

CREATE TABLE IF NOT EXISTS weather (

    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    temperature DECIMAL(5,2) DEFAULT NULL,

    humidity DECIMAL(5,2) DEFAULT NULL,

    pressure DECIMAL(6,2) DEFAULT NULL,

    wind DECIMAL(5,2) DEFAULT NULL,

    gust DECIMAL(5,2) DEFAULT NULL,

    winddir VARCHAR(10) DEFAULT NULL,

    rain DECIMAL(6,2) DEFAULT NULL,

    uv DECIMAL(4,2) DEFAULT NULL,

    INDEX idx_created_at (created_at)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

SQL;

        self::$connection->exec($sql);
    }

    /**
     * Verifica la connessione al database
     */
    public static function ping(): bool
    {
        try {

            self::getConnection()->query('SELECT 1');

            return true;

        } catch (Throwable) {

            return false;

        }
    }
}