let charts = {};
let periodoAttuale = '24h';


/**
 * Converte il timestamp MySQL in millisecondi
 */
function timestampData(dataString) {

    const data = new Date(
        dataString.replace(' ', 'T')
    );

    return data.getTime();
}


/**
 * Formatta l'etichetta dell'asse X
 */
function formattaDataAsse(timestamp, periodo) {

    const data = new Date(timestamp);

    if (isNaN(data.getTime())) {
        return '';
    }

    if (periodo === '24h') {

        return data.toLocaleTimeString(
            'it-IT',
            {
                hour: '2-digit',
                minute: '2-digit'
            }
        );

    }

    if (
        periodo === '7d' ||
        periodo === '30d'
    ) {

        return data.toLocaleDateString(
            'it-IT',
            {
                day: 'numeric',
                month: 'short'
            }
        );

    }

    if (periodo === '365d') {

        return data.toLocaleDateString(
            'it-IT',
            {
                month: 'short',
                year: 'numeric'
            }
        );

    }

    return '';
}


/**
 * Formatta data e ora nel tooltip
 */
function formattaTooltip(timestamp) {

    const data = new Date(timestamp);

    if (isNaN(data.getTime())) {
        return '';
    }

    return data.toLocaleString(
        'it-IT',
        {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        }
    );
}


/**
 * Formatta un valore meteorologico
 */
function formattaValore(valore) {

    return Number(valore).toLocaleString(
        'it-IT',
        {
            minimumFractionDigits: 1,
            maximumFractionDigits: 1
        }
    );
}


/**
 * Restituisce i valori numerici validi
 */
function datiValidi(dati, campo) {

    return dati
        .map(riga => Number(riga[campo]))
        .filter(valore => !isNaN(valore));
}


/**
 * Statistiche generiche
 *
 * Attuale
 * Min
 * Max
 * Media
 */
function aggiornaStatistiche(
    dati,
    campo,
    elementoId,
    unita
) {

    const elemento =
        document.getElementById(
            elementoId
        );

    if (!elemento) {
        return;
    }

    const valori =
        datiValidi(
            dati,
            campo
        );

    if (valori.length === 0) {

        elemento.textContent =
            'Nessun dato disponibile';

        return;
    }

    const attuale =
        valori[valori.length - 1];

    const minimo =
        Math.min(...valori);

    const massimo =
        Math.max(...valori);

    const somma =
        valori.reduce(
            (totale, valore) =>
                totale + valore,
            0
        );

    const media =
        somma / valori.length;

    elemento.innerHTML = `
        <span class="me-3">
            <strong>Attuale:</strong>
            ${formattaValore(attuale)} ${unita}
        </span>

        <span class="me-3">
            <strong>Min:</strong>
            ${formattaValore(minimo)} ${unita}
        </span>

        <span class="me-3">
            <strong>Max:</strong>
            ${formattaValore(massimo)} ${unita}
        </span>

        <span>
            <strong>Media:</strong>
            ${formattaValore(media)} ${unita}
        </span>
    `;
}


/**
 * Statistiche temperatura
 */
function aggiornaStatisticheTemperatura(dati) {

    aggiornaStatistiche(
        dati,
        'temperature',
        'temperatureStats',
        '°C'
    );
}


/**
 * Statistiche umidità
 */
function aggiornaStatisticheUmidita(dati) {

    aggiornaStatistiche(
        dati,
        'humidity',
        'humidityStats',
        '%'
    );
}


/**
 * Statistiche pressione
 */
function aggiornaStatistichePressione(dati) {

    aggiornaStatistiche(
        dati,
        'pressure',
        'pressureStats',
        'hPa'
    );
}


/**
 * Statistiche vento
 *
 * Attuale
 * Media
 * Max
 */
function aggiornaStatisticheVento(dati) {

    const elemento =
        document.getElementById(
            'windStats'
        );

    if (!elemento) {
        return;
    }

    const valori =
        datiValidi(
            dati,
            'wind'
        );

    if (valori.length === 0) {

        elemento.textContent =
            'Nessun dato disponibile';

        return;
    }

    const attuale =
        valori[valori.length - 1];

    const massimo =
        Math.max(...valori);

    const somma =
        valori.reduce(
            (totale, valore) =>
                totale + valore,
            0
        );

    const media =
        somma / valori.length;

    elemento.innerHTML = `
        <span class="me-3">
            <strong>Attuale:</strong>
            ${formattaValore(attuale)} km/h
        </span>

        <span class="me-3">
            <strong>Media:</strong>
            ${formattaValore(media)} km/h
        </span>

        <span>
            <strong>Max:</strong>
            ${formattaValore(massimo)} km/h
        </span>
    `;
}


/**
 * Statistiche pioggia
 *
 * Totale
 * Max rilevato
 */
