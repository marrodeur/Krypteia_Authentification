<?php
 sécuritésession_start();
if (file_exists(__DIR__ . '/../config/database.php')) {
    die("Script déjà installé");
}

// CSRF
$_SESSION['csrf'] = bin2hex(random_bytes(32));

function old($key, $default = '') {
    return htmlspecialchars($_SESSION[$key] ?? $default);
}

$checked = !empty($_SESSION['install_notable']) ? 'checked' : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Installation - Tables</title>

    ../assets/style.css

    https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css

    <script>
    function toggleTables() {
        const disabled = document.getElementById('noTable').checked;

        document.getElementById('tableUser').disabled = disabled;
        document.getElementById('tableConf').disabled = disabled;
    }
    </script>
</head>

<body>

<div class="install-container">

    <h1><i class="fa-solid fa-table"></i> Création des tables</h1>

    <?php if (!empty($_SESSION['report'])): ?>
        <div class="alert error">
            <?= htmlspecialchars($_SESSION['report']) ?>
        </div>
        <?php unset($_SESSION['report']); ?>
    <?php endif; ?>

    step_register.php

        <input type="hidden" name="step" value="2">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

        <label>Table utilisateurs</label>
        <input type="text"
               id="tableUser"
               name="install_tablemembre"
               value="<?= old('install_tablemembre', 'users') ?>"
               required>

        <label>Table configuration</label>
        <input type="text"
               id="tableConf"
               name="install_tableconf"
               value="<?= old('install_tableconf', 'config') ?>"
               required>

        <label class="checkbox">
            <input type="checkbox"
                   id="noTable"
                   name="install_notable"
                   value="1"
                   <?= $checked ?>
                   onchange="toggleTables()">

            Ne pas créer les tables (elles existent déjà)
        </label>

        <div class="install-actions">

            step1.php" class="btn secondary">
                <i class="fa-solid fa-arrow-left"></i> Retour
            </a>

            <button class="btn primary">
                <i class="fa-solid fa-arrow-right"></i> Étape suivante
            </button>

        </div>

    </form>

</div>

<script>toggleTables();</script>

</body>
</html>

