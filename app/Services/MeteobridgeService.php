<?php

declare(strict_types=1);

class MeteobridgeService
{
    private array $config;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config.php';

        $this->config = $config['weather'];
    }

    /**
     * Recupera i dati dalla Meteobridge.
     */
    public function fetch(): array
    {
        throw new RuntimeException(
            'Metodo fetch() non ancora implementato.'
        );
    }
}