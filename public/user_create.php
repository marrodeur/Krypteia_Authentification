<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../lib/db.php';

session_start();

// Redirection si connecté
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die('Erreur CSRF');
    }

    $pseudo   = trim($_POST['pseudo'] ?? '');
    $password = $_POST['password'] ?? '';
    $password1 = $_POST['password1'] ?? '';
    $email    = strtolower(trim($_POST['email'] ?? ''));

    // ✅ validations
    if (!$pseudo || !$password || !$email) {
        $error = "Tous les champs sont obligatoires.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    }
    elseif ($password !== $password1) {
        $error = "Les mots de passe ne correspondent pas.";
    }
    elseif (strlen($pseudo) < 3) {
        $error = "Pseudo trop court.";
    }
    elseif (strlen($password) < 6) {
        $error = "Mot de passe trop court.";
    }
    else {

        $pdo = getPDO();

        // Vérif pseudo existant
        $stmt = $pdo->prepare("SELECT id FROM users WHERE pseudo = ?");
        $stmt->execute([$pseudo]);

        if ($stmt->fetch()) {
            $error = "Ce pseudo existe déjà.";
        } else {

            // ✅ ID sécurisé
            $id = bin2hex(random_bytes(16));

            // ✅ hash moderne
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // activation
            $activ = 'oui';

            // date
            $register_date = date('Y-m-d H:i:s');

            $stmt = $pdo->prepare("
                INSERT INTO users (id, pseudo, password, email, role, register_date, activ)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $id,
                $pseudo,
                $hash,
                $email,
                1,
                $register_date,
                $activ
            ]);

            $success = true;
        }
    }
}

// CSRF token
$_SESSION['csrf'] = bin2hex(random_bytes(32));

$pageTitle = "Inscription";

require __DIR__ . '/../views/header.php';
require __DIR__ . '/../views/register.php';
require __DIR__ . '/../views/footer.php';
?>