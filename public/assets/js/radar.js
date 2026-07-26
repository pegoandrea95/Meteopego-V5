"use strict";

document.addEventListener("DOMContentLoaded", () => {

    const config = window.METEOPEGO;

    windyInit({

        key: config.windyKey,
        lat: config.latitude,
        lon: config.longitude,
        zoom: config.zoom

    }, (windyAPI) => {

        console.log("✅ Windy inizializzato");

        const { map, store } = windyAPI;

        // Salva l'istanza globalmente (utile per debug e future funzioni)
        window.windyAPI = windyAPI;

        /*
        |--------------------------------------------------------------------------
        | Marker Meteopego
        |--------------------------------------------------------------------------
        */

        const marker = L.marker([
            config.latitude,
            config.longitude
        ]).addTo(map);

        marker.bindPopup(`
            <strong>📍 Meteopego</strong><br>
            Marghera (VE)
        `);

        /*
        |--------------------------------------------------------------------------
        | Cambio Overlay
        |--------------------------------------------------------------------------
        */

        function changeOverlay(layer) {

            console.log("Cambio overlay:", layer);

            store.set("overlay", layer);

            // Evidenzia il pulsante attivo
            document.querySelectorAll(".toolbar button").forEach(button => {
                button.classList.remove("active");
            });

            const activeButton = document.querySelector(`[data-layer="${layer}"]`);

            if (activeButton) {
                activeButton.classList.add("active");
            }

        }

        /*
        |--------------------------------------------------------------------------
        | Toolbar
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll("[data-layer]").forEach(button => {

            button.addEventListener("click", () => {

                changeOverlay(button.dataset.layer);

            });

        });

        // Overlay iniziale
        changeOverlay("wind");

    });

});