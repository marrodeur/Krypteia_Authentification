<?php

require_once __DIR__ . '/../lib/auth.php';

startSecureSession();

// ✅ Vérifier que l'utilisateur est admin (role 3 ou 4)
requireLogin(3, '../user_login.php');

// ✅ Redirection vers dashboard admin
header('Location: admin_user.php');
exit;
?>