<?php

function getPDO(): PDO
{
    static $pdo = null;

    if ($pdo === null) {

        $config = require __DIR__ . '/../config/database.php';

        try {
            $pdo = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
                $config['user'],
                $config['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

        } catch (PDOException $e) {
            die("Erreur connexion DB");
        }
    }

    return $pdo;
}

?>