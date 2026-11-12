<?php
session_start();
require_once __DIR__ . 'config/database.php';

//récuperer les données envoyées par le formulaire de connexion
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

//vérifier si les champs ont été remplis
if ($email === '' || $password === '') {
    exit ("Tous les champs sont obligatoires.");
}

// rechercher l'utilisateur dans la base de données
$sql = "SELECT player_id, nom, email, password FROM player
        WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'email' => $email
]);

$player = $stmt->fetch();

echo '<pre>';
var_dump($player);
echo '</pre>';

// vérifier si l'utilisateur existe
if (!$player) {
    exit("Vérifiez les champs saisis.");
}

// vérifier le mot de passe
if (!password_verify($password, $player['password'])) {
    exit ("Le mot de passe incorrect.");
}

//regénerer l'id de la session après la vérification de mot de passe pour pouvoir conserver les données de la session
session_regenerate_id(true);

$_SESSION['player_id'] = $player['player_id'];
$_SESSION['player_nom'] = $player['nom'];

header('Location: /invasion_ia/cookie/accueil.php');
exit;
