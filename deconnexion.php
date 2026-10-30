<?php
// récuperer la session en cours
session_start();

//vider toutes les variables de la session
$_SESSION = [];

//détruire la session côté serveur
session_destroy();

// rédiriger vers la page de connexion
header('Location: connexion.php');

exit;