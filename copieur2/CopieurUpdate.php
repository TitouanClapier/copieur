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

        $matricule = strip_tags($_POST['matricule']);
        $numero_ugap = strip_tags($_POST['numero_ugap']);
        $numero_dossier = strip_tags($_POST['numero_dossier']);
        $file_attente = strip_tags($_POST['file_attente']);
        $adresse_ip = strip_tags($_POST['adresse_ip']);
        $commentaire = strip_tags($_POST['commentaire']);
        $date_achat = strip_tags($_POST['date_achat']);
        $date_livraison = strip_tags($_POST['date_livraison']);
        $date_reforme = strip_tags($_POST['date_reforme']);

        
        $sql = "UPDATE `copieur` SET 
        `matricule`=:matricule, 
        `numero_ugap`=:numero_ugap ,
        `numero_dossier`=:numero_dossier, 
        `file_attente`=:file_attente ,
        `adresse_ip`=:adresse_ip, 
        `commentaire`=:commentaire ,
        `date_achat`=:date_achat, 
        `date_livraison`=:date_livraison, 
        `date_reforme`=:date_reforme, 
        WHERE `id`=:id;";
        $query = $db->prepare($sql);
        $tab=array('matricule'=>$matricule, 'numero_ugap'=>$numero_ugap, 'numero_dossier'=>$numero_dossier, 'file_attente'=>$file_attente, 'adresse_ip'=>$adresse_ip, 'commentaire'=>$commentaire, 'date_achat'=>$date_achat, 'date_livraison'=>$date_livraison, 'date_reforme'=>$date_reforme, 'id'=>$id);
        $query->execute($tab);
        
    
}
echo ' <meta http-equiv="refresh" content="5; URL=CopieurDetail.php?id=:id">';