function aggiornaStatistichePioggia(dati) {

    const elemento =
        document.getElementById(
            'rainStats'
        );

    if (!elemento) {
        return;
    }

    const valori =
        datiValidi(
            dati,
            'rain'
        );

    if (valori.length === 0) {

        elemento.textContent =
            'Nessun dato disponibile';

        return;
    }

    const totale =
        valori.reduce(
            (somma, valore) =>
                somma + valore,
            0
        );

    const massimo =
        Math.max(...valori);

    elemento.innerHTML = `
        <span class="me-3">
            <strong>Totale:</strong>
            ${formattaValore(totale)} mm
        </span>

        <span>
            <strong>Max rilevato:</strong>
            ${formattaValore(massimo)} mm
        </span>
    `;
}


/**
 * Statistiche UV
 *
 * Attuale
 * Max
 */
function aggiornaStatisticheUV(dati) {

    const elemento =
        document.getElementById(
            'uvStats'
        );

    if (!elemento) {
        return;
    }

    const valori =
        datiValidi(
            dati,
            'uv'
        );

    if (valori.length === 0) {

        elemento.textContent =
            'Nessun dato disponibile';

        return;
    }

    const attuale =
        valori[valori.length - 1];

    const massimo =
        Math.max(...valori);

    elemento.innerHTML = `
        <span class="me-3">
            <strong>Attuale:</strong>
            ${formattaValore(attuale)}
        </span>

        <span>
            <strong>Max:</strong>
            ${formattaValore(massimo)}
        </span>
    `;
}


/**
 * Statistiche radiazione solare
 *
 * Attuale
 * Media
 * Max
 */
function aggiornaStatisticheSolare(dati) {

    const elemento =
        document.getElementById(
            'solarStats'
        );

    if (!elemento) {
        return;
    }

    const valori =
        datiValidi(
            dati,
            'solar'
        );

    if (valori.length === 0) {

        elemento.textContent =
            'Nessun dato disponibile';

        return;
    }

    const attuale =
        valori[valori.length - 1];

    const massimo =
        Math.max(...valori);

    const somma =
        valori.reduce(
            (totale, valore) =>
                totale + valore,
            0
        );

    const media =
        somma / valori.length;

    elemento.innerHTML = `
        <span class="me-3">
            <strong>Attuale:</strong>
            ${formattaValore(attuale)} W/m²
        </span>

        <span class="me-3">
            <strong>Media:</strong>
            ${formattaValore(media)} W/m²
        </span>

        <span>
            <strong>Max:</strong>
            ${formattaValore(massimo)} W/m²
        </span>
    `;
}


/**
 * Statistiche punto di rugiada
 *
 * Attuale
 * Min
 * Max
 * Media
 */
function aggiornaStatisticheRugiada(dati) {

    aggiornaStatistiche(
        dati,
        'dew',
        'dewStats',
        '°C'
    );
}


/**
 * Statistiche temperatura percepita
 *
 * Attuale
 * Min
 * Max
 * Media
 */
function aggiornaStatistichePercepita(dati) {

    aggiornaStatistiche(
        dati,
        'feels',
        'feelsStats',
        '°C'
    );
}


/**
 * Crea o aggiorna un grafico
 */
