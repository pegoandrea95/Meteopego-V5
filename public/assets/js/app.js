async function aggiornaMeteo() {

    try {

        const response = await fetch('/api/weather.php?' + Date.now());

        const data = await response.json();

        if (!data.success) {
            console.error(data.message);
            return;
        }

        aggiorna('temp', data.temperature, '°C');
        aggiorna('hum', data.humidity, '%');
        aggiorna('pressure', data.pressure, ' hPa');
        aggiorna('wind', data.wind, ' km/h');
        aggiorna('rain', data.rain ?? 0, ' mm');
        aggiorna('uv', data.uv ?? '--', '');

        const ts = document.getElementById('last-update');

        if (ts) {
            ts.textContent = data.timestamp;
        }

        const status = document.getElementById('station-status');

        if (status) {

            status.className = "badge bg-success";

            status.textContent = "🟢 Online";

        }

    } catch (e) {

        console.error(e);

        const status = document.getElementById('station-status');

        if (status) {

            status.className = "badge bg-danger";

            status.textContent = "🔴 Offline";

        }

    }

}

function aggiorna(id, valore, unita = '') {

    const el = document.getElementById(id);

    if (!el) return;

    if (valore === null || valore === undefined || valore === '') {

        el.textContent = '--';

        return;

    }

    el.textContent = valore + unita;

}

aggiornaMeteo();

setInterval(aggiornaMeteo, 30000);