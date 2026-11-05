<?php require_once __DIR__ . '/fonctions.php'; ?>

<?php 

// Informations du joueur
$PlayerInfo = infoPlayer();

// Information de la base
$BaseInfo = infoBase();

// Récupérer des ressources
if (isset($_POST['recuperer'])) {
    try {
        $query = "SELECT ressource_id, nom, type FROM ressources ORDER BY RAND() LIMIT 4 ";
        $stmt = $pdo->query($query);
        $ressources = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
            echo "Erreur de base de données :" . $e->getMessage();
        }
}

// Diminuer quantité de l'inventaire
if (isset($_POST['nourrir']['reparer'])) {
    $playerId = (int) $_POST['player_id'];
    $ressources= allRessources();

    if (isset($_SESSION['inventoryRessources'][$ressources])) {
        $_SESSION['inventoryRessources']['quantite'];

        if ($_SESSION['inventoryRessources']['quantite'] <= 0) {
            unset($_SESSION['inventoryRessources']);
        }
    }
}

// Sauvegarde
if (isset($_POST['saveGameBTN'])) {
    saveGame();
}
$GameSave = saveGame();

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

// Limite PdV 
// $health_bar = true;
// $stamina_bar = true;
// $durability = true;
// $nom = false;
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

        <?php foreach($GameSave as $SavingGame) ?>
        <p>Jour actuel: <?=htmlspecialchars($SavingGame['jour_actuel'])?></p>
        
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
        <?php foreach ($BaseInfo as $InfoBase): ?>
            <p>Durabilité :<? htmlspecialchars($InfoBase['durability']) ?> </p>
        <?php endforeach; ?>
        <form method="POST">
            <button type="submit" name="addRessources">Réparer la base</button>
        </form>
        

        <h4>Nourriture</h4>
        <!-- La barre des points de vie remonte -->
        <form method="POST" action="">
            <button type="submit" name="nourrir">Se nourrir</button>
        </form>
        
    </div>

    <!-- Sauvegarder -->
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