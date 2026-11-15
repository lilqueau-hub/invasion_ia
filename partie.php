<?php 
session_start();
require_once __DIR__ . '/fonctions.php';

$playerId = (int) $_SESSION['player_id'];
// Informations du joueur
$playerInfo = getPlayer($pdo, $playerId);
// Information de l'abris
$baseInfo = infoBase($pdo, $playerId);
// Sauvegarde du joueur
$gameSave = getGame($pdo, $playerId);

if ($gameSave === false) {
    createGame($pdo, $playerId);
    $gameSave = getGame($pdo, $playerId);
}

nextDay($pdo, $playerId, $baseInfo['base_id'], $baseInfo['durability'], (int) $gameSave['jour_actuel'], (int) $playerInfo['stamina_bar'], (int) $playerInfo['health_bar']);

// Passer au jour suivant
if (isset($_POST['nextdayBTN']) && $gameSave !== false) {
    $jourActuel = (int) $gameSave['jour_actuel'];
    if ($jourActuel >= 30) {
        endGame($pdo, $playerId, $jourActuel);
    } else {
        nextDay($pdo, $playerId, (int) ($baseInfo['base_id'] ?? 0), (int) ($baseInfo['durability'] ?? 0), $jourActuel, (int) ($playerInfo['stamina_bar'] ?? 0), (int) ($playerInfo['health_bar'] ?? 0));
    }
    $gameSave = getGame($pdo, $playerId);
}

// Ressources
$ressources = [];
// $addRessources = addRessources($pdo, $playerId);

// Récupérer des ressources
if (isset($_POST['recuperer'])) {
    $allRessources = allRessources($pdo);
}
if (isset($_POST['recuperer'])) {
    try {
        $query = "SELECT ressource_id, nom, type FROM ressources ORDER BY RAND() LIMIT 4 ";
        $stmt = $pdo->query($query);
        $ressources = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
            echo "Erreur de base de données :" . $e->getMessage();
        }
}

// Les effets ne sont appliqués qu'après l'action correspondante.
if (isset($_POST['reparer']) && $baseInfo !== false) {
    effectMaterials($pdo, (int) $baseInfo['base_id'], 10);
    $baseInfo = infoBase($pdo, $playerId);
}

if (isset($_POST['nourrir']) && $playerInfo !== false) {
    effectFood($pdo, $playerId, 10);
    $playerInfo = getPlayer($pdo, $playerId);
}

// Inventaire 
if (isset($_POST['stocker'])) {
    $playerId =(int) $_POST['player_id'];
    $inventoryRessources = inventoryRessources($pdo, $playerId);
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

// if(isset($_POST['resetGameBTN'])) {
//     resetGame();
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
        <?php if (!empty($ressources)): ?>
            <?php foreach ($ressources as $ressource): ?>
                <p><?= htmlspecialchars((string) $ressource['nom']) ?> - <?= htmlspecialchars((string) $ressource['type']) ?></p>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune ressource disponible pour le moment.</p>
        <?php endif; ?>
    </div>    

    <div class="base">
        <h4>Base</h4>
        <!-- <p>Etat de la base : $base_status</p> -->
        <?php if ($baseInfo !== false): ?>
            <p>Durabilité: <?= htmlspecialchars((string) $baseInfo['durability']) ?></p>
        <?php endif; ?>
        <form method="POST">
            <button type="submit" name="reparer">Réparer la base</button>
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