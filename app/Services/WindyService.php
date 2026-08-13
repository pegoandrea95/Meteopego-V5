<?php

declare(strict_types=1);

final class WindyService
{
    public function __construct(
        private readonly array $config
    ) {
    }

    public function getConfig(): array
    {
        return [
            'apiKey'    => $this->config['windy']['api_key'],
            'latitude'  => (float) $this->config['windy']['latitude'],
            'longitude' => (float) $this->config['windy']['longitude'],
            'zoom'      => (int) $this->config['windy']['zoom'],
        ];
    }
}