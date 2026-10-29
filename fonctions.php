<?php require_once __DIR__ . '/database.php';

// Création de compte
function createPlayer(
    PDO $pdo,
    string $nom)
    {
        $sql = "INSERT INTO player (nom)
        VALUES(:nom)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom'=>$nom
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
function saveFile(PDO $pdo): array
{
    $sql = "SELECT * FROM partie";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Inventaire des ressources
function inventoryRessources(
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