<?php
require_once __DIR__ . '/../includes/header.php';
?>

<main class="container py-4">

    <!-- Hero -->
    <section class="text-center mb-5">

        <h1 class="display-4 fw-bold">
            <i class="bi bi-cloud-sun-fill text-primary"></i>
            Meteopego Stazione
        </h1>

        <p class="lead text-secondary">
            Stazione Meteorologica di Marghera (VE)
        </p>

        <div class="mt-3">

            <span id="station-status" class="badge bg-secondary fs-6">
                Connessione...
            </span>

        </div>

        <div class="mt-2">

            <small class="text-muted">

                Ultimo aggiornamento

                <strong id="last-update">--</strong>

            </small>

        </div>

    </section>

    <!-- Dashboard -->

    <section class="row g-4">

        <!-- Temperatura -->

        <div class="col-12 col-md-6 col-lg-4">

            <div class="card shadow-sm h-100">

                <div class="card-body text-center">

                    <i class="bi bi-thermometer-half display-4 text-danger"></i>

                    <h5 class="mt-3">Temperatura</h5>

                    <h2 id="temp" class="display-5 fw-bold">--.- °C</h2>

                </div>

            </div>

        </div>

        <!-- Umidità -->

        <div class="col-12 col-md-6 col-lg-4">

            <div class="card shadow-sm h-100">

                <div class="card-body text-center">

                    <i class="bi bi-droplet-fill display-4 text-primary"></i>

                    <h5 class="mt-3">Umidità</h5>

                    <h2 id="hum" class="display-5 fw-bold">-- %</h2>

                </div>

            </div>

        </div>

        <!-- Pressione -->

        <div class="col-12 col-md-6 col-lg-4">

            <div class="card shadow-sm h-100">

                <div class="card-body text-center">

                    <i class="bi bi-speedometer2 display-4 text-success"></i>

                    <h5 class="mt-3">Pressione</h5>

                    <h2 id="pressure" class="display-5 fw-bold">---- hPa</h2>

                </div>

            </div>

        </div>

        <!-- Vento -->

        <div class="col-12 col-md-6 col-lg-4">

            <div class="card shadow-sm h-100">

                <div class="card-body text-center">

                    <i class="bi bi-wind display-4 text-info"></i>

                    <h5 class="mt-3">Vento</h5>

                    <h2 id="wind" class="display-5 fw-bold">-- km/h</h2>

                </div>

            </div>

        </div>

        <!-- Pioggia -->

        <div class="col-12 col-md-6 col-lg-4">

            <div class="card shadow-sm h-100">

                <div class="card-body text-center">

                    <i class="bi bi-cloud-rain-heavy-fill display-4 text-primary"></i>

                    <h5 class="mt-3">Pioggia</h5>

                    <h2 id="rain" class="display-5 fw-bold">-- mm</h2>

                </div>

            </div>

        </div>

        <!-- UV -->

        <div class="col-12 col-md-6 col-lg-4">

            <div class="card shadow-sm h-100">

                <div class="card-body text-center">

                    <i class="bi bi-sun-fill display-4 text-warning"></i>

                    <h5 class="mt-3">Indice UV</h5>

                    <h2 id="uv" class="display-5 fw-bold">--</h2>

                </div>

            </div>

        </div>

    </section>

    <!-- Informazioni stazione -->

    <section class="mt-5">

        <div class="card shadow-sm">

            <div class="card-header bg-primary text-white">

                <i class="bi bi-cpu"></i>

                Sistema

            </div>

            <div class="card-body">

                <div class="row text-center">

                    <div class="col-md-4">

                        <h6>Stazione</h6>

                        <strong>Ventus W835</strong>

                    </div>

                    <div class="col-md-4">

                        <h6>Gateway</h6>

                        <strong>Meteobridge</strong>

                    </div>

                    <div class="col-md-4">

                        <h6>Server</h6>

                        <strong>Synology NAS</strong>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- Grafici -->

    <section class="mt-5">

        <div class="card shadow-sm">

            <div class="card-header bg-primary text-white">

                <i class="bi bi-graph-up"></i>

                Grafici

            </div>

            <div class="card-body text-center">

                <i class="bi bi-bar-chart display-3 text-secondary"></i>

                <h4 class="mt-3">

                    Grafici in sviluppo

                </h4>

                <p class="text-muted">

                    Nella prossima versione saranno disponibili
                    temperatura, umidità, pressione,
                    vento e pioggia con storico.

                </p>

            </div>

        </div>

    </section>

</main>

<script src="/assets/js/app.js"></script>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>