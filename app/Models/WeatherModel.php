<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class WeatherModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Restituisce l'ultima rilevazione meteo
     */
    public function getCurrent(): ?array
    {
        $stmt = $this->db->query("
            SELECT
                id,
                created_at,
                temperature,
                humidity,
                pressure,
                wind,
                gust,
                winddir,
                rain,
                uv
            FROM weather
            ORDER BY created_at DESC
            LIMIT 1
        ");

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * Restituisce gli ultimi record
     */
    public function getLatest(int $limit = 100): array
    {
        $stmt = $this->db->prepare("
            SELECT
                created_at,
                temperature,
                humidity,
                pressure,
                wind,
                gust,
                winddir,
                rain,
                uv
            FROM weather
            ORDER BY created_at DESC
            LIMIT :limit
        ");

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Restituisce lo storico delle ultime ore
     * Compatibile con SQLite
     */
    public function getHistory(int $hours = 24): array
    {
        $hours = max(1, $hours);

        $fromDate = date(
            'Y-m-d H:i:s',
            strtotime("-{$hours} hours")
        );

        $stmt = $this->db->prepare("
            SELECT
                created_at,
                temperature,
                humidity,
                pressure,
                wind,
                gust,
                winddir,
                rain,
                uv
            FROM weather
            WHERE created_at >= :fromDate
            ORDER BY created_at ASC
        ");

        $stmt->bindValue(':fromDate', $fromDate);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Temperatura minima
     */
    public function getMinTemperature(int $hours = 24): ?float
    {
        $fromDate = date(
            'Y-m-d H:i:s',
            strtotime("-{$hours} hours")
        );

        $stmt = $this->db->prepare("
            SELECT MIN(temperature)
            FROM weather
            WHERE created_at >= :fromDate
        ");

        $stmt->bindValue(':fromDate', $fromDate);
        $stmt->execute();

        $value = $stmt->fetchColumn();

        return $value !== false ? (float)$value : null;
    }

    /**
     * Temperatura massima
     */
    public function getMaxTemperature(int $hours = 24): ?float
    {
        $fromDate = date(
            'Y-m-d H:i:s',
            strtotime("-{$hours} hours")
        );

        $stmt = $this->db->prepare("
            SELECT MAX(temperature)
            FROM weather
            WHERE created_at >= :fromDate
        ");

        $stmt->bindValue(':fromDate', $fromDate);
        $stmt->execute();

        $value = $stmt->fetchColumn();

        return $value !== false ? (float)$value : null;
    }

    /**
     * Pioggia totale
     */
    public function getTotalRain(int $hours = 24): ?float
    {
        $fromDate = date(
            'Y-m-d H:i:s',
            strtotime("-{$hours} hours")
        );

        $stmt = $this->db->prepare("
            SELECT SUM(rain)
            FROM weather
            WHERE created_at >= :fromDate
        ");

        $stmt->bindValue(':fromDate', $fromDate);
        $stmt->execute();

        $value = $stmt->fetchColumn();

        return $value !== false ? (float)$value : null;
    }

    /**
     * Vento massimo
     */
    public function getMaxWind(int $hours = 24): ?float
    {
        $fromDate = date(
            'Y-m-d H:i:s',
            strtotime("-{$hours} hours")
        );

        $stmt = $this->db->prepare("
            SELECT MAX(wind)
            FROM weather
            WHERE created_at >= :fromDate
        ");

        $stmt->bindValue(':fromDate', $fromDate);
        $stmt->execute();

        $value = $stmt->fetchColumn();

        return $value !== false ? (float)$value : null;
    }

    /**
     * Numero totale dei record
     */
    public function countRecords(): int
    {
        $stmt = $this->db->query("
            SELECT COUNT(*)
            FROM weather
        ");

        return (int)$stmt->fetchColumn();
    }
}