<?php

require_once __DIR__ . '/../../lib/auth.php';
require_once __DIR__ . '/../../lib/db.php';
require_once __DIR__ . '/../../lib/functions.php';

startSecureSession();
requireLogin(3);

$user = getCurrentUser();
$pdo = getPDO();


// ACTIONS (POST sécurisé)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die("Erreur CSRF");
    }

    $action = $_POST['action'] ?? '';
    $userId = $_POST['user'] ?? '';

    // récupérer utilisateur cible
    $stmt = $pdo->prepare("SELECT id, pseudo, role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $target = $stmt->fetch();

    if (!$target) {
        report("Utilisateur introuvable");
        header("Location: admin_user.php");
        exit;
    }

    // protection rôles
    if (($target['role'] >= 3 && $user['role'] < 4) || $target['role'] == 4) {
        report("Accès refusé");
        header("Location: admin_user.php");
        exit;
    }

    switch ($action) {

        case 'activate':
            $pdo->prepare("UPDATE users SET activ='oui' WHERE id=?")->execute([$userId]);
            report("Utilisateur activé", "success");
            break;

        case 'deactivate':
            $pdo->prepare("UPDATE users SET activ='non' WHERE id=?")->execute([$userId]);
            report("Utilisateur désactivé", "success");
            break;

        case 'blacklist':
            $pdo->prepare("UPDATE users SET activ='black' WHERE id=?")->execute([$userId]);
            report("Utilisateur blacklisté", "success");
            break;

        case 'delete':
            $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$userId]);
            report("Utilisateur supprimé", "success");
            break;

        case 'role':
            $newRole = (int)$_POST['role'];
            if ($newRole >= 1 && $newRole <= 3) {
                $pdo->prepare("UPDATE users SET role=? WHERE id=?")->execute([$newRole, $userId]);
                report("Rôle modifié", "success");
            }
            break;
    }

    header("Location: admin_user.php");
    exit;
}


// LISTE UTILISATEURS
$stmt = $pdo->query("SELECT * FROM users ORDER BY role DESC, pseudo ASC");
$users = $stmt->fetchAll();

$_SESSION['csrf'] = bin2hex(random_bytes(32));

$pageTitle = "Admin utilisateurs";

require __DIR__ . '/../../views/header.php';
?>

<div class="card">

    <h2><i class="fa-solid fa-users"></i> Gestion utilisateurs</h2>

    <?php report_disp(); ?>

    <table class="table">

        <thead>
            <tr>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($users as $u): ?>

            <tr>
                <td><?= htmlspecialchars($u['pseudo']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>

                <td>
                    <form method="post" class="inline">
                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                        <input type="hidden" name="action" value="role">
                        <input type="hidden" name="user" value="<?= $u['id'] ?>">

                        <select name="role" onchange="this.form.submit()">
                            <option value="1" <?= $u['role']==1?'selected':'' ?>>User</option>
                            <option value="2" <?= $u['role']==2?'selected':'' ?>>Modo</option>
                            <option value="3" <?= $u['role']==3?'selected':'' ?>>Admin</option>
                        </select>
                    </form>
                </td>

                <td><?= htmlspecialchars($u['register_date']) ?></td>
                <td><?= htmlspecialchars($u['activ']) ?></td>

                <td class="actions">

                    <!-- Activer -->
                    if="<?= $_SESSION['csrf'] ?>">
                        <input type="hidden" name="action" value="activate">
                        <input type="hidden" name="user" value="<?= $u['id'] ?>">
                        <button title="Activer"><i class="fa-solid fa-check"></i></button>
                    </form>

                    <!-- Désactiver -->
                    if="<?= $_SESSION['csrf'] ?>">
                        <input type="hidden" name="action" value="deactivate">
                        <input type="hidden" name="user" value="<?= $u['id'] ?>">
                        <button title="Désactiver"><i class="fa-solid fa-ban"></i></button>
                    </form>

                    <!-- Blacklist -->
                    if="<?= $_SESSION['csrf'] ?>">
                        <input type="hidden" name="action" value="blacklist">
                        <input type="hidden" name="user" value="<?= $u['id'] ?>">
                        <button title="Blacklist"><i class="fa-solid fa-skull"></i></button>
                    </form>

                    <!-- Delete -->
                    if="<?= $_SESSION['csrf'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="user" value="<?= $u['id'] ?>">
                        <button class="danger" title="Supprimer"><i class="fa-solid fa-trash"></i></button>
                    </form>

                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>
    </table>

</div>

<?php require __DIR__ . '/../../views/footer.php'; ?>