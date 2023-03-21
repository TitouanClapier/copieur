<?php
    // Déconnexion de l'utilisateur
    session_start(); 
    // Suppresion des variables de session utilisées
    $_SESSION["cop_identifiant"] = null;
    $_SESSION['cop_prenom'] = null;
	$_SESSION['cop_nom'] = null;
	$_SESSION['cop_role'] = null;
    
    $redirect= "<meta http-equiv='refresh' content='0; url=connexion.php'>";
    echo $redirect;
?>