<?php 
include_once 'Connexion_PDO_COPIEUR.php';

    $db = Connexion_PDO_COPIEUR::getInstance();
    $con = $db->getDbh();
    
if(isset($_POST)){
    if (isset($_POST['libelle']) && !empty($_POST['libelle']) && isset($_POST['num_ordre']) && !empty($_POST['num_ordre']) && isset($_POST['invest_fonct']) && !empty($_POST['invest_fonct'])){
            $num_ordre = $_POST['num_ordre'];
            $libelle = $_POST['libelle'];
            $invest_fonct = $_POST['invest_fonct'];
            $sql="INSERT INTO type_document (libelle, num_ordre, invest_fonct) VALUES ( :libelle, :num_ordre, :invest_fonct);";
            $query = $con->prepare($sql);
            $tab=array('libelle'=>$libelle, 'num_ordre'=>$num_ordre, 'invest_fonct'=>$invest_fonct);
            $query->execute($tab);
            
        }
    }

?>

