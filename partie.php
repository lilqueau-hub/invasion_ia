<?php
session_start();
require_once __DIR__ . '/fonctions.php';

$playerId = isset($_SESSION['player_id']) ? (int) $_SESSION['player_id'] : 0;
$playerInfo = $playerId > 0 ? getPlayer($pdo, $playerId) : [];
$baseInfo = infoBase($pdo);
$ressources = [];
$gameSave = $playerId > 0 ? getGame($pdo, $playerId) : [];

if (isset($_POST['recuperer'])) {
    $ressources = allRessources($pdo);
}

if (isset($_POST['reparer'])) {
    foreach ($baseInfo as &$InfoBase) {
        $InfoBase['durability'] = min(100, (int) $InfoBase['durability'] + 10);
    }
    unset($InfoBase);
}

if (isset($_POST['nourrir'])) {
    if ($playerInfo) {
        $playerInfo['health_bar'] = min(100, (int) $playerInfo['health_bar'] + 10);
        $playerInfo['stamina_bar'] = min(100, (int) $playerInfo['stamina_bar'] + 5);
    }
}

if (isset($_POST['saveGameBTN'])) {
    if ($playerId > 0) {
        $jourActuel = isset($gameSave['jour_actuel']) ? (int) $gameSave['jour_actuel'] : 1;
        saveGame($pdo, $playerId, $jourActuel);
        $gameSave = getGame($pdo, $playerId);
    }
}
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

        <!-- <h4>Nom :htmlspecialchars($nom</h4> -->
        
        <?php foreach ($PlayerInfo as $InfoPlayer): ?>
            <h4>Nom: <? htmlspecialchars($InfoPlayer['nom'])  ?></h4>
            <p>Points de vie: <?= htmlspecialchars($InfoPlayer['health_bar']) ?></p>
            <p>Energie: <?=htmlspecialchars($InfoPlayer['stamina_bar']) ?></p>
        <?php endforeach; ?>

        <?php if (!empty($gameSave)): ?>
            <p>Jour actuel : <?= htmlspecialchars((string) $gameSave['jour_actuel']) ?></p>
        <?php endif; ?>
    </div>

    <form method="POST" action="">
        <button type="submit" name="recuperer">Récupérer des ressources</button>
    </form>

    <div class="inventory">
        <h4>Inventaire :</h4>
        <?php if (!empty($ressources)): ?>
            <?php foreach ($ressources as $ressource): ?>
                <p><?= htmlspecialchars($ressource['nom']) ?> - <?= htmlspecialchars($ressource['type']) ?></p>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune ressource récupérée pour le moment.</p>
        <?php endif; ?>
    </div>

    <div class="base">
        <h4>Base</h4>
        <?php foreach ($baseInfo as $InfoBase): ?>
            <p>Durabilité : <?= htmlspecialchars((string) $InfoBase['durability']) ?></p>
        <?php endforeach; ?>

        <form method="POST" action="">
            <button type="submit" name="reparer">Réparer la base</button>
        </form>
        

        <h4>Nourriture</h4>
        <form method="POST" action="">
            <button type="submit" name="nourrir">Se nourrir</button>
        </form>
        
    </div>

    <form method="POST" action="">
        <button type="submit" name="saveGameBTN">Sauvegarder</button>
    </form>

    <!-- Passer la nuit, passer à un autre jour -->
    <!-- <form method="POST" action=""></form> -->
    
    <!-- Reset le jeu -->
    <form method="POST" action="">
        <button type="reset" name="resetGameBTN">Rejouer la partie</button>
    </form>
    

    <!-- Quitter la partie -->
    <button><a href="accueil.php">Quitter la partie</a></button>

</body>
</html>