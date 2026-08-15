<?php

declare(strict_types=1);

class Env
{
    /**
     * Variabili caricate dal file .env
     */
    private static array $variables = [];

    /**
     * Carica il file .env una sola volta
     */
    public static function load(string $file = null): void
    {
        if (!empty(self::$variables)) {
            return;
        }

        $file ??= dirname(__DIR__, 2) . '/.env';

        if (!file_exists($file)) {
            throw new RuntimeException('.env non trovato: ' . $file);
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');

            self::$variables[trim($key)] = trim($value);
        }
    }

    /**
     * Restituisce una variabile
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::load();

        return self::$variables[$key] ?? $default;
    }

    /**
     * Verifica se una variabile esiste
     */
    public static function has(string $key): bool
    {
        self::load();

        return array_key_exists($key, self::$variables);
    }

    /**
     * Restituisce tutte le variabili
     */
    public static function all(): array
    {
        self::load();

        return self::$variables;
    }
}