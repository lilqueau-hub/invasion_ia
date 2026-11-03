<?php require_once __DIR__ . '/database.php';

// Création de compte
function createPlayer(
    PDO $pdo,
    string $nom,
    string $email,
    string $hash){
        $sql = "INSERT INTO player (nom, email, password)
        VALUES (:nom, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'email' => $email,
            'password' => $hash
        ]);  
    }

function getPlayer(PDO $pdo, int $playerId): array|false
{
    $sql = "SELECT player_id, nom, health_bar, stamina_bar FROM player WHERE player_id = :player_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['player_id' => $playerId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Sauvegarder la partie
function saveGame(PDO $pdo, int $playerId, int $jour_actuel): void
{
    $sql = "UPDATE partie SET jour_actuel = :jour_actuel WHERE player_id = :player_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'jour_actuel' => $jour_actuel,
        'player_id' => $playerId]);
}

// Ressources
function allRessources(
    PDO $pdo,
    string $ressourceId,
    string $nom,
    string $type)
    {
        $sql = "INSERT INTO ressources (ressource_id, nom, type)
        VALUES(:nom, :type)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'ressource_id' => $ressourceId,
            'nom' => $nom,
            'type' => $type
        ]);

    }

// Stockage des ressourcess 
function inventoryRessources(PDO $pdo, int $playerId): array
{
    $sql = "SELECT r.nom, r.type, s.quantite
            FROM stocker s
            JOIN ressources r ON r.ressource_id = s.ressource_id
            WHERE s.player_id = :player_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['player_id' => $playerId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}