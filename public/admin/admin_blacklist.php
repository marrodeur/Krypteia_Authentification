<?php

require_once __DIR__ . '/../../lib/auth.php';
require_once __DIR__ . '/../../lib/db.php';
require_once __DIR__ . '/../../lib/functions.php';

startSecureSession();
requireLogin(3);

$pdo = getPDO();


// ✅ ACTIONS (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die("Erreur CSRF");
    }

    $action = $_POST['action'] ?? '';
    $userId = $_POST['user'] ?? '';

    $stmt = $pdo->prepare("SELECT id, role, pseudo FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $target = $stmt->fetch();

    if (!$target) {
        report("Utilisateur introuvable");
        header("Location: admin_blacklist.php");
        exit;
    }

    // 🔐 protection hiérarchie
    $current = getCurrentUser();
    if (($target['role'] >= 3 && $current['role'] < 4) || $target['role'] == 4) {
        report("Accès refusé");
        header("Location: admin_blacklist.php");
        exit;
    }

    if ($action === 'restore') {
        $pdo->prepare("UPDATE users SET activ='oui' WHERE id=?")
            ->execute([$userId]);

        report("Utilisateur restauré", "success");
    }

    elseif ($action === 'delete') {

        $pdo->prepare("DELETE FROM users WHERE id=?")
            ->execute([$userId]);

        report("Utilisateur supprimé", "success");
    }

    header("Location: admin_blacklist.php");
    exit;
}


// ✅ LISTE BLACKLIST
$stmt = $pdo->query("SELECT * FROM users WHERE activ = 'black' ORDER BY role DESC");
$users = $stmt->fetchAll();

$_SESSION['csrf'] = bin2hex(random_bytes(32));

$pageTitle = "Blacklist";

require __DIR__ . '/../../views/header.php';
?>

<div class="card">

    <h2><i class="fa-solid fa-skull"></i> Blacklist</h2>

    <?php report_disp(); ?>

    <?php if (empty($users)): ?>
        <p>Aucun utilisateur blacklisté</p>
    <?php else: ?>

    <table class="table">
        <thead>
            <tr>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($users as $u): ?>

            <tr>
                <td><?= htmlspecialchars($u['pseudo']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
                <td><?= htmlspecialchars($u['register_date']) ?></td>

                <td class="actions">

                    <!-- Restaurer -->
                    if="<?= $_SESSION['csrf'] ?>">
                        <input type="hidden" name="action" value="restore">
                        <input type="hidden" name="user" value="<?= $u['id'] ?>">
                        <button title="Restaurer">
                            <i class="fa-solid fa-rotate-left"></i>
                        </button>
                    </form>

                    <!-- Supprimer -->
                    if="<?= $_SESSION['csrf'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="user" value="<?= $u['id'] ?>">
                        <button class="danger" title="Supprimer">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>

                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>
    </table>

    <?php endif; ?>

</div>

<?php require __DIR__ . '/../../views/footer.php'; ?>