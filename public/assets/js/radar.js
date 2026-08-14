"use strict";

let windyAPI = null;
let map = null;
let marker = null;
let rainViewerLayer = null;

let rainViewerFrames = [];
let rainViewerFrameIndex = 0;
let rainViewerPlaying = false;
let rainViewerPlayTimer = null;
let rainViewerTimelineInitialized = false;

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

    const available =
        windyAPI.store.getAllowed("overlay");

    console.log(
        "Overlay disponibili:",
        available
    );

    document
        .querySelectorAll("[data-overlay]")
        .forEach(button => {

            const overlay =
                button.dataset.overlay;

            /*
             * Nasconde i pulsanti non disponibili
             */

            if (!available.includes(overlay)) {

                button.style.display = "none";

                return;

            }

            button.addEventListener(
                "click",
                () => {

                    changeOverlay(overlay);

                }
            );

        });

    const current =
        windyAPI.store.get("overlay");

    setActiveButton(current);

    initRainViewerButton();

}

/**
 * Cambia overlay
 */
function changeOverlay(name) {

    if (
        !windyAPI.store
            .getAllowed("overlay")
            .includes(name)
    ) {

        console.warn(
            `Overlay "${name}" non disponibile`
        );

        return;

    }

    windyAPI.store.set(
        "overlay",
        name
    );

    setActiveButton(name);

    console.log(
        "Overlay:",
        name
    );

}

/**
 * Evidenzia il pulsante attivo
 */
function setActiveButton(active) {

    document
        .querySelectorAll("[data-overlay]")
        .forEach(button => {

            button.classList.remove("active");

            if (
                button.dataset.overlay ===
                active
            ) {

                button.classList.add("active");

            }

        });

}

/**
 * Pulsante RainViewer
 */
function initRainViewerButton() {

    const button =
        document.getElementById(
            "btnRainViewer"
        );

    if (!button) {

        return;

    }

    button.classList.add("active");

    button.addEventListener(
        "click",
        () => {

            if (!rainViewerLayer) {

                console.warn(
                    "RainViewer non ancora disponibile"
                );

                return;

            }

            if (
                map.hasLayer(
                    rainViewerLayer
                )
            ) {

                /*
                 * Quando RainViewer viene
                 * disattivato, fermiamo anche
                 * l'eventuale animazione.
                 */

                stopRainViewerAnimation();

                map.removeLayer(
                    rainViewerLayer
                );

                button.classList.remove(
                    "active"
                );

                console.log(
                    "🌧 RainViewer disattivato"
                );

            } else {

                rainViewerLayer.addTo(map);

                button.classList.add(
                    "active"
                );

                console.log(
                    "🌧 RainViewer attivato"
                );

            }

        }
    );

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
            "/api/rainviewer.php?" +
            Date.now()
        );

        if (!response.ok) {

            throw new Error(
                `RainViewer HTTP ${response.status}`
            );

        }

        const result =
            await response.json();

        if (result.status !== "ok") {

            throw new Error(
                result.error ||
                "Risposta RainViewer non valida"
            );

        }

        const data =
            result.data;

        if (
            !data ||
            !data.host ||
            !data.radar ||
            !Array.isArray(
                data.radar.past
            ) ||
            data.radar.past.length === 0
        ) {

            throw new Error(
                "Nessun frame radar RainViewer disponibile"
            );

        }

        rainViewerFrames =
            data.radar.past;

        rainViewerFrameIndex =
            rainViewerFrames.length - 1;

        console.log(
            "🌧 Frame RainViewer disponibili:",
            rainViewerFrames.length
        );

        initRainViewerTimeline();

        selectRainViewerFrame(
            data.host,
            rainViewerFrameIndex
        );

    } catch (error) {

        console.error(
            "❌ Errore RainViewer:",
            error
        );

    }

}

/**
 * Inizializza i controlli della timeline
 */
