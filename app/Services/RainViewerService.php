<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

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

    /**
     * Recupera i dati radar da RainViewer.
     */
    public function getRadarData(): array
    {
        $url = $this->getApiUrl();

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 10,
                'ignore_errors' => true,
                'header' => [
                    'Accept: application/json',
                    'User-Agent: Meteopego-V5/1.0'
                ]
            ]
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            throw new RuntimeException(
                'Impossibile raggiungere il servizio RainViewer.'
            );
        }

        $data = json_decode(
            $response,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (
            !isset($data['host']) ||
            !isset($data['radar'])
        ) {
            throw new RuntimeException(
                'Risposta RainViewer non valida.'
            );
        }

        return [
            'version' => $data['version'] ?? null,

            'generated' => $data['generated'] ?? null,

            'host' => $data['host'],

            'radar' => [
                'past' => $data['radar']['past'] ?? [],
                'nowcast' => $data['radar']['nowcast'] ?? []
            ]
        ];
    }
}