<?php
$pageScripts = ['assets/js/charts.js'];

require_once __DIR__ . '/../includes/header.php';
?>

<main class="container py-4">

    <div class="text-center mb-5">

        <h1 class="display-4 fw-bold">
            <i class="bi bi-graph-up"></i>
            Grafici Meteo
        </h1>

        <p class="lead text-secondary">
            Storico della stazione Meteopego
        </p>

    </div>


    <!-- ========================================= -->
    <!-- SELETTORE PERIODO -->
    <!-- ========================================= -->

    <div class="mb-4 text-center">

        <div class="btn-group">

            <button class="btn btn-primary" data-period="24h">
                24 Ore
            </button>

            <button class="btn btn-outline-primary" data-period="7d">
                7 Giorni
            </button>

            <button class="btn btn-outline-primary" data-period="30d">
                30 Giorni
            </button>

            <button class="btn btn-outline-primary" data-period="365d">
                1 Anno
            </button>

        </div>

    </div>


    <div class="row g-4">


        <!-- ========================================= -->
        <!-- TEMPERATURA -->
        <!-- ========================================= -->

        <div class="col-12">

            <div class="card shadow-sm">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <strong>
                            🌡️ Temperatura
                        </strong>

                        <div
                            id="temperatureStats"
                            class="small text-secondary"
                        >
                            Caricamento statistiche...
                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="chart-container">

                        <canvas id="temperatureChart"></canvas>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================= -->
        <!-- UMIDITÀ -->
        <!-- ========================================= -->

        <div class="col-12">

            <div class="card shadow-sm">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <strong>
                            💧 Umidità
                        </strong>

                        <div
                            id="humidityStats"
                            class="small text-secondary"
                        >
                            Caricamento statistiche...
                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="chart-container">

                        <canvas id="humidityChart"></canvas>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================= -->
        <!-- PRESSIONE -->
        <!-- ========================================= -->

        <div class="col-12">

            <div class="card shadow-sm">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <strong>
                            🧭 Pressione
                        </strong>

                        <div
                            id="pressureStats"
                            class="small text-secondary"
                        >
                            Caricamento statistiche...
                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="chart-container">

                        <canvas id="pressureChart"></canvas>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================= -->
        <!-- VENTO -->
        <!-- ========================================= -->

        <div class="col-12">

            <div class="card shadow-sm">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <strong>
                            🌬️ Vento
                        </strong>

                        <div
                            id="windStats"
                            class="small text-secondary"
                        >
                            Caricamento statistiche...
                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="chart-container">

                        <canvas id="windChart"></canvas>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================= -->
        <!-- PIOGGIA -->
        <!-- ========================================= -->

        <div class="col-12">

            <div class="card shadow-sm">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <strong>
                            🌧️ Pioggia
                        </strong>

                        <div
                            id="rainStats"
                            class="small text-secondary"
                        >
                            Caricamento statistiche...
                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="chart-container">

                        <canvas id="rainChart"></canvas>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================= -->
        <!-- INDICE UV -->
        <!-- ========================================= -->

        <div class="col-12">

            <div class="card shadow-sm">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <strong>
                            ☀️ Indice UV
                        </strong>

                        <div
                            id="uvStats"
                            class="small text-secondary"
                        >
                            Caricamento statistiche...
                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="chart-container">

                        <canvas id="uvChart"></canvas>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================= -->
        <!-- RADIAZIONE SOLARE -->
        <!-- ========================================= -->

        <div class="col-12">

            <div class="card shadow-sm">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <strong>
                            🌞 Radiazione Solare
                        </strong>

                        <div
                            id="solarStats"
                            class="small text-secondary"
                        >
                            Caricamento statistiche...
                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="chart-container">

                        <canvas id="solarChart"></canvas>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================= -->
        <!-- PUNTO DI RUGIADA -->
        <!-- ========================================= -->

        <div class="col-12">

            <div class="card shadow-sm">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <strong>
                            💦 Punto di rugiada
                        </strong>

                        <div
                            id="dewStats"
                            class="small text-secondary"
                        >
                            Caricamento statistiche...
                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="chart-container">

                        <canvas id="dewChart"></canvas>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================= -->
        <!-- TEMPERATURA PERCEPITA -->
        <!-- ========================================= -->

        <div class="col-12">

            <div class="card shadow-sm">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <strong>
                            🥵 Temperatura percepita
                        </strong>

                        <div
                            id="feelsStats"
                            class="small text-secondary"
                        >
                            Caricamento statistiche...
                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="chart-container">

                        <canvas id="feelsChart"></canvas>

                    </div>

                </div>

            </div>

        </div>


    </div>

</main>


<?php
require_once __DIR__ . '/../includes/footer.php';
?>