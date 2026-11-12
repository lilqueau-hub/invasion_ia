<?php 
session_start();
require_once __DIR__ . 'src/fonctions.php';
require_once __DIR__ . '/vendor/autoload.php';

$playerId = (int) $_SESSION['player_id'];
// Informations du joueur
$playerInfo = getPlayer($pdo, $playerId);
// Information de l'abris
$baseInfo = infoBase($pdo, $playerId);
// Sauvegarde du joueur
$gameSave = getGame($pdo, $playerId);

//passer au jour suivant
if(isset($_POST['nextdayBTN']) && $gameSave !== false) {
    $jourActuel = (int) $gameSave['jour_actuel'];

    $nouveauJour = $jourActuel + 1;

    saveGame($pdo, $playerId, $nouveauJour);

    $gameSave = getGame($pdo, $playerId);
}

// Ressources
$ressources = [];
$allRessources = allRessources($pdo, $playerId);
// $addRessources = addRessources($pdo, $playerId);

// Sauvegarder le jeu
// if (isset($_POST['saveGameBTN'])) {
//     saveGame();
// }
// $GameSave = saveGame();

// Récupérer des ressources
if (isset($_POST['recuperer'])) {
    $allRessources = allRessources($pdo);
}
// if (isset($_POST['recuperer'])) {
//     try {
//         $query = "SELECT ressource_id, nom, type FROM ressources ORDER BY RAND() LIMIT 4 ";
//         $stmt = $pdo->query($query);
//         $ressources = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     } catch (PDOException $e) {
//             echo "Erreur de base de données :" . $e->getMessage();
//         }
// }

// Réparer la base
if (isset($_POST['reparer']) && $baseInfo !== false) {
    $baseInfo['durability'] = min(100, (int) $baseInfo['durability'] + 10);
}
// Se nourrir
if (isset($_POST['nourrir'])) 
    {
    if ($playerInfo) {
        $playerInfo['health_bar'] = min(
            100,
            (int) $playerInfo['health_bar'] + 10
            );
    }      
}

// Inventaire 
if (isset($_POST['stocker'])) {
    $playerId =(int) $_POST['player_id'];
    $inventoryRessources= inventoryRessources($pdo);
    $ressources = allRessources($pdo);

    if (isset($_SESSION['inventoryRessources'][$ressources][$inventoryRessources])) {
        $_SESSION['inventoryRessources']['quantite'];

        if ($_SESSION['inventoryRessources']['quantite'] <= 0) {
            unset($_SESSION['inventoryRessources']);
        }
    }
}

// Diminuer quantité de l'inventaire
if (isset($_POST['nourrir']['reparer'])) {
    $playerId = (int) $_POST['player_id'];
    $ressources= allRessources($pdo);

    if (isset($_SESSION['inventoryRessources'][$ressources])) {
        $_SESSION['inventoryRessources']['quantite'];

        if ($_SESSION['inventoryRessources']['quantite'] <= 0) {
            unset($_SESSION['inventoryRessources']);
        }
    }
}

// Se reposer
if (isset($_POST['passer'])) {
    $playerId =(int) $_POST['player_id'];
    $nextDay = nextDay($pdo);

    if(isset($_SESSION['nextDay']));
}

// if(isset($_POST['resetGameBTN'])) {
//     resetGame();
// }

// Jour
// for ($jour_actuel = 1; $jour_actuel <= 30; $jour_actuel++) {
    
//     if ($jour_actuel === 30) {
//         echo "Victoire";
//         break;
//     }
//     echo $jour_actuel;
// }
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
        
        <?php if ($playerInfo !== false): ?>
            <h4>Nom: <?= htmlspecialchars($playerInfo['nom']) ?></h4>
            <p>Points de vie: <?= htmlspecialchars((string) $playerInfo['health_bar']) ?></p>
            <p>Energie: <?= htmlspecialchars((string) $playerInfo['stamina_bar']) ?></p>
        <?php endif; ?>

        <?php if ($gameSave !== false): ?>
            <p>Jour actuel: <?= htmlspecialchars((string) $gameSave['jour_actuel']) ?></p>
        <?php endif; ?>
        
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
        <?php if ($baseInfo !== false): ?>
            <p>Durabilité: <?= htmlspecialchars((string) $baseInfo['durability']) ?></p>
        <?php endif; ?>
        <form method="POST">
            <button type="submit" name="addRessources">Réparer la base</button>
        </form>
        
        <h4>Nourriture</h4>
        <!-- La barre des points de vie remonte -->
        <form method="POST" action="">
            <button type="submit" name="nourrir">Se nourrir</button>
        </form>
        
    </div>

    <!-- Passer la nuit, passer à un autre jour -->
    <form method="POST" action="">
        <button type="submit" name="nextdayBTN">Passer au jour suivant</button>
    </form>

    <!-- Sauvegarder -->
    <form method="POST" action="">
        <button type="submit" name="saveGameBTN">Sauvegarder</button>
    </form>
    
    <!-- Reset le jeu -->
    <form method="POST" action="">
        <button type="reset" name="resetGameBTN">Rejouer la partie</button>
    </form>
    

    <!-- Quitter la partie -->
    <button><a href="/../accueil.php">Quitter la partie</a></button>

</body>
</html>