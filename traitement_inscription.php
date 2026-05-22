<?php 
session_start(); 
require_once 'config/connexion.php'; // Inclure la connexion PDO 
 
// ── 1. Verification de la methode HTTP ────────────────────────────── 
// Ce fichier ne doit etre appele qu'en POST (depuis le formulaire) 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: inscription.php'); 
    exit; 
} 
 
// ── 2. Recuperation et nettoyage des donnees ───────────────────────── 
// trim() supprime les espaces en debut et fin de chaine 
// htmlspecialchars() protege contre les injections HTML (XSS) 
$prenom      = trim($_POST['prenom']      ?? ''); 
$email       = trim($_POST['email']       ?? ''); 
$mdp         = trim($_POST['mot_de_passe']?? ''); 
$mdp_confirm = trim($_POST['confirmer_mdp']?? ''); 
 
// ── 3. Validation des donnees ──────────────────────────────────────── 
// On verifie que tous les champs sont remplis et corrects 
 
// 3a. Champs vides 
if (empty($prenom) || empty($email) || empty($mdp) || empty($mdp_confirm)) { 
    $_SESSION['erreur_inscription'] = "Tous les champs sont obligatoires."; 
    $_SESSION['old_prenom'] = $prenom; 
    $_SESSION['old_email']  = $email; 
    header('Location: inscription.php'); 
    exit; 
} 
 
// 3b. Validation du format de l'email 
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
    $_SESSION['erreur_inscription'] = "L'adresse email n'est pas valide."; 
    $_SESSION['old_prenom'] = $prenom; 
    header('Location: inscription.php'); 
    exit; 
} 
 
// 3c. Longueur du prenom 
if (strlen($prenom) < 2 || strlen($prenom) > 100) { 
    $_SESSION['erreur_inscription'] = "Le prenom doit avoir entre 2 et 100 caracteres."; 
    $_SESSION['old_email'] = $email; 
    header('Location: inscription.php'); 
    exit; 
} 
 
// 3d. Longueur du mot de passe 
if (strlen($mdp) < 8) { 
    $_SESSION['erreur_inscription'] = "Le mot de passe doit avoir au moins 8 caracteres."; 
    $_SESSION['old_prenom'] = $prenom; 
    $_SESSION['old_email']  = $email; 
    header('Location: inscription.php'); 
    exit; 
} 
 
// 3e. Confirmation du mot de passe 
if ($mdp !== $mdp_confirm) { 
    $_SESSION['erreur_inscription'] = "Les mots de passe ne correspondent pas."; 
    $_SESSION['old_prenom'] = $prenom; 
    $_SESSION['old_email']  = $email; 
    header('Location: inscription.php'); 
    exit; 
} 
 
// ── 4. Verification que l'email n'est pas deja utilise ─────────────── 
// Requete preparee pour eviter les injections SQL 
$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email LIMIT 1"); 
$stmt->execute([':email' => $email]); 
$utilisateur_existant = $stmt->fetch(); 
 
if ($utilisateur_existant) { 
    $_SESSION['erreur_inscription'] = "Cette adresse email est deja utilisee."; 
    $_SESSION['old_prenom'] = $prenom; 
    header('Location: inscription.php'); 
    exit; 
} 
 
// ── 5. Hashage du mot de passe ─────────────────────────────────────── 
// PASSWORD_DEFAULT utilise bcrypt, l'algorithme recommande par PHP 
// Ce hash est irreversible : on ne peut pas retrouver le mot de passe original 
$mdp_hache = password_hash($mdp, PASSWORD_DEFAULT); 
 
// ── 6. Insertion en base de donnees ───────────────────────────────── 
// Requete preparee avec des placeholders (:prenom, :email, :mdp) 
// Cela protege completement contre les injections SQL 
$stmt = $pdo->prepare( 
    "INSERT INTO utilisateurs (prenom, email, mot_de_passe) 
     VALUES (:prenom, :email, :mdp)" 
); 
 
$resultat = $stmt->execute([ 
    ':prenom' => htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8'), 
    ':email'  => $email, 
    ':mdp'    => $mdp_hache, // On insere le HASH, jamais le mot de passe en clair 
]); 
 
// ── 7. Redirection selon le resultat ──────────────────────────────── 
if ($resultat) { 
    // Succes : on informe l'utilisateur et on le redirige vers la connexion 
    $_SESSION['succes_inscription'] = "Compte cree avec succes ! Connectez-vous maintenant."; 
    header('Location: connexion.php'); 
} else { 
    // Echec inattendu 
    $_SESSION['erreur_inscription'] = "Une erreur est survenue. Veuillez reessayer."; 
    header('Location: inscription.php'); 
} 
exit;