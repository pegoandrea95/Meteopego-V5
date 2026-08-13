<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Meteopego V5
| Radar Windy
|--------------------------------------------------------------------------
*/

$config = require dirname(__DIR__) . '/bootstrap.php';

require_once __DIR__ . '/../app/Services/WindyService.php';

$windyService = new WindyService($config);
$windy = $windyService->getConfig();

?>

<!DOCTYPE html>
<html lang="it">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>
        Radar Meteo |
        <?= htmlspecialchars($config['site']['title']) ?>
    </title>

    <meta
        name="description"
        content="Radar meteo in tempo reale della stazione Meteopego">

    <meta
        name="theme-color"
        content="#2563eb">

    <link
        rel="icon"
        href="<?= asset('assets/images/favicon.ico') ?>">

    <!-- CSS -->
    <link
        rel="stylesheet"
        href="<?= asset('assets/css/style.css') ?>">

    <link
        rel="stylesheet"
        href="<?= asset('assets/css/radar.css') ?>">

    <!-- Leaflet -->
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css">

</head>

<body>

<header class="header">

    <h1>🛰 Radar Meteo</h1>

    <p>

        Radar Windy in tempo reale -
        <?= htmlspecialchars($config['weather']['location']) ?>

    </p>

</header>

<nav class="toolbar">

    <button
        type="button"
        id="btnRainViewer">
        🌧️ Pioggia
    </button>

    <button
        type="button"
        data-overlay="wind">
        🌬️ Vento
    </button>

    <button
        type="button"
        data-overlay="temp">
        🌡️ Temperatura
    </button>

    <button
        type="button"
        data-overlay="pressure">
        📊 Pressione
    </button>

</nav>

<section
    class="radar-timeline"
    id="radarTimeline">

    <div class="radar-timeline-header">

        <strong>
            🌧️ Radar precipitazioni
        </strong>

        <span id="radarTime">
            --:--
        </span>

    </div>

    <div class="radar-timeline-controls">

        <button
            type="button"
            id="radarPrev">
            ◀
        </button>

        <input
            type="range"
            id="radarSlider"
            min="0"
            max="0"
            value="0"
            step="1">

        <button
            type="button"
            id="radarNext">
            ▶
        </button>

    </div>

    <div class="radar-timeline-footer">

        <button
            type="button"
            id="radarPlay">
            ▶ Play
        </button>

    </div>

</section>

<main class="page">

    <div id="windy"></div>

</main>

<footer class="footer">

    <p>

        Ultimo aggiornamento

        <strong>

            <?= date('d/m/Y H:i:s') ?>

        </strong>

    </p>

</footer>

<script>

window.METEOPEGO = {

    windyKey:
        "<?= htmlspecialchars($windy['apiKey']) ?>",

    latitude:
        <?= (float)$windy['latitude'] ?>,

    longitude:
        <?= (float)$windy['longitude'] ?>,

    zoom:
        <?= (int)$windy['zoom'] ?>

};

</script>

<!-- Leaflet -->
<script
    src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js">
</script>

<!-- Windy SDK -->
<script
    src="https://api.windy.com/assets/map-forecast/libBoot.js">
</script>

<!-- Meteopego API -->
<script
    src="<?= asset('assets/js/weather-api.js') ?>">
</script>

<!-- Radar -->
<script
    src="<?= asset('assets/js/radar.js') ?>">
</script>

<script>

setTimeout(() => {

    location.reload();

}, 300000);

</script>

</body>

</html>