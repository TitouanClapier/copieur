<?php
// including the database connection file

include_once 'head.php';
include_once 'Connexion_PDO_COPIEUR.php';
$db = Connexion_PDO_COPIEUR::getInstance();
$db = $db->getDbh();

foreach ($_POST as $key=>$value){
    if(substr($key,0,4)=='btn2'){
        $id=substr($key,4);
    }
}
if(isset($_POST)){
    
        $libelle = strip_tags($_POST['libelle']);
        $lien = strip_tags($_POST['lien']);
        $sql = "UPDATE `modele` SET `libelle`=:libelle, `lien`=:lien WHERE `id`=:id;";
        $query = $db->prepare($sql);
        $tab=array('libelle'=>$libelle, 'lien'=>$lien, 'id'=>$id);
        $query->execute($tab);
        
    
}
echo ' <meta http-equiv="refresh" content="0; URL=ModeleListe.php">';
?>
