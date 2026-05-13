<?php
session_start();

require_once __DIR__ . '/../lib/db.php';

//  CSRF
if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
    die("Erreur CSRF");
}

//  helper redirect
function go($step) {
    header("Location: $step.php");
    exit;
}

//  STEP 1 (connexion DB)
if ($_POST['step'] == '1') {

    $host = trim($_POST['install_hote']);
    $user = trim($_POST['install_user']);
    $pass = $_POST['install_pass'];
    $db   = trim($_POST['install_base']);

    if (!$host || !$user || !$db) {
        $_SESSION['report'] = "Champs obligatoires";
        go('step1');
    }

    try {
        new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    } catch (Exception $e) {
        $_SESSION['report'] = "Connexion DB impossible";
        go('step1');
    }

    $_SESSION['install'] = compact('host','user','pass','db');

    go('step2');
}


//  STEP 2 (tables)
elseif ($_POST['step'] == '2') {

    $tableUser = $_POST['install_tablemembre'];
    $tableConf = $_POST['install_tableconf'];

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableUser)) {
        $_SESSION['report'] = "Nom table invalide";
        go('step2');
    }

    $pdo = getPDO();

    if (empty($_POST['install_notable'])) {

        $pdo->exec("DROP TABLE IF EXISTS $tableUser");
        $pdo->exec("DROP TABLE IF EXISTS $tableConf");

        $pdo->exec("
            CREATE TABLE $tableUser (
                id VARCHAR(32) PRIMARY KEY,
                pseudo VARCHAR(50),
                password TEXT,
                email VARCHAR(100),
                role INT DEFAULT 1,
                register_date DATETIME,
                activ VARCHAR(10)
            )
        ");

        $pdo->exec("
            CREATE TABLE $tableConf (
                type VARCHAR(50),
                nom VARCHAR(50),
                valeur TEXT
            )
        ");
    }

    $_SESSION['tables'] = compact('tableUser','tableConf');

    go('step3');
}


//  STEP 3 (config)
elseif ($_POST['step'] == '3') {

    $pdo = getPDO();
    $table = $_SESSION['tables']['tableConf'];

    $data = [
        ['config','crypt_md5', $_POST['install_crypt']],
        ['config','register_activ', $_POST['install_activ']],
        ['config','log_user', $_POST['install_logs']],
        ['config','mail_type', $_POST['install_email']],
        ['config','version','3.0'],
    ];

    $stmt = $pdo->prepare("INSERT INTO $table (type, nom, valeur) VALUES (?, ?, ?)");

    foreach ($data as $row) {
        $stmt->execute($row);
    }

    go('step4');
}


//  STEP 4 (admin)
elseif ($_POST['step'] == '4') {

    $pseudo = trim($_POST['install_userpseudo']);
    $email  = trim($_POST['install_usermail']);
    $pass   = $_POST['install_userpass'];
    $pass2  = $_POST['install_userpass2'];

    if (!$pseudo || !$pass) {
        $_SESSION['report'] = "Champs obligatoires";
        go('step4');
    }

    if ($pass !== $pass2) {
        $_SESSION['report'] = "Passwords différents";
        go('step4');
    }

    $pdo = getPDO();
    $table = $_SESSION['tables']['tableUser'];

    $id = bin2hex(random_bytes(16));
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO $table (id, pseudo, password, email, role, register_date, activ)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $id,
        $pseudo,
        $hash,
        $email,
        4,
        date('Y-m-d H:i:s'),
        'oui'
    ]);


    //  CONFIG FILE
    $config = "<?php return [
        'host' => '{$ _SESSION['install']['host']}',
        'dbname' => '{$ _SESSION['install']['db']}',
        'user' => '{$ _SESSION['install']['user']}',
        'pass' => '{$ _SESSION['install']['pass']}'
    ];";

    file_put_contents(__DIR__ . '/../config/database.php', $config);

    go('step5');
}
?>