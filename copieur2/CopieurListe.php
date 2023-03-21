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
    <title>Liste</title>
  </head>
  <body>
    <!--Navigation bar-->
    <?php  include_once 'nav.php'; ?>
    <div style="padding: 3px;">

    <h1>Liste</h1>
    <?php 
      $compte = 0;
      $sql = "SELECT copieur.id , matricule, numero_dossier, adresse_ip, modele_id, file_attente, service_id, copieur_site.site_id, date_achat, modele.libelle as libelModele, site.libelle as libelSite, service.libelle as libelService, adr1, adr2, cp, ville, chemin, fax, lecteur_badge, a3, couleur, recto_verso, logo, finisseur FROM copieur 
      left JOIN modele on modele.id  = modele_id
      left JOIN copieur_site on copieur.id  = copieur_id
      left JOIN copieur_service on  copieur_service.copieur_id = copieur.id 
      left JOIN organigramme.site on  copieur_site.site_id = site.id 
      left JOIN organigramme.service on  copieur_service.service_id = service.id
      left JOIN plan_site on plan_site.site_id  = copieur_site.site_id 
      WHERE copieur_service.date_depart IS NULL
      AND copieur_site.date_depart IS NULL
      AND date_reforme IS NULL";
      $query = $db->prepare($sql);
      $query->execute();
      $copieurs = $query->fetchAll(PDO::FETCH_ASSOC);   

    ?>
    <table class="table-bordered " table-dark id='table'>
      <thead class="thead-dark"> <!-- add class="thead-light" for a light header -->
        <tr>
        <th>N° série copieur</th>
          <th>Dossier</th>
          <th>Adresse IP</th>
          <th>Modèle</th>
          <th>Site</th>
          <th>Date d'acquisition</th>
          <th>Fax</th>
          <th>Lecteur de badge</th>
          <th>A3</th>
          <th>Recto-verso</th>
          <th>Couleur</th>
          <th>Logo</th>
          <th>Finisseur</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($copieurs as $copieur): $compte=$compte+1;?>
        <tr>
          <style>
          a:hover {
          color:#EDD200;
          text-decoration:none;
          }

          a{
          color:black;
          }
          </style>
          <td><a href="CopieurDetail.php?id=<?= $copieur['id'] ?>"><?php echo $copieur['matricule']; ?></a> </td>
          <td><?php echo $copieur['numero_dossier']; ?></td>
          <?php $ip = $copieur['adresse_ip']?>
          <td> <?php if ($copieur['adresse_ip'] == NULL) {?><b style='color: <?php echo $color; ?>;'>HORS RESEAU</b><?php } else{ echo "<a href= http://$ip > $ip ";   } ;?></td>
          <td><?php echo $copieur['libelModele']; ?></td>

          <?php 
          $plan =  $copieur['chemin'];
          $lib = API_LibelleComplet('site', $copieur['site_id'], ' - ');?>
          <td><?php echo $lib."<br>".API_Adresse($copieur['site_id']);?> <?php  if ($copieur['chemin']){  ?> <a href= <?php echo $plan; ?>>  <i class="bi bi-map-fill"style=" font-size: 1rem; color: cornflowerblue;"></i> <?php } ?> </a> </td> 
          <td> <?php $ladate= $copieur['date_achat']; echo date('d/m/Y', strtotime($ladate)); ?> </td>
          <td><?php if ($copieur['fax'] == 0) echo('non'); else echo('oui'); ?></td>
          <td><?php if ($copieur['lecteur_badge'] == 0) echo('non'); else echo('oui'); ?></td>
          <td><?php if ($copieur['a3'] == 0) echo('non'); else echo('oui'); ?></td>
          <td><?php if ($copieur['recto_verso'] == 0) echo('non'); else echo('oui'); ?></td>
          <td><?php if ($copieur['couleur'] == 0) echo('non'); else echo('oui'); ?></td>
          <td><?php if ($copieur['logo'] == 0) echo('non'); else echo('oui'); ?></td>
          <td><?php if ($copieur['finisseur'] == 'N') echo('non'); elseif ($copieur['finisseur'] == 'O') echo('oui') ; else echo $copieur['finisseur'];; ?></td>
        </tr>
        <?php endforeach; ?>
        Liste de tous les copieurs:(<?php echo($compte)?> )
      </tbody>
    </table>
    </div>
  </body>
</html>



