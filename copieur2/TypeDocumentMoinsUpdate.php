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

        $invest_fonct = strip_tags($_POST['invest_fonct']-1);
        $sql = "UPDATE `type_document` SET  `num_ordre`=:num_ordre WHERE `id`=:id;";
        $query = $db->prepare($sql);
        $tab=array('num_ordre'=>$num_ordre);
        $query->execute($tab);
        
    
}
echo ' <meta http-equiv="refresh" content="0; URL=TypeDocumentListe.php">';
?>
