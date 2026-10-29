<?php

$host = '127.0.0.1';
$dbname = 'invasion_ia';
$username = 'root';
$password = 'Nedelec12345';
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "Connexion réussie";
} catch (PDOException $e) {
    die ("Erreur de connexion : " .$e->getMessage());
}

?>