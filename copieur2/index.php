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
  $color2 = 'orange';
  $color3 = 'green';
  ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accueil</title>
</head>

<body>
  <!--Navigation bar-->
  <?php include_once 'nav.php'; ?>
  <div style="padding: 5px;">

    <h1>home</h1>
    <?php
    $compte = 0;
    $sql = "SELECT copieur.id , matricule, numero_dossier, adresse_ip, modele_id, file_attente, service_id, copieur_site.site_id, date_achat,
      modele.libelle as libelModele, site.libelle as libelSite, service.libelle as libelService, adr1, adr2, cp, ville, chemin, date_debut,
      duree, max(date_releve) as DateReleve FROM copieur 
      left JOIN modele on modele.id  = modele_id
      left JOIN copieur_site on copieur.id  = copieur_id
      left JOIN copieur_service on  copieur_service.copieur_id = copieur.id 
      left JOIN organigramme.site on  copieur_site.site_id = site.id 
      left JOIN organigramme.service on  copieur_service.service_id = service.id
      left JOIN plan_site on plan_site.site_id  = copieur_site.site_id 
      left JOIN contrat on contrat.copieur_id  = copieur.id 
      left JOIN compteur on compteur.copieur_id  = copieur.id 
      WHERE copieur_service.date_depart IS NULL
      AND copieur_site.date_depart IS NULL
      AND date_reforme IS NULL
      group by copieur.id;";

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
          <th>File attente</th>
          <th>Site</th>
          <th>Service</th>
          <th>Date d'acquisition</th>
          <th>Dernier relevé</th>
          <th>Fin de contrat</th>

        </tr>
      </thead>
      <tbody>
        <?php foreach ($copieurs as $copieur) : $compte = $compte + 1; ?>
          <tr>


            <style>
              a:hover {
                color: #EDD200;

              }

              a {
                color: black;
              }
            </style>
            <td> <u> <a href="CopieurDetail.php?id=<?= $copieur['id'] ?>"><?php echo $copieur['matricule']; ?></a> <u> </td>
            <td><?php echo $copieur['numero_dossier']; ?></td>
            <td><?php $ip = $copieur['adresse_ip'];
                $regex = '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/';
                if (preg_match($regex, $ip)) {
                  echo " <u> <a href= http://$ip > $ip <u>  ";
                } else {
                ?>
                <b style='color: <?php echo $color; ?>;'>HORS RESEAU</b><?php
                                                                        // l'adresse IP n'est pas dans le bon format
                                                                      } ?>
            </td>

            <td><?php echo $copieur['libelModele']; ?></td>
            <td><?php echo $copieur['file_attente']; ?></td>

            <?php
            $plan =  $copieur['chemin'];
            $lib = API_LibelleComplet('site', $copieur['site_id'], ' - ');
            ?><td><?php echo $lib . "<br>" . API_Adresse($copieur['site_id']); ?> <?php if ($copieur['chemin']) {  ?> <a href=<?php echo $plan; ?>> <i class="bi bi-map-fill" style=" font-size: 1rem; color: cornflowerblue;"></i> <?php } ?> </a> </td> <?php
            $lib = API_LibelleCompletSansPremiersNiveaux('service', $copieur['service_id'], ' <br> ');
            ?><td><?php echo $lib; ?></td>

            <td> <?php $ladate = $copieur['date_achat'];
                  echo date('d/m/Y', strtotime($ladate)); ?> </td>
            <td> <?php $ladate3 = $copieur['DateReleve'];
                  echo date('d/m/Y', strtotime($ladate3)); ?> </td>
            <td>
              <?php
              $ladate2 = $copieur['date_debut'];
              $duree = $copieur['duree'];

              $dateFin = date("Y-m-d", strtotime($ladate2 . ' + ' . $duree . ' year '));
              $DateAvantFin = date("Y-m-d", strtotime($dateFin . '- 6 months'));
              $DateActuel = date("Y-m-d");
              $laDateFin = date("d/m/Y", strtotime($dateFin));

              if ($dateFin < $DateActuel) { ?><b style='color: <?php echo $color; ?>;'><?php echo $laDateFin; ?></b><?php } elseif ($DateAvantFin < $DateActuel) { ?><b style='color: <?php echo $color2; ?>;'><?php echo $laDateFin; ?></b><?php } else { ?><b style='color: <?php echo $color3; ?>;'><?php echo $laDateFin; ?></b><?php }; ?>
            </td>


          </tr>
        <?php endforeach; ?>
        Liste de tous les copieurs:(<?php echo ($compte) ?> )
      </tbody>
    </table>
  </div>
</body>

</html>