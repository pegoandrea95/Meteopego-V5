<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/config.php';

?>
<!DOCTYPE html>
<html lang="it">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Radar Meteo | <?= htmlspecialchars($config['site']['title']) ?></title>

    <link rel="stylesheet" href="/assets/css/style.css">

    <style>

        body{
            background:#eef5ff;
            margin:0;
            font-family:Arial,Helvetica,sans-serif;
        }

        .container{

            max-width:1400px;

            margin:auto;

            padding:30px;

        }

        h1{

            text-align:center;

            color:#1d4ed8;

            margin-bottom:10px;

        }

        p{

            text-align:center;

            color:#666;

            margin-bottom:30px;

        }

        .radar{

            width:100%;

            height:80vh;

            border-radius:15px;

            overflow:hidden;

            box-shadow:0 8px 30px rgba(0,0,0,.15);

        }

        iframe{

            width:100%;

            height:100%;

            border:none;

        }

    </style>

</head>

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

const map = L.map('radar', {

    center: [45.478, 12.245], // Marghera

    zoom: 8,

    zoomControl: true

});

L.tileLayer(

    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

    {

        attribution: '&copy; OpenStreetMap contributors'

    }

).addTo(map);

</script>

<body>

<div class="container">

    <h1>🛰 Radar Meteo</h1>

    <p>Radar precipitazioni in tempo reale - Marghera (VE)</p>

    <div id="radar" class="radar"></div>

</div>

</body>

</html>