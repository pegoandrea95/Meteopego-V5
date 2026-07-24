"use strict";

document.addEventListener("DOMContentLoaded", () => {

    const config = window.METEOPEGO;

    const map = L.map("map", {

    center: [45.478, 12.245],

    zoom: 8,

    zoomControl: true

});

    L.tileLayer(
        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
            attribution: "&copy; OpenStreetMap contributors",
            maxZoom: 19
        }
    ).addTo(map);

    L.marker([
        config.latitude,
        config.longitude
    ])
    .addTo(map)
    .bindPopup("<b>Meteopego Stazione</b><br>Marghera (VE)");

    /*
     * IMPORTANTISSIMO
     */

    setTimeout(() => {

        map.invalidateSize(true);

    }, 100);

    window.addEventListener("resize", () => {

        map.invalidateSize(true);

    });

});