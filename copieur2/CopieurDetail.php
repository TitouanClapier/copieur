<!DOCTYPE html>
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
    $conorg = $dborg->getDbh();
    $color = 'red';
    $color2 = 'lightgreen';

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = strip_tags($_GET['id']);

        $sql = 'SELECT * FROM copieur WHERE `id`=:id';
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $copieurs = $query->fetch();
        $idmodele = $copieurs['modele_id'];

        $sql = "SELECT * FROM modele WHERE `id`= '$idmodele'";
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $modeles = $query->fetch();

        $sql = 'SELECT * FROM contrat WHERE `copieur_id`=:id';
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $contrats = $query->fetch();

        $sql = 'SELECT * FROM probleme WHERE `copieur_id`=:id';
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $problemes = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT document.id as idDoc,	numero,	date_reception,	description,	chemin_scan, numero_engagement, numero_mandat, montant_ttc,	type_doc_id, copieur_id,	periode_id,	periode.libelle as libellePeriode,	num_ordre,	type_document.libelle as libelleType,	invest_fonct  FROM document inner JOIN periode on periode_id=periode.id inner JOIN type_document on type_doc_id=type_document.id WHERE `copieur_id`=:id';
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $documents = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT * FROM compteur WHERE `copieur_id`=:id';
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $compteurs = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT * FROM copieur_site WHERE `copieur_id`=:id';
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $copieur_sites = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT * FROM organigramme.site WHERE `id`= (SELECT site_id FROM copieur_site WHERE `copieur_id`=:id)';
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $sites = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT * FROM copieur_service WHERE `copieur_id`=:id';
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $copieur_services = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT * FROM organigramme.service WHERE `id`= (SELECT service_id FROM copieur_service WHERE `copieur_id`=:id)';
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $services = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT * FROM contacter WHERE `copieur_id`=:id';
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $contacter = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT * FROM personne WHERE `id`= (SELECT personne_id FROM contacter WHERE `copieur_id`=:id)';
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $personnes = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT * FROM commande WHERE `copieur_id`=:id';
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $commandes = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!$copieurs) {
            header('Location: index.php');
        }
    } else {
        header('Location: index.php');
    }
    require_once('close.php');
    ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des copieurs</title>

</head>

