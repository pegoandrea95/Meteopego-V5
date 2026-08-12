<?php
$config = include __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/Helpers/Url.php';

?>
<!DOCTYPE html>

<html lang="it">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= htmlspecialchars($config['site']['title']) ?></title>

    <meta name="description" content="Stazione Meteorologica di Marghera (VE) - Meteopego">

    <meta name="theme-color" content="#0d6efd">

    <link rel="icon" href="<?= asset('assets/images/favicon.ico') ?>">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">

    <div class="container">

        <a class="navbar-brand fw-bold" href="<?= url() ?>">

            <i class="bi bi-cloud-sun-fill"></i>

            Meteopego Stazione

        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbar">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbar">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link <?= active('index.php') ?>"
                       aria-current="<?= active('index.php') ? 'page' : '' ?>"
                       href="<?= url() ?>">
                        <i class="bi bi-house-door"></i>
                        Home
                    </a>

                </li>

                <li class="nav-item">
                    <a class="nav-link <?= active('graphs.php') ?>"
                       aria-current="<?= active('graphs.php') ? 'page' : '' ?>"
                       href="<?= url('graphs.php') ?>">
                        <i class="bi bi-graph-up"></i>
                        Grafici
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= active('archivio.php') ?>"
                       aria-current="<?= active('archivio.php') ? 'page' : '' ?>"
                       href="<?= url('archivio.php') ?>">
                        <i class="bi bi-calendar3"></i>
                        Archivio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= active('webcam.php') ?>"
                       aria-current="<?= active('webcam.php') ? 'page' : '' ?>"
                       href="<?= url('webcam.php') ?>">
                        <i class="bi bi-camera-video"></i>
                        Webcam
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= active('radar.php') ?>"
                       aria-current="<?= active('radar.php') ? 'page' : '' ?>"
                       href="<?= url('radar.php') ?>">
                        <i class="bi bi-radar"></i>
                        Radar
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= active('stazione.php') ?>"
                       aria-current="<?= active('stazione.php') ? 'page' : '' ?>"
                       href="<?= url('stazione.php') ?>">
                        <i class="bi bi-info-circle"></i>
                        Stazione
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav