<?php

/**
 * Meteopego V4 Phoenix
 * Configurazione principale
 */

return [

    'site' => [
        'name' => 'Meteopego Stazione',
        'domain' => 'meteopegostazione.it',
        'timezone' => 'Europe/Rome',
        'language' => 'it',
    ],

    'station' => [
        'city' => 'Marghera',
        'province' => 'VE',
        'country' => 'Italia',

        'latitude' => '45.471686',
        'longitude' => '12.216167',
        'altitude' => 3,

        'hardware' => 'VENTUS W835',
    ],

    'meteobridge' => [
        'host' => '192.168.1.234',
        'port' => 80,
        'api' => '/livedata.htm',
    ],

];