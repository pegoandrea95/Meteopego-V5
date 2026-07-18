<?php

class WeatherModel
{
    private string $jsonFile;

    public function __construct()
    {
        $this->jsonFile = __DIR__ . '/../../data/current.json';
    }

    public function getCurrent(): array
    {
        if (!file_exists($this->jsonFile)) {

            return [
                'success' => false,
                'message' => 'current.json non trovato'
            ];

        }

        $json = file_get_contents($this->jsonFile);

        $data = json_decode($json, true);

        if (!$data) {

            return [
                'success' => false,
                'message' => 'JSON non valido'
            ];

        }

        return [
            'success' => true,

            'temperature' => $data['temperature'] ?? null,

            'humidity' => $data['humidity'] ?? null,

            'pressure' => $data['pressure'] ?? null,

            'wind' => $data['wind'] ?? null,

            'gust' => $data['gust'] ?? null,

            'winddir' => $data['winddir'] ?? null,

            'rain' => $data['rain'] ?? null,

            'uv' => $data['uv'] ?? null,

            'timestamp' => $data['timestamp'] ?? date('Y-m-d H:i:s')
        ];
    }
}