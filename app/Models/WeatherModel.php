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
     * Restituisce l'ultima rilevazione
     */
    public function getLatest(): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM weather
            ORDER BY created_at DESC
            LIMIT 1
        ");

        $stmt->execute();

        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * Restituisce lo storico
     */
    public function getHistory(int $limit = 500): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM weather
            ORDER BY created_at DESC
            LIMIT :limit
        ");

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Inserisce una nuova rilevazione
     */
    public function insert(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO weather (

                temperature,
                humidity,
                pressure,
                wind,
                gust,
                winddir,
                rain,
                uv

            ) VALUES (

                :temperature,
                :humidity,
                :pressure,
                :wind,
                :gust,
                :winddir,
                :rain,
                :uv

            )
        ");

        return $stmt->execute([

            ':temperature' => $data['temperature'] ?? null,
            ':humidity'    => $data['humidity'] ?? null,
            ':pressure'    => $data['pressure'] ?? null,
            ':wind'        => $data['wind'] ?? null,
            ':gust'        => $data['gust'] ?? null,
            ':winddir'     => $data['winddir'] ?? null,
            ':rain'        => $data['rain'] ?? null,
            ':uv'          => $data['uv'] ?? null

        ]);
    }

    /**
     * Pioggia di oggi
     */
    public function getRainToday(): float
    {
        $stmt = $this->db->query("
            SELECT SUM(rain) AS total
            FROM weather
            WHERE DATE(created_at)=CURDATE()
        ");

        $row = $stmt->fetch();

        return (float) ($row['total'] ?? 0);
    }

    /**
     * Raffica massima oggi
     */
    public function getMaxWind(): float
    {
        $stmt = $this->db->query("
            SELECT MAX(gust) AS maxgust
            FROM weather
            WHERE DATE(created_at)=CURDATE()
        ");

        $row = $stmt->fetch();

        return (float) ($row['maxgust'] ?? 0);
    }

    /**
     * Statistiche generali
     */
    public function getStatistics(): array
    {
        $stmt = $this->db->query("
            SELECT

                MIN(temperature) AS min_temp,
                MAX(temperature) AS max_temp,
                AVG(temperature) AS avg_temp,

                MIN(humidity) AS min_humidity,
                MAX(humidity) AS max_humidity,

                MAX(gust) AS max_gust,

                SUM(rain) AS total_rain

            FROM weather
            WHERE DATE(created_at)=CURDATE()
        ");

        return $stmt->fetch() ?: [];
    }
}