<?php
session_start();

// sécurité
if (file_exists(__DIR__ . '/../config/database.php')) {
    die("Script déjà installé");
}

// CSRF
$_SESSION['csrf'] = bin2hex(random_bytes(32));

function old($key) {
    return htmlspecialchars($_SESSION[$key] ?? '');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Installation - Super Admin</title>

    ../assets/style.css

    https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css
</head>

<body>

<div class="install-container">

    <h1><i class="fa-solid fa-user-shield"></i> Super Administrateur</h1>

    <?php if (!empty($_SESSION['report'])): ?>
        <div class="alert error">
            <?= htmlspecialchars($_SESSION['report']) ?>
        </div>
        <?php unset($_SESSION['report']); ?>
    <?php endif; ?>

    step_register.php

        <input type="hidden" name="step" value="4">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

        <label>Pseudo</label>
        <input type="text" name="install_userpseudo"
               value="<?= old('install_userpseudo') ?>"
               required>

        <label>Email</label>
        <input type="email" name="install_usermail"
               value="<?= old('install_usermail') ?>"
               required>

        <label>Mot de passe</label>
        <input type="password" name="install_userpass" required>

        <label>Confirmer mot de passe</label>
        <input type="password" name="install_userpass2" required>

        <div class="install-actions">

            step3.php" class="btn secondary">
                <i class="fa-solid fa-arrow-left"></i> Retour
            </a>

            <button class="btn primary">
                <i class="fa-solid fa-check"></i> Finaliser
            </button>

        </div>

    </form>

</div>

</body>
</html>