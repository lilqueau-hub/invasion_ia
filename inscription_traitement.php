<?php
require_once __DIR__ . '/database.php';

// récupérer les données saisies par l'utilisateur
$nom = trim($_POST['nom'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// vérifier les informations saisies
if ($nom ==='' || $email ==='' || $password ==='') {
    exit ("Tous les champs sont obligatoires.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Adresse mail invalide.");
}
if(strlen($password) < 10) {
    exit ("Le mot de passe doit contenir au moins 10 caractères.");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $sql = "INSERT INTO user (nom, email, password)
            VALUES (:nom, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom'=> $nom,
        'email' => $email,
        'password' => $mdp
    ]);
    echo "Compte créé avec succès !";
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        echo "Cette adresse mail est déjà utilisée.";
    } else {
        throw $e;
    }
}

var_dump($hash);