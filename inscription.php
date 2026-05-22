<?php 
session_start(); // Demarrer la session pour afficher les messages 
?> 
<!DOCTYPE html> 
<html lang="fr"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Inscription</title> 
    <link rel="stylesheet" href="css/style.css"> 
</head> 
<body> 
 
<!-- Navigation --> 
<nav> 
    <a class="logo" href="#">MonSite</a> 
    <div> 
        <a href="inscription.php">S'inscrire</a> 
        <a href="connexion.php">Se connecter</a> 
    </div> 
</nav> 
 
<!-- Formulaire d'inscription --> 
<div class="container"> 
    <h1>Creer un compte</h1> 
    <p class="subtitle">Rejoignez-nous en quelques secondes</p> 
 
    <!-- Affichage des erreurs (si elles existent) --> 
    <?php if (!empty($_SESSION['erreur_inscription'])): ?> 
        <div class="alerte alerte-erreur"> 
            <?= htmlspecialchars($_SESSION['erreur_inscription']) ?> 
        </div> 
        <?php unset($_SESSION['erreur_inscription']); ?> 
    <?php endif; ?> 
 
    <!-- Affichage du succes --> 
    <?php if (!empty($_SESSION['succes_inscription'])): ?> 
        <div class="alerte alerte-succes"> 
            <?= htmlspecialchars($_SESSION['succes_inscription']) ?> 
        </div> 
        <?php unset($_SESSION['succes_inscription']); ?> 
    <?php endif; ?> 
 
    <!-- Le formulaire envoie les donnees en POST vers traitement_inscription.php --> 
    <form action="traitement_inscription.php" method="POST"> 
          <div class="form-group"> 
            <label for="nom">Nom de famille</label> 
            <input type="text" 
                   id="nom" 
                   name="Nom" 
                   placeholder="Entrez votre nom de famille" 
                   value="<?= htmlspecialchars($_SESSION['old_prenom'] ?? '') ?>" 
                   required> 
            <?php unset($_SESSION['old_nom']); ?> 
        </div> 
 
 
        <div class="form-group"> 
            <label for="prenom">Prenom</label> 
            <input type="text" 
                   id="prenom" 
                   name="prenom" 
                   placeholder="Entrez votre prenom" 
                   value="<?= htmlspecialchars($_SESSION['old_prenom'] ?? '') ?>" 
                   required> 
            <?php unset($_SESSION['old_prenom']); ?> 
        </div> 
 
        <div class="form-group"> 
            <label for="email">Adresse email</label> 
            <input type="email" 
                   id="email" 
                   name="email" 
                   placeholder="exemple@email.com" 
                   value="<?= htmlspecialchars($_SESSION['old_email'] ?? '') ?>" 
                   required> 
            <?php unset($_SESSION['old_email']); ?> 
        </div> 
 
        <div class="form-group"> 
            <label for="mot_de_passe">Mot de passe</label> 
            <input type="password" 
                   id="mot_de_passe" 
                   name="mot_de_passe" 
                   placeholder="Minimum 8 caracteres" 
                   required> 
        </div> 
 
        <div class="form-group"> 
            <label for="confirmer_mdp">Confirmer le mot de passe</label> 
            <input type="password" 
                   id="confirmer_mdp" 
                   name="confirmer_mdp" 
                   placeholder="Retapez le meme mot de passe" 
                   required> 
        </div> 
 
        <button type="submit" class="btn">Creer mon compte</button> 
    </form> 
 
    <div class="form-footer"> 
        Vous avez deja un compte ? 
        <a href="connexion.php">Se connecter</a> 
    </div> 
</div> 
 
</body> 
</html>