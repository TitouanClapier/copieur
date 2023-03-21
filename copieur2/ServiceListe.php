<html lang="fr">
  <head>  
    <?php 
      session_start();
      
      include_once 'head.php';
      require_once '../annuaire/api.php';
      include_once 'Connexion_PDO_COPIEUR.php';
      $db = Connexion_PDO_COPIEUR::getInstance();
      $db = $db->getDbh();

      include_once 'Connexion_PDO_ORGANIGRAMME.php';
      $dborg = Connexion_PDO_ORGANIGRAMME::getInstance();
      $dborg = $dborg->getDbh();
      $color = 'red';
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>gestion service</title>
  </head>
  <body>
    <!--Navigation bar-->
    <?php  include_once 'nav.php'; ?>
    <div style="padding: 3px;">
    
    <h1>services ayant des copieurs :</h1>
    <?php 
      $sql = "SELECT count(copieur_service.id) as nbcop,  copieur_service.service_id, libelle FROM copieur_service 
      left JOIN organigramme.service on  copieur_service.service_id = service.id 
      WHERE copieur_service.date_depart IS NULL
      group by service_id";
      
      $query = $db->prepare($sql);
      $query->execute();
      $copieurs = $query->fetchAll(PDO::FETCH_ASSOC);   
    ?>
    <table class="table-bordered " table-dark id='table'>
      <thead class="thead-dark"> <!-- add class="thead-light" for a light header -->
        <tr>
          <th>service</th>
          <th>compteur</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($copieurs as $copieur): ?>
        <tr>
          <?php $lib = API_LibelleComplet('service', $copieur['service_id'], ' - ');?>
          <td><?php echo $lib;?></td>
          <td><?php echo $copieur['nbcop']; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  </body>
</html>



