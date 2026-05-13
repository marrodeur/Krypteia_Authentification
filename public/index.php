<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/functions.php';

// Vérification installation
if (!file_exists(__DIR__ . '/../config/config.php')) {
    header('Location: install/index.php');
    exit;
}

// Vérification utilisateur
requireLogin(1, 'user_login.php');

// Récup user sécurisé
$user = getCurrentUser();

$pageTitle = "Dashboard";

require __DIR__ . '/../views/header.php';
require __DIR__ . '/../views/dashboard.php';
require __DIR__ . '/../views/footer.php';
?>