<body>
    <?php include_once 'nav.php'; ?>
    <div style="padding: 3px;">
        <h2><?= $modeles['libelle'] ?> :
            <?php
            $ip = $copieurs['adresse_ip'];
            $regex = '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/';
            if (preg_match($regex, $ip)) {
                // l'adresse IP est dans le bon format
                $port = 80;
                $timeout = 2;

                $socket = @fsockopen($ip, $port, $errorCode, $errorMessage, $timeout);
                if ($socket) {
            ?> <b style='color: <?php echo $color2; ?>;'>ON</b><?php
                                                                fclose($socket);
                                                            } else {
                                                                ?><b style='color: <?php echo $color; ?>;'>OFF</b><?php
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        // l'adresse IP n'est pas dans le bon format
                                                                                                                            ?><b style='color: <?php echo $color; ?>;'>OFF</b><?php
                                                                                                                        } ?></h2>
        <table class="table table-bordered " id='table'> </table>
        <table class="table table-bordered " id='table'>
            <thead class="thead-light"> <!-- add class="thead-light" for a light header -->
                <tr>
                    <th class="sorter-false filter-false"> <b> Matricule </b> </th>
                    <th class="sorter-false filter-false">numero ugap </th>
                    <th class="sorter-false filter-false">numero de dossier </th>
                    <th class="sorter-false filter-false">file d attente </th>
                    <th class="sorter-false filter-false">adresse ip </th>

                </tr>
            </thead>
            <tbody>
                <form name="form" method="POST" action="CopieurUpdate.php">
                    <tr id="ligne1" class="visible">
                        <td><b><?= $copieurs['matricule'] ?></b></td>
                        <td><?= $copieurs['numero_ugap'] ?></td>
                        <td><?= $copieurs['numero_dossier'] ?></td>
                        <td><?= $copieurs['file_attente'] ?></td>
                        <?php $ip = $copieurs['adresse_ip'] ?>
                        <td> <?php if ($copieurs['adresse_ip'] == NULL) { ?><b style='color: <?php echo $color; ?>;'>HORS RESEAU</b><?php } else {
                                                                                                                                    echo "<a href= http://$ip > $ip ";
                                                                                                                                }; ?></td>

                    </tr>
                    <tr id="ligne2" class="invisible">
                        <td><input class="form-control" name="matricule" type="text" value="<?php echo $copieurs['matricule']; ?>"></td>
                        <td><input class="form-control" name="numero_ugap" type="text" value="<?php echo $copieurs['numero_ugap']; ?>"></td>
                        <td><input class="form-control" name="numero_dossier" type="text" value="<?php echo $copieurs['numero_dossier']; ?>"></td>
                        <td><input class="form-control" name="file_attente" type="text" value="<?php echo $copieurs['file_attente']; ?>"></td>
                        <td><input class="form-control" name="adresse_ip" type="text" value="<?php echo $copieurs['adresse_ip']; ?>"></td>
                    </tr>

            </tbody>
        </table>
        <table class="table table-bordered " id='table'>
            <thead class="thead-light"> <!-- add class="thead-light" for a light header -->
                <tr>
                    <th class="sorter-false filter-false">commentaire </th>
                    <th class="sorter-false filter-false">date d achat </th>
                    <th class="sorter-false filter-false">date de livraison </th>
                    <th class="sorter-false filter-false">date réforme</th>
                    <th class="sorter-false filter-false">fin du contrat</th>
                    <th class="sorter-false filter-false">periode en cour du contrat</th>

                </tr>
            </thead>
            <tbody>
                <tr id="ligne3" class="visible">
                    <td><?= $copieurs['commentaire'] ?></td>
                    <td><?= $copieurs['date_achat'] ?></td>

                    <td><?= $copieurs['date_livraison'] ?></td>
                    <td><?= $copieurs['date_reforme'] ?></td>
                    <td>
                        <?php
                        $ladate2 = $contrats['date_debut'];
                        $duree = $contrats['duree'];

                        $dateFin = date("Y-m-d", strtotime($ladate2 . ' + ' . $duree . ' year '));
                        $DateAvantFin = date("Y-m-d", strtotime($dateFin . '- 6 months'));
                        $DateActuel = date("Y-m-d");
                        $laDateFin = date("d/m/Y", strtotime($dateFin));
                        echo $laDateFin; ?>
                    </td>

                    <td><?= '???' ?></td>
                </tr>
                <tr id="ligne4" class="invisible">
                    <td><input class="form-control" name="commentaire" type="text" value="<?php echo $copieurs['commentaire']; ?>"></td>
                    <td><input class="form-control" name="date_achat" type="text" value="<?php echo $copieurs['date_achat']; ?>"></td>
                    <td><input class="form-control" name="date_livraison" type="text" value="<?php echo $copieurs['date_livraison']; ?>"></td>
                    <td><input class="form-control" name="date_reforme" type="text" value="<?php echo $copieurs['date_reforme']; ?>"></td>
                </tr>
            </tbody>
        </table>

        <a href="#ligne1" class="visible" id="btn1" onClick="change('ligne1','ligne2'); change('ligne3','ligne4'); change('btn1','btn2')">Modifier</a>
        <span id="btn2" class="invisible">
            <button class="btn btn-outline-success" id="btn" name="btn2<?php echo $id ?>" type="submit" value="enregistrer"><i class="bi bi-check2"></i></button>
        </span>
        </form>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-detail-tab" data-bs-toggle="tab" data-bs-target="#nav-detail" type="button" role="tab" aria-controls="nav-detail" aria-selected="true">Détails</button>
                <button class="nav-link" id="nav-cout-tab" data-bs-toggle="tab" data-bs-target="#nav-cout" type="button" role="tab" aria-controls="nav-cout" aria-selected="false">Coûts</button>
                <button class="nav-link" id="nav-probleme-tab" data-bs-toggle="tab" data-bs-target="#nav-probleme" type="button" role="tab" aria-controls="nav-probleme" aria-selected="false">Problèmes</button>
                <button class="nav-link" id="nav-document-tab" data-bs-toggle="tab" data-bs-target="#nav-document" type="button" role="tab" aria-controls="nav-document" aria-selected="false">Documents</button>
                <button class="nav-link" id="nav-compteur-tab" data-bs-toggle="tab" data-bs-target="#nav-compteur" type="button" role="tab" aria-controls="nav-compteur" aria-selected="false">Compteur</button>
                <button class="nav-link" id="nav-service-tab" data-bs-toggle="tab" data-bs-target="#nav-service" type="button" role="tab" aria-controls="nav-service" aria-selected="false">Service</button>
                <button class="nav-link" id="nav-consommable-tab" data-bs-toggle="tab" data-bs-target="#nav-consommable" type="button" role="tab" aria-controls="nav-consommable" aria-selected="false">Consommables</button>
                <button class="nav-link" id="nav-contrat-tab" data-bs-toggle="tab" data-bs-target="#nav-contrat" type="button" role="tab" aria-controls="nav-contrat" aria-selected="false">Contrat</button>
                <button class="nav-link" id="nav-depense-tab" data-bs-toggle="tab" data-bs-target="#nav-depense" type="button" role="tab" aria-controls="nav-depense" aria-selected="false">Dépenses</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-detail" role="tabpanel" aria-labelledby="nav-detail-tab">
                <h3>Caractéristiques techniques :</h3>
                <table class="table " '>
                        <thead class="thead"> <!-- add class="thead-light" for a light header -->
                        <tr>
                        
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php $imgOui = 'images/approuve.png';
                                $imgNon = 'images/crossed.png'; ?>
                                <td width=10% ><li>Fax 
                                <td width=90% ><?php if ($copieurs['fax'] == 0) print '<img src="' . $imgNon . '"width="18" height="18" class="d-inline-block align-center" alt="" />';
                                                else print '<img src="' . $imgOui . '"width="18" height="18" class="d-inline-block align-center" alt="" />'; ?>
                            </tr>
                            <tr>
                                <td><li>Lecteur de badge 
                                <td><?php if ($copieurs['lecteur_badge'] == 0) print '<img src="' . $imgNon . '"width="18" height="18" class="d-inline-block align-center" alt="" />';
                                    else print '<img src="' . $imgOui . '"width="18" height="18" class="d-inline-block align-center" alt="" />'; ?>
                            </tr>
                            <tr>
                                <td><li>A3 
                                <td><?php if ($copieurs['a3'] == 0) print '<img src="' . $imgNon . '"width="18" height="18" class="d-inline-block align-center" alt="" />';
                                    else print '<img src="' . $imgOui . '"width="18" height="18" class="d-inline-block align-center" alt="" />'; ?>
                            </tr>
                            <tr>
                                <td><li>Recto-verso 
                                <td><?php if ($copieurs['recto_verso'] == 0) print '<img src="' . $imgNon . '"width="18" height="18" class="d-inline-block align-center" alt="" />';
                                    else print '<img src="' . $imgOui . '"width="18" height="18" class="d-inline-block align-center" alt="" />'; ?>
                            </tr>
                            <tr>
                                <td><li>Couleur 
                                <td><?php if ($copieurs['couleur'] == 0) print '<img src="' . $imgNon . '"width="18" height="18" class="d-inline-block align-center" alt="" />';
                                    else print '<img src="' . $imgOui . '"width="18" height="18" class="d-inline-block align-center" alt="" />'; ?>
                            </tr>
                            <tr>
                                <td><li>Logo 
                                <td><?php if ($copieurs['logo'] == 0) print '<img src="' . $imgNon . '"width="18" height="18" class="d-inline-block align-center" alt="" />';
                                    else print '<img src="' . $imgOui . '"width="18" height="18" class="d-inline-block align-center" alt="" />'; ?>
                            </tr>
                            <tr>
                                <td><li>Finisseur 
                                <td><?php if ($copieurs['finisseur'] == 'N') print '<img src="' . $imgNon . '"width="18" height="18" class="d-inline-block align-center" alt="" />';
                                    elseif ($copieurs['finisseur'] == 'O') print '<img src="' . $imgOui . '"width="18" height="18" class="d-inline-block align-center" alt="" />';
                                    else echo $copieurs['finisseur']; ?>
                            </tr>
                        </tbody>  
                    </table>
                </div>
                <div class="tab-pane fade" id="nav-cout" role="tabpanel" aria-labelledby="nav-cout-tab">
                    <h3>Coûts :</h3>
                    <table class="table" id=' table'>
                    <thead class="thead"> <!-- add class="thead-light" for a light header -->
                        <tr>
                            <th>Prix d achat TTC : </th>
                            <th>prix d'achat HT : </th>
                            <th>Prix trimestriel de la maintenance :</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $copieurs['prix_achat_ttc']  ?> (Euros)</td>
                            <td><?= $copieurs['prix_achat_ht']  ?> (Euros)</td>
                            <td> <?= $contrats['prix_trim_maintenance'] ?> (Euros)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="nav-probleme" role="tabpanel" aria-labelledby="nav-probleme-tab">
                <h3>liste des problemes :</h3>
                <table class="table" id='table'>
                    <thead class="thead"> <!-- add class="thead-light" for a light header -->
                        <tr>
                            <th>Date </th>
                            <th>Description </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php foreach ($problemes as $probleme) { ?>
                                <td><?= $probleme['date']; ?></td>
                                <td><?= $probleme['description']; ?></td>
                        </tr>
                    <?php }
                            if ($problemes == Null) {
                                echo "Aucun problemes trouver";
                            }
                    ?>
                    </tbody>
                </table>

            </div>
            <div class="tab-pane fade" id="nav-document" role="tabpanel" aria-labelledby="nav-document-tab">
                <h3>Documents :</h3>
                <table class="table" id='table'>
                    <thead class="thead"> <!-- add class="thead-light" for a light header -->
                        <tr>
                            <th>Numero du document </th>
                            <th>Type du document </th>
                            <th>Date de Réception </th>
                            <th>Période </th>
                            <th>Montant </th>
                            <th>Numéro de mandat </th>
                            <th>Numéro d'engagement </th>
                            <th>Scan </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $document) { ?>
                            <tr>
                                <td><?= $document['numero']; ?></td>
                                <td><?= $document['libelleType']; ?></td>
                                <td><?= $document['date_reception']; ?></td>
                                <td><?= $document['libellePeriode']; ?></td>
                                <td><?= $document['montant_ttc']; ?></td>
                                <td><?= $document['numero_mandat']; ?></td>
                                <td><?= $document['numero_engagement']; ?></td>
                                <td><a href=<?= $document['chemin_scan']; ?> lien </a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>


            </div>
            <div class="tab-pane fade" id="nav-compteur" role="tabpanel" aria-labelledby="nav-compteur-tab">
                <h3>Relevés compteur :</h3>
                <table class="table" id='table'>
                    <thead class="thead"> <!-- add class="thead-light" for a light header -->
                        <tr>
                            <th>Date du relevé </th>
                            <th>Nombre de copie noir </th>
                            <th>Nombre de copies couleur </th>
                            <th>Nombre de copies logo </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($compteurs as $compteur) { ?>
                            <tr>
                                <td><?= $compteur['date_releve']; ?></td>
                                <td><?= $compteur['nb_cop_noir']; ?></td>
                                <td><?= $compteur['nb_cop_coul']; ?></td>
                                <td><?= $compteur['nb_cop_logo']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
            <div class="tab-pane fade" id="nav-service" role="tabpanel" aria-labelledby="nav-service-tab">
                <h3>Site :</h3>
                <table class="table" id='table'>
                    <thead class="thead"> <!-- add class="thead-light" for a light header -->
                        <tr>
                            <th>Date d'arrivée </th>
                            <th>Date de depart </th>
                            <th>Site </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sites as $site) { ?>
                            <?php foreach ($copieur_sites as $copieur_site) { ?>
                                <tr>
                                    <td><?= $copieur_site['date_arrivee']; ?></td>
                                    <td><?php if ($copieur_site['date_depart']) echo $copieur_site['date_depart'];
                                        else echo "present sur site"; ?> </td>
                                    <?php $lib = API_LibelleComplet('site', $copieur_site['id'], ' - '); ?>
                                    <td><?php echo $lib . "<br>" . API_Adresse($copieur_site['id']); ?> </td>

                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
                
                <h3>Service :</h3>
                <table class="table" id='table'>
                    <thead class="thead"> <!-- add class="thead-light" for a light header -->
                        <tr>
                            <th>Date d'arrivée </th>
                            <th>Date de depart </th>
                            <th>Service </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $service) { ?>
                            <?php foreach ($copieur_services as $copieur_service) { ?>
                                <tr>
                                    <td><?= $copieur_service['date_arrivee']; ?></td>
                                    <td><?= $copieur_service['date_depart']; ?></td>
                                    <td><?= $service['libelle']; ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
                
                <h3>Personne a contacter :</h3>
                <table class="table" id='table'>
                    <thead class="thead"> <!-- add class="thead-light" for a light header -->
                        <tr>
                            <th>Nom </th>
                            <th>Prenom </th>
                            <th>Telephone </th>
                            <th>Date d'arrivée </th>
                            <th>Date de depart </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacter as $contact) { ?>
                            <?php foreach ($personnes as $personne) { ?>
                                <tr>
                                    <td><?= $personne['nom']; ?></td>
                                    <td><?= $personne['prenom']; ?></td>
                                    <td><?= $personne['telephone']; ?></td>
                                    <td><?= $contact['date_arrivee']; ?></td>
                                    <td><?= $contact['date_depart']; ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
                
            </div>
            <div class="tab-pane fade" id="nav-consommable" role="tabpanel" aria-labelledby="nav-consommable-tab">
                <h3>Liste des commandes :</h3>
                <table class="table" id='table'>
                    <thead class="thead"> <!-- add class="thead-light" for a light header -->
                        <tr>
                            <th>Date de commande </th>
                            <th>Toner noir </th>
                            <th>Toner jaune </th>
                            <th>Toner magenta </th>
                            <th>Toner cyan </th>
                            <th>Bac récupérateur </th>
                            <th>Agrafes </th>
                            <th>Commentaires </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commandes as $commande) { ?>
                            <tr>
                                <td><?= $commande['date_envoi']; ?></td>
                                <td><?= $commande['toner_noir']; ?></td>
                                <td><?= $commande['toner_jaune']; ?></td>
                                <td><?= $commande['toner_magenta']; ?></td>
                                <td><?= $commande['toner_cyan']; ?></td>
                                <td><?= $commande['bac_recup']; ?></td>
                                <td><?= $commande['agrafe']; ?></td>
                                <td><?= $commande['commentaires']; ?></td>
                            </tr>
                        <?php }
                        if ($commandes == Null) {
                            echo "Aucune commandes trouver";
                        }
                        ?>
                    </tbody>
                </table>
                
            </div>
            <div class="tab-pane fade" id="nav-contrat" role="tabpanel" aria-labelledby="nav-contrat-tab">
                <h3>Contrat actuel :</h3>
                <table class="table" id='table'>
                    <thead class="thead"> <!-- add class="thead-light" for a light header -->
                        <tr>
                            <th>Numéro du contrat en cours </th>
                            <th>Date de début </th>
                            <th>Durée </th>
                            <th>Nombre de copies noires par trimestre </th>
                            <th>Coût du dépassement de la copie noire </th>
                            <th>Nombre de copies couleurs par trimestre </th>
                            <th>Coût du dépassement de la copie couleur </th>
                            <th>Nombre de copies logo par trimestre </th>
                            <th>Coût du dépassement de la copie logo </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $contrats['numero']; ?></td>
                            <td><?= $contrats['date_debut']; ?></td>
                            <td><?= $contrats['duree']; ?></td>
                            <td><?= $contrats['nb_trim_cop_noir']; ?></td>
                            <td><?= $contrats['cout_cop_noir']; ?> (euros)</td>
                            <td><?= $contrats['nb_trim_cop_coul']; ?></td>
                            <td><?= $contrats['cout_cop_coul']; ?> (euros)</td>
                            <td><?= $contrats['nb_trim_cop_logo']; ?></td>
                            <td><?= $contrats['cout_cop_logo']; ?> (euros)</td>
                        </tr>
                    </tbody>
                </table>
                
            </div>
            <div class="tab-pane fade" id="nav-depense" role="tabpanel" aria-labelledby="nav-depense-tab">
                <h3>Listes des dépenses :</h3>
                <table class="table" id='table'>
                    <thead class="thead"> <!-- add class="thead-light" for a light header -->
                        <tr>
                            <th>Année </th>
                            <th>Investissement (Euros TTC) </th>
                            <th>Prévisionnel </th>
                            <th>Réel </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commandes as $commande) { ?>
                            <tr>
                                <td><?= $commande['date_envoi']; ?></td>
                                <td><?= $commande['toner_noir']; ?></td>
                                <td><?= $commande['toner_jaune']; ?></td>
                                <td><?= $commande['toner_magenta']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <script>
            $('table')
              .tablesorter({
                widgets: ['filter', 'math']
                }
              );
          </script>
            </div>
        </div>
    </div>
</body>

</html>