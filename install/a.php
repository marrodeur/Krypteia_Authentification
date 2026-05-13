<?php

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
    ? 'https://' 
    : 'http://';

$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// chemin sans /install/xxx.php
$path = dirname(dirname($_SERVER['SCRIPT_NAME']));

$path = str_replace('\\', '/', $path);

// construit URL propre
$url = rtrim($protocol . $host . $path, '/') . '/';

echo htmlspecialchars($url);