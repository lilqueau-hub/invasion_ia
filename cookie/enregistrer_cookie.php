<?php
// récupérer le nom envoyé par le formulaire
$nom = trim($_POST['nom'] ?? '');

// vérifier le nom n'est pas vide
if ($nom === '') {
    exit ("Veuillez saisir un nom");
}

// créer un cookie
setcookie(
    'nom',
    $nom,
    time() + (7 * 24 * 60 * 60),
    '/'
);

// rédiriger vers la page d'accueil
header(('Location : accueil.php'));
exit;
?>
