<div class="login-card">

    <h2><i class="fa-solid fa-user-lock"></i> Connexion</h2>

    <?php if (!empty($error)): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">

        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

        <label>Pseudo</label>
        <input type="text" name="pseudo" required>

        <label>Mot de passe</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">
            <i class="fa-solid fa-right-to-bracket"></i> Connexion
        </button>

    </form>

    <a href="user_create.php" class="link">
        <i class="fa-solid fa-user-plus"></i> S'inscrire
    </a>

</div>