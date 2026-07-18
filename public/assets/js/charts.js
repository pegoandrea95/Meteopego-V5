async function caricaGraficoTemperatura() {

    try {

        const response = await fetch('/api/history.php');

        const dati = await response.json();

        const labels = [];
        const temperature = [];

        dati.reverse().forEach(riga => {

            labels.push(riga.created_at.substring(11,16));

            temperature.push(riga.temperature);

        });

        const ctx = document
            .getElementById('temperatureChart')
            .getContext('2d');

        new Chart(ctx, {

            type: 'line',

            data: {

                labels: labels,

                datasets: [{

                    label: 'Temperatura °C',

                    data: temperature,

                    tension: .4,

                    fill: true

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false

            }

        });

    }
    catch(e){

        console.error(e);

    }

}

caricaGraficoTemperatura();