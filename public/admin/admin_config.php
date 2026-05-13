<?php

require_once __DIR__ . '/../../lib/auth.php';
require_once __DIR__ . '/../../lib/db.php';
require_once __DIR__ . '/../../lib/functions.php';

startSecureSession();
requireLogin(3);

$pdo = getPDO();


// TRAITEMENT FORMULAIRE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die("Erreur CSRF");
    }

    $allowedFields = [
        'no_champs','no_pass','compte_desactive','compte_blacklist','no_passold','no_passconf',
        'mail_sent','mail_sentproto','register_conf','modif_conf',
        'crypt_color','crypt_avert','crypt_md5','register_activ','log_user','mail_type',
        'mail_subject','mail_msg','modif_timer','register_timer','error_color',
        'pseudo_min','pseudo_max','pass_min','pass_max',
        'pseudo_minlen','pseudo_maxlen','pass_minlen','pass_maxlen'
    ];

    try {
        $stmt = $pdo->prepare("UPDATE config SET valeur = ? WHERE nom = ?");

        foreach ($allowedFields as $field) {

            if (!isset($_POST[$field])) continue;

            $value = trim($_POST[$field]);

            // Validation spécifique
            if (in_array($field, ['pseudo_min','pseudo_max','pass_min','pass_max','modif_timer','register_timer'])) {
                $value = (int)$value;
            }

            $stmt->execute([$value, $field]);
        }

        report("Configuration mise à jour", "success");

    } catch (Exception $e) {
        report("Erreur lors de la mise à jour");
    }

    header("Location: admin_config.php");
    exit;
}


// CHARGEMENT CONFIG
$stmt = $pdo->query("SELECT type, nom, valeur FROM config");
$config = [];

foreach ($stmt as $row) {
    $config[$row['type']][$row['nom']] = $row['valeur'];
}

// CSRF
$_SESSION['csrf'] = bin2hex(random_bytes(32));

$pageTitle = "Configuration";

require __DIR__ . '/../../views/header.php';
?>

<div class="card">

    <h2><i class="fa-solid fa-gear"></i> Configuration</h2>

    <?php report_disp(); ?>

    <form method="post" class="form">

        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

        <h3>Messages</h3>

        <label>Champs vides</label>
        <input type="text" name="no_champs" value="<?= htmlspecialchars($config['message']['no_champs'] ?? '') ?>">

        <label>Password incorrect</label>
        <input type="text" name="no_pass" value="<?= htmlspecialchars($config['message']['no_pass'] ?? '') ?>">

        <label>Compte désactivé</label>
        <input type="text" name="compte_desactive" value="<?= htmlspecialchars($config['message']['compte_desactive'] ?? '') ?>">

        <h3>Paramètres</h3>

        <label>Pseudo min</label>
        <input type="number" name="pseudo_min" value="<?= htmlspecialchars($config['config']['pseudo_min'] ?? 3) ?>">

        <label>Pseudo max</label>
        <input type="number" name="pseudo_max" value="<?= htmlspecialchars($config['config']['pseudo_max'] ?? 20) ?>">

        <label>Password min</label>
        <input type="number" name="pass_min" value="<?= htmlspecialchars($config['config']['pass_min'] ?? 6) ?>">

        <label>Password max</label>
        <input type="number" name="pass_max" value="<?= htmlspecialchars($config['config']['pass_max'] ?? 50) ?>">

        <h3>Sécurité</h3>

        <label>Logs connexions</label>
        <select name="log_user">
            <option value="1" <?= ($config['config']['log_user']??0)==1?'selected':'' ?>>Activé</option>
            <option value="0" <?= ($config['config']['log_user']??0)==0?'selected':'' ?>>Désactivé</option>
        </select>

        <label>Activation admin</label>
        <select name="register_activ">
            <option value="1" <?= ($config['config']['register_activ']??0)==1?'selected':'' ?>>Oui</option>
            <option value="0" <?= ($config['config']['register_activ']??0)==0?'selected':'' ?>>Non</option>
        </select>

        <button class="btn">
            <i class="fa-solid fa-floppy-disk"></i> Sauvegarder
        </button>

    </form>

</div>

<?php require __DIR__ . '/../../views/footer.php'; ?>