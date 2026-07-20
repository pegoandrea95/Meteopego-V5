<?php

header('Content-Type: text/plain');

echo "===== GET =====\n";
print_r($_GET);

echo "\n===== POST =====\n";
print_r($_POST);

echo "\n===== RAW =====\n";
echo file_get_contents('php://input');