<?php

namespace App\Services;

class Config
{
    private static array $config;

    public static function load(): void
    {
        if (!isset(self::$config)) {
            self::$config = include __DIR__ . '/../../config/config.php';
        }
    }

    public static function get(string $section, ?string $key = null)
    {
        self::load();

        if ($key === null) {
            return self::$config[$section] ?? null;
        }

        return self::$config[$section][$key] ?? null;
    }
}