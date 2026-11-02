<?php require_once __DIR__ . '/database.php';

// Création de compte

function createPlayer(
    PDO $pdo,
    string $nom,
    string $health_bar,
    string $stamina_bar){
        $sql = "INSERT INTO player (nom, health_bar, stamina_bar)
        VALUES (:nom, :health_bar, :stamina_bar)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'health_bar' => $health_bar,
            'stamina_bar' => $stamina_bar
        ]);  
    }

function getPlayer(PDO $pdo): array
{
    $sql = "SELECT * FROM player";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$player = getPlayer($pdo);

// Sauvegarder la partie
function saveGame(PDO $pdo): array
{
    $sql = "SELECT * FROM partie";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ressources
function allRessources(
    PDO $pdo,
    string $nom,
    string $type)
    {
        $sql = "INSERT INTO ressources (nom, type)
        VALUES(:nom, :type)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'type' => $type
        ]);

    }

function inventoryRessources(PDO $pdo): array
{
    $sql = "SELECT * FROM ressources";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRest(PDO $pdo){
   $sql = "SELECT health_bar FROM player";
    $stmt = $pdo->query($sql); 
    return $stmt->fetchAll (PDO::FETCH_ASSOC);
}