async function caricaGrafico(
    canvasId,
    campo,
    titolo,
    colore = '#0d6efd',
    period = '24h'
) {

    try {

        const response = await fetch(
            '/api/history.php?period=' +
            encodeURIComponent(period) +
            '&_=' +
            Date.now()
        );

        if (!response.ok) {

            throw new Error(
                'Errore HTTP ' +
                response.status
            );

        }

        const dati =
            await response.json();

        if (!Array.isArray(dati)) {

            throw new Error(
                'La risposta API non contiene un array.'
            );

        }

        const canvas =
            document.getElementById(
                canvasId
            );

        if (!canvas) {
            return;
        }


        /*
         * L'API restituisce i dati
         * dal più recente al più vecchio.
         *
         * Li invertiamo:
         *
         * passato → presente
         */
        const datiOrdinati =
            [...dati].reverse();


        /*
         * Aggiornamento statistiche
         */
        if (campo === 'temperature') {

            aggiornaStatisticheTemperatura(
                datiOrdinati
            );

        }

        if (campo === 'humidity') {

            aggiornaStatisticheUmidita(
                datiOrdinati
            );

        }

        if (campo === 'pressure') {

            aggiornaStatistichePressione(
                datiOrdinati
            );

        }

        if (campo === 'wind') {

            aggiornaStatisticheVento(
                datiOrdinati
            );

        }

        if (campo === 'rain') {

            aggiornaStatistichePioggia(
                datiOrdinati
            );

        }

        if (campo === 'uv') {

            aggiornaStatisticheUV(
                datiOrdinati
            );

        }

        if (campo === 'solar') {

            aggiornaStatisticheSolare(
                datiOrdinati
            );

        }

        if (campo === 'dew') {

            aggiornaStatisticheRugiada(
                datiOrdinati
            );

        }

        if (campo === 'feels') {

            aggiornaStatistichePercepita(
                datiOrdinati
            );

        }


        /*
         * Costruzione punti grafico
         */
        const punti =
            datiOrdinati
                .map(riga => {

                    const timestamp =
                        timestampData(
                            riga.created_at
                        );

                    return {

                        x: timestamp,

                        y: Number(
                            riga[campo]
                        )

                    };

                })
                .filter(punto =>
                    !isNaN(punto.x) &&
                    !isNaN(punto.y)
                );


        /*
         * Distrugge il grafico precedente
         */
        if (charts[canvasId]) {

            charts[canvasId].destroy();

        }


        const ctx =
            canvas.getContext('2d');


        /*
         * Crea il grafico
         */
        charts[canvasId] =
            new Chart(
                ctx,
                {

                    type: 'line',

                    data: {

                        datasets: [

                            {

                                label: titolo,

                                data: punti,

                                borderColor:
                                    colore,

                                backgroundColor:
                                    colore + '33',

                                borderWidth: 2,

                                tension: 0.35,

                                pointRadius: 0,

                                pointHoverRadius: 4,

                                fill: true

                            }

                        ]

                    },

                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        interaction: {

                            mode: 'nearest',

                            intersect: false

                        },

                        plugins: {

                            legend: {

                                display: true

                            },

                            tooltip: {

                                enabled: true,

                                callbacks: {

                                    title:
                                        function(context) {

                                            const timestamp =
                                                context[0]
                                                    .parsed
                                                    .x;

                                            return formattaTooltip(
                                                timestamp
                                            );

                                        }

                                }

                            }

                        },

                        scales: {

                            x: {

                                type: 'linear',

                                ticks: {

                                    maxTicksLimit:
                                        period === '24h'
                                            ? 8
                                            : period === '7d'
                                                ? 7
                                                : period === '30d'
                                                    ? 10
                                                    : 12,

                                    callback:
                                        function(value) {

                                            return formattaDataAsse(
                                                value,
                                                period
                                            );

                                        }

                                }

                            },

                            y: {

                                beginAtZero: false

                            }

                        }

                    }

                }
            );

    } catch (e) {

        console.error(
            'Errore grafico ' +
            canvasId +
            ':',
            e
        );

    }

}


/**
 * Aggiorna tutti i grafici
 */
function aggiornaGrafici() {

    caricaGrafico(
        'temperatureChart',
        'temperature',
        'Temperatura (°C)',
        '#dc3545',
        periodoAttuale
    );

    caricaGrafico(
        'humidityChart',
        'humidity',
        'Umidità (%)',
        '#0d6efd',
        periodoAttuale
    );

    caricaGrafico(
        'pressureChart',
        'pressure',
        'Pressione (hPa)',
        '#198754',
        periodoAttuale
    );

    caricaGrafico(
        'windChart',
        'wind',
        'Vento (km/h)',
        '#0dcaf0',
        periodoAttuale
    );

    caricaGrafico(
        'rainChart',
        'rain',
        'Pioggia (mm)',
        '#6610f2',
        periodoAttuale
    );

    caricaGrafico(
        'uvChart',
        'uv',
        'Indice UV',
        '#ffc107',
        periodoAttuale
    );

    caricaGrafico(
        'solarChart',
        'solar',
        'Radiazione Solare (W/m²)',
        '#fd7e14',
        periodoAttuale
    );

    caricaGrafico(
        'dewChart',
        'dew',
        'Punto di rugiada (°C)',
        '#20c997',
        periodoAttuale
    );

    caricaGrafico(
        'feelsChart',
        'feels',
        'Temperatura percepita (°C)',
        '#d63384',
        periodoAttuale
    );

}


/**
 * Gestione pulsanti periodo
 */
function inizializzaPeriodi() {

    const buttons =
        document.querySelectorAll(
            '[data-period]'
        );

    buttons.forEach(button => {

        button.addEventListener(
            'click',
            () => {

                periodoAttuale =
                    button.dataset.period;

                buttons.forEach(btn => {

                    btn.classList.remove(
                        'btn-primary'
                    );

                    btn.classList.add(
                        'btn-outline-primary'
                    );

                });

                button.classList.remove(
                    'btn-outline-primary'
                );

                button.classList.add(
                    'btn-primary'
                );

                aggiornaGrafici();

            }
        );

    });

}


/**
 * Avvio della pagina
 */
document.addEventListener(
    'DOMContentLoaded',
    () => {

        console.log(
            'Meteopego Charts avviato.'
        );

        if (
            typeof Chart ===
            'undefined'
        ) {

            console.error(
                'ERRORE: Chart.js non è stato caricato.'
            );

            return;
        }

        inizializzaPeriodi();

        aggiornaGrafici();

    }
);


/**
 * Aggiornamento automatico
 * ogni 60 secondi
 */
setInterval(
    () => {

        if (
            typeof Chart !==
            'undefined'
        ) {

            aggiornaGrafici();

        }

    },
    60000
);