<?php require_once __DIR__ . '/fonctions.php';

//joueur

$search = '';
if (isset($_GET['nom'])) {
    $search = trim($_GET['nom']);
    if ($search !== '') {
        $player = getPlayer($pdo, $search);
    }
}

// energie
if ($stamina_bar <=0) {
    echo "La barre de stamina est vide";
} else if ($stamina_bar <=25) {
    echo "La barre de stamina est quasiment vide";
} else {
    echo "La barre de stamina est pleine";
} 

// santé
if ($health <= 0) {
    echo "Défaite";
} elseif ($health < 30) {
    echo "Le personnage est en danger";
} elseif ($health < 70) {
    echo "Personnage est blessé";
} else {
    echo "En forme <br>" ;
}

?>
<?php $message = ''; ?>

<?php if ($message !== ''): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST">
    <h3>Création de personnage</h3>

    <label for="nom">Nom :</label>
    <input type="hidden" name="id" value="<?= $player['player_id'] ?>">
    <button type="submit">Enregistrer</button>

</form>

<!-- function createPlayer -->