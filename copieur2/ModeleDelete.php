<?php 
include_once 'Connexion_PDO_COPIEUR.php';

    $db = Connexion_PDO_COPIEUR::getInstance();
    $con = $db->getDbh();
    
    
    if(isset($_GET['id']) && !empty($_GET['id'])){
    
        $id = strip_tags($_GET['id']);
        $sql = "DELETE FROM `modele` WHERE `id`=:id;";
    
        $query = $con->prepare($sql);
    
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();

        echo ' <meta http-equiv="refresh" content="1; URL=ModeleListe.php">';
    }
    

    
?>

