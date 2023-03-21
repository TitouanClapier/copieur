<?php
// including the database connection file

include_once 'head.php';
include_once 'Connexion_PDO_COPIEUR.php';
$db = Connexion_PDO_COPIEUR::getInstance();
$db = $db->getDbh();

if(isset($_GET['id']) && !empty($_GET['id'])){
    $id = strip_tags($_GET['id']);
    $sql = "SELECT * FROM `modele` WHERE `id`=:id;";

    $query = $db->prepare($sql);
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch();
}
require_once('close.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des modeles</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <h1>Modifier un modele</h1>
    <form action="ModeleUpdate" method="post">

        <p>
            <label for="libelle">libelle</label>
            <input type="text" name="libelle" id="libelle" value="<?= $result['libelle'] ?>">
        </p>
        <p>
            <label for="lien">lien</label>
            <input type="text" name="lien" id="lien" value="<?= $result['lien'] ?>">
        </p>
        
        <p>
            <button >Enregistrer </button>
        </p>
        <input type="hidden" name="id" value="<?= $result['id'] ?>">
        
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>
</html>