<div class="login-card">

    <h2><i class="fa-solid fa-user-gear"></i> Profil</h2>

    <?php if ($error): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success">
            <i class="fa-solid fa-circle-check"></i>
            Profil mis à jour !
        </div>
    <?php endif; ?>

    <form method="post">

        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

        <label>Pseudo</label>
        <input type="text" value="<?= htmlspecialchars($user['pseudo']) ?>" disabled>

        <label>Email</label>
        <input type="email" name="email_modif" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Mot de passe actuel</label>
        <input type="password" name="password" required>

        <label>Nouveau mot de passe</label>
        <input type="password" name="password_new">

        <label>Confirmer</label>
        <input type="password" name="password_new1">

        <button class="btn">
            <i class="fa-solid fa-floppy-disk"></i> Enregistrer
        </button>

    </form>

    index.php" class="link">
        <i class="fa-solid fa-arrow-left"></i> Retour
    </a>

</div>
