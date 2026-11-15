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

    createBase($pdo, (int) $pdo->lastInsertId());
}

function createBase(PDO $pdo, int $partieId): void
{
    $sql = "INSERT INTO base (partie_id, durability)
            VALUES (:partie_id, 50)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['partie_id' => $partieId]);
}

// Sauvegarder
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


// Informations sur l'abris
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

// Ressources différentes ayant un impact différent selon leur type sur la santé et l'endurance du joueur
function impactRessources(PDO $pdo, int $playerId, string $type, int $baseId, int $quantite): void
{
    $gain = $quantite * 10;

    switch ($type) {
        case 'nourriture':
            $sql = "UPDATE player
                    SET health_bar = LEAST(100, health_bar + :gain)
                    WHERE player_id = :player_id";
            $params = [
                'gain' => $gain,
                'player_id' => $playerId,
            ];
            break;

        case 'materiaux':
            $sql = "UPDATE base
                    SET durability = LEAST(100, durability + :gain)
                    WHERE base_id = :base_id";
            $params = [
                'gain' => $gain,
                'base_id' => $baseId,
            ];
            break;

        default:
            return;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
}

// appliquer les effets nourriture sur santé du joueur
function effectFood(PDO $pdo, int $playerId, int $health_bar): void
{
    $sql = "UPDATE player SET health_bar = LEAST(100, health_bar + :health_gain)
            WHERE player_id = :player_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'health_gain' => $health_bar,
        'player_id' => $playerId
    ]);
}
// appliquer les effets matériaux sur la durabilité de l'abris
function effectMaterials (PDO $pdo, int $baseId, int $durability): void
{
    $sql = "UPDATE base SET durability = LEAST(100, durability + :durability_gain)
            WHERE base_id = :base_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'durability_gain' => $durability,
        'base_id' => $baseId
    ]);
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

// Conséquence des actions 
function loseStamina(PDO $pdo, int $playerId, int $amount): void
{
    $sql = "UPDATE player
            SET stamina_bar = GREATEST(0, stamina_bar - :amount)
            WHERE player_id = :player_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'amount' => $amount,
        'player_id' => $playerId,
    ]);
}
// Perte de santé du joueur
function loseHealth(PDO $pdo, int $playerId, int $amount): void
{
    $sql = "UPDATE player
            SET health_bar = GREATEST(0, health_bar - :amount)
            WHERE player_id = :player_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'amount' => $amount,
        'player_id' => $playerId,
    ]);
}

// function losePlayer (PDO $pdo, int $playerId): array 
// {
//     $sql = "SELECT p.health_bar, p.stamina_bar
//             FROM player p
//             
// }
// player_id, nom, health_bar, stamina_bar FROM player WHERE player_id = :player_id"


// Passer au jour suivant
function nextDay(PDO $pdo, int $playerId, int $baseId, int $baseDurability, int $jour_actuel, int $stamina_bar, int $health_bar): void
{
    $newJour = $jour_actuel + 1;
    $sql = "UPDATE partie SET jour_actuel = :jour_actuel WHERE player_id = :player_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'jour_actuel' => $newJour,
        'player_id' => $playerId,
    ]);

    if ($newJour >= 30) {
        endGame($pdo, $playerId, $newJour);
    }
}

function endGame(PDO $pdo, int $playerId, ?int $jour_actuel = null): void
{
    $lastDay = $jour_actuel !== null ? max(30, $jour_actuel) : 30;
    $sql = "UPDATE partie SET jour_actuel = :jour_actuel WHERE player_id = :player_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'jour_actuel' => $lastDay,
        'player_id' => $playerId,
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

