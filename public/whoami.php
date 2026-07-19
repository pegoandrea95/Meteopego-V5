<?php

echo "<pre>";

echo "Utente PHP: " . trim(shell_exec('whoami')) . PHP_EOL;

echo PHP_EOL;

echo "UID/GID:" . PHP_EOL;

system('id');

echo PHP_EOL;

echo "Percorso DB:" . PHP_EOL;

echo realpath(__DIR__ . '/../storage/database');

echo PHP_EOL;

echo PHP_EOL;

echo "Permessi cartella:" . PHP_EOL;

system('ls -ld ' . escapeshellarg(__DIR__ . '/../storage/database'));

echo PHP_EOL;

echo "Permessi file:" . PHP_EOL;

system('ls -l ' . escapeshellarg(__DIR__ . '/../storage/database'));

echo "</pre>";