<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Meteopego V5 Bootstrap
|--------------------------------------------------------------------------
|
| Carica la configurazione e gli helper comuni del progetto.
|
*/

$config = require __DIR__ . '/config.php';

require_once __DIR__ . '/app/Helpers/Url.php';

return $config;