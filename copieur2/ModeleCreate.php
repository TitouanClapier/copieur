<?php 
include_once 'Connexion_PDO_COPIEUR.php';

    $db = Connexion_PDO_COPIEUR::getInstance();
    $con = $db->getDbh();
    
if(isset($_POST)){
    if (isset($_POST['libelle']) && !empty($_POST['libelle']) && isset($_POST['lien']) && !empty($_POST['lien'])){
            $lien = $_POST['lien'];
            $libelle = $_POST['libelle'];
            $sql="INSERT INTO modele (libelle, lien) VALUES ( :libelle, :lien);";
            $query = $con->prepare($sql);
            $tab=array('libelle'=>$libelle, 'lien'=>$lien);
            $query->execute($tab);
            
        }
    }

?>

