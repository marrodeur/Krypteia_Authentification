<?php

require_once __DIR__ . '/../lib/auth.php';

startSecureSession();

$user = getCurrentUser();

$logFile = __DIR__ . '/../logs/log_result.txt';

// ✅ créer fichier si absent
if (!file_exists($logFile)) {
    file_put_contents($logFile, '');
}

// ✅ récupération infos
$sep = ' ; ';

$date   = date('Y-m-d H:i:s');
$ip     = $_SERVER['REMOTE_ADDR'] ?? 'NC';
$host   = $_SERVER['REMOTE_HOST'] ?? 'NC';
$refer  = $_SERVER['HTTP_REFERER'] ?? 'NC';
$page   = $_SERVER['REQUEST_URI'] ?? 'NC';
$method = $_SERVER['REQUEST_METHOD'] ?? 'NC';
$pseudo = $user['pseudo'] ?? 'guest';

// sécurisation (éviter injection dans log)
function cleanLog($value) {
    return str_replace(["\n", "\r", ";"], ' ', $value);
}

// construction ligne
$line = implode($sep, [
    $date,
    cleanLog($host),
    cleanLog($ip),
    cleanLog($refer),
    cleanLog($page),
    cleanLog($pseudo),
    cleanLog($method)
]) . PHP_EOL;


// écriture sécurisée
file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);


// redirection
header('Location: index.php');
exit;