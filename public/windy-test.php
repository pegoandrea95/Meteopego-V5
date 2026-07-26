<?php

$config = require dirname(__DIR__) . '/config.php';

$windy = $config['windy'];
?>
<!DOCTYPE html>
<html lang="it">

<head>

<meta charset="utf-8">

<title>Windy Test</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

html,
body,
#windy {
    width:100%;
    height:100%;
    margin:0;
    padding:0;
}

</style>

<link rel="stylesheet"
href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css">

</head>

<body>

<div id="windy"></div>

<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>

<script src="https://api.windy.com/assets/map-forecast/libBoot.js"></script>

<script>

const options = {

    key: "<?= htmlspecialchars($windy['api_key']) ?>",

    lat: <?= $windy['latitude'] ?>,

    lon: <?= $windy['longitude'] ?>,

    zoom: <?= $windy['zoom'] ?>

};

windyInit(options, function (windyAPI) {

    console.log("Windy caricato!");

});

</script>

</body>

</html>