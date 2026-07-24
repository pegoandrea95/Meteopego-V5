<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/config.php';

$windy = $config['windy'] ?? [
    'api_key'   => '',
    'latitude'  => 45.478,
    'longitude' => 12.245,
    'zoom'      => 8,
];

?>
<!DOCTYPE html>
<html lang="it">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>

        Radar Meteo |
        <?= htmlspecialchars($config['site']['title']) ?>

    </title>

    <meta
        name="description"
        content="Radar meteo in tempo reale della stazione Meteopego.">

    <meta
        http-equiv="refresh"
        content="300">

    <link
        rel="stylesheet"
        href="/assets/css/style.css">

    <link
        rel="stylesheet"
        href="/assets/css/radar.css">

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

</head>

<body>

<header class="header">

    <h1>🛰 Radar Meteo</h1>

    <p>

        Radar precipitazioni in tempo reale -
        <?= htmlspecialchars($config['weather']['location']) ?>

    </p>

</header>

<nav class="toolbar">

    <button id="btnRadar">
        🛰 Radar
    </button>

    <button id="btnSatellite">
        ☁ Satellite
    </button>

    <button id="btnLightning">
        ⚡ Fulmini
    </button>

    <button id="btnWind">
        🌬 Vento
    </button>

</nav>

<main class="page">

    <div id="map"></div>

</main>

<footer class="footer">

    <p>

        Ultimo aggiornamento:

        <strong>

            <?= date('d/m/Y H:i:s') ?>

        </strong>

    </p>

</footer>

<script>

window.METEOPEGO = {

    windyKey: "<?= htmlspecialchars($windy['api_key']) ?>",

    latitude: <?= (float)$windy['latitude'] ?>,

    longitude: <?= (float)$windy['longitude'] ?>,

    zoom: <?= (int)$windy['zoom'] ?>

};

</script>

<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
</script>

<script
    src="/assets/js/radar.js">
</script>

<script>

setTimeout(function () {

    location.reload();

}, 300000);

</script>

</body>

</html>