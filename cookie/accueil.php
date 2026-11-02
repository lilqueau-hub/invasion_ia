<?php
if (isset($_COOKIE['nom'])) {
    $nom = $_COOKIE['nom'];
} else {
    $nom = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Après l'invasion de l'IA</title>
</head>
<body>
    <h1>Après l'invasion de l'IA</h1>

    <button>Se déconnecter</button>

    <?php if ($nom !== ''): ?>
    <form action="supprimer_cookie.php" method="POST">
        <button type="submit">Supprimer les cookies</button>
    </form>

    <?php else: ?>
        <p>Bonjour <?= htmlspecialchars($nom) ?></p>
        
        <div class="presentation">
            <h4>Contexte</h4>
            
            <p>L'intelligence artificielle a envahi le monde.</p>
            <p>Vous incarnez un survivant.</p>

            <h4>Objectifs :</h4>
            <ul>
                <li>Rester en vie pendant 30 jours.</li>
                <li>Gérer vos ressources.</li>
                <li>Prenez les bonnes décisions.</li>
            </ul>
        </div>
    <?php endif; ?>
    <button><a href="partie.php">Commencer la survie</a></button>
    <!-- <button><a href="">Reprendre la partie</a></button> -->
</body>
</html>