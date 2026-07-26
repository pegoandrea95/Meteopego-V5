<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/bootstrap.php';

require_once __DIR__ . '/../app/Services/WindyService.php';

$windyService = new WindyService($config);
$windy = $windyService->getConfig();

?>
<!DOCTYPE html>
<html lang="it">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Radar Meteo |
        <?= htmlspecialchars($config['site']['title']) ?>
    </title>

    <meta name="description"
          content="Radar meteo in tempo reale della stazione Meteopego.">

    <meta http-equiv="refresh"
          content="300">

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Radar CSS -->
    <link
        rel="stylesheet"
        href="<?= asset('assets/css/radar.css') ?>">

    <!-- Leaflet -->
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

    windyKey: "<?= htmlspecialchars($windy['apiKey']) ?>",

    latitude: <?= $windy['latitude'] ?>,

    longitude: <?= $windy['longitude'] ?>,

    zoom: <?= $windy['zoom'] ?>

};

</script>

<!-- Leaflet -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Radar JS -->
<script src="<?= asset('assets/js/radar.js') ?>"></script>

<script>

setTimeout(() => {

    location.reload();

}, 300000);

</script>

</body>

</html>