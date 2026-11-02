<?php require_once __DIR__ . '/fonctions.php'; ?>

<?php 

// ITEMS
$allRessources = ['materials'];
$getItems = 'true';
// inventoryressources

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
    
</body>
</html>