function initRainViewerTimeline() {

    if (
        rainViewerTimelineInitialized
    ) {

        return;

    }

    const slider =
        document.getElementById(
            "radarSlider"
        );

    const prev =
        document.getElementById(
            "radarPrev"
        );

    const next =
        document.getElementById(
            "radarNext"
        );

    const play =
        document.getElementById(
            "radarPlay"
        );

    if (
        !slider ||
        !prev ||
        !next ||
        !play
    ) {

        console.warn(
            "⚠️ Controlli timeline RainViewer non trovati"
        );

        return;

    }

    rainViewerTimelineInitialized =
        true;

    slider.addEventListener(
        "input",
        () => {

            const index =
                Number(
                    slider.value
                );

            stopRainViewerAnimation();

            selectRainViewerFrame(
                null,
                index
            );

        }
    );

    prev.addEventListener(
        "click",
        () => {

            stopRainViewerAnimation();

            const index =
                rainViewerFrameIndex > 0
                    ? rainViewerFrameIndex - 1
                    : rainViewerFrames.length - 1;

            selectRainViewerFrame(
                null,
                index
            );

        }
    );

    next.addEventListener(
        "click",
        () => {

            stopRainViewerAnimation();

            const index =
                rainViewerFrameIndex <
                rainViewerFrames.length - 1
                    ? rainViewerFrameIndex + 1
                    : 0;

            selectRainViewerFrame(
                null,
                index
            );

        }
    );

    play.addEventListener(
        "click",
        () => {

            if (rainViewerPlaying) {

                stopRainViewerAnimation();

            } else {

                startRainViewerAnimation();

            }

        }
    );

}

/**
 * Seleziona un frame RainViewer
 */
function selectRainViewerFrame(
    host,
    index
) {

    if (
        !Array.isArray(
            rainViewerFrames
        ) ||
        rainViewerFrames.length === 0
    ) {

        return;

    }

    if (
        index < 0 ||
        index >= rainViewerFrames.length
    ) {

        return;

    }

    rainViewerFrameIndex =
        index;

    const frame =
        rainViewerFrames[index];

    if (
        !frame ||
        !frame.path
    ) {

        return;

    }

    const currentHost =
        host ||
        window.rainViewerHost;

    if (!currentHost) {

        console.warn(
            "⚠️ Host RainViewer non disponibile"
        );

        return;

    }

    window.rainViewerHost =
        currentHost;

    createRainViewerLayer(
        currentHost,
        frame.path
    );

    updateRainViewerTimeline(
        frame
    );

}

/**
 * Aggiorna slider, orario e informazioni
 * della timeline RainViewer
 */
function updateRainViewerTimeline(
    frame
) {

    const slider =
        document.getElementById(
            "radarSlider"
        );

    const time =
        document.getElementById(
            "radarTime"
        );

    const frameInfo =
        document.getElementById(
            "radarFrameInfo"
        );

    const relativeTime =
        document.getElementById(
            "radarRelativeTime"
        );

    /*
     * Aggiorna slider
     */

    if (slider) {

        slider.min = 0;

        slider.max =
            Math.max(
                rainViewerFrames.length - 1,
                0
            );

        slider.value =
            rainViewerFrameIndex;

    }

    /*
     * Numero di frame disponibili
     */

    if (frameInfo) {

        const totalFrames =
            rainViewerFrames.length;

        frameInfo.textContent =
            `${totalFrames} frame`;

    }

    /*
     * Aggiorna ora del frame
     */

    if (
        time &&
        frame &&
        frame.time
    ) {

        const date =
            new Date(
                frame.time * 1000
            );

        time.textContent =
            date.toLocaleTimeString(
                "it-IT",
                {
                    hour: "2-digit",
                    minute: "2-digit"
                }
            );

    }

    /*
     * Calcola la distanza temporale
     * del frame rispetto all'ultimo
     * frame disponibile.
     */

    if (
        relativeTime &&
        frame &&
        frame.time &&
        rainViewerFrames.length > 0
    ) {

        const lastFrame =
            rainViewerFrames[
                rainViewerFrames.length - 1
            ];

        if (
            lastFrame &&
            lastFrame.time
        ) {

            const differenceMinutes =
                Math.round(
                    (
                        lastFrame.time -
                        frame.time
                    ) / 60
                );

            if (
                differenceMinutes <= 0
            ) {

                relativeTime.textContent =
                    "ORA";

            } else {

                relativeTime.textContent =
                    `−${differenceMinutes} min`;

            }

        }

    }

}

