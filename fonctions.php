<?php require_once __DIR__ . '/database.php';

// Création de compte
function createPlayer(
    PDO $pdo,
    string $nom,
    string $email,
    string $hash): void 
    {
        $sql = "INSERT INTO player (nom, email, password)
        VALUES (:nom, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'email' => $email,
            'password' => $hash
        ]);  
    }

// Trouver le joueur 
function getPlayer(PDO $pdo, int $playerId): array|false
{
    $sql = "SELECT player_id, nom, health_bar, stamina_bar FROM player WHERE player_id = :player_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['player_id' => $playerId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


//créer la partie
function createGame(PDO $pdo, int $playerId): void
{
    $sql = "INSERT INTO partie (player_id, jour_actuel)
            VALUES (:player_id, 1)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'player_id' => $playerId
    ]);
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

// récuperer la partie du joueur
function getGame(PDO $pdo, int $playerId): array|false
{
    $sql = "SELECT * FROM partie
    WHERE player_id = :player_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['player_id' => $playerId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Données du joueur
// function infoPlayer(PDO $pdo, int $playerId, int $baseId, int $health_bar, int $stamina_bar, int $durability) : array
// {
//     $sql = "SELECT b.durability, p.health_bar, p.stamina_bar
//             FROM base b
//             JOIN player p ON p.player_id = b.player_id
//             WHERE b.base_id = :base_id" ;
//     $stmt = $pdo->prepare($sql);
//     $stmt ->execute(['base_id' => $baseId]);
//     return $stmt->fetch(PDO::FETCH_ASSOC);
// }

// function infoPlayer(PDO $pdo): array
// {
//     $sql = "SELECT player_id, nom, health_bar, stamina_bar
//             FROM player 
//             WHERE player_id = :player_id";
//     $stmt = $pdo->prepare($sql);

//     $stmt->execute([
//         'player_id' => $playerId
//     ]);
//     return $stmt->fetch(PDO::FETCH_ASSOC);
// }

function infoBase(PDO $pdo, int $playerId): array|false
{
    $sql = "SELECT b.base_id, b.durability
            FROM base b
            JOIN partie p
            ON p.partie_id = b.partie_id
            Where p.player_id = :player_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'player_id' => $playerId
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Ressources
function allRessources(PDO $pdo): array
    {
        $sql = "SELECT ressource_id, nom, type
                FROM ressources
                ORDER BY RAND()
                LIMIT 4";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

// ajouter de l'inventaire
function addRessources(PDO $pdo, int $partieId, int $ressourceId, int $quantite): void
{
    $sql = "INSERT INTO stocker(ressource_id, partie_id, quantite) 
            VALUES(:ressource_id, :partie_id, :quantite)
            ON DUPLICATE KEY UPDATE quantite = quantite + VALUES(quantite)";
    $stmt = $pdo->prepare($sql);
    $stmt -> execute([
        'partie_id' => $partieId,
        'ressource_id' => $ressourceId,
        'quantite' => $quantite
    ]);
}


// function resetGame(PDO $pdo, int $partieId, int $playerId):array
// {
//     $sql =""
// }

// function resetGame (PDO $pdo, int $playerId, int $jour_actuel, int $partieId, int $quantite): void
// {
//     $sql = "UPDATE partie SET jour_actuel"

// }

// Diminuer les PDV et l'énergie
// function losePlayer (PDO $pdo, int $playerId): array 
// {
//     $sql = "SELECT p.health_bar, p.stamina_bar
//             FROM player p
//             
// }
// player_id, nom, health_bar, stamina_bar FROM player WHERE player_id = :player_id"

// Conséquences d'actions sur diff ressource