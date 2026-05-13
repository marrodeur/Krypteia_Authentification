<?php
require_once __DIR__ . '/../lib/auth.php';
startSecureSession();

$user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin') ?></title>

    /assets/style.css

    <!-- Font Awesome -->
    https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css
</head>

<body>

<header class="admin-header">

    <h1>
        <i class="fa-solid fa-shield-halved"></i>
        Administration
    </h1>

    <nav class="admin-nav">

        ../index.php" class="btn">
            <i class="fa-solid fa-arrow-left"></i> Site
        </a>

        index.php" class="btn">
            <i class="fa-solid fa-gauge"></i> Dashboard
        </a>

        admin_user.php" class="btn">
            <i class="fa-solid fa-users"></i> Utilisateurs
        </a>

        admin_config.php" class="btn">
            <i class="fa-solid fa-gear"></i> Config
        </a>

        admin_log.php" class="btn">
            <i class="fa-solid fa-file-lines"></i> Logs
        </a>

        admin_blacklist.php" class="btn">
            <i class="fa-solid fa-skull"></i> Blacklist
        </a>

    </nav>

</header>

<main>
