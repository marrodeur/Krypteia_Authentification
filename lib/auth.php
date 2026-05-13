<?php

require_once __DIR__ . '/db.php';

function startSecureSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {

        session_start([
            'cookie_httponly' => true,
            'cookie_secure'   => isset($_SERVER['HTTPS']),
            'cookie_samesite' => 'Strict'
        ]);
    }
}

function getCurrentUser(): ?array
{
    startSecureSession();

    if (empty($_SESSION['user']['id'])) {
        return null;
    }

    $pdo = getPDO();

    $stmt = $pdo->prepare("SELECT id, pseudo, email, role, register_date, activ FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user']['id']]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        logout();
        return null;
    }

    // Vérif état compte
    if ($user['activ'] === 'non' || $user['activ'] === 'black') {
        logout();
        return null;
    }

    return $user;
}

function requireLogin(int $minRole = 1, string $redirect = 'user_login.php'): void
{
    $user = getCurrentUser();

    if (!$user || $user['role'] < $minRole) {
        header("Location: $redirect");
        exit;
    }
}

function logout(): void
{
    startSecureSession();
    session_unset();
    session_destroy();
}
?>