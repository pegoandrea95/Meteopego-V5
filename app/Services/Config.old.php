<?php

declare(strict_types=1);

class Config
{
    public static function station(): array
    {
        return [

            'name' => 'Meteopego',

            'location' => 'Marghera (VE)',

            'updateInterval' => 30

        ];
    }

    public static function paths(): array
    {
        return [

            'json' => __DIR__ . '/../../data/current.json',

            'database' => __DIR__ . '/../../storage/database/meteopego.sqlite'

        ];
    }
}