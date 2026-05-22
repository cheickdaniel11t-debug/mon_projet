<?php 
/** 
 * Fichier de connexion PDO a MySQL 
 * A inclure avec : require_once '../config/connexion.php'; 
 */ 
 
// ── Parametres de connexion ────────────────────────────── 
$hote    = 'localhost';    // Adresse du serveur MySQL 
$base    = 'auth_tp';     // Nom de la base de donnees 
$user    = 'root';         // Utilisateur MySQL (root sur XAMPP) 
$pass    = '';             // Mot de passe MySQL (vide sur XAMPP) 
$charset = 'utf8mb4';      // Encodage des caracteres 
 
// ── Options PDO ────────────────────────────────────────── 
$options = [ 
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Affiche les erreurs 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Resultats en tableau associatif 
    PDO::ATTR_EMULATE_PREPARES  => false,                  // Vraies requetes preparees 
]; 
 
// ── DSN (Data Source Name) ─────────────────────────────── 
$dsn = "mysql:host=$hote;dbname=$base;charset=$charset"; 
 
// ── Tentative de connexion ─────────────────────────────── 
try { 
    $pdo = new PDO($dsn, $user, $pass, $options); 
    // Si on arrive ici, la connexion est OK 
} catch (PDOException $e) { 
    // En cas d'erreur, on affiche un message generique 
    // (ne jamais afficher $e->getMessage() en production !) 
    die("Erreur : Impossible de se connecter a la base de donnees."); 
}