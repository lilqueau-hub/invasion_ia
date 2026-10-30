<?php require_once __DIR__ . '/fonctions'; ?>

<?php 

// ITEMS
$allRessources = ['materials'];
$huntFood = 'true';

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
    
</body>
</html>