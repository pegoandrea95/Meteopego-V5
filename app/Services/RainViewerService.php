<?php

declare(strict_types=1);

namespace App\Services;

final class RainViewerService
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config['rainviewer'];
    }

    public function getApiUrl(): string
    {
        return $this->config['api_url'];
    }

    public function getCacheMinutes(): int
    {
        return (int) $this->config['cache_minutes'];
    }
}