<?php

require_once __DIR__ . '/../lib/db.php';

function loadConfig(): array
{
    $pdo = getPDO();

    $stmt = $pdo->query("SELECT type, nom, valeur FROM config ORDER BY type, nom ASC");

    $cfg = [];

    while ($row = $stmt->fetch()) {
        $cfg[$row['type']][$row['nom']] = $row['valeur'];
    }

    return $cfg;
}

// Chargement
$cfg = loadConfig();


// constantes (compatibilité avec ton ancien code)
define("URL_AUTH",  $cfg['path']['url_auth']   ?? '');
define("URL_REDIR", $cfg['path']['url_redir']  ?? 'index.php');
define("URL_CREATE",$cfg['path']['url_create'] ?? 'user_create.php');
define("URL_LOGIN", $cfg['path']['url_login']  ?? 'user_login.php');
define("URL_LOGOUT",$cfg['path']['url_logout'] ?? 'user_login.php?action=logout');
define("URL_MODIF", $cfg['path']['url_modif']  ?? 'user_update.php');


// helper propre (optionnel mais utile)
function config(string $type, string $key, $default = null)
{
    global $cfg;
    return $cfg[$type][$key] ?? $default;
}
?>