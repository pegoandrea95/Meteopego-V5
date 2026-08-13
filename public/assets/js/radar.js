"use strict";

let windyAPI = null;
let map = null;
let marker = null;
let rainViewerLayer = null;

delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({

    iconRetinaUrl: "/assets/images/leaflet/marker-icon-2x.png",

    iconUrl: "/assets/images/leaflet/marker-icon.png",

    shadowUrl: "/assets/images/leaflet/marker-shadow.png"

});

/**
 * Inizializza la mappa Windy
 */
document.addEventListener("DOMContentLoaded", () => {

    windyInit({
        key: window.METEOPEGO.windyKey,
        lat: window.METEOPEGO.latitude,
        lon: window.METEOPEGO.longitude,
        zoom: window.METEOPEGO.zoom

    }, api => {

        windyAPI = api;
        window.windyAPI = api;

        map = api.map;

        console.log("✅ Meteopego Radar avviato");

        createMarker();

        initToolbar();

        initRainViewer();

        loadWeather();

        startAutoRefresh();

    });

});

/**
 * Crea il marker della stazione
 */
function createMarker() {

    marker = L.marker([
        window.METEOPEGO.latitude,
        window.METEOPEGO.longitude
    ]).addTo(map);

}

/**
 * Carica i dati meteo
 */
async function loadWeather() {

    try {

        const weather = await getCurrentWeather();

        updatePopup(weather);

    } catch (error) {

        console.error(error);

    }

}

/**
 * Aggiorna popup marker
 */
function updatePopup(weather) {

    if (!marker) return;

    marker.bindPopup(`
        <strong>${window.METEOPEGO.stationName}</strong>
        <hr>
        🌡 ${weather.temperature} °C<br>
        💧 ${weather.humidity} %<br>
        🌬 ${weather.wind} km/h<br>
        🧭 ${weather.pressure} hPa
    `);

}

/**
 * Toolbar overlay
 */
function initToolbar() {

    const available = windyAPI.store.getAllowed("overlay");

    console.log("Overlay disponibili:", available);

    document
        .querySelectorAll("[data-overlay]")
        .forEach(button => {

            const overlay = button.dataset.overlay;

            /*
             * Nasconde i pulsanti non disponibili
             */

            if (!available.includes(overlay)) {

                button.style.display = "none";
                return;

            }

            button.addEventListener("click", () => {

                changeOverlay(overlay);

            });

        });

    const current = windyAPI.store.get("overlay");

    setActiveButton(current);

    initRainViewerButton();

}

/**
 * Cambia overlay
 */
function changeOverlay(name) {

    if (!windyAPI.store.getAllowed("overlay").includes(name)) {

        console.warn(`Overlay "${name}" non disponibile`);

        return;

    }

    windyAPI.store.set("overlay", name);

    setActiveButton(name);

    console.log("Overlay:", name);

}

/**
 * Evidenzia il pulsante attivo
 */
function setActiveButton(active) {

    document
        .querySelectorAll("[data-overlay]")
        .forEach(button => {

            button.classList.remove("active");

            if (button.dataset.overlay === active) {

                button.classList.add("active");

            }

        });

}

/**
 * Pulsante RainViewer
 */
function initRainViewerButton() {

    const button =
        document.getElementById("btnRainViewer");

    if (!button) {
        return;
    }

    button.classList.add("active");

    button.addEventListener("click", () => {

        if (!rainViewerLayer) {

            console.warn(
                "RainViewer non ancora disponibile"
            );

            return;

        }

        if (map.hasLayer(rainViewerLayer)) {

            map.removeLayer(rainViewerLayer);

            button.classList.remove("active");

            console.log(
                "🌧 RainViewer disattivato"
            );

        } else {

            rainViewerLayer.addTo(map);

            button.classList.add("active");

            console.log(
                "🌧 RainViewer attivato"
            );

        }

    });

}

/**
 * Inizializza RainViewer
 */
async function initRainViewer() {

    try {

        console.log(
            "🌧 Caricamento RainViewer..."
        );

        const response = await fetch(
            "/api/rainviewer.php?" + Date.now()
        );

        if (!response.ok) {

            throw new Error(
                `RainViewer HTTP ${response.status}`
            );

        }

        const result = await response.json();

        if (result.status !== "ok") {

            throw new Error(
                result.error ||
                "Risposta RainViewer non valida"
            );

        }

        const data = result.data;

        if (
            !data ||
            !data.host ||
            !data.radar ||
            !Array.isArray(data.radar.past) ||
            data.radar.past.length === 0
        ) {

            throw new Error(
                "Nessun frame radar RainViewer disponibile"
            );

        }

        const lastFrame =
            data.radar.past[
                data.radar.past.length - 1
            ];

        console.log(
            "🌧 Ultimo frame RainViewer:",
            lastFrame
        );

        createRainViewerLayer(
            data.host,
            lastFrame.path
        );

    } catch (error) {

        console.error(
            "❌ Errore RainViewer:",
            error
        );

    }

}

/**
 * Crea layer RainViewer
 */
function createRainViewerLayer(host, path) {

    const tileUrl =
        `${host}${path}/256/{z}/{x}/{y}/2/1_1.png`;

    console.log(
        "🌧 RainViewer tile URL:",
        tileUrl
    );

    if (rainViewerLayer) {

        map.removeLayer(rainViewerLayer);

    }

    rainViewerLayer = L.tileLayer(
        tileUrl,
        {

            opacity: 0.65,

            maxZoom: 7,

            attribution:
                'Weather data by <a href="https://www.rainviewer.com/" target="_blank" rel="noopener">RainViewer</a>'

        }
    );

    rainViewerLayer.addTo(map);

    window.rainViewerLayer = rainViewerLayer;

    const button =
        document.getElementById("btnRainViewer");

    if (button) {

        button.classList.add("active");

    }

    console.log(
        "✅ Layer RainViewer aggiunto"
    );

}

/**
 * Refresh automatico dati
 */
function startAutoRefresh() {

    setInterval(
        loadWeather,
        60000
    );

    setInterval(
        initRainViewer,
        300000
    );

}