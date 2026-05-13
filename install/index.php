<?php
session_start();

// ✅ bloquer si déjà installé
if (file_exists(__DIR__ . '/../config/database.php')) {
    die("Le script est déjà installé.");
}

$pageTitle = "Installation - étape 0";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    ../assets/style.css

    <!-- Font Awesome -->
    https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css
</head>

<body>

<div class="install-container">

    <h1><i class="fa-solid fa-wrench"></i> Installation Shiva Auth</h1>

    <p>Bienvenue dans l’assistant d’installation (version moderne)</p>

    <div class="install-actions">

        update.php" class="btn secondary">
            <i class="fa-solid fa-rotate"></i> Mise à jour
        </a>

        step1.php" class="btn primary">
            <i class="fa-solid fa-play"></i> Installation
        </a>

    </div>

</div>

</body>
</html>