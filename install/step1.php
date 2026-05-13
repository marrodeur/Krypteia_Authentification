<?php
 bloquer si déjà installésession_start();
if (file_exists(__DIR__ . '/../config/database.php')) {
    die("Script déjà installé");
}

// CSRF
$_SESSION['csrf'] = bin2hex(random_bytes(32));

function old($key, $default = '') {
    return htmlspecialchars($_SESSION[$key] ?? $default);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Installation - Base de données</title>

    ../assets/style.css

    https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css
</head>

<body>

<div class="install-container">

    <h1><i class="fa-solid fa-database"></i> Configuration MySQL</h1>

    <?php if (!empty($_SESSION['report'])): ?>
        <div class="alert error">
            <?= htmlspecialchars($_SESSION['report']) ?>
        </div>
        <?php unset($_SESSION['report']); ?>
    <?php endif; ?>

    <form action="step_register.php" method="post">

        <input type="hidden" name="step" value="1">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

        <label>Serveur / Hôte</label>
        <input type="text" name="install_hote" value="<?= old('install_hote', 'localhost') ?>" required>

        <label>Utilisateur</label>
        <input type="text" name="install_user" value="<?= old('install_user', 'root') ?>" required>

        <label>Mot de passe</label>
        <input type="password" name="install_pass" value="<?= old('install_pass') ?>">

        <label>Base de données</label>
        <input type="text" name="install_base" value="<?= old('install_base') ?>" required>

        <label>URL du script</label>
        <input type="url" name="install_path" value="<?= old('install_path', 'http://localhost/') ?>" required>

        <div class="install-actions">

            index.php" class="btn secondary">
                <i class="fa-solid fa-arrow-left"></i> Retour
            </a>

            <button class="btn primary">
                <i class="fa-solid fa-arrow-right"></i> Étape suivante
            </button>

        </div>

    </form>

</div>

</body>
</html>

