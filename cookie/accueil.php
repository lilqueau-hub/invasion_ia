<?php require_once __DIR__ . '/../database.php';

if (isset($_COOKIE['nom'])) {
    $nom = $_COOKIE['nom'];
} else {
    $nom = '';
}

$createGame = [];
$players = [];
$search = '';

if (isset($_GET['nom'])) {
    $search = trim($_GET['nom']);

    if($search !== '') {
        $players = getPlayer($pdo, $search);
    }
}

if (isset($_GET['createGame'])) {
    $createGame = trim($_GET['createGame']);
    
    if($createGame !=='') {
        $players = getPlayer($pdo, $createGame);
    }
}
// $GetGame = getGame();
// Déconnexion
// if (isset($_POST['deconnexion.php'])) {
//     deconnexionBtn();
// }

// function createGame(PDO $pdo, int $playerId): void
// {
//     $sql = "INSERT INTO partie (player_id, jour_actuel)
//             VALUES (:player_id, 1)";

//     $stmt = $pdo->prepare($sql);
//     $stmt->execute([
//         'player_id' => $playerId
//     ]);
// }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Après l'invasion de l'IA</title>
</head>
<body>
    <h1>Après l'invasion de l'IA</h1>

    <form method="" action=""></form>

    <!-- <button>Se déconnecter</button> -->

    <?php if ($nom !== ''): ?>
    <form action="supprimer_cookie.php" method="POST">
        <button type="submit">Supprimer les cookies</button>
    </form>

    <?php else: ?>
        <p>Bonjour <?= htmlspecialchars($nom) ?></p>
        
        <div class="presentation">
            <h4>Contexte</h4>
            
            <p>L'intelligence artificielle a envahi le monde.</p>
            <p>Vous incarnez un survivant.</p>

            <h4>Objectifs :</h4>
            <ul>
                <li>Rester en vie pendant 30 jours.</li>
                <li>Gérer vos ressources.</li>
                <li>Prenez les bonnes décisions.</li>
            </ul>
        </div>
    <?php endif; ?>
    <form method="GET">
        <button name="createGame"><a href="../partie.php">Commencer la survie</a></button>
    </form>
    

    <form method="GET">
        <label for="nom">Nom du joueur :</label>
        <input 
            type="text"
            id="player_id"
            name="nom"
            value="<?= htmlspecialchars($search) ?>"
            required>
            <button type="submit">Rechercher le joueur</button>
    </form>

    <form method="GET">
        <button type="submit" name="GetGame">Récupérer la partie</button>
    </form>

</body>
</html>