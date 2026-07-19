<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Sito
    |--------------------------------------------------------------------------
    */

    'site' => [

        'title'       => 'Meteopego Stazione',

        'description' => 'Stazione Meteorologica di Marghera (VE)',

        'timezone'    => 'Europe/Rome',

        'version'     => '5.1.0'

    ],

    /*
    |--------------------------------------------------------------------------
    | Database MariaDB
    |--------------------------------------------------------------------------
    */

    'database' => [

        'driver'   => 'mysql',

        'host'     => 'localhost',

        'port'     => 3306,

        'database' => 'meteopego',

        'username' => 'meteopego',

        'password' => 'Tornado25!',

        'charset'  => 'utf8mb4'

    ],

    /*
    |--------------------------------------------------------------------------
    | Meteo
    |--------------------------------------------------------------------------
    */

    'weather' => [

        'station' => 'Ventus W835',

        'gateway' => 'Meteobridge',

        'location' => 'Marghera (VE)',

        'refresh' => 30

    ]

];