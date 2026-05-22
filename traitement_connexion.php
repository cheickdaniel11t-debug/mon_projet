<?php 
session_start(); 
require_once 'config/connexion.php'; 
 
// ── 1. Methode POST uniquement 

if($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: connexion.php'); 
    exit; 
} 
 
// ── 2. Recuperation des donnees ────────────────────────────────────── 
$email = trim($_POST['email']        ?? ''); 
$mdp   = trim($_POST['mot_de_passe'] ?? ''); 
 
// ── 3. Verification basique ────────────────────────────────────────── 
if (empty($email) || empty($mdp)) { 
    $_SESSION['erreur_connexion'] = "Veuillez remplir tous les champs."; 
    header('Location: connexion.php'); 
    exit; 
} 
 
// ── 4. Recherche de l'utilisateur dans la BDD ──────────────────────── 
// On cherche l'utilisateur par son email 
// IMPORTANT : on ne compare pas encore le mot de passe ici, 
// car le mot de passe en BDD est un hash, pas le mot de passe en clair. 
$stmt = $pdo->prepare( 
    "SELECT id, prenom, email, mot_de_passe 
     FROM utilisateurs 
     WHERE email = :email 
     LIMIT 1" 
); 
$stmt->execute([':email' => $email]); 
$utilisateur = $stmt->fetch(); // Retourne un tableau ou false 
 
// ── 5. Verification du mot de passe avec password_verify() ────────── 
// password_verify() compare le mot de passe saisi avec le hash en BDD. 
// Elle retourne true si le mot de passe correspond, false sinon. 
// 
// SECURITE : Si l'email n'existe pas ($utilisateur est false), 
// on fait quand meme un appel a password_verify() avec une chaine vide. 
// Cela evite une attaque par "timing" qui permettrait de deviner 
// si un email existe dans la base. 
 
$hash_test = $utilisateur ? $utilisateur['mot_de_passe'] : ''; 
$mdp_correct = password_verify($mdp, $hash_test); 
 
if (!$utilisateur || !$mdp_correct) { 
    // Message d'erreur GENERIQUE : on ne dit pas si c'est l'email ou le mdp 
    // qui est incorrect, pour ne pas aider un attaquant. 
    $_SESSION['erreur_connexion']      = "Email ou mot de passe incorrect."; 
    $_SESSION['old_email_connexion']   = $email; 
    header('Location: connexion.php'); 
    exit; 
} 
 
// ── 6. Connexion reussie : creation de la session ───────────────────── 
// On regenere l'ID de session pour eviter les attaques de fixation de session 
session_regenerate_id(true); 
 
// On stocke les informations de l'utilisateur en session 
$_SESSION['utilisateur_id']     = $utilisateur['id']; 
$_SESSION['utilisateur_prenom'] = $utilisateur['prenom']; 
$_SESSION['utilisateur_email']  = $utilisateur['email']; 
 
// ── 7. Redirection vers la page protegee ───────────────────────────── 
header('Location: accueil.php'); 
exit;