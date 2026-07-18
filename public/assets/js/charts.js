let charts = {};

/**
 * Crea o aggiorna un grafico
 */
async function caricaGrafico(canvasId, campo, titolo, colore = '#0d6efd') {

    try {

        const response = await fetch('/api/history.php?hours=24');

        const dati = await response.json();

        const labels = [];
        const values = [];

        dati.forEach(riga => {

            labels.push(riga.created_at.substring(11, 16));
            values.push(Number(riga[campo]));

        });

        const ctx = document
            .getElementById(canvasId)
            .getContext('2d');

        if (charts[canvasId]) {
            charts[canvasId].destroy();
        }

        charts[canvasId] = new Chart(ctx, {

            type: 'line',

            data: {

                labels,

                datasets: [{

                    label: titolo,

                    data: values,

                    borderColor: colore,

                    backgroundColor: colore + '33',

                    borderWidth: 2,

                    tension: 0.35,

                    pointRadius: 0,

                    fill: true

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        display: true

                    }

                },

                scales: {

                    x: {

                        ticks: {

                            maxTicksLimit: 8

                        }

                    },

                    y: {

                        beginAtZero: false

                    }

                }

            }

        });

    } catch (e) {

        console.error("Errore grafico:", e);

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
        '#dc3545'
    );

    // Futuri grafici:
    //
    // caricaGrafico('humidityChart', 'humidity', 'Umidità (%)', '#0d6efd');
    // caricaGrafico('pressureChart', 'pressure', 'Pressione (hPa)', '#198754');
    // caricaGrafico('windChart', 'wind', 'Vento (km/h)', '#0dcaf0');
    // caricaGrafico('rainChart', 'rain', 'Pioggia (mm)', '#6610f2');

}

aggiornaGrafici();