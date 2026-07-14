<?php include '../includes/header.php'; ?>

<main>

    <section class="hero">
        <h1>Benvenuto su Meteopego Stazione</h1>
        <p>Dashboard Meteo in tempo reale</p>
    </section>

    <section class="dashboard">

        <div class="card">
            <h2>🌡 Temperatura</h2>
            <p id="temp">--.- °C</p>
        </div>

        <div class="card">
            <h2>💧 Umidità</h2>
            <p id="hum">-- %</p>
        </div>

        <div class="card">
            <h2>🌬 Vento</h2>
            <p id="wind">-- km/h</p>
        </div>

        <div class="card">
            <h2>🌧 Pioggia</h2>
            <p id="rain">-- mm</p>
        </div>

        <div class="card">
            <h2>📈 Pressione</h2>
            <p id="pressure">---- hPa</p>
        </div>

        <div class="card">
            <h2>☀️ UV</h2>
            <p id="uv">--</p>
        </div>

    </section>

</main>

<script src="/assets/js/app.js"></script>

<?php include '../includes/footer.php'; ?>