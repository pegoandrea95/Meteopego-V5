<?php
$config = include __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="it">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= $config['site']['name']; ?></title>

<link rel="stylesheet" href="/assets/css/style.css">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

</head>

<body>

<header>

<div class="container">

<div class="logo">

🌪 <strong>METEOPEGO</strong>

<span>STAZIONE</span>

</div>

<nav>

<a href="/">Home</a>

<a href="/grafici.php">Grafici</a>

<a href="/archivio.php">Archivio</a>

<a href="/stazione.php">Stazione</a>

<a href="/radar.php">Radar</a>

</nav>

</div>

</header>