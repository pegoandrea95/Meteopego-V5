async function aggiornaMeteo() {

    console.log("1 - Funzione avviata");

    try {

        const response = await fetch('/api/current.php?' + Date.now());

        console.log("2 - Fetch OK");

        const data = await response.json();

        console.log("3 - JSON:", data);


        // ==========================
        // Temperatura
        // ==========================

        aggiorna('temp', data.temperature, ' °C');


        // ==========================
        // Umidità
        // ==========================

        aggiorna('hum', data.humidity, ' %');


        // ==========================
        // Pressione
        // ==========================

        aggiorna('pressure', data.pressure, ' hPa');


        // ==========================
        // Vento
        // ==========================

        aggiorna('wind', data.wind, ' km/h');

        aggiorna('wind-gust', data.gust, ' km/h');


        // ==========================
        // Direzione vento
        // ==========================

        const windSpeed = Number(data.wind ?? 0);

        const directionElement =
            document.getElementById('wind-direction');

        const arrow =
            document.getElementById('wind-arrow');


        if (windSpeed <= 0.5) {

            // Vento praticamente assente

            if (directionElement) {

                directionElement.textContent =
                    'CALMA';

            }

            if (arrow) {

                arrow.style.transform =
                    'translate(-50%, -100%) rotate(0deg)';

                arrow.style.opacity = '0.25';

            }

        } else if (
            data.winddir !== undefined &&
            data.winddir !== null &&
            !isNaN(Number(data.winddir))
        ) {

            const windDirection =
                Number(data.winddir);

            const directionText =
                getDirezioneVento(windDirection);


            if (directionElement) {

                directionElement.textContent =
                    `${directionText} · ${windDirection.toFixed(0)}°`;

            }


            if (arrow) {

                arrow.style.transform =
                    `translate(-50%, -100%) rotate(${windDirection}deg)`;

                arrow.style.opacity = '1';

            }

        } else {

            // Direzione non disponibile

            if (directionElement) {

                directionElement.textContent =
                    '-- · --°';

            }

            if (arrow) {

                arrow.style.opacity = '0.25';

            }

        }


        // ==========================
        // Pioggia
        // ==========================

        aggiorna(
            'rain',
            data.rain ?? 0,
            ' mm'
        );


        // ==========================
        // UV
        // ==========================

        aggiorna(
            'uv',
            data.uv ?? '--'
        );


        console.log("4 - Card aggiornate");


        // ==========================
        // Timestamp
        // ==========================

        const ts =
            document.getElementById('last-update');

        if (ts) {

            ts.textContent =
                data.timestamp;

        }


        console.log("5 - Timestamp aggiornato");


        // ==========================
        // Stato stazione
        // ==========================

        const status =
            document.getElementById('station-status');

        console.log("6 - Status:", status);


        if (status) {

            status.className =
                "badge bg-success fs-6";

            status.textContent =
                "🟢 Online";

            console.log("7 - Badge aggiornato");

        }


    } catch (e) {

        console.error("ERRORE:", e);


        // ==========================
        // Stazione offline
        // ==========================

        const status =
            document.getElementById('station-status');

        if (status) {

            status.className =
                "badge bg-danger fs-6";

            status.textContent =
                "🔴 Offline";

        }

    }

}


// ==========================
// Conversione gradi → direzione
// ==========================

function getDirezioneVento(gradi) {

    const direzioni = [

        'N',
        'NE',
        'E',
        'SE',
        'S',
        'SO',
        'O',
        'NO'

    ];

    const indice =
        Math.round(gradi / 45) % 8;

    return direzioni[indice];

}


// ==========================
// Aggiornamento elemento
// ==========================

function aggiorna(id, valore, unita = '') {

    const el =
        document.getElementById(id);

    if (!el) {

        console.log(
            "Elemento non trovato:",
            id
        );

        return;

    }

    el.textContent =
        (valore ?? '--') + unita;

}


// ==========================
// Avvio
// ==========================

aggiornaMeteo();

setInterval(
    aggiornaMeteo,
    30000
);