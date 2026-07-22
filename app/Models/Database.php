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
            self::updateSchema();

        } catch (PDOException $e) {

            die('Errore connessione database: ' . $e->getMessage());

        }

        return self::$connection;
    }

    /**
     * Crea la tabella weather
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

    solar DECIMAL(7,2) DEFAULT NULL,

    dew DECIMAL(5,2) DEFAULT NULL,

    feels DECIMAL(5,2) DEFAULT NULL,

    INDEX idx_created_at (created_at)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

SQL;

        self::$connection->exec($sql);

    }

    /**
     * Aggiorna automaticamente lo schema
     */
    private static function updateSchema(): void
    {

        $columns = [

            'solar' => 'ALTER TABLE weather ADD COLUMN solar DECIMAL(7,2) DEFAULT NULL',

            'dew' => 'ALTER TABLE weather ADD COLUMN dew DECIMAL(5,2) DEFAULT NULL',

            'feels' => 'ALTER TABLE weather ADD COLUMN feels DECIMAL(5,2) DEFAULT NULL'

        ];

        foreach ($columns as $column => $sql) {

            $stmt = self::$connection->prepare("
                SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'weather'
                AND COLUMN_NAME = ?
            ");

            $stmt->execute([$column]);

            if (!$stmt->fetchColumn()) {

                self::$connection->exec($sql);

            }

        }

    }

    /**
     * Test connessione
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