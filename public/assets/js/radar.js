"use strict";

let windyAPI = null;
let map = null;
let marker = null;

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
 * Refresh automatico dati
 */
function startAutoRefresh() {

    setInterval(loadWeather, 60000);

}