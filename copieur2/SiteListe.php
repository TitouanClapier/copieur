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
    <title>gestion site</title>
  </head>
  <body>
    <!--Navigation bar-->
    <?php  include_once 'nav.php'; ?>
    <div style="padding: 3px;">

    <h1>Sites ayant des copieurs :</h1>
    <?php 

      $sql = "SELECT count(copieur_site.id) as nbcop,  copieur_site.site_id, chemin, libelle FROM copieur_site 
      left JOIN organigramme.site on  copieur_site.site_id = site.id 
      left JOIN plan_site on plan_site.site_id  = copieur_site.site_id 
      WHERE copieur_site.date_depart IS NULL
      group by site_id";
      
      $query = $db->prepare($sql);
      $query->execute();
      $sites = $query->fetchAll(PDO::FETCH_ASSOC);   

    ?>
    <table class="table-bordered " table-dark id='table'>
      <thead class="thead-dark"> <!-- add class="thead-light" for a light header -->
        <tr>
          <th>Site</th>
          <th>compteur</th>
          <th>plan du site</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($sites as $copieur): ?>
        <tr>
          <?php 
          $plan =  $copieur['chemin'];
          $lib = API_LibelleComplet('site', $copieur['site_id'], ' - ');
          ?>
          <td><?php echo $lib;?></td>
          <td><?php echo $copieur['nbcop']; ?></td>
          <td> <?php  if ($copieur['chemin']){  ?> <a href= <?php echo $plan; ?>>  <i class="bi bi-map-fill"style=" font-size: 1rem; color: cornflowerblue;"></i> <?php } ?> </a> </td> 

        </tr>
        <?php endforeach; ?>

      </tbody>
    </table>
    </div>
  </body>
</html>



