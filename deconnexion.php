<?php 
session_start(); 
 
// ── Destruction complete de la session ────────────────────────────── 
 
// Etape 1 : Vider toutes les variables de session 
$_SESSION = []; 
 
// Etape 2 : Supprimer le cookie de session dans le navigateur 
if (ini_get("session.use_cookies")) { 
    $params = session_get_cookie_params(); 
    setcookie( 
        session_name(), '', 
        time() - 42000, 
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"] 
    ); 
} 
 
// Etape 3 : Detruire la session cote serveur 
session_destroy(); 
 
// ── Redirection vers la page de connexion ───────────────────────── 
header('Location: connexion.php'); 
exit; 