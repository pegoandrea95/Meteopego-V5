<?php include '../includes/header.php'; ?>

<main class="container py-4">

    <!-- Hero -->
    <section class="text-center mb-5">
        <h1 class="display-4">🌦 Meteopego Stazione</h1>
        <p class="lead">Stazione Meteorologica di Marghera (VE)</p>

        <div class="badge bg-primary fs-6">
            Aggiornamento automatico ogni 30 secondi
        </div>
    </section>

    <!-- Dashboard -->
    <section class="row g-4">

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <h2>🌡</h2>
                    <h5 class="card-title">Temperatura</h5>
                    <p id="temp" class="display-5 fw-bold">--.- °C</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <h2>💧</h2>
                    <h5 class="card-title">Umidità</h5>
                    <p id="hum" class="display-5 fw-bold">-- %</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <h2>💨</h2>
                    <h5 class="card-title">Vento</h5>
                    <p id="wind" class="display-5 fw-bold">-- km/h</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <h2>🌧</h2>
                    <h5 class="card-title">Pioggia</h5>
                    <p id="rain" class="display-5 fw-bold">-- mm</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <h2>📈</h2>
                    <h5 class="card-title">Pressione</h5>
                    <p id="pressure" class="display-5 fw-bold">---- hPa</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <h2>☀️</h2>
                    <h5 class="card-title">Indice UV</h5>
                    <p id="uv" class="display-5 fw-bold">--</p>
                </div>
            </div>
        </div>

    </section>

    <!-- Grafici -->
    <section class="mt-5">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                📊 Grafici Meteo (in arrivo)
            </div>

            <div class="card-body text-center py-5">
                <h4>Prossimamente</h4>
                <p class="text-muted">
                    Grafici di temperatura, pressione, vento e pioggia.
                </p>
            </div>

        </div>

    </section>

</main>

<script src="/assets/js/app.js"></script>

<?php include '../includes/footer.php'; ?>