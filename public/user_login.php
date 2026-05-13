<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/auth.php';

session_start();

// Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: user_login.php');
    exit;
}

// Si déjà connecté
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

// Traitement login
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF check
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die("Erreur CSRF");
    }

    $pseudo  = trim($_POST['pseudo'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($pseudo) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } else {

        $pdo = getPDO();

        $stmt = $pdo->prepare("SELECT id, pseudo, email, password, role, activ FROM users WHERE pseudo = ?");
        $stmt->execute([$pseudo]);

        $user = $stmt->fetch();

        if (!$user) {
            $error = "Utilisateur inconnu.";
        }
        elseif ($user['activ'] === 'non') {
            $error = "Compte désactivé.";
        }
        elseif ($user['activ'] === 'black') {
            $error = "Compte blacklisté.";
        }
        elseif (!password_verify($password, $user['password'])) {
            $error = "Mot de passe incorrect.";
        }
        else {
            // OK Login
            session_regenerate_id(true);

            $_SESSION['user'] = [
                'id' => $user['id'],
                'pseudo' => $user['pseudo'],
                'email' => $user['email'],
                'role' => (int)$user['role']
            ];

            header('Location: index.php');
            exit;
        }
    }
}

// CSRF token
$_SESSION['csrf'] = bin2hex(random_bytes(32));

$pageTitle = "Connexion";

require __DIR__ . '/../views/header.php';
require __DIR__ . '/../views/login.php';
require __DIR__ . '/../views/footer.php';
?>