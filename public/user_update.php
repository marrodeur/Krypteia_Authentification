<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/auth.php';

startSecureSession();

// Vérification login
requireLogin(1);

$user = getCurrentUser();

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die("Erreur CSRF");
    }

    $currentPassword = $_POST['password'] ?? '';
    $newPassword     = $_POST['password_new'] ?? '';
    $confirmPassword = $_POST['password_new1'] ?? '';
    $email           = strtolower(trim($_POST['email_modif'] ?? ''));

    if (!$currentPassword || !$email) {
        $error = "Tous les champs obligatoires ne sont pas remplis.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    }
    else {

        $pdo = getPDO();

        // récupérer vrai password
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        $dbUser = $stmt->fetch();

        if (!$dbUser || !password_verify($currentPassword, $dbUser['password'])) {
            // compatibilité md5 (migration douce)
            if (md5($currentPassword) !== $dbUser['password']) {
                $error = "Mot de passe actuel incorrect.";
            }
        }

        if (!$error) {

            // nouveau password ?
            if (!empty($newPassword)) {

                if ($newPassword !== $confirmPassword) {
                    $error = "Les nouveaux mots de passe ne correspondent pas.";
                }
                elseif (strlen($newPassword) < 6) {
                    $error = "Mot de passe trop court.";
                }
                else {
                    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                }
            }

            if (!$error) {

                // requête dynamique
                if (!empty($newPassword)) {
                    $stmt = $pdo->prepare("
                        UPDATE users SET password = ?, email = ? WHERE id = ?
                    ");
                    $stmt->execute([$newHash, $email, $user['id']]);
                } else {
                    $stmt = $pdo->prepare("
                        UPDATE users SET email = ? WHERE id = ?
                    ");
                    $stmt->execute([$email, $user['id']]);
                }

                $success = true;
            }
        }
    }
}

// CSRF
$_SESSION['csrf'] = bin2hex(random_bytes(32));

$pageTitle = "Modifier mon profil";

require __DIR__ . '/../views/header.php';
require __DIR__ . '/../views/profile.php';
require __DIR__ . '/../views/footer.php';
?>