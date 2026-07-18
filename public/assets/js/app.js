async function aggiornaMeteo() {

    console.log("1 - Funzione avviata");

    try {

        const response = await fetch('/api/weather.php?' + Date.now());

        console.log("2 - Fetch OK");

        const data = await response.json();

        console.log("3 - JSON:", data);

        aggiorna('temp', data.temperature, ' °C');
        aggiorna('hum', data.humidity, ' %');
        aggiorna('pressure', data.pressure, ' hPa');
        aggiorna('wind', data.wind, ' km/h');
        aggiorna('rain', data.rain ?? 0, ' mm');
        aggiorna('uv', data.uv ?? '--');

        console.log("4 - Card aggiornate");

        const ts = document.getElementById('last-update');

        if (ts) {
            ts.textContent = data.timestamp;
        }

        console.log("5 - Timestamp aggiornato");

        const status = document.getElementById('station-status');

        console.log("6 - Status:", status);

        if (status) {

            status.className = "badge bg-success fs-6";
            status.textContent = "🟢 Online";

            console.log("7 - Badge aggiornato");

        }

    } catch (e) {

        console.error("ERRORE:", e);

    }

}

function aggiorna(id, valore, unita = '') {

    const el = document.getElementById(id);

    if (!el) {
        console.log("Elemento non trovato:", id);
        return;
    }

    el.textContent = (valore ?? '--') + unita;

}

aggiornaMeteo();
setInterval(aggiornaMeteo, 30000);