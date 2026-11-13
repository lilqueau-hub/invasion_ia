<?php
require_once __DIR__ . 'database.php';

// récupérer les données saisies par l'utilisateur
$nom = trim($_POST['nom'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// vérifier les informations saisies
if ($nom === '' || $email === '' || $password === '') {
    exit("Tous les champs sont obligatoires.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Adresse mail invalide.");
}

if (strlen($password) < 10) {
    exit("Le mot de passe doit contenir au moins 10 caractères.");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $pdo->beginTransaction();

    // 1. Créer le joueur
    $sql = "INSERT INTO player (nom, email, password)
            VALUES (:nom, :email, :password)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom' => $nom,
        'email' => $email,
        'password' => $hash
    ]);

    // 2. Récupérer l'identifiant du joueur créé
    $playerId = (int) $pdo->lastInsertId();

    // 3. Créer sa partie au jour 1
    $sql = "INSERT INTO partie (player_id, jour_actuel)
            VALUES (:player_id, 1)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'player_id' => $playerId
    ]);

    $pdo->commit();
    echo "Compte créé avec succès !";

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    if (isset($e->errorInfo[1]) && (int) $e->errorInfo[1] === 1062) {
        echo "Ce nom ou cette adresse e-mail est déjà utilisé.";
    } else {
        throw $e;
    }
}