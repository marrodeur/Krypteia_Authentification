<?php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';


//--------------------------------
// Report (message flash sécurisé)
//--------------------------------

function report(string $message, string $type = 'error'): void
{
    startSecureSession();

    $_SESSION['flash'][] = [
        'type' => $type,
        'message' => $message
    ];
}


//--------------------------------
// Affichage report
//--------------------------------

function report_disp(): void
{
    startSecureSession();

    if (empty($_SESSION['flash'])) {
        return;
    }

    foreach ($_SESSION['flash'] as $msg) {
        echo '<div class="alert ' . htmlspecialchars($msg['type']) . '">';
        echo htmlspecialchars($msg['message']);
        echo '</div>';
    }

    unset($_SESSION['flash']);
}


//--------------------------------
// Accès utilisateur (remplace user())
//--------------------------------

function user(string $key): string
{
    $user = getCurrentUser();

    if (!$user) {
        return '';
    }

    $roles = [
        0 => 'anonyme',
        1 => 'utilisateur',
        2 => 'modérateur',
        3 => 'administrateur',
        4 => 'super utilisateur'
    ];

    if ($key === 'rang') {
        return $roles[$user['role']] ?? 'inconnu';
    }

    return htmlspecialchars($user[$key] ?? '');
}


//--------------------------------
// Stats utilisateurs
//--------------------------------

function stats_count(array $roles): int
{
    $pdo = getPDO();

    $in = implode(',', array_fill(0, count($roles), '?'));

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role IN ($in)");
    $stmt->execute($roles);

    return (int)$stmt->fetchColumn();
}


function stats_list(array $roles): array
{
    $pdo = getPDO();

    $in = implode(',', array_fill(0, count($roles), '?'));

    $stmt = $pdo->prepare("
        SELECT pseudo 
        FROM users 
        WHERE role IN ($in)
        ORDER BY pseudo ASC
    ");

    $stmt->execute($roles);

    return $stmt->fetchAll();
}


//--------------------------------
// Affichage conditionnel (roles)
//--------------------------------

function canAccess(array $roles): bool
{
    $user = getCurrentUser();

    return $user && in_array($user['role'], $roles);
}


function affiche(string $html, array $roles): void
{
    if (canAccess($roles)) {
        echo $html;
    }
}
?>