/**
 * Avvia animazione RainViewer
 */
function startRainViewerAnimation() {

    if (
        !rainViewerFrames ||
        rainViewerFrames.length < 2
    ) {

        return;

    }

    if (rainViewerPlaying) {

        return;

    }

    /*
     * Se il layer è stato disattivato,
     * lo riattiviamo prima di avviare
     * l'animazione.
     */

    if (
        rainViewerLayer &&
        !map.hasLayer(rainViewerLayer)
    ) {

        rainViewerLayer.addTo(map);

        const button =
            document.getElementById(
                "btnRainViewer"
            );

        if (button) {

            button.classList.add(
                "active"
            );

        }

    }

    rainViewerPlaying =
        true;

    updateRainViewerPlayButton();

    rainViewerPlayTimer =
        setInterval(
            () => {

                let nextIndex =
                    rainViewerFrameIndex + 1;

                if (
                    nextIndex >=
                    rainViewerFrames.length
                ) {

                    nextIndex = 0;

                }

                selectRainViewerFrame(
                    null,
                    nextIndex
                );

            },
            700
        );

}

/**
 * Ferma animazione RainViewer
 */
function stopRainViewerAnimation() {

    rainViewerPlaying =
        false;

    if (rainViewerPlayTimer) {

        clearInterval(
            rainViewerPlayTimer
        );

        rainViewerPlayTimer =
            null;

    }

    updateRainViewerPlayButton();

}

/**
 * Aggiorna pulsante Play/Pausa
 */
function updateRainViewerPlayButton() {

    const play =
        document.getElementById(
            "radarPlay"
        );

    if (!play) {

        return;

    }

    play.textContent =
        rainViewerPlaying
            ? "⏸ Pausa"
            : "▶ Play";

}

/**
 * Crea layer RainViewer
 */
function createRainViewerLayer(
    host,
    path
) {

    const tileUrl =
        `${host}${path}/256/{z}/{x}/{y}/2/1_1.png`;

    console.log(
        "🌧 RainViewer tile URL:",
        tileUrl
    );

    const layerWasVisible =
        rainViewerLayer &&
        map.hasLayer(
            rainViewerLayer
        );

    if (rainViewerLayer) {

        map.removeLayer(
            rainViewerLayer
        );

    }

    rainViewerLayer =
        L.tileLayer(
            tileUrl,
            {

                opacity: 0.65,

                maxZoom: 7,

                attribution:
                    'Weather data by <a href="https://www.rainviewer.com/" target="_blank" rel="noopener">RainViewer</a>'

            }
        );

    if (
        !rainViewerLayer ||
        !map
    ) {

        return;

    }

    /*
     * Il primo frame viene mostrato
     * automaticamente.
     *
     * Nei cambi frame successivi
     * manteniamo lo stato ON/OFF
     * del pulsante Pioggia.
     */

    if (
        layerWasVisible ||
        !window.rainViewerInitialized
    ) {

        rainViewerLayer.addTo(map);

        window.rainViewerInitialized =
            true;

    }

    window.rainViewerLayer =
        rainViewerLayer;

    const button =
        document.getElementById(
            "btnRainViewer"
        );

    if (
        button &&
        map.hasLayer(
            rainViewerLayer
        )
    ) {

        button.classList.add(
            "active"
        );

    } else if (button) {

        button.classList.remove(
            "active"
        );

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