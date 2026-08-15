<?php

declare(strict_types=1);

if (!function_exists('base_url')) {

    function base_url(): string
    {
        static $base = null;

        if ($base === null) {
            $config = require dirname(__DIR__, 2) . '/config.php';
            $base = rtrim($config['app']['base_url'] ?? '', '/');
        }

        return $base;
    }
}

if (!function_exists('url')) {

    function url(string $path = ''): string
    {
        $path = ltrim($path, '/');

        if ($path === '') {
            return base_url() ?: '/';
        }

        return base_url() . '/' . $path;
    }
}

if (!function_exists('asset')) {

    function asset(string $path): string
    {
        return url($path);
    }
}

if (!function_exists('active')) {

    function active(string $page): string
    {
        $current = basename($_SERVER['PHP_SELF']);

        return $current === $page ? 'active' : '';
    }
}