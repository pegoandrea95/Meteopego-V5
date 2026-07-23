<?php

declare(strict_types=1);

class MeteobridgeService
{
    /**
     * Configurazione Meteobridge
     */
    private array $config;

    /**
     * URL della Meteobridge
     */
    private string $url;

    /**
     * Username HTTP Basic
     */
    private string $username;

    /**
     * Password HTTP Basic
     */
    private string $password;

    /**
     * Timeout richiesta HTTP
     */
    private int $timeout;

    /**
     * Template Meteobridge
     */
    private string $template;

    /**
     * Costruttore
     */
    public function __construct()
    {
        $config = require __DIR__ . '/../../config.php';

        if (!isset($config['meteobridge'])) {
            throw new RuntimeException(
                'Configurazione Meteobridge non trovata.'
            );
        }

        $this->config = $config['meteobridge'];

        $this->url = rtrim($this->config['url'], '/');

        $this->username = $this->config['username'];

        $this->password = $this->config['password'];

        $this->timeout = (int) ($this->config['timeout'] ?? 10);

        $this->template = $this->config['template'];
    }

    /**
     * Costruisce la URL completa della richiesta.
     */
    private function buildUrl(): string
    {
        return $this->url .
            '/cgi-bin/template.cgi?template=' .
            urlencode($this->template);
    }

    /**
     * Recupera i dati dalla Meteobridge.
     *
     * Verrà implementato nel prossimo step.
     */
    public function fetch(): array
    {
        throw new RuntimeException(
            'Metodo fetch() non ancora implementato.'
        );
    }
}