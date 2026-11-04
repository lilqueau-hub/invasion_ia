<?php require_once __DIR__ . '/fonctions.php'; ?>

<?php 
// Récupérer des ressources
if (isset($_POST['recuperer']) && $_POST['recuperer'] === 'recuperer') {
    try {
        $query = "SELECT ressource_id, nom, type FROM ressources ORDER BY RAND() LIMIT 3 ";
        $stmt = $dbname->query($query);
        $ressources = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($ressources as $ressource) {
            echo "ID : " . $ressource['ressource_id'] . " - Nom : " . $ressource['nom'] . " - Nom : " . $ressource['type'] . "<br>";
        } catch(PDOException $e) {
            echo "Erreur de base de données :" . $e->getMessage();
        }
        exit;
    }
}

// Récupérer des ressources
// $ressources = $pdo->query(
//     'SELECT ressource_id, nom, type FROM ressources'
//     ) -> fetchAll(PDO::FETCH_ASSOC);


// Diminuer quantité de l'inventaire
if (isset($_POST['Se nourrir']['Réparer la base'])) {
    $ressourceId = (int) $_POST['ressource_id'];

    if(isset($_SESSION['inventoryRessources'][$ressourceId])) {
        $_SESSION['inventoryRessources'][$ressourceId]['quantite'];

        if ($_SESSION['inventoryRessources'][$ressourceId]['quantite'] <= 0) {
            unset($_SESSION['inventoryRessources'][$ressourceId]);
        }
    }
}

// Sauvegarde
if (isset($_POST['saveGameBTN'])) {
    saveGame();
    
}
// function saveGame(PDO $pdo, int $playerId, int $jour_actuel): void
// {
//     $sql = "UPDATE partie SET jour_actuel = :jour_actuel WHERE player_id = :player_id";
//     $stmt = $pdo->prepare($sql);
//     $stmt->execute([
//         'jour_actuel' => $jour_actuel,
//         'player_id' => $playerId]);
// }


// Jour
// for ($jour_actuel = 1; $jour_actuel <= 30; $jour_actuel++) {
    
//     if ($jour_actuel === 30) {
//         echo "Victoire";
//         break;
//     }
//     echo $jour_actuel;
// }

// Limite PdV 
$health_bar = false;
$stamina_bar = true;
$durability = true;
$nom = false;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invasion IA</title>
</head>
<body>
    <h2>Invasion IA</h2>
    <div class="joueurCaractéristiques">
        <h4>Nom:<?=htmlspecialchars($nom) ?></h4>
        <p>Points de vie : <?= htmlspecialchars($health_bar)?></p>
        <p>Energie : <?= htmlspecialchars($stamina_bar)?></p> 
        <p>Jour actuel: <?=htmlspecialchars($jour_actuel)?></p>
    </div>

    <form method="POST" action="">
        <button type="submit" name="recuperer">Récupérer des ressources</button>
    </form>
    
    <div class="inventory">
        <h4>Inventaire :</h4>
        <?php if (isset($_GET['nom']) && $search !== ''): ?>
            <?php if(!empty($ressource)): ?>
            <?php foreach($ressources as $ressource) ?>
                <p><?= htmlspecialchars($ressource['ressource_id']['nom']['type']); ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>    

    <div class="base">
        <h4>Base</h4>
        <!-- <p>Etat de la base : $base_status</p> -->
        <p>Durabilité : <?= htmlspecialchars($durability)?></p>
        <button type="submit">Réparer la base</button>

        <h4>Nourriture</h4>
        <!-- La barre des points de vie remonte -->
        <form method="POST" action="">
            <button type="submit" name="">Se nourrir</button>
        </form>
        
    </div>

    <!-- Sauvegarder / repose = passer la journée et fait remonter la stamina -->
    <form method="POST" action="">
        <button type="submit" name="saveGameBTN">Se reposer</button>
    </form>
    
    <!-- Quitter la partie -->
    <button><a href="accueil.php">Quitter la partie</a></button>

</body>
</html>