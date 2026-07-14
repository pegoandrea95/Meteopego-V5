// Meteopego V4
// Aggiornamento automatico dashboard

async function aggiornaMeteo() {
    try {

        const response = await fetch('/weather.php');

        if (!response.ok) {
            throw new Error('Errore HTTP ' + response.status);
        }

        const dati = await response.json();

        // Temperatura
        if (document.getElementById('temp')) {
            document.getElementById('temp').textContent =
                dati.temperature + " °C";
        }

        // Umidità
        if (document.getElementById('hum')) {
            document.getElementById('hum').textContent =
                dati.humidity + " %";
        }

        // Pressione
        if (document.getElementById('pressure')) {
            document.getElementById('pressure').textContent =
                dati.pressure + " hPa";
        }

        // Vento
        if (document.getElementById('wind')) {
            document.getElementById('wind').textContent =
                dati.wind + " km/h";
        }

        // Pioggia (non ancora disponibile)
        if (document.getElementById('rain')) {
            if (dati.rain !== undefined) {
                document.getElementById('rain').textContent =
                    dati.rain + " mm";
            } else {
                document.getElementById('rain').textContent = "-- mm";
            }
        }

        // UV (non ancora disponibile)
        if (document.getElementById('uv')) {
            if (dati.uv !== undefined) {
                document.getElementById('uv').textContent = dati.uv;
            } else {
                document.getElementById('uv').textContent = "--";
            }
        }

    } catch (errore) {

        console.error("Errore Meteopego:", errore);

        ["temp","hum","pressure","wind","rain","uv"].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.textContent = "--";
            }
        });

    }
}

// Primo caricamento
aggiornaMeteo();

// Aggiornamento ogni 30 secondi
setInterval(aggiornaMeteo, 30000);