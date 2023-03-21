<?php
// including the database connection file

include_once 'head.php';
include_once 'Connexion_PDO_COPIEUR.php';
$db = Connexion_PDO_COPIEUR::getInstance();
$db = $db->getDbh();


if(isset($_POST)){
    
        $id = strip_tags($_GET['id']);
        $num_ordre = strip_tags($_GET['num_ordre']+1);
        $sql = "UPDATE `type_document` SET `libelle`=:libelle, `num_ordre`=:num_ordre, `invest_fonct`=:invest_fonct WHERE `id`=:id;";
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id,  PDO::PARAM_INT);
        $query->execute();
        
    
}

echo ' <meta http-equiv="refresh" content="10; URL=TypeDocumentListe.php">';
?>
