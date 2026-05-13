<div class="login-card">

    <h2><i class="fa-solid fa-user-plus"></i> Inscription</h2>

    <?php if ($error): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success">
            <i class="fa-solid fa-circle-check"></i>
            Compte créé avec succès !
        </div>

        user_login.php" class="btn">
            Se connecter
        </a>

    <?php else: ?>

    <form method="post">

        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

        <label>Pseudo</label>
        <input type="text" name="pseudo" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Mot de passe</label>
        <input type="password" name="password" required>

        <label>Confirmer</label>
        <input type="password" name="password1" required>

        <button class="btn">
            <i class="fa-solid fa-user-plus"></i> S'inscrire
        </button>

    </form>

    <?php endif; ?>

</div>
