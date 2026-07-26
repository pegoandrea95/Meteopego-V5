"use strict";

document.addEventListener("DOMContentLoaded", () => {

    console.clear();

    console.log("1 - JS caricato");

    console.log("2 - windyInit =", typeof windyInit);

    try {

        windyInit({

            key: window.METEOPEGO.windyKey,

            lat: window.METEOPEGO.latitude,

            lon: window.METEOPEGO.longitude,

            zoom: window.METEOPEGO.zoom

        }, function (windyAPI) {

            console.log("3 - CALLBACK ESEGUITO");

            console.log(windyAPI);

        });

    } catch (e) {

        console.error("ERRORE:", e);

    }

});