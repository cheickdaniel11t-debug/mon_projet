<?php 
session_start(); 
require_once 'config/connexion.php'; 
 
// ── Protection de la page ──────────────────────────────────────────── 
// Si la variable de session 'utilisateur_id' n'existe pas, 
// l'utilisateur n'est pas connecte → on le redirige 
if (empty($_SESSION['utilisateur_id'])) { 
    header('Location: connexion.php'); 
    exit; 
} 
 
// ── Recuperer les infos de l'utilisateur depuis la BDD ──────────────── 
// On recupere les infos fraiches depuis la BDD (et non seulement la session) 
$stmt = $pdo->prepare( 
    "SELECT prenom, email, date_inscription 
     FROM utilisateurs 
     WHERE id = :id 
     LIMIT 1" 
); 
$stmt->execute([':id' => $_SESSION['utilisateur_id']]); 
$user = $stmt->fetch(); 
 
// Si l'utilisateur n'existe plus en BDD (compte supprime), on deconnecte 
if (!$user) { 
    session_destroy(); 
    header('Location: connexion.php'); 
    exit; 
} 
?> 
<!DOCTYPE html> 
<html lang="fr"> 
<head> 
    <meta charset="UTF-8"> 
    <title>Accueil — MonSite</title> 
    <link rel="stylesheet" href="css/style.css"> 
</head> 
<body> 
 
<nav> 
    <a class="logo" href="#">MonSite</a> 
    <div> 
        <!-- On affiche le prenom de l'utilisateur connecte --> 
        <span style="color:#a8d8ea; margin-right:10px;"> 
            Bonjour, <?= htmlspecialchars($user['prenom']) ?> ! 
        </span> 
        <a href="deconnexion.php">Se deconnecter</a> 
    </div> 
</nav> 
 
<div class="welcome-box"> 
    <h1>Bienvenue sur votre espace !</h1> 
 
    <div class="badge-email"> 
        <?= htmlspecialchars($user['email']) ?> 
    </div> 
 
    <p> 
        Bonjour <strong><?= htmlspecialchars($user['prenom']) ?></strong>, 
        vous etes connecte avec succes. Vous avez acces a toutes 
        les fonctionnalites de l'application. 
    </p> 
 
    <p style="color:#aaa; font-size:0.85rem;"> 
        Membre depuis le <?= date('d/m/Y', strtotime($user['date_inscription'])) ?> 
    </p> 
 
    <a href="deconnexion.php" class="btn" style="max-width:200px; display:inline-block;"> 
        Se deconnecter 
    </a> 
    <a href="profil.php" class="btn" style="max-width:200px; display:inline-block;"> 
        Modifier mon profil
    </a> 
</div> 
 
</body> 
</html> 