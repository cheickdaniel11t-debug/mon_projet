<?php 
session_start(); 
 
// Si l'utilisateur est deja connecte, le rediriger vers l'accueil 
if (!empty($_SESSION['utilisateur_id'])) { 
    header('Location: accueil.php'); 
    exit; 
} 
?> 
<!DOCTYPE html> 
<html lang="fr"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Connexion</title> 
    <link rel="stylesheet" href="css/style.css"> 
</head> 
<body> 
 
<nav> 
    <a class="logo" href="#">MonSite</a> 
    <div> 
        <a href="inscription.php">S'inscrire</a> 
        <a href="connexion.php">Se connecter</a> 
    </div> 
</nav> 
 
<div class="container"> 
    <h1>Connexion</h1> 
    <p class="subtitle">Heureux de vous revoir !</p> 
 
    <!-- Message de succes apres inscription --> 
    <?php if (!empty($_SESSION['succes_inscription'])): ?> 
        <div class="alerte alerte-succes"> 
            <?= htmlspecialchars($_SESSION['succes_inscription']) ?> 
        </div> 
        <?php unset($_SESSION['succes_inscription']); ?> 
    <?php endif; ?> 
 
    <!-- Message d'erreur de connexion --> 
    <?php if (!empty($_SESSION['erreur_connexion'])): ?> 
        <div class="alerte alerte-erreur"> 
            <?= htmlspecialchars($_SESSION['erreur_connexion']) ?> 
        </div> 
        <?php unset($_SESSION['erreur_connexion']); ?> 
    <?php endif; ?> 
 
    <form action="traitement_connexion.php" method="POST"> 
 
        <div class="form-group"> 
            <label for="email">Adresse email</label> 
            <input type="email" 
                   id="email" 
                   name="email" 
                   placeholder="exemple@email.com" 
                   value="<?= htmlspecialchars($_SESSION['old_email_connexion'] ?? '') ?>" 
                   required> 
            <?php unset($_SESSION['old_email_connexion']); ?> 
        </div> 
 
        <div class="form-group"> 
            <label for="mot_de_passe">Mot de passe</label> 
            <input type="password" 
                   id="mot_de_passe" 
                   name="mot_de_passe" 
                   placeholder="Votre mot de passe" 
                   required> 
        </div> 
 
        <button type="submit" class="btn">Se connecter</button> 
    </form> 
 
    <div class="form-footer"> 
        Pas encore de compte ? 
        <a href="inscription.php">S'inscrire gratuitement</a> 
    </div> 
</div> 
 
</body> 
</html>