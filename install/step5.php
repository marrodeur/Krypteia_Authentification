<?php
session_start();
session_destroy();

// sécurité supplémentaire
$installDir = __DIR__;
$configFile = __DIR__ . '/../config/database.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Installation terminée</title>

    ../assets/style.css

    https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css
</head>

<body>

<div class="install-container">

    <h1><i class="fa-solid fa-circle-check"></i> Installation terminée</h1>

    <div class="success">
         Shiva Auth a été installé avec succès !
    </div>

    <p>
        Votre système est maintenant prêt à être utilisé.
    </p>

    <div class="install-actions">
        ../index.php" class="btn primary">
            <i class="fa-solid fa-right-to-bracket"></i>
            Accéder au site
        </a>
    </div>

    <div class="alert error" style="margin-top:20px;">
        <strong> Sécurité :</strong><br>
        Supprimez le dossier <b>/install</b> pour éviter toute réinstallation non autorisée.
    </div>

</div>

</body>
</html>