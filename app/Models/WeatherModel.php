<?php

namespace App\Models;

class WeatherModel
{
    private string $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../../data/current.json';
    }

    public function getCurrent(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }

        $json = file_get_contents($this->file);

        return json_decode($json, true) ?? [];
    }
}