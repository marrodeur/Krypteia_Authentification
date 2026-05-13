<section class="card">

    <h2><i class="fa-solid fa-user"></i> Informations</h2>

    <p>
        Connecté sous :
        "><?= $user['pseudo'] ?></a>
        en tant que <strong><?= $user['role'] ?></strong>
    </p>

</section>


<section class="card">
    <h2><i class="fa-solid fa-users"></i> Membres</h2>

    <p>
        Il y a <strong><?= stats_count([1,2,3,4]) ?></strong> membres.
    </p>

    <ul>
        <?php foreach (stats_list([1,2,3,4]) as $member): ?>
            <li><?= htmlspecialchars($member['pseudo']) ?></li>
        <?php endforeach; ?>
    </ul>

</section>


<section class="card">
    <h2><i class="fa-solid fa-user-shield"></i> Rôles</h2>

    <p>Admins : <?= stats_count([3]) ?></p>
    <p>Modérateurs : <?= stats_count([2]) ?></p>
</section>


<section class="actions">
    <a href="admin/index.php" class="btn admin">
        <i class="fa-solid fa-cog"></i> Admin
    </a>

    <a href="user_update.php" class="btn">
        <i class="fa-solid fa-user-pen"></i> Profil
    </a>

    <a href="user_login.php?action=logout" class="btn logout">
        <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
    </a>
</section>
