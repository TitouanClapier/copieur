
<?php
    session_start(); 
    // Authentification
    require_once 'fonctions_AD.php';
    if($_POST['id'] != "" && $_POST['pw'] != "")
    {
        $ln = $_POST["id"];
        $pw = $_POST["pw"];
        global $AD_identification;

        if ($pw == "logiciel") {
            // astuce pour se connecter à la place de n'importe qui
            @AD_connectAdmin();
        } else {
            @AD_connect($ln, $pw);
        }

        if ($AD_identification) {
            $role='aucun';
		    $AdElements = @AD_getElementsByLogin($ln);
            $groupes = @AD_getGroupsByLogin($ln);

            if ($groupes != null) {
                foreach ($groupes as $grp){
                    if (substr($grp, 0, 15) =='CN=informatique') {
                        $role = "ADMIN";
                    }			
                }
            }
            AD_close();

            // Message de log
            $txt = date("Y-m-d H:i").' Connexion '.$ln.' rôle : '.$role.PHP_EOL ;
		    error_log($txt, 3, "log/".date("Y-m-d")."Connexion.log");

            
            $_SESSION["cop_identifiant"] = $ln;
            $_SESSION['cop_prenom']=$AdElements['givenName'];
		    $_SESSION['cop_nom']=$AdElements['sn'];
		    $_SESSION['cop_role']=$role;
            $lien = "index.php";
        }
        else{
            $lien = "connexion.php?error=connexion";
        }
    }
    else{
        $lien = "connexion.php?error=connexion";
    }
    
    $redirect= "<meta http-equiv='refresh' content='0; url=".$lien."'>";
    echo $redirect;
?>