"use strict";

/*
|--------------------------------------------------------------------------
| Meteopego API
|--------------------------------------------------------------------------
*/

async function getCurrentWeather() {

    const response = await fetch("/api/current.php", {
        cache: "no-store"
    });

    if (!response.ok) {
        throw new Error("Errore caricamento dati meteo");
    }

    return await response.json();

}