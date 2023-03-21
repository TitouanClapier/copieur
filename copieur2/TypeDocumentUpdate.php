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
        $num_ordre = strip_tags($_POST['num_ordre']);
        $invest_fonct = strip_tags($_POST['invest_fonct']);
        $sql = "UPDATE `type_document` SET `libelle`=:libelle, `num_ordre`=:num_ordre, `invest_fonct`=:invest_fonct WHERE `id`=:id;";
        $query = $db->prepare($sql);
        $tab=array('libelle'=>$libelle, 'num_ordre'=>$num_ordre, 'id'=>$id, 'invest_fonct'=>$invest_fonct);
        $query->execute($tab);
        
    
}
echo ' <meta http-equiv="refresh" content="0; URL=TypeDocumentListe.php">';
?>
