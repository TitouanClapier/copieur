<?php

namespace App\src\controller;
use App\src\controller\Controller;
use App\src\model\View;
use App\config\request;

//require_once '..\annuaire/api.php';


class ConnexionController extends Controller
{

    public function affichage()
    {

        

        return $this->view->twig('connexion.html.twig');

    }

    public function login()
    {

    require_once '../config/fonctions_AD.php';

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
//            $txt = date("Y-m-d H:i").' Connexion '.$ln.' rôle : '.$role.PHP_EOL ;
//		    error_log($txt, 3, "log/".date("Y-m-d")."Connexion.log");

            
            $_SESSION["cop_identifiant"] = $ln;
            $_SESSION['cop_prenom']=$AdElements['givenName'];
		    $_SESSION['cop_nom']=$AdElements['sn'];
		    $_SESSION['cop_role']=$role;
            $lien = "/copieurMVC/public/";
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
        
    }

    public function logout()
    {
        $this->session->destroy();
        
        $redirect= "<meta http-equiv='refresh' content='0; url=/copieurMVC/public/'>";
        echo $redirect;
        
    }
}