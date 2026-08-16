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
/**
 * Pulsante modalità Precipitazioni
 */
function initRainViewerButton() {

    const button =
        document.getElementById(
            "btnRainViewer"
        );

    const radarMode =
        document.getElementById(
            "radarMode"
        );

    const radarTimeline =
        document.getElementById(
            "radarTimeline"
        );

    const radarLegend =
        document.getElementById(
            "radarLegend"
        );

    if (!button) {

        return;

    }

    /*
     * Stato iniziale:
     * modalità precipitazioni disattivata.
     */

    window.rainViewerMode =
        false;

    setRainViewerMode(
        false,
        button,
        radarMode,
        radarTimeline,
        radarLegend
    );

    button.addEventListener(
        "click",
        () => {

            if (!rainViewerLayer) {

                console.warn(
                    "RainViewer non ancora disponibile"
                );

                return;

            }

            setRainViewerMode(
                !window.rainViewerMode,
                button,
                radarMode,
                radarTimeline,
                radarLegend
            );

        }
    );

}

/**
 * Attiva/disattiva la modalità
 * radar precipitazioni.
 */
function setRainViewerMode(
    enabled,
    button,
    radarMode,
    radarTimeline,
    radarLegend
) {

    window.rainViewerMode =
        enabled;

    if (enabled) {

        if (rainViewerLayer) {

            rainViewerLayer.addTo(map);

        }

        if (radarMode) {

            radarMode.hidden = false;

        }

        if (radarTimeline) {

            radarTimeline.hidden = false;

        }

        if (radarLegend) {

            radarLegend.hidden = false;

        }

        if (button) {

            button.classList.add(
                "active"
            );

            button.setAttribute(
                "aria-pressed",
                "true"
            );

            button.innerHTML =
                "🌧️ Precipitazioni";

        }

        console.log(
            "🌧️ Modalità precipitazioni attivata"
        );

    } else {

        stopRainViewerAnimation();

        if (
            rainViewerLayer &&
            map.hasLayer(
                rainViewerLayer
            )
        ) {

            map.removeLayer(
                rainViewerLayer
            );

        }

        if (radarMode) {

            radarMode.hidden = true;

        }

        if (radarTimeline) {

            radarTimeline.hidden = true;

        }

        if (radarLegend) {

            radarLegend.hidden = true;

        }

        if (button) {

            button.classList.remove(
                "active"
            );

            button.setAttribute(
                "aria-pressed",
                "false"
            );

            button.innerHTML =
                "🌧️ Pioggia";

        }

        console.log(
            "🌧️ Modalità precipitazioni disattivata"
        );

    }

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

        /*
         * Frame radar osservati
         */

        const pastFrames =
            data.radar.past;

        /*
         * Frame nowcast.
         *
         * RainViewer può restituire
         * un array vuoto quando il nowcast
         * non è disponibile.
         */

        const nowcastFrames =
            Array.isArray(
                data.radar.nowcast
            )
                ? data.radar.nowcast
                : [];

        /*
         * Manteniamo separati i due gruppi.
         */

        window.rainViewerPastFrames =
            pastFrames;

        window.rainViewerNowcastFrames =
            nowcastFrames;

        /*
         * Per ora la timeline utilizza
         * tutti i frame disponibili.
         *
         * Se il nowcast è vuoto:
         *
         * past + []
         *
         * quindi il comportamento rimane
         * esattamente quello attuale.
         */

        rainViewerFrames = [
            ...pastFrames,
            ...nowcastFrames
        ];

        /*
         * Partiamo dall'ultimo frame osservato,
         * non dall'ultimo frame nowcast.
         */

        rainViewerFrameIndex =
            pastFrames.length - 1;

        console.log(
            "🌧 Frame radar osservati:",
            pastFrames.length
        );

        console.log(
            "🔮 Frame nowcast:",
            nowcastFrames.length
        );

        console.log(
            "🌧 Totale frame RainViewer:",
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

    const labels =
        document.querySelector(
            ".radar-timeline-labels"
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
     * Numero totale di frame
     */

    if (frameInfo) {

        frameInfo.textContent =
            `${rainViewerFrames.length} frame`;

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
     * Individua l'ultimo frame osservato.
     */

    const pastFrames =
        Array.isArray(
            window.rainViewerPastFrames
        )
            ? window.rainViewerPastFrames
            : [];

    const nowcastFrames =
        Array.isArray(
            window.rainViewerNowcastFrames
        )
            ? window.rainViewerNowcastFrames
            : [];

    const lastPastFrame =
        pastFrames.length > 0
            ? pastFrames[
                pastFrames.length - 1
            ]
            : null;

    /*
     * Calcola la posizione temporale
     * del frame selezionato.
     */

    if (
        relativeTime &&
        frame &&
        frame.time
    ) {

        if (
            nowcastFrames.length > 0 &&
            lastPastFrame &&
            lastPastFrame.time &&
            frame.time >
            lastPastFrame.time
        ) {

            const futureMinutes =
                Math.max(
                    0,
                    Math.round(
                        (
                            frame.time -
                            lastPastFrame.time
                        ) / 60
                    )
                );

            relativeTime.textContent =
                `+${futureMinutes} min`;

        } else if (
            lastPastFrame &&
            lastPastFrame.time
        ) {

            const differenceMinutes =
                Math.max(
                    0,
                    Math.round(
                        (
                            lastPastFrame.time -
                            frame.time
                        ) / 60
                    )
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

    /*
     * Aggiorna le etichette della timeline.
     */

    if (
        labels &&
        rainViewerFrames.length > 0
    ) {

        labels.innerHTML = "";

        /*
         * Se non esiste nowcast,
         * manteniamo la timeline attuale:
         * cinque punti distribuiti sui frame reali.
         */

        if (
            nowcastFrames.length === 0
        ) {

            const total =
                pastFrames.length;

            const points =
                Math.min(
                    5,
                    total
                );

            const indices = [];

            for (
                let i = 0;
                i < points;
                i++
            ) {

                const index =
                    points === 1
                        ? 0
                        : Math.round(
                            (
                                i *
                                (total - 1)
                            ) /
                            (points - 1)
                        );

                if (
                    !indices.includes(
                        index
                    )
                ) {

                    indices.push(
                        index
                    );

                }

            }

            indices.forEach(
                (
                    index,
                    position
                ) => {

                    const item =
                        pastFrames[
                            index
                        ];

                    const span =
                        document.createElement(
                            "span"
                        );

                    let text =
                        "--";

                    if (
                        item &&
                        item.time &&
                        lastPastFrame &&
                        lastPastFrame.time
                    ) {

                        const differenceMinutes =
                            Math.max(
                                0,
                                Math.round(
                                    (
                                        lastPastFrame.time -
                                        item.time
                                    ) / 60
                                )
                            );

                        text =
                            differenceMinutes <= 0
                                ? "ORA"
                                : `−${differenceMinutes} min`;

                    }

                    span.textContent =
                        text;

                    if (
                        position ===
                        indices.length - 1
                    ) {

                        span.classList.add(
                            "current"
                        );

                    }

                    labels.appendChild(
                        span
                    );

                }
            );

            return;

        }

        /*
         * Con nowcast disponibile:
         * costruiamo una timeline composta
         * da passato + ORA + futuro.
         *
         * ORA viene sempre inserita esplicitamente
         * come punto centrale della transizione.
         */

        const timelineFrames = [
            ...pastFrames,
            ...nowcastFrames
        ];

        const total =
            timelineFrames.length;

        const nowIndex =
            pastFrames.length - 1;

        /*
         * Selezioniamo alcuni punti del passato
         * e alcuni del futuro, mantenendo sempre
         * visibile il punto ORA.
         */

        const selectedIndices = new Set();

        selectedIndices.add(
            nowIndex
        );

        /*
         * Quattro punti rappresentativi
         * del passato.
         */

        const pastPoints =
            Math.min(
                4,
                pastFrames.length
            );

        for (
            let i = 0;
            i < pastPoints;
            i++
        ) {

            const index =
                pastPoints === 1
                    ? 0
                    : Math.round(
                        (
                            i *
                            nowIndex
                        ) /
                        (pastPoints - 1)
                    );

            selectedIndices.add(
                index
            );

        }

        /*
         * Quattro punti rappresentativi
         * del nowcast.
         */

        const futureCount =
            nowcastFrames.length;

        const futurePoints =
            Math.min(
                4,
                futureCount
            );

        for (
            let i = 0;
            i < futurePoints;
            i++
        ) {

            const futureOffset =
                futurePoints === 1
                    ? futureCount
                    : Math.round(
                        (
                            (i + 1) *
                            futureCount
                        ) /
                        futurePoints
                    );

            const index =
                nowIndex +
                Math.min(
                    futureOffset,
                    futureCount
                );

            if (
                index <
                total
            ) {

                selectedIndices.add(
                    index
                );

            }

        }

        const indices =
            Array.from(
                selectedIndices
            ).sort(
                (a, b) =>
                    a - b
            );

        indices.forEach(
            (
                index
            ) => {

                const item =
                    timelineFrames[
                        index
                    ];

                const span =
                    document.createElement(
                        "span"
                    );

                let text =
                    "--";

                if (
                    item &&
                    item.time &&
                    lastPastFrame &&
                    lastPastFrame.time
                ) {

                    const differenceMinutes =
                        Math.round(
                            (
                                item.time -
                                lastPastFrame.time
                            ) / 60
                        );

                    if (
                        differenceMinutes > 0
                    ) {

                        text =
                            `+${differenceMinutes} min`;

                        span.classList.add(
                            "nowcast"
                        );

                    } else if (
                        differenceMinutes === 0
                    ) {

                        text =
                            "ORA";

                        span.classList.add(
                            "current"
                        );

                    } else {

                        text =
                            `−${Math.abs(
                                differenceMinutes
                            )} min`;

                    }

                }

                span.textContent =
                    text;

                labels.appendChild(
                    span
                );

            }
        );
    }

}

/**
 * Avvia animazione RainViewer
 */
/**
 * Avvia animazione RainViewer
 */
function startRainViewerAnimation() {

    if (
        !window.rainViewerMode
    ) {

        console.warn(
            "🌧️ Modalità precipitazioni non attiva"
        );

        return;

    }

    if (
        !rainViewerFrames ||
        rainViewerFrames.length < 2
    ) {

        return;

    }

    if (rainViewerPlaying) {

        return;

    }

    if (
        rainViewerLayer &&
        !map.hasLayer(
            rainViewerLayer
        )
    ) {

        rainViewerLayer.addTo(map);

    }

    rainViewerPlaying =
        true;

    updateRainViewerPlayButton();

    rainViewerPlayTimer =
        setInterval(
            () => {

                if (
                    !window.rainViewerMode
                ) {

                    stopRainViewerAnimation();

                    return;

                }

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
     * Mostriamo il nuovo frame soltanto
     * quando la modalità precipitazioni
     * è realmente attiva.
     *
     * Se la modalità è ON e stavamo già
     * mostrando RainViewer, manteniamo
     * la visualizzazione durante il cambio
     * frame.
     */

    if (
        window.rainViewerMode &&
        (
            layerWasVisible ||
            !window.rainViewerInitialized
        )
    ) {

        rainViewerLayer.addTo(map);

    }

    window.rainViewerInitialized =
        true;

    window.rainViewerLayer =
        rainViewerLayer;

    const button =
        document.getElementById(
            "btnRainViewer"
        );

    if (button) {

        button.classList.toggle(
            "active",
            !!window.rainViewerMode
        );

        button.setAttribute(
            "aria-pressed",
            window.rainViewerMode
                ? "true"
                : "false"
        );

    }

    console.log(
        "✅ Layer RainViewer aggiornato"
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