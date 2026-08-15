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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Radar Meteo - Meteopego
    </title>

    <link
        rel="stylesheet"
        href="/assets/css/radar.css">

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css">

</head>

<body>

<header class="header">

    <h1>
        🛰 Radar Meteo
    </h1>

    <p>

        Radar Windy in tempo reale -
        <?= htmlspecialchars(
            $config['weather']['location']
        ) ?>

    </p>

</header>

<nav class="toolbar">

    <button
        type="button"
        id="btnRainViewer"
        aria-pressed="false">
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

<!--
|--------------------------------------------------------------------------
| Modalità radar precipitazioni
|--------------------------------------------------------------------------
|
| La timeline e la legenda sono nascoste
| inizialmente e vengono mostrate quando
| viene attivata la modalità Pioggia.
|
-->

<section
    class="radar-mode"
    id="radarMode"
    hidden>

    <!-- Timeline RainViewer -->

    <div
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

        <div class="radar-timeline-info">

            <span id="radarFrameInfo">
                0 frame
            </span>

            <span id="radarRelativeTime">
                --
            </span>

        </div>

        <div class="radar-timeline-controls">

            <button
                type="button"
                id="radarPrev"
                aria-label="Frame precedente">
                ◀
            </button>

            <input
                type="range"
                id="radarSlider"
                min="0"
                max="0"
                value="0"
                step="1"
                aria-label="Timeline radar precipitazioni">

            <button
                type="button"
                id="radarNext"
                aria-label="Frame successivo">
                ▶
            </button>

        </div>

        <div class="radar-timeline-labels">

            <span>
                -60 min
            </span>

            <span>
                -30 min
            </span>

            <span>
                -10 min
            </span>

            <span>
                ORA
            </span>

        </div>

        <div class="radar-timeline-footer">

            <button
                type="button"
                id="radarPlay">
                ▶ Play
            </button>

        </div>

    </div>

    <!-- Legenda precipitazioni -->

    <div
        class="radar-legend"
        id="radarLegend">

        <div class="radar-legend-title">
            Intensità precipitazioni
        </div>

        <div class="radar-legend-scale">

            <div class="radar-legend-item">
                <span class="radar-color radar-color-1"></span>
                <span>Debole</span>
            </div>

            <div class="radar-legend-item">
                <span class="radar-color radar-color-2"></span>
                <span>Moderata</span>
            </div>

            <div class="radar-legend-item">
                <span class="radar-color radar-color-3"></span>
                <span>Forte</span>
            </div>

            <div class="radar-legend-item">
                <span class="radar-color radar-color-4"></span>
                <span>Molto forte</span>
            </div>

            <div class="radar-legend-item">
                <span class="radar-color radar-color-5"></span>
                <span>Intensa</span>
            </div>

            <div class="radar-legend-item">
                <span class="radar-color radar-color-6"></span>
                <span>Estrema</span>
            </div>

        </div>

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
            <?= json_encode(
                $windy['apiKey'],
                JSON_UNESCAPED_SLASHES
            ) ?>,

        latitude:
            <?= json_encode(
                $windy['latitude']
            ) ?>,

        longitude:
            <?= json_encode(
                $windy['longitude']
            ) ?>,

        zoom:
            <?= json_encode(
                $windy['zoom']
            ) ?>,

        stationName:
            <?= json_encode(
                $config['weather']['station_name'] ?? 'Meteopego',
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES
            ) ?>

    };

</script>

<script
    src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js">
</script>

<script
    src="https://api.windy.com/assets/map-forecast/libBoot.js">
</script>

<script
    src="/assets/js/weather-api.js">
</script>

<script
    src="/assets/js/radar.js">
</script>

</body>

</html>