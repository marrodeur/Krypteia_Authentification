<?php

require_once __DIR__ . '/../../lib/auth.php';
require_once __DIR__ . '/../../lib/functions.php';

startSecureSession();
requireLogin(3);

$logFile = __DIR__ . '/../../logs/log_result.txt';


// ✅ ACTION (vider log)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die("Erreur CSRF");
    }

    if ($_POST['action'] === 'clear') {

        if (file_exists($logFile)) {
            copy($logFile, __DIR__ . '/../../logs/' . time() . '.log');
            file_put_contents($logFile, '');
        }

        report("Log vidé", "success");

        header("Location: admin_log.php");
        exit;
    }
}


// ✅ lecture log
$lines = [];

if (file_exists($logFile)) {

    // ⚠️ limite (sécurité mémoire)
    $fileLines = file($logFile, FILE_IGNORE_NEW_LINES);

    // garder les 200 dernières lignes
    $lines = array_slice($fileLines, -200);
}

// CSRF
$_SESSION['csrf'] = bin2hex(random_bytes(32));

$pageTitle = "Logs";

require __DIR__ . '/../../views/header.php';
?>

<div class="card">

    <h2><i class="fa-solid fa-file-lines"></i> Logs</h2>

    <?php report_disp(); ?>

    <?php if (empty($lines)): ?>
        <p>Aucun log disponible</p>
    <?php else: ?>

    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th>IP</th>
                <th>Provenance</th>
                <th>Page</th>
                <th>Pseudo</th>
                <th>Méthode</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($lines as $line): 

            $parts = explode(';', $line);

        ?>

            <tr>
                <?php foreach ($parts as $p): ?>
                    <td><?= htmlspecialchars(trim($p)) ?></td>
                <?php endforeach; ?>
            </tr>

        <?php endforeach; ?>

        </tbody>
    </table>

    <?php endif; ?>

    <!-- vider log -->
    <form method="post" style="margin-top:20px;">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
        <input type="hidden" name="action" value="clear">

        <button class="btn danger">
            <i class="fa-solid fa-trash"></i> Vider le log
        </button>
    </form>

</div>

<?php require __DIR__ . '/../../views/footer.php'; ?>