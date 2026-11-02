<?php require_once __DIR__ . '/fonctions.php'; ?>

<?php 

// ITEMS

// inventaire/stockage

// Jour
for ($day = 1; $day <= 30; $day++) {
    if ($day === 30) {
        echo "Victoire";
        break;
    }
    echo $day;
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
        <h4><?=htmlspecialchars($nom)?></h4>
        <p>Points de vie : <?= htmlspecialchars($health_bar)?></p>
        <p>Energie : <?= htmlspecialchars($stamina_bar)?></p> 
    </div>

    <div class="inventory">
        <h4>Inventaire :</h4>
        <p><?= htmlspecialchars($ressources)?></p>
    </div>

    <div class="base">
        <h4>Base</h4>
        <!-- <p>Etat de la base : $base_status</p> -->
        <button type="">Réparer la base</button>
    </div>

    <!-- La barre des points de vie remonte -->
    <button type="">Se nourrir</button>

    <!-- Se reposer = passe la journée et fait remonter la stamina. -->
    <button><a href="">Se reposer</a></button>

    <!-- Sauvegarder -->
    <button><a href="">Sauvegarder la partie</a></button>
    
    <!-- Quitter la partie -->
    <button><a href="invasion_ia/accueil.php">Quitter la partie</a></button>
</body>
</html>