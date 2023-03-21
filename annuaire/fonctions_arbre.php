<?php

require_once('objet.class.php');
require_once 'objet_DAO.class.php';


$environnementProd = false;
if (isset($_SERVER["HTTP_HOST"])) {
    if ($_SERVER["HTTP_HOST"] == 'srvphp8' or $_SERVER["HTTP_HOST"] == '10.1.251.124') {
        $environnementProd = true;
    }
} else {
    // mode batch
    $environnementProd = true;
}
if ($environnementProd == true) {
    require_once'Connexion_PDO_ORGANIGRAMME.php'; // laisser chemin en dur pour le batch de relevé des compteurs
} else {
    require_once'Connexion_PDO_ORGANIGRAMME.php'; // laisser chemin en dur pour le batch de relevé des compteurs
}

// construit l'arbre en mémoire avec un niveau 1 à n
function ConstruitArbreComplet($niveauMax) {
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();

    global $table;
    global $columns;
    global $role;
    // boucle pour les niveaux 1 à niveauMax (1 à n)
    for ($n = 1; $n <= $niveauMax; $n++) {
        // requete de recherche

        $query = 'SELECT ' . $columns . ' FROM ' . $table . ' WHERE niveau =' . $n;
        if ($role != 'dsi' && $role != 'drh') {
            $query = $query . ' and visu=1';
        }
        $query = $query . ' ORDER BY num_ordre, libelle ';
        $result = $con->query($query);
        ;

        $nbLigne = $result->rowCount();
        if ($nbLigne > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {  // boucle sur les lignes trouvées
                if ($table == 'site') {
                    $objet = new Site($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17]);
                } else {
                    $objet = new Service($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11]);
                }
                // ajout objet trouvé dans l'arbre
                AddObjet($objet);
            }
        }
    }
}

Function ConstruitArbreResultatDeLaRecherche($texteRecherche, $table) {

    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();

    global $arbre; //arbre pour stocker tous les objets (clé parentId, valeur tableau d'objets avec le n°ordre en clé)
    global $objetsIdsAffiches;
    global $columns;
    global $role;

    // requete de recherche (insensible à la casse et aux accents)
    $query1 = 'SELECT ' . $columns . ' FROM ' . $table
            . ' WHERE (libelle like "%' . $texteRecherche . '%"'
            . ' OR commentaire like "%' . $texteRecherche . '%")';
    if ($role != 'dsi') {
        $query1 = $query1 . ' and actif=1';
    }
    $query1 = $query1 . ' ORDER BY niveau, num_ordre, libelle ';
    //echo $query1;
    $result1 = $con->query($query1);
    //var_dump($query1);
    $nbLigne = $result1->rowCount();
    if ($nbLigne > 0) {
        while ($row = $result1->fetch(PDO::FETCH_NUM)) {  // boucle sur les lignes trouvées
            $niveauMax = 1;
            $objetEtSesParents = array();
            if ($table == 'site') {
                $objet = new Site($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17]);
            } else {
                $objet = new Service($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11]);
            }
            if ($objet->getNiveau() > $niveauMax) {
                $niveauMax = $objet->getNiveau();
            }

            if (in_array($objet->getId(), $arbre)) {
                // déjà présent : pas besoin de l'ajouter
            } else {
                // il faut ajouter cet objet dans notre arbre
                $objetEtSesParents[] = $objet;
                $objetIdsAffiches[] = $objet->getId();

                //echo $objetEtSesParents[0]['libelle'];
                // RECHERCHE DES PARENTS
                $parent = $row[0];
                while ($parent != 0) {  // si le parent à déjà été ajouté, on n'a pas besoin de le rechercher à nouveau
                    $query = 'SELECT ' . $columns . ' FROM ' . $table . ' WHERE id=' . $parent;
                    $result = $con->query($query);
                    $row = $result->fetch(PDO::FETCH_NUM);
                    ;
                    if ($table == 'site') {
                        $objet = new Site($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17]);
                    } else {
                        $objet = new Service($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11]);
                    }
                    if (in_array($objet->getId(), $arbre)) {
                        // déjà présent : pas besoin de l'ajouter
                    } else {
                        $objetEtSesParents[] = $objet;
                        $objetsIdsAffiches[] = $objet->getId();
                    }
                    //echo $couleurFond[$n]."parent(s) : ".$id[$n]." ".$libelle[$n]."parentId : ".$parentId."niveau : ".$niveau[$n]." </br>";
                    $parent = $objet->getParentId();
                }
                // AJOUT DES PARENTS DANS L'ARBRE GLOBAL
                for ($n = 1; $n <= $niveauMax; $n++) {   // boucle pour ajouter les objectifs dans l'ordre des niveaux 1, 2, 3... pour pouvoir les rattacher à un noeud
                    foreach ($objetEtSesParents as $key => $obj) {
                        if ($obj->getNiveau() == $n) {
                            AddObjet($obj);
                        }
                    }
                }
            }
        }
    }
}

Function ConstruitArbreDesAgents($table) {
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    global $arbre; //arbre pour stocker tous les objectifs (clé parentId, valeur tableau d'objectifs avec le n°ordre en clé)
    global $objetsIdsAffiches;
    global $columns;
    global $role;
    // requete de recherche
    $query1 = 'SELECT * FROM ' . $table;
    if ($role != 'dsi') {
        $query1 = $query1 . ' WHERE visu=1';
    }
    $query1 = $query1 . ' ORDER BY num_ordre, libelle ';
    $result1 = $con->query($query1);
    $nbLigne = $result1->rowCount();
    if ($nbLigne > 0) {
        while ($ro = $result1->fetch(PDO::FETCH_OBJ)) {  // boucle sur les lignes trouvées
            $niveauMax = 1;
            $objetEtSesParents = array();
            if ($table == 'site') {
                $objet = new Site($ro->parent_id, $ro->id, $ro->libelle, $ro->niveau, $ro->commentaire, $ro->couleur_fond, $ro->couleur_texte, $ro->gras, $ro->italique, $ro->num_ordre, $ro->actif, $ro->adr1, $ro->adr2, $ro->cp, $ro->ville, $ro->latitude, $ro->longitude, $ro->pool_id);
            } else {
                $objet = new Service($ro->parent_id, $ro->id, $ro->libelle, $ro->niveau, $ro->commentaire, $ro->couleur_fond, $ro->couleur_texte, $ro->gras, $ro->italique, $ro->num_ordre, $ro->actif);
            }
            if ($objet->getNiveau() > $niveauMax) {
                $niveauMax = $objet->getNiveau();
            }

            if (in_array($objet->getId(), $arbre)) {
                // déjà présent : pas besoin de l'ajouter
            } else {
                // il faut ajouter cet objet dans notre arbre
                $objetEtSesParents[] = $objet;
                $objetIdsAffiches[] = $objet->getId();

                //echo $objetEtSesParents[0]['libelle'];
                // RECHERCHE DES PARENTS
                $parent = $ro->parent_id;
                while ($parent != 0) {  // si le parent à déjà été ajouté, on n'a pas besoin de le rechercher à nouveau
                    $query = 'SELECT * FROM ' . $table . ' WHERE id=' . $parent;
                    $query = $query . ' ORDER BY num_ordre, libelle ';
                    $result2 = $con->query($query);
                    $ro = $result2->fetch(PDO::FETCH_OBJ);
                    //$row = $result->rowCount();
                    if ($table == 'site') {
                        $objet = new Site($ro->parent_id, $ro->id, $ro->libelle, $ro->niveau, $ro->commentaire, $ro->couleur_fond, $ro->couleur_texte, $ro->gras, $ro->italique, $ro->num_ordre, $ro->actif, $ro->adr1, $ro->adr2, $ro->cp, $ro->ville, $ro->latitude, $ro->longitude, $ro->pool_id);
                    } else {
                        $objet = new Service($ro->parent_id, $ro->id, $ro->libelle, $ro->niveau, $ro->commentaire, $ro->couleur_fond, $ro->couleur_texte, $ro->gras, $ro->italique, $ro->num_ordre, $ro->actif);
                    }
                    if (in_array($objet->getId(), $arbre)) {
                        // déjà présent : pas besoin de l'ajouter
                    } else {
                        $objetEtSesParents[] = $objet;
                        $objetsIdsAffiches[] = $objet->getId();
                    }
                    //echo $couleurFond[$n]."parent(s) : ".$id[$n]." ".$libelle[$n]."parentId : ".$parentId."niveau : ".$niveau[$n]." </br>";
                    $parent = $ro->parent_id;
                }
                // AJOUT DES PARENTS DANS L'ARBRE GLOBAL
                for ($n = 1; $n <= $niveauMax; $n++) {   // boucle pour ajouter les objectifs dans l'ordre des niveaux 1, 2, 3... pour pouvoir les rattacher à un noeud
                    foreach ($objetEtSesParents as $key => $obj) {
                        if ($obj->getNiveau() == $n) {
                            AddObjet($obj);
                        }
                    }
                }
            }
        }
    }
}

/* fonction qui construit un arbre de navigation */

function ConstruitArbreNavigation($courantId) {

    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();

    global $arbre; //arbre pour stocker tous les objets (clé parentId, valeur tableau d'objets avec le num ordre en clé)
    global $table;
    global $columns;
    global $role;
    // RECHERCHE DES ENFANTS
    // si admin on affiche tous les enfants
    $query = 'SELECT ' . $columns
            . ' FROM ' . $table . ' WHERE parent_id = ' . $courantId;
    if ($role != 'dsi') {
        $query = $query . ' and visu=1';
    }
    $query = $query . ' ORDER BY  num_ordre, libelle ';
    $result = $con->query($query);
    $nbLigne = $result->rowCount();
    if ($nbLigne == 0) {
        // RECHERCHE DE L'ELEMENT
        $query = 'SELECT ' . $columns
                . ' FROM ' . $table . ' WHERE id = ' . $courantId;
        if ($role != 'dsi' && $role != 'drh') {
            $query = $query . ' and visu=1';
        }
        $query = $query . ' ORDER BY niveau, num_ordre, libelle ';
        $result = $con->query($query);
        $nbLigne = $result->rowCount();
    }
    //echo $nbLigne.$query;
    if ($nbLigne > 0) {
        while ($row = $result->fetch(PDO::FETCH_NUM)) {  // boucle sur les lignes trouvees
            if ($table == 'site') {
                $objet = new Site($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17]);
            } else {
                $objet = new Service($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10]);
            }
            AddObjet($objet);
        }
    }

    // RECHERCHE DES PARENTS ET AJOUT DES PARENTS DANS L'ARBRE GLOBAL
    $parent = $courantId;
    while ($parent != 0) {
        $query = 'SELECT ' . $columns . ' FROM ' . $table . ' WHERE id=' . $parent;
        $result = $con->query($query);
        $row = $result->fetch(PDO::FETCH_NUM);
        if ($table == 'site') {
            $objet = new Site($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17]);
        } else {
            $objet = new Service($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10]);
        }
        if ($objet->getId() != 0) {
            AddObjet($objet);
        }
        $parent = $objet->getParentId();
    }
    // RECHERCHE DES FRERES ET AJOUT DANS L'ARBRE GLOBAL
    $query = 'SELECT parent_id, niveau FROM ' . $table . ' WHERE id=' . $courantId;
    //echo $query;
    $result = $con->query($query);
    while ($row = $result->fetch(PDO::FETCH_OBJ)) {
        $query = 'SELECT ' . $columns . ' FROM ' . $table . ' WHERE parent_id=' . $row->parent_id . ' and niveau=' . $row->niveau . ' and visu=1 order by num_ordre';
        //echo $query;
        $result = $con->query($query);
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            if ($table == 'site') {
                $objet = new Site($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17]);
            } else {
                $objet = new Service($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10]);
            }
            if ($objet->getId() != 0) {
                AddObjet($objet);
            }
        }
    }
}

/* fonction qui surligne $aSurligner dans une chaine $texte */

Function Surligne($texte, $aSurligner) {


    if ($aSurligner == "") {
        return $texte;
    } else {
        $pos = stripos(supprAccents($texte), supprAccents($aSurligner));
        if ($pos === false) {
            return $texte;
        } else {
            $longueur = strlen($aSurligner);
            $returnResult = substr($texte, 0, $pos);
            $returnResult = $returnResult . '<font style="background:yellow;">' . substr($texte, $pos, $longueur) . '</font>';
            $returnResult = $returnResult . substr($texte, $pos + $longueur);
            return $returnResult;
        }
    }
}

/**
 * fonction qui affiche une branche de l'arbre de niveau $n
 * @param $n Integer identifiant de la branche de départ
 * 			    0 pour afficher la racine
 * 				sinon id de l'élément de départ
 * @param $mode String mode	'ajout'
 * 						'modif'
 * @param $role String role '' ou 'admin'
 * @param $lien String lien hypertexte positionné sur les éléments de l'arbre
 * @param $couleurBranche String couleur code HTML pour la branche qui sera degradé dans les sous-éléments
 * @param $element String contenu à afficher dans l arbre ''=rien ou 'agent'=tous les agents ou 'agentresp'=seulement les chefs
 * @param $niv Integer nombre de sous niveaux à afficher (0: pas de limite, 1: un seul niveau, 2: deux niveaux...)
 */
function AfficheBranche($n, $mode, $role, $lien, $couleurBranche, $element, $niv) {
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();

    global $arbre;
    global $id;
    /* if ($n==0) {
      // affichage du home
      AfficheHome($lien);
      } */
    $nbLignes = count($arbre[$n]);
    $ligCourante = 1;
    $possedeDesSousElements = false;
    foreach ($arbre[$n] as $key => $obj) {
        $couleurBranche = '#FFFFFF';
        if ($obj->getCouleurFond() != null and strlen($obj->getCouleurFond()) > 0) {
            $couleurBranche = $obj->getCouleurFond();
        }
        //echo $couleurBranche;
        AfficheLigne($obj, $mode, $role, $lien, $couleurBranche, $ligCourante, $nbLignes, $element);
        if ($element == "agent" && $id == $obj->getId()) {
            AfficheAgents($obj, $role, 'tous', $n, $couleurBranche);
        }
        if ($element == "agentresp" /* && $id==$obj->getId() */) {
            AfficheAgents($obj, $role, 'chefs', $n, $couleurBranche);
        }
        if ($element == 'complet') {
            AfficheAgents($obj, $role, 'tous', $n, $couleurBranche);
        }
        if (array_key_exists($obj->getId(), $arbre)) {   // ce fils est-il un noeud ?
            $possedeDesSousElements = true;
            //echo 'branche trouvée : '.$obj->getId().' de couleur : '.$obj->getCouleurFond().' / '.$couleurBranche.'<br>';
            //echo ' dont voici les enfants : '.degradeHexa($obj->getCouleurFond()).'<BR/>';
            if ($obj->getCouleurFond() != null and strlen($obj->getCouleurFond()) > 0) {
                $c = $obj->getCouleurFond();
            } else {
                $c = $couleurBranche;
            }
            $couleurDegradeePourLesEnfants = degradeHexa($c);
            if ($niv != 1) {
                // on affiche les sous branches tant qu'on arrive pas au niveau 1
                // si $niv=0 (pas de limite) on affiche toutes les sous-branches
                AfficheBranche($obj->getId(), $mode, $role, $lien, $couleurDegradeePourLesEnfants, $element, $niv - 1);
            }
        }
        $ligCourante++;
    }
//    if ($possedeDesSousElements==false && $role=="admin")
//    {
//		// affichage du +
//		AfficheAjout($role, $mode, $obj->getNiveau() - 1, $obj->getParentId(), $obj->getId());
//
//	}
}

/* fonction qui permet d'ajouter un objet dans l'arbre global */

function AddObjet($obj) {
    global $arbre;
    if (array_key_exists($obj->getParentId(), $arbre)) { //le parent est-il déjà  en clé dans l'arbre ?
        //le parent est déjà en clé dans l'arbre
        $noeud = $arbre[$obj->getParentId()];   //récupère ce noeud dans son état actuel
        if (array_key_exists($obj->getNumOrdre(), $noeud)) {
            // l'objet est déja présent dans le noeud : on ne l'ajoute pas à nouveau
            //echo 'deja la  ! '.$obj['libelle'].' - '.$value['libelle'].' - '.$obj['parentId'].'<BR/>';
        } else {
            $noeud[$obj->getNumOrdre()] = $obj;  //ajoute le nouveau fils avec son num ordre comme clé
            $arbre[$obj->getParentId()] = $noeud;    // on remet le noeud dans l'arbre
            //echo 'ajoute '.$obj['libelle'].' - '.$value['libelle'].' - '.$obj['parentId'];
        }
    } else {
        $arbre[$obj->getParentId()] = array($obj->getNumOrdre() => $obj);  // on crée le noeud parent et on y place notre element indexé par son numéro d'ordre
        //echo 'crée '.$obj->getLibelle().' - '.$obj->getParentId();
    }
    return $arbre;
}

/**
 * fonction qui permet d'afficher une ligne d'objet à l'écran
 * @param $obj Object objet à afficher
 * @param $mode String mode 'ajout' | 'modif'
 * @param $role String role 'admin' | ''
 * @param $lien String lienHypertexte précise la page de redirection (href="xxx") sur chaque objet
 *                  si $lien='' --> aucun href n'est ajouté *
 * @param $couleurDegrade String couleur de fond dégradée
 * @param $ligCourante Integer n° de la ligne courante
 * @param $nbLignes Integer nb de lignes du niveau courant pour les fleches haut et bas
 */
function AfficheLigne($obj, $mode, $role, $lien, $couleurDegrade, $ligCourante, $nbLignes, $element) {
    global $id;
    global $table;
    global $text; // text à surligner
    global $niveauColor; // tableau de constantes : couleurs de fond par niveau (niveau, valeurs des couleurs HTML correspondantes)
    global $modeCouleur; // 'BD' ou 'constantes'
    global $niveauMax;  // nombre de niveau d'objets
//	echo " couleur dégradée pour affichage de la ligne : ".$couleurDegrade;
    echo '<table width="100%">';
    echo '<tr>';
    //echo '<td colspan="1" width="10%"><FONT color="grey">'.Surligne($obj->getCommentaire(), $text).'</font></td>';
    echo '<td colspan="1" width="10%"></td>';

    // indentation des niveaux en fonction du niveau courant et du niveauMax
    $n = $obj->getNiveau();
    echo '<td colspan="' . ($n - 1) . '" width="' . (($n - 1) * 4) . '%">&nbsp;</td>';
    echo '<td colspan="' . ($niveauMax + 1 - $n) . '" width="' . (83 - ($n * 4)) . '%" style="line-height: 30px; ';
    if ($modeCouleur[$table] === 'BD') {
        // couleur définie en base pour l'objet courant
        if ($obj->getCouleurFond() === '' || $obj->getCouleurFond() === null || $obj->getCouleurFond() === 0) {
            // couleur non définie pour cet objet
            echo ' border-left:10px solid ' . $couleurDegrade . '; border-bottom:1px solid #e7e7e7; border-top: 1px solid #e7e7e7; border-right: 1px solid #e7e7e7; background-color:#fcfcfc;" >';
        } else {
            echo ' border-left:10px solid ' . $obj->getCouleurFond() . '; border-bottom:1px solid #e7e7e7; border-top: 1px solid #e7e7e7; border-right: 1px solid #e7e7e7; background-color:#fcfcfc;" >';
        }
        //echo $couleurDegrade.$obj->getCouleurFond();
    } else if ($modeCouleur[$table] === 'BDbrut') {
        // couleur définie en base pour l'objet courant
        if ($obj->getCouleurFond() === '' || $obj->getCouleurFond() === null || $obj->getCouleurFond() === 0) {
            // couleur non définie pour cet objet: on lui affecte un degradé du niveau supérieur
            echo ' >';
        } else {
            echo ' border-left: 10px solid ' . $obj->getCouleurFond() . '; border-bottom: 1px solid #e7e7e7; border-top: 1px solid #e7e7e7; border-right: 1px solid #e7e7e7; background-color: #fcfcfc;" >';
        }
    } else {
        // mode couleurs statiques définies dans le tableau de constantes
        echo ' border-left: 10px solid ' . $niveauColor[$obj->getNiveau()] . '; border-bottom: 1px solid #e7e7e7; border-top: 1px solid #e7e7e7; border-right: 1px solid #e7e7e7; background-color: #fcfcfc;" >';
    }

    //formatage
    if ($obj->getGras() == 1) {
        echo '<b>';
    }
    if ($obj->getItalique() == 1) {
        echo '<i>';
    }

    if ($lien != '') {
        echo '<a href="' . $lien . '&id=' . $obj->getId() . '"';
        if ($table == 'site') {
            $adr = $obj->getAdresse(' ');
            if ($adr != null) {
                echo ' title="' . supprAccents($adr) . '"';
            }
        }
        echo ' >';
    }
    echo '<span style="padding-left: 5px; color:';
    if ($obj->getCouleurTexte() != '') {
        echo $obj->getCouleurTexte();
    } else {
        echo 'black';
    }
    echo ';';
    if (!$obj->isActif()) {
        echo 'text-decoration:line-through;font-size:x-small;';
    }
    $intitule = $obj->getLibelle();
    if ($obj->getCommentaire() != null and strlen($obj->getCommentaire()) > 0) {
        $intitule = $intitule . ' (' . $obj->getCommentaire() . ')';
    }

    echo '">' . Surligne($intitule, $text) . '</span>';

    //echo ' couleurDeDegrade:'.$couleurDegrade;
    if ($lien != '') {
        echo '</a>';
    }

    //formatage (fin)
    if ($obj->getItalique() == 1) {
        echo '</i>';
    }
    if ($obj->getGras() == 1) {
        echo '</b>';
    }
    echo '</td><td colspan="1" width="11%">'; //zone droite pour les icones de modification || cases à cocher
    if ($table == 'site') {
        $link = "OpenStreetMap_carte.php?idSite=" . $obj->getId();
        $popupSize = '750,650';
        echo '&nbsp;<a href="JavaScript:popup(\'' . $link . '\',\'toto\',' . $popupSize . ');" title="Voir sur la carte"><span class="glyphicon glyphicon-map-marker" style="font-size: 20px;color:#FBB900"></span></a>&nbsp;';
    }
    switch ($role) {
        case 'dsi':
            if ($element != "agent") {
                // icones d'édition (uniquement si ADMIN)
                // édition
                echo '&nbsp;<a title="Modifier" href="index.php?table=' . $table . '&menu1=' . $table . 's&id=' . $obj->getId() . '&mode=modif"><span class="glyphicon glyphicon-pencil" style="color:black"></span></a>&nbsp;';
                // désactivation / réactivation
                if ($obj->isActif()) {
                    echo '&nbsp;<a title="Désactiver" href="objet_suppression.php?actif=0&table=' . $table . '&id=' . $obj->getId() . '&parentId=' . $obj->getParentId() . '" onclick="javascript:return(confirm(\'Etes-vous certain de vouloir désactiver cet élément (et ses sous-éléments) ?\'))"><span class="glyphicon glyphicon-remove" style="color:red"></span></a>';
                } else {
                    echo '&nbsp;<a title="Réactiver" href="objet_suppression.php?actif=1&table=' . $table . '&id=' . $obj->getId() . '&parentId=' . $obj->getParentId() . '" onclick="javascript:return(confirm(\'Etes-vous certain de vouloir réactiver cet élément (et ses sous-éléments) ?\'))"><span class="glyphicon glyphicon-ok" ></span></a>';
                }
                // changement de niveau - fleche vers la gauche et droite
                if ($obj->getNiveau() > 1) {
                    echo '&nbsp;<a title="Remonter d\'un niveau" href="ordre.php?table=' . $table . '&id=' . $obj->getId() . '&parentId=' . $obj->getParentId() . '&niveau=' . $obj->getNiveau() . '&action=left"><span class="glyphicon glyphicon-arrow-left"></span></a>&nbsp;';
                }
                echo '&nbsp;<a title="Descendre d\'un niveau" href="ordre.php?table=' . $table . '&id=' . $obj->getId() . '&parentId=' . $obj->getParentId() . '&niveau=' . $obj->getNiveau() . '&action=right"><span class="glyphicon glyphicon-arrow-right"></span></a>&nbsp;';
                // trier les lignes
                if ($nbLignes > 1 && $ligCourante > 1) {  // fleche vers le haut
                    echo '&nbsp;<a title="Déplacer vers le haut" href="ordre.php?table=' . $table . '&id=' . $obj->getId() . '&parentId=' . $obj->getParentId() . '&numOrdre=' . $obj->getNumOrdre() . '&action=up"><span class="glyphicon glyphicon-arrow-up"></span></a>&nbsp;';
                }
                if ($nbLignes > 1 && $ligCourante < $nbLignes) {  // fleche vers le bas
                    echo '&nbsp;<a title="Déplacer vers le bas" href="ordre.php?table=' . $table . '&id=' . $obj->getId() . '&parentId=' . $obj->getParentId() . '&numOrdre=' . $obj->getNumOrdre() . '&action=down"><span class="glyphicon glyphicon-arrow-down"></span></a>&nbsp;';
                }
                // recopie de la geoloc du parent si niveau !=1
                if ($table == 'site' && $obj->getNiveau() > 1) {
                    $monUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    echo '&nbsp;<a title="Recopier l\'adresse et la géolocalisation du parent" href="gps_modif.php?site_id=' . $obj->getId() . '&url=' . $monUrl . '"  onclick="javascript:return(confirm(\'Etes-vous certain de vouloir recopier les coordonnées géographiques du parent ?\'))" style="color:#080"><span class="glyphicon glyphicon-globe"></span></a>&nbsp;';
                }
            }
            break;
        case 'drh':
            echo '&nbsp;<a title="XLS du service" href="XLS_organigramme.php?id=' . $obj->getId() . '&lib=' . $obj->getLibelle() . '" target="_blank"><img src="images/excel.png"/></a>';
            echo '&nbsp;<a title="Organigramme du service" href="org.php?id=' . $obj->getId() . '" target="_blank"><img src="images/sitemap_color.png"/></a>&nbsp;';
        default :
            break;
    }
    echo '&nbsp;</td>';
    echo '</tr>';
    echo '</table>';
}

/* fonction qui permet d'afficher le bouton "home" (au début de l'arbre)
 * le parametre $lien précise la page de redirection (href="xxx") sur chaque objet
 *              si $lien='' --> aucun href n'est ajouté */

function AfficheHome($lien) {
    global $table;
    if ($lien != '') {
        echo '<table width="100%">';
        echo '<tr>';
        echo '<td colspan="1" width="10%"></td>';
        echo '<td colspan="6" width="90%">';
        echo '<a href="' . $lien . '&id=0">';
        echo '<img src="images/house.png">';
        echo '</a>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
    }
}

/* fonction qui permet d'afficher le bouton "+" (à la fin de l'arbre)
 * parametres :
 *   $role --> 'admin' ou ''
 *   $mode --> 'ajout' ou 'modif'
 *   $niveau --> niveau de l'élément (1 : 1er niveau, 2 : 2eme niveau...)
 *   $parentId --> id du parent
 * */

function AfficheAjout($id, $mode, $role) {

    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();

    global $table;
    global $columns;
    global $niveauMax;
    global $txtcolor;
    global $bgcolor;
    if ($role == 'dsi') {// && $niveau[0]<=5){
        // RECHERCHE DE L'ELEMENT
        $query = 'SELECT ' . $columns . ' FROM ' . $table . ' WHERE id = "' . $id . '"  ';
        $result = $con->query($query);
        $nbLigne = $result->rowCount();
        $objet = null;
        $niveau = 1;
        //echo $nbLigne.$query;
        if ($nbLigne > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {  // boucle sur les lignes trouvees
                if ($table == 'site') {
                    $objet = new Site($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17]);
                } else {
                    $objet = new Service($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10]);
                    $objet->setAlias($row[11]);
                }
            }
            $niveau = $objet->getNiveau();
        }
        // indentation en fonction du niveaux
        $indent = '';
        $n = $niveau;
        while ($n > 1) {
            $indent = $indent . '&nbsp;&nbsp;&nbsp;&nbsp;';
            $n--;
        }
        switch ($mode) {
            case 'ajout':
                echo '<form name="ajout" action="objet_ajout.php" method="get">';
                echo '<input type="hidden" name="courantId" value="' . $id . '">';
                echo '<input type="hidden" name="table" value="' . $table . '">';
                echo '<table width="100%"><tr>';
                echo '<td colspan="1" width="10%" rowspan="14"></td>';
                echo '<td colspan="' . ($niveau) . '" width="' . ($niveau * 4) . '%" rowspan="14">&nbsp;</td>';
                echo '<td width="12%">';
                $lib = 'Premier niveau';
                if ($objet != NULL) {
                    $lib = 'Sous niveau de ' . $objet->getLibelle();
                    if ($table == 'site') {
                        $adr1 = $objet->getAdr1();
                        $adr2 = $objet->getAdr2();
                        $cp = $objet->getCp();
                        $ville = $objet->getVille();
                        $lat = $objet->getLatitude();
                        $lng = $objet->getLongitude();
                        $poolId = $objet->getPoolId();
                    } else {
                        $alias = $objet->getAlias();
                    }
                }
                echo 'Libellé  </td><td><input type="text" name="libelle" size="100" maxlength="1024" value="' . $lib . '"></td></tr>';
                echo '<tr><td>Commentaire  </td><td><input type="text" name="commentaire" size="45" maxlength="45"></td></tr>';
                echo '<tr><td>Couleur du texte  </td><td>' . construitSelect('couleurTexte', $txtcolor, 'Noir') . '</td></tr>';
                echo '<tr><td>Couleur de fond  </td><td>' . construitSelect('couleurFond', $bgcolor, 'Aucune') . '</td></tr>';
                echo '<tr><td>Gras  </td><td>' . construitCheckbox("gras", 0) . '</td></tr>';
                echo '<tr><td>Italique  </td><td>' . construitCheckbox("italique", 0) . '</td></tr>';
                if ($table == 'site') {
                    require_once'E:/wamp64/www/flotte/api_flotte.php';
                    $CD28Pools = API_TableauDesPools();
                    $CD28Pools[-1] = 'Aucun';
                    ksort($CD28Pools);
                    echo '<tr><td>Pool</td><td>' . construitSelectStd('pool', $CD28Pools, $poolId) . '</td></tr>';
                    echo '<tr><td>Adresse 1</td><td><input type="text" name="adr1" size="100" maxlength="255" value="' . $adr1 . '"></td></tr>';
                    echo '<tr><td>Adresse 2</td><td><input type="text" name="adr2" size="100" maxlength="255" value="' . $adr2 . '"></td></tr>';
                    echo '<tr><td>Code postal</td><td><input type="text" name="cp" size="100" maxlength="100" value="' . $cp . '"></td></tr>';
                    echo '<tr><td>Ville</td><td><input type="text" name="ville" size="100" maxlength="100" value="' . $ville . '"></td></tr>';
                    echo '<tr><td>Latitude</td><td><input type="text" name="lat" size="100" maxlength="20" value="' . $lat . '"></td></tr>';
                    echo '<tr><td>Longitude</td><td><input type="text" name="lng" size="100" maxlength="20" value="' . $lng . '"></td></tr>';
                }
                if ($table == 'service') {
                    echo '<tr><td>Alias  </td><td><input type="text" name="alias" size="45" maxlength="45" value="' . $alias . '"></td></tr>';
                }
                echo '<tr><td></td><td><input type="submit" value="Ajouter"></td></tr></table>';
                echo '</form>';
                break;
            case 'modif':
                echo '<form name="modif" action="objet_modif.php" method="get">';
                echo '<input type="hidden" name="courantId" value="' . $objet->getId() . '">';
                echo '<input type="hidden" name="parentId" value="' . $objet->getParentId() . '">';
                echo '<input type="hidden" name="table" value="' . $table . '">';
                echo '<table width="100%"><tr>';
                echo '<td colspan="1" width="10%" rowspan="16"></td>';
                echo '<td colspan="' . ($niveau) . '" width="' . (($niveau - 1) * 4) . '%" rowspan="16">&nbsp;</td>';
                echo '<td width="12%">';
                echo 'Libellé  </td><td><input type="text" name="libelle" size="100" maxlength="64" value="' . $objet->getLibelle() . '"></td></tr>';
                // 64 car maxi sinon impossible de maj la zone direction dans l'AD pour la signature mail
                echo '<tr><td>Commentaire  ';
                infoBulle('Ce commentaire sera affiché entre parenthèses à droite du libellé du service. Il sera également utilisé pour les recherches');
                echo '</td><td><input type="text" name="commentaire" size="45" maxlength="45" value="' . $objet->getCommentaire() . '"></td></tr>';
                echo '<tr><td>Couleur du texte  </td><td>' . construitSelect('couleurTexte', $txtcolor, $objet->getCouleurTexte()) . '</td></tr>';
                echo '<tr><td>Couleur de fond  ';
                infoBulle('A préciser uniquement si le service doit être affiché d\'une couleur particulière. Par défaut la couleur sera un dégradé de la couleur du parent');
                echo '</td><td>' . construitSelect('couleurFond', $bgcolor, $objet->getCouleurFond()) . '</td></tr>';
                echo $indent . '<tr><td>Gras  </td><td>' . construitCheckbox("gras", $objet->getGras()) . '</td></tr>';
                echo $indent . '<tr><td>Italique  </td><td>' . construitCheckbox("italique", $objet->getItalique()) . '</td></tr>';
                if ($table == 'site') {
                    require_once'E:/wamp64/www/flotte/api_flotte.php';
                    $CD28Pools = API_TableauDesPools();
                    $CD28Pools[-1] = 'Aucun';
                    ksort($CD28Pools);
                    echo '<tr><td>Pool</td><td>' . construitSelectStd('pool', $CD28Pools, $objet->getPoolId()) . '</td></tr>';
                    echo '<tr><td>Adresse 1</td><td><input type="text" name="adr1" size="100" maxlength="255" value="' . $objet->getAdr1() . '"></td></tr>';
                    echo '<tr><td>Adresse 2</td><td><input type="text" name="adr2" size="100" maxlength="255" value="' . $objet->getAdr2() . '"></td></tr>';
                    echo '<tr><td>Code postal</td><td><input type="text" name="cp" size="100" maxlength="255" value="' . $objet->getCp() . '"></td></tr>';
                    echo '<tr><td>Ville</td><td><input type="text" name="ville" size="100" maxlength="100" value="' . $objet->getVille() . '"></td></tr>';
                    echo '<tr><td>Latitude</td><td><input type="text" name="lat" size="20" maxlength="100" value="' . $objet->getLatitude() . '"></td></tr>';
                    echo '<tr><td>Longitude</td><td><input type="text" name="lng" size="20" maxlength="100" value="' . $objet->getLongitude() . '"></td></tr>';
                    echo '<tr><td>Téléphone</td><td>';
                    afficheTableauTel('site', $id, $mode, $role);
                    echo '</td></tr>';
                }
                if ($table == 'service') {
                    echo '<tr><td>Alias  ';
                    infoBulle('Chaîne de caractère supplémentaire permettant de retrouver ce service dans une recherche annuaire');
                    echo '</td><td><input type="text" name="alias" size="45" maxlength="45" value="' . $objet->getAlias() . '"></td></tr>';
                    $query2 = 'SELECT RESP_ID FROM SERVICE WHERE ID = "' . $objet->getId() . '"  ';
                    $result2 = $con->query($query2);
                    $row2 = $result2->fetch(PDO::FETCH_NUM);
                    $resp = agent_DAO::LoadOne($row2[0]);
                    echo '<tr><td>Responsable ';
                    infoBulle('Encadrant des agents de ce service');
                    echo '</td><td><select name="respId" style="align=left;width:474px;font-size:13px;margin-left:0px">';
                    $query3 = 'SELECT AG_ID FROM AGENT WHERE AG_TYPE=1 ORDER BY AG_NOM asc';
                    $result3 = $con->query($query3);
                    echo '<option value="NULL"></option>';
                    while ($row3 = $result3->fetch(PDO::FETCH_NUM)) {
                        $agt = agent_DAO::LoadOne($row3[0]);
                        $mis = mission_DAO::LoadOne($agt->getMisCod());
                        if ($resp && $resp->getId() == $agt->getId()) {
                            echo '<option value="' . $agt->getId() . '" selected="selected">' . $agt->getAlias() . " " . $agt->getPrenom() . " : " . $mis->getLib() . '</option>';
                        } else {
                            echo '<option value="' . $agt->getId() . '">' . $agt->getAlias() . " " . $agt->getPrenom() . " : " . $mis->getLib() . '</option>';
                        }
                    }
                    echo '</select></td></tr>';
                }
                echo '<tr><td>Nouveau parent ';
                infoBulle('ID : Identifiant du service auquel ce service sera rattaché');
                echo '</td><td><input type="text" name="newParentId" size="5" maxlength="5" value="' . $objet->getParentId() . '"></td></tr>';

                echo $indent . '<tr><td colspan=2 align="center"><input type="submit" value="Modifier"></td></tr></form>';
                break;
            default :
                echo '<table width="100%"><tr><td colspan="1" width="10%"></td>';
                // indentation des niveaux en fonction du niveau courant et du niveauMax
                if ($nbLigne > 0)
                    $niveau++;
                echo '<td colspan="' . ($niveau) . '" width="' . (($niveau - 1) * 4) . '%">&nbsp;</td>';
                echo '<td width="' . (83 - ($niveau * 4)) . '%">';
                echo '<a href="index.php?table=' . $table . '&id=' . $id . '&mode=ajout"><img src="images/add.png" alt="Ajouter" border="0" ></a>';
                echo '</td><td width="11%">&nbsp;</td></tr></table>';
        }
    }
}

// fonction qui construit une balise HTML select. Les parametres sont :
//  - nom de la balise "select"
//  - tableau de valeurs [valeur à afficher; valeur à stocker]
//  - valeur à sélectionner
function construitSelect($name, $tableau, $selected) {
    $build = '<select name="' . $name . '">';
    foreach ($tableau as $valeurClaire => $valeurHtml) {
        $build = $build . '<option value="' . $valeurHtml . '" ';
        //selected or not ?
        if ($valeurClaire == $selected || $valeurHtml == $selected) {
            $build = $build . 'selected';
        }
        $build = $build . '>' . $valeurClaire . '</option>';
    }
    $build = $build . '</select>';
    return $build;
}

// fonction qui construit une balise HTML select. Les parametres sont :
//  - nom de la balise "select"
//  - tableau de valeurs [valeur clé à stocker; valeur à afficher]
//  - valeur à sélectionner
function construitSelectStd($name, $tableau, $selected) {
    $build = '<select name="' . $name . '">';
    foreach ($tableau as $key => $value) {
        $build = $build . '<option value="' . $key . '" ';
        //selected or not ?
        if ($key == $selected) {
            $build = $build . 'selected';
        }
        $build = $build . '>' . $value . '</option>';
    }
    $build = $build . '</select>';
    return $build;
}

function construitCheckbox($name, $checked) {
    $build = '<input type="checkbox" name="' . $name . '" value="1" ';
    if ($checked == 1) {
        $build = $build . 'checked';
    }
    $build = $build . ' />';
    return $build;
}

/* fonction qui construit le libellé d'un objet avec tous ses niveaux parents */

function ConstruitLibelleComplet($tabl, $objetId, $separateur) {

    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();

    global $table;
    global $columns;
    // RECHERCHE DES PARENTS ET AJOUT DES PARENTS DANS L'ARBRE GLOBAL
    $parentId = $objetId;
    $libComplet = '';
    $tabInverse = array();
    $tabSize = 0;
    $niv = 2;
    if ($objetId != null) {
        while ($niv > 1) {
            $query = 'SELECT parent_id, id, libelle, niveau FROM organigramme.' . $tabl . ' WHERE id=' . $parentId;
            $result = $con->query($query);
            $row = $result->fetch(PDO::FETCH_NUM);
            $objetCourant = new Element($row[0], $row[1], $row[2], $row[3], null, null, null, null, null, null, 1, 1);

            //if ($objetCourant->getId() != 0){
            $tabInverse[$tabSize] = $objetCourant->getLibelle();
            //}
            $parentId = $objetCourant->getParentId();
            $niv = $objetCourant->getNiveau();
            $tabSize++;
//		$libComplet=$libComplet.$objetCourant->getLibelle();
        }
        // parcourt du tableau pour inverser l'ordre des parents :
        $tabSize--;
        while ($tabSize >= 0) {
            if ($libComplet != '') {
                $libComplet = $libComplet . $separateur;
            }
            $libComplet = $libComplet . $tabInverse[$tabSize];
            $tabSize--;
        }
    }
    return $libComplet;
}

/* fonction qui construit le libellé d'un objet avec tous ses niveaux parents sauf le premier niveau (Conseil dép ou Structures satellit.) */

function ConstruitLibelleCompletSansPremierNiveau($tabl, $objetId, $separateur) {

    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();

    global $table;
    global $columns;
    // RECHERCHE DES PARENTS ET AJOUT DES PARENTS DANS L'ARBRE GLOBAL
    $parentId = $objetId;
    $libComplet = '';
    $tabInverse = array();
    $tabSize = 0;
    $niv = 12;
    if ($objetId != null) {
        while ($niv > 2) {
            $query = 'SELECT parent_id, id, libelle, niveau FROM organigramme.' . $tabl . ' WHERE id=' . $parentId;
            $result = $con->query($query);
            $row = $result->fetch(PDO::FETCH_NUM);
            $objetCourant = new Element($row[0], $row[1], $row[2], $row[3], null, null, null, null, null, null, 1, 1);

            //if ($objetCourant->getId() != 0){
            $tabInverse[$tabSize] = $objetCourant->getLibelle();
            //}
            $parentId = $objetCourant->getParentId();
            $niv = $objetCourant->getNiveau();
            $tabSize++;
// 			if ($row[2] =='Direction générale adjointe des territoires'
// 					OR $row[2] =='Direction générale adjointe des investissements'
// 					OR $row[2] =='Direction générale adjointe des solidarités'
// 					OR $row[2] =='Direction générale adjointe des cultures'
// 					OR $row[2] =='Direction générale adjointe des ressources'){
//				if (startsWith($row[2], 'Direction générale adjointe')) {
            if (strpos($row[2], 'Direction générale adjointe') === 0) {
                $niv = 2; // on n'affiche pas DGS au dessus de DGAI, DGAC...
                // TODO revoir liste des DGA
            }
            //		$libComplet=$libComplet.$objetCourant->getLibelle();
        }
        // parcourt du tableau pour inverser l'ordre des parents :
        $tabSize--;
        while ($tabSize >= 0) {
            if ($libComplet != '') {
                $libComplet = $libComplet . $separateur;
            }
            $libComplet = $libComplet . $tabInverse[$tabSize];
            $tabSize--;
        }
    }
    return $libComplet;
}

/* fonction qui construit le libellé d'un objet sans ses niveaux parents */

function ConstruitLibelleSimple($tabl, $objetId) {
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $affectation = '';
    if ($objetId > 0) {
        $query = 'SELECT libelle FROM organigramme.' . $tabl . ' WHERE id=' . $objetId;
        $result = $con->query($query);
        $row = $result->fetch(PDO::FETCH_NUM);
        if ($row) {
            $affectation = $row[0];
        }
    }
    return $affectation;
}

/* fonction qui construit un tableau avec le libellé des niveaux parents */

function ConstruitTableauParents($tabl, $objetId) {
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();

    global $table;
    global $columns;
    // RECHERCHE DES PARENTS ET AJOUT DES PARENTS DANS L'ARBRE GLOBAL
    $parentId = $objetId;
    $parents = array();
    $tabSize = 0;
    $niv = 2;
    if ($objetId != null) {
        while ($niv > 1) {
            $query = 'SELECT parent_id, id, libelle, niveau FROM organigramme.' . $tabl . ' WHERE id=' . $parentId;
            $result = $con->query($query);
            $row = $result->fetch(PDO::FETCH_OBJ);
            $objetCourant = new Element($row->parent_id, $row->id, $row->libelle, $row->niveau, null, null, null, null, null, null, 1, 1);

            if ($objetCourant->getId() != 0) {
                $parents[$objetCourant->getNiveau()] = $objetCourant->getLibelle();
            }
            $parentId = $objetCourant->getParentId();
            $niv = $objetCourant->getNiveau();
            $tabSize++;
        }
    }
    ksort($parents);
    return $parents;
}

///* fonction qui recherche le libellé d'un niveau n pour tout objet */
//function ConstruitLibelleDuNiveau($tabl, $objetId, $niveau){
//	$db = Connexion_PDO3::getInstance();
//	$con = $db->getDbh();
//
//	global $table;
//	global $columns;
//	// RECHERCHE DE L'OBJET $objetId;
//	$query = 'SELECT parent_id, id, libelle, niveau FROM organigramme.'.$tabl.' WHERE id='.$objetId;
//	$result = $con->query($query);
//	$row = $result->fetch(PDO::FETCH_NUM);
//	// CONSTRUCTION DE L'OBJET
//	$objetCourant = new Element($row[0], $row[1], $row[2], $row[3], null, null, null, null, null, null, 1, 1);
//
//	$libNiveau=$objetCourant->getLibelleDuNiveau($niveau);
//	var_dump($libNiveau);
//	return $libNiveau;
//}

/* fonction qui recherche le libellé du service d'un agent à un niveau n */
function GetServiceDunAgent($agentId, $niveau) {
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $libNiveau = '';
    $agent = agent_DAO::LoadOne($agentId);
    if ($agent != null) {
        $serviceId = $agent->getserviceId();
        $service = service_DAO::LoadOne($serviceId);
        if ($service == null) {
            $libNiveau = '';
        } else {
            $libNiveau = $service->getLibelleDuNiveau($niveau);
        }
    }
    // RECHERCHE DE L'OBJET $objetId;
    return $libNiveau;
}

/*
 * fonction qui affiche les agents d un niveau hiérarchique
 * $obj objet parent (site ou service)
 * $role 'dsi' ou 'drh'
 * $qui 'tous' ou 'chefs' tous les agents ou seulement les chefs
 * $n niveau de la branche
 * $couleurBranche couleur de la branche
 */

function AfficheAgents($obj, $role, $qui, $n, $couleurBranche) {
    $couleurBranche = degradeHexa($couleurBranche);
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();

    global $table;
    global $niveauMax;
    global $display_nonvisibles;

    echo '<table width="100%"  >';
    $droitModificationGroupee = getAutorisation($role, 'ModificationGroupee');
    if ($droitModificationGroupee == 2) {
        echo '<form name="form_chck" action="agent_modif_groupe.php" onsubmit="return valider()" method="post">';
        echo '<input title="Sélectionner tous les agents" type="checkbox" name="all" onclick="selectall(document.form_chck.all,document.form_chck.check)" style="position:fixed;z-index:1111111111;top:98px;left:193px"></br><label for="all" style="position:fixed;z-index:1111111111;top:98px;left:208px">Tout sélectionner</label>';
        echo '<button type="submit" class="btn btn-primary btn-sm" style="position:fixed;z-index:1111111111;top:92px;left:345px"><span class="glyphicon glyphicon-floppy-saved"></span>  Modifier la selection</button>';
    }
    // recherche des agents
    if ($table == 'site') {
        $query = 'select *';
        $query = $query . '	from agent A';
        $query = $query . '	where A.ID_SITE=' . $obj->getId();
        if ($display_nonvisibles == 1 && ($role == "dsi" || $role == "drh")) {
            // on affiche tous les agents
            $query = $query . '	AND A.AG_TYPE!=7';
        } else {
            $query = $query . '	AND A.AG_VISU!=0';
        }
        if ($role != "drh") {
            // seule la drh peut voir les postes vacants
            $query = $query . '	AND A.AG_TYPE!=6';
        }
        $query = $query . ' order by A.AG_ALIAS asc';
    } else if ($table == 'service') {
        $query = 'select *';
        $query = $query . '	from agent A INNER JOIN mission M ON A.AG_MIS_COD=M.MIS_ID';
        $query = $query . '	where (A.ID_SERVICE=' . $obj->getId();
        if ($obj->getId() == 1) {
            // on affiche le PCD un seconde fois en tête des élus
            //$query=$query.'	OR A.AG_MIS_COD="137")';
            $query = $query . ')';
        } else {
            $query = $query . ')';
        }
        if ($display_nonvisibles == 1 && ($role == "dsi" || $role == "drh")) {
            // on affiche tous les agents
            $query = $query . '	AND A.AG_TYPE!=7';
        } else {
            $query = $query . '	AND A.AG_VISU!=0';
        }
        if ($qui == 'chefs') {
            //$query=$query.'	AND A.AG_TYPE=1';
            $query = $query . '	AND A.AG_MIS_COD in (SELECT MIS_ID from MISSION where NUM_ORDRE <3 OR MIS_ID=137) AND ID_SERVICE != 5'; // pour ne pas afficher le directeur de l'ATD
        }
        if ($role != "drh") {
            // seule la drh peut voir les postes vacants
            $query = $query . '	AND A.AG_TYPE!=6';
        }
        $query = $query . ' order by M.NUM_ORDRE asc, A.AG_DOMAINE, A.AG_ALIAS asc';
        //echo $query;
    }
    $result = $con->query($query);
    $domainePrecedent = '';
    while ($ag = $result->fetch(PDO::FETCH_OBJ)) {  // boucle sur les agents trouves
        if ($ag->AG_TYPE == 4) {
            $query = 'SELECT * FROM AGENT WHERE AG_AFFECT_SEC=' . $ag->AG_ID;
            $res2 = $con->query($query);
            $affprin = $res2->fetch(PDO::FETCH_OBJ);
        }

        $mission = mission_DAO::LoadOne($ag->AG_MIS_COD);
        $query = 'SELECT * FROM STATUT INNER JOIN AGENT_STATUT ON STATUT.ID=AGENT_STATUT.STATUT_ID
				 WHERE AGENT_STATUT.AG_ID=' . $ag->AG_ID . ' ORDER BY ID_ASSOC ASC';
        $resStatuts = $con->query($query);
        $n = $obj->getNiveau();

        // affichage du domaine
        if ($table == 'service' && $domainePrecedent != $ag->AG_DOMAINE and $qui != 'chefs') {
            echo '</table><table width="100%"  >';
            echo '<tr><td width="' . (10 + (($n) * 4)) . '%">&nbsp;</td>';
            echo '<td width="' . (79 - ($n * 4)) . '%" style="line-height: 30px; border-left:10px solid ' . $couleurBranche . '; border-bottom:1px solid #e7e7e7; border-top: 1px solid #e7e7e7; border-right: 1px solid #e7e7e7; background-color:#fcfcfc;">&nbsp;&nbsp;';
            echo $ag->AG_DOMAINE;
            echo '</td>';
            echo '<td colspan="1" width="11%"></td>';
            echo '</tr>';
            echo '</table><table width="100%"  style="border-spacing: 0px;margin: 0px;">';
        }
        // affichage de l'agent
        echo '<tr>';
        echo '<td width="30%">&nbsp;</td>';
        echo '<td width="54%">';
        echo '  <div class="row" style="min-height:50px; padding-top:10px">'; // ligne pour nom, poste, tél
        echo '	<div class="col-xs-7">';  // zone pour le NOM
        if ($droitModificationGroupee == 2) {
            echo '<input type="checkbox" id="check" title="Sélectionner" name="tableau[]" value="' . $ag->AG_ID . '">&nbsp;';
        }
        if ($ag->AG_TYPE == 6) {
            $np = '<font color=red>' . $ag->AG_ALIAS . ' ' . $ag->AG_PRENOM . '</font>';
        } else {
            $np = $ag->AG_ALIAS . ' ' . $ag->AG_PRENOM;
        }

        if (!$ag->AG_VISU) {
            if ($ag->AG_DATE_ARCHIVAGE == '9999-12-31') {
                //masqué
                echo '<a href="agent.php?id=' . $ag->AG_ID . '" >' . $np . '</a> (Masqué)';
            } else {
                //archivé
                echo '<a href="agent.php?id=' . $ag->AG_ID . '" ><font style="text-decoration:line-through;font-size:x-small;">' . $np . '</font></a> (Archivé)';
            }
        } else {
            if ($ag->AG_TYPE == 4) { // affectation secondaire
                echo '<a href="agent.php?id=' . $affprin->AG_ID . '" >' . $np . '</a>';
            } else {
                echo '<a href="agent.php?id=' . $ag->AG_ID . '" >' . $np . ' </a>';
            }
        }
        // POSTE ou METIER
        if ($ag->AG_TYPE != 3) { // !astreinte
            echo '		<br>';
            // Construction du libellé affiché sous le nom de l'agent
            // Son poste, sinon son métier, sinon sa mission
            $lib = '';
            $alt = '';
            $poste = poste_DAO::LoadOne($ag->ID_POSTE);
            if ($poste != null) {
                $lib = $poste->getLib();
                $alt = 'Poste de l\'agent';
                if ($ag->ID_SERVICE == 264 or $ag->ID_SERVICE == 1) {
                    $alt = 'Fonction élective';
                }
            }
            if (strlen($lib) == 0) {
                $metier = metier_DAO::LoadOne($ag->ID_METIER);
                if ($metier != null) {
                    $lib = $metier->getLib();
                    $alt = 'Métier de l\'agent';
                }
            }
            // 			if (strlen($lib) == 0){
            // 				$lib=$mission->getLib();
            // 			}
            if ($role == 'drh') {
                if (strlen($lib) > 0) {
                    $lib = $lib . ' - ';
                }
                $lib = $lib . $ag->AG_TPS;
            }
            //echo $lib;
            echo '
		<ul class="nav nav-pills" role="tablist">
		<li role="presentation" style="margin-left: -3px; margin-right: 10px;"><a href="#" title="' . $alt . '"><span style="color:black;font-size:x-small;">' . $lib . '</span></a></li>
		</ul>';
        }
        if ($role == 'drh') {
            $premier = true;
            while ($stat = $resStatuts->fetch(PDO::FETCH_OBJ)) {
                if (!$premier)
                    echo '<br>';
                $libfinal = $stat->lib_statut;
                if ($stat->ST_DATE) {
                    $libfinal = $libfinal . " - " . date_fr($stat->ST_DATE);
                }
                if ($stat->couleur) {
                    echo '  	<font COLOR=' . $stat->couleur . ' size=1px>' . $libfinal . '</font>';
                } else {
                    echo '  	<font size=1px>' . $libfinal . '</font>';
                }
                if ($stat->LIBELLE) {
                    echo '  	<font color=red size=1px>' . $stat->LIBELLE . '</font>';
                }
                $premier = false;
            }
        }


        echo '</div>';

        //numéro de téléphone principal
        $query = 'SELECT LIG_NUM_APPEL, LIG_NUM_APPEL_COURT FROM LIGNE WHERE AGENT_ID=' . $ag->AG_ID . ' AND LIG_AFF_ANNUAIRE=1 AND LIG_TYPE !=3 ORDER BY NUM_ORDRE ';
        $res = $con->query($query);
        //var_dump($res);
        //echo $query;
        $COURT = $LONG = $altCourt = $altLong = '';

        if ($res->rowCount() > 0) {
            $altCourt = 'Numéro d\'appel court';
            $altLong = 'Numéro d\'appel long';

            $row = $res->fetch(PDO::FETCH_NUM);
            if ($row[0] || $row[1]) {
                $LONG = '';
                if ($row[0]) {
                    $LONG = chunk_split($row[0], 2, ' ');
                }
                $COURT = '';
                if ($row[1] != null) {
                    $COURT = chunk_split($row[1], 2, ' ');
                }
            } else {
                $numappelAbrege = site_DAO::GetNumAppelAbrege($ag->ID_SITE);
                if ($numappelAbrege) {
                    $COURT = chunk_split($numappelAbrege, 2, ' ');
                    $COURT = $COURT . ' *';
                    $altCourt = $altCourt . ' du site';
                }
                $numappel = site_DAO::GetNumAppel($ag->ID_SITE);
                if ($numappel) {
                    $LONG = chunk_split($numappel, 2, ' ');
                    $LONG = $LONG . ' *';
                    $altLong = $altLong . ' du site';
                }
            }
        }
        echo '<div class="col-xs-2">';
        echo '<ul class="nav nav-pills" role="tablist">';
        echo '<li role="presentation" style="margin-left: 10px; margin-right: 10px;">';
        echo '  <a href="#" title="' . $altCourt . '"><span style="color:black; font-size: 10px;">' . $COURT . '</span></a>';
        echo '</li></ul>';
        echo '</div>';

        echo '<div class="col-xs-3">';
        echo '<ul class="nav nav-pills" role="tablist">';
        echo '<li role="presentation" style="margin-left: 10px; margin-right: 10px;">';
        echo '  <a href="#" title="' . $altLong . '"><span style="color:black; font-size: 10px;">' . $LONG . '</span></a>';
        echo '</li></ul>';
        echo '</div>';

        echo '  </div>'; // fin de ligne pour nom, poste, tél

        echo '</td>';
        echo '<td width="5%" align="right" style="text-align: right; padding-right:0px;">';
        if (isset($_SESSION['display_photos']) && $_SESSION['display_photos'] == 1) {
            $photo = '';
            switch ($ag->AG_TYPE) {
                case 1: //agent
                case 2: //encadrant
                case 4: //affectation secondaire
                    if ($ag->AG_PHOTO != null) {
                        //echo '<div style="height:80px;width:52px;overflow:hidden">';// zone d'affichage de la photo
                        $photo = $ag->AG_PHOTO;
                        //echo '</div>';
                    } else {
                        $photo = 'nophoto.jpg';
                    }
                    break;
                case 3: //astreinte
                    $photo = 'astreinte.jpg';
                    break;
                case 5: // salle de réunion
                    $photo = 'salle.jpg';
                    break;
                default:
                    $photo = 'nophoto.jpg';
                    break;
            }
            echo '<div style="background-size: cover; width: 62.5px; height:80px; background-image:url(../photos/' . $photo . ')"></div>';
        }
        echo '</td>';
        echo '<td width="11%">&nbsp;</td>'; //zone droite pour les icones de modification || cases à cocher
        echo '</tr>';
        $domainePrecedent = $ag->AG_DOMAINE;
    }
    echo '</form>';
    echo '</table>';
}

// affiche le tableau des num téléphone d'un proprio (agent ou site) et renvoie le nombre de lignes
function afficheTableauTel($proprio, $id, $mode, $role) {
    $agent = null;
    if ($proprio == 'agent') {
        $agent = agent_DAO::LoadOne($id);
    }
    $drTelephoneVisibilite = getAutorisation($role, 'TelephoneVisibilite');
    $lignes = array();
    if ($proprio == 'agent') {
        $lignes = ligne_DAO::LoadLignesDunAgent($id);
    }
    if ($proprio == 'site') {
        $lignes = ligne_DAO::LoadLignesDunSite($id);
    }

    echo '<table class="table2" style="margin-left: 0;">'; // tableau des n° téléphone
    // l'agent a t il un fixe ? si non, on affiche le numéro du site
    if ($proprio == 'agent' and $agent->getType() < 3) {
        $yaUnFixe = false;
        foreach ($lignes as $ligne) {
            if ($ligne->getType() == 1) {
                $yaUnFixe = true;
            }
        }
        if ($proprio == 'agent' and!$yaUnFixe and $mode == null) {
            // 		afficheTableauTel('site',$agent->getSiteId(), 'consult', $role);
            // ligne du site
            $lignesSite = ligne_DAO::LoadLignesDunSite($agent->getSiteId());
            $lignesAgent = $lignes;
            $lignes = array_merge($lignesSite, $lignesAgent);
        }
    }

    $first = true;
    $entete = 0;
    $pair = 0;
    foreach ($lignes as $ligne) {
        if ($ligne->getAffAnnuaire() == 1 || $drTelephoneVisibilite == 2) {  // si la ligne est visible ou si le user peut gérer la visibilité des téléphones
            // AFFICHAGE DE L ENTETE DU TABLEAU DES TELEPHONES
            if ($entete == 0) {
                echo '<tr class="TabImpair">
			   		  <td></td>
			   		  <td align="center">N° Abrégé</td>
			   		  <td align="center" style="min-width:120px;">N° Long';
                echo '</td>';
                if (($mode == 'ajout' || $mode == 'modif') && $drTelephoneVisibilite == 2 && $proprio == 'agent') {
                    echo '<td align="center" style="font-size: 10px;">Visible sur l\'annuaire</td>
			        	  <td align="center" style="font-size: 10px;">Visible sur la signature mail</td>
			        	  </tr>';
                } else {
                    // visu simple
                    // radio bouton annuaire et mail masqués
                    //echo '<td colspan=2></td>';
                }
                echo '</tr>';
                $entete = 1;
            }
            // AFFICHAGE DES LIGNES DE TELEPHONES
            if ($pair == 0) {
                echo '<tr class="TabPair">';
                $pair = 1;
            } else {
                echo '<tr class="TabImpair">';
                $pair = 0;
            }
            // type de ligne
            switch ($ligne->getType()) {
                case 3:
                    //echo 'Fax';
                    echo '<td><span class="glyphicon glyphicon-print"></span></td>';
                    break;
                case 2:
                    //echo 'Mobile';
                    echo '<td><span class="glyphicon glyphicon-phone"></span></td>';
                    break;
                case 1:
                default:
                    //echo 'Fixe';
                    echo '<td><span class="glyphicon glyphicon-earphone"></span></td>';
                    break;
            }
            // num court
            echo '<td align="center">' . $ligne->getNumAppelCourt();
            if ($ligne->getSiteId() > 0 and strlen($ligne->getNumAppelCourt()) > 1) {
                echo '<a href="#" class="link tooltip-link" data-toggle="tooltip" data-original-title="Numéro d\'appel du site">
					<span class="glyphicon glyphicon-asterisk" ></span></a>';
            }
            echo '&nbsp;</td>';
            // num long
            echo '<td align="center">' . $ligne->getNumAppel();
            if ($ligne->getSiteId() > 0 and strlen($ligne->getNumAppel()) > 1) {
                echo '<a href="#" class="link tooltip-link" data-toggle="tooltip" data-original-title="Numéro d\'appel du site">
					<span class="glyphicon glyphicon-asterisk" ></span></a>';
            }
            if ($ligne->getLib() != null and strlen($ligne->getLib()) > 0) {
                echo '<br/>' . $ligne->getLib();
            }
            echo '&nbsp;</td>';
            if (($mode == 'ajout' || $mode == 'modif') && $drTelephoneVisibilite == 2 && $proprio == 'agent') {
                $modifiable = false;
                echo '<td align="center" width="100px">';
                // non modifiable sur cet écran
                if ($ligne->getAffAnnuaire() == 1) {
                    echo '<input type="hidden" name="visuAnnu' . $ligne->getId() . '" value="on">';
                    echo '<span class="glyphicon glyphicon-ok"></span>';
                } else {
                    echo '<input type="hidden" name="visuAnnu' . $ligne->getId() . '" value="off">';
                    echo '<span class="glyphicon glyphicon-remove"></span>';
                }

                echo '</td>';
                // 			if ($first){
                // 				// la première ligne est obligatoirement visible sur l'annuaire
                // 				echo '<td align="center" width="100px">';
                // 				echo '<img src="images/tick.png"></td>';
                // 			} else {
                // 				echo '<td align="center" width="100px">';
                // 				echo '<input type="hidden" name="visuAnnu'.$ligne->getId().'" value="off">';
                // 				if($ligne->getAffAnnuaire()==1) {
                // 					echo '<input type="checkbox" name="visuAnnu'.$ligne->getId().'" checked>';
                // 				}
                // 				else {
                // 					echo '<input type="checkbox" name="visuAnnu'.$ligne->getId().'">';
                // 				}
                // 				echo "&nbsp;&nbsp;&nbsp;</td>";
                // 			}
                echo'<td align="center" width="100px">';
                echo '<input type="hidden" name="visuMail' . $ligne->getId() . '" value="off">';
                if ($ligne->getAffSignature() == 1) {
                    echo '<input type="checkbox" name="visuMail' . $ligne->getId() . '" checked>';
                } else {
                    echo '<input type="checkbox" name="visuMail' . $ligne->getId() . '">';
                }
                echo "</td>";
            } else {
                // visu simple
                // radio bouton annuaire et mail masqués
                //echo '<td colspan=2></td>';
            }
            echo '</tr>';
        }
        $first = false;
    } //fin du while sur les n° téléphone
    if ($mode == 'modif' && (getAutorisation($role, 'Telephone') == 2 || getAutorisation($role, 'TelephoneVisibilite') == 2)) {
        // affichage du CRAYON pour gérer les lignes
        $link = 'lignes.php?agentid=' . $id;
        if ($proprio == 'site') {
            $link = 'lignes.php?siteid=' . $id;
        }
        if (sizeof($lignes) > 0) {
            echo '	<tr><td colspan=5>';
        }
        echo '<a title= "Gérer les coordonnées téléphoniques" href="JavaScript:popup(\'' . $link . '\',\'toto\',900,400);"><span class="glyphicon glyphicon-pencil" style="font-size: 20px;color:#080"></span></a>';
        if (sizeof($lignes) > 0) {
            echo '</td></tr>';
        }
    }
    //if (sizeof($lignes) >0){
    echo '</table>'; // fin du tableau des n° téléphone
    //}
    return sizeof($lignes);
}

/*
 * Méthode qui renvoie un tableau avec les initiales des valeurs d'une colonne d'une table
 * @table : la table concernée
 * @colonne : la colonne concernée
 */

function ConstruitListeInitiales($base, $table, $colonne, $where) {
    $returnResult = array();
    if (strlen($where) == 0) {
        $where = ' 1 = 1 ';
    }
    if ($base == 'ORGANIGRAMME') {
        $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    }
    if ($base == 'SIRH') {
        $db = Connexion_PDO_SIRH::getInstance();
    }
    $con = $db->getDbh();
    if ($base == 'ORGANIGRAMME') {
        $query = 'SELECT DISTINCT LEFT(' . $colonne . ',1) as champ_alias FROM ' . $table . ' WHERE ' . $where . ' ORDER BY ' . $colonne . ' ASC'; // syntaxe mysql
    }
    if ($base == 'SIRH') {
        $query = 'SELECT  distinct champ_alias from (select SUBSTR(UPPER(' . $colonne . '), 1, 1) as champ_alias FROM ' . $table . ' where ' . $where . ' ) order by champ_alias'; // syntaxe ORACLE
    }
    $result = $con->query($query);
    while ($occur = $result->fetch(PDO::FETCH_NUM)) {
        $returnResult[] = $occur[0];
    }
    return $returnResult;
}

function ConstruitInitialesAlpha() {
    $tab = array();
    foreach (range('A', 'Z') as $i) {
        $tab[] = $i;
    }
    return $tab;
}

function ConstruitInitialesAlphaNum() {
    $tab = array();
    foreach (range('A', 'Z') as $i) {
        $tab[] = $i;
    }
    foreach (range('0', '9') as $i) {
        $tab[] = $i;
    }
    return $tab;
}

function AfficheBrancheMobile($n, $couleurBranche, $element, $niv) {

    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();

    global $arbre;
    global $id;
    /* if ($n==0) {
      // affichage du home
      AfficheHome($lien);
      } */
    $nbLignes = count($arbre[$n]);
    $ligCourante = 1;
    $possedeDesSousElements = false;
    foreach ($arbre[$n] as $key => $obj) {
        //if ($n==28) { echo $key.$obj->getLibelle().'<br>';}
        AfficheLigneMobile($obj, $couleurBranche, $ligCourante, $nbLignes, $element);
        if (array_key_exists($obj->getId(), $arbre)) {   // ce fils est-il un noeud ?
            $possedeDesSousElements = true;
            if ($obj->getCouleurFond() != null) {
//         	    echo 'branche trouvée : '.$obj->getId().' de couleur : '.$obj->getCouleurFond().'<br>';
//         	    echo ' dont voici les enfants : <br>';//.degradeHexa($obj->getCouleurFond()).'<BR/>';
                $couleurDegradeePourLesEnfants = degradeHexa($obj->getCouleurFond());
            } else {
                $couleurDegradeePourLesEnfants = degradeHexa($couleurBranche);
            }

            if (strlen($couleurDegradeePourLesEnfants) < 4) {
                $couleurDegradeePourLesEnfants = degradeHexa($couleurBranche);
            }
            if ($niv != 1) {
                // on affiche les sous branches tant qu'on arrive pas au niveau 1
                // si $niv=0 (pas de limite) on affiche toutes les sous-branches
                AfficheBrancheMobile($obj->getId(), $couleurDegradeePourLesEnfants, $element, $niv - 1);
            }
        }
        $ligCourante++;
    }
//    if ($possedeDesSousElements==false && $role=="admin")
//    {
//		// affichage du +
//		AfficheAjout($role, $mode, $obj->getNiveau() - 1, $obj->getParentId(), $obj->getId());
//
//	}
}

/**
 * fonction qui permet d'afficher une ligne d'objet à l'écran
 * @param $obj Object objet à afficher
 * @param $couleurDegrade String couleur de fond dégradée
 * @param $ligCourante Integer n° de la ligne courante
 * @param $nbLignes Integer nb de lignes du niveau courant pour les fleches haut et bas
 */
function AfficheLigneMobile($obj, $couleurDegrade, $ligCourante, $nbLignes, $element) {
    global $id;
    global $table;
    global $text; // text à surligner
    global $niveauColor; // tableau de constantes : couleurs de fond par niveau (niveau, valeurs des couleurs HTML correspondantes)
    global $modeCouleur; // 'BD' ou 'constantes'
    global $niveauMax;  // nombre de niveau d'objets

    $idService = $obj->getId();
    $agent = getNbAgentByServiceId($idService);
    $mail = getNbMailByServiceId($idService);
    $fix = getNbFixByServiceId($idService);
    $mobile = getNbMobileByServiceId($idService);
    $tab = getNbTabletteByServiceId($idService);
    $cle = getNbCleByServiceId($idService);
    $smartphone = getNbSmartphonByServiceId($idService);
//	echo " couleur dégradée pour affichage de la ligne : ".$couleurDegrade;
    /* if ($obj -> getNiveau() == 1 ) {
      echo "<table width='100%>
      <tr>
      <td colspan='0' width='0%'></td>
      <td colspan='7' width='51%' style='border-radius: 12px; line-height: 30px;'  bgcolor='#FFFFFF' >
      <td width='7%' align='center'>NB Agent</td>
      <td width='7%' align='center'>NB Mail</td>
      <td width='7%' align='center'>NB Fixe</td>
      <td width='7%' align='center'>NB Mobile</td>
      <td width='7%' align='center'>NB Tablette</td>
      <td width='7%' align='center'>NB Cle</td>
      <td width='7%' align='center'>NB Smartphone</td>
      </tr>
      </table>";
      } */
    //if (!($mobile==0 && $tab==0 && $cle==0 && $smartphone==0)) {

    echo '<table width="100%">';
    echo '<tr>';
    //echo '<td colspan="1" width="10%">'.Surligne($obj->getCommentaire(), $text).'</td>';
    // indentation des niveaux en fonction du niveau courant et du niveauMax
    $n = $obj->getNiveau();
    echo '<td colspan="' . ($n - 1) . '" width="' . (($n - 1) * 4) . '%">&nbsp;</td>';
    echo '<td colspan="' . ($niveauMax + 1 - $n) . '" width="' . (55 - ($n * 4)) . '%" style="line-height: 30px;" ';
    if ($modeCouleur[$table] === 'BD') {
        // couleur définie en base pour l'objet courant
        if ($obj->getCouleurFond() === '' || $obj->getCouleurFond() === null || $obj->getCouleurFond() === 0) {
            // couleur non définie pour cet objet
            echo ' bgcolor="' . $couleurDegrade . '" >';
        } else {
            echo ' bgcolor="' . $obj->getCouleurFond() . '" >';
        }
    } else if ($modeCouleur[$table] === 'BDbrut') {
        // couleur définie en base pour l'objet courant
        if ($obj->getCouleurFond() === '' || $obj->getCouleurFond() === null || $obj->getCouleurFond() === 0) {
            // couleur non définie pour cet objet: on lui affecte un degradé du niveau supérieur
            echo ' >';
        } else {
            echo ' bgcolor="' . $obj->getCouleurFond() . '" >';
        }
    } else {
        // mode couleurs statiques définies dans le tableau de constantes
        echo ' bgcolor="' . $niveauColor[$obj->getNiveau()] . '" >';
    }

    //formatage
    if ($obj->getGras() == 1) {
        echo '<b>';
    }
    if ($obj->getItalique() == 1) {
        echo '<i>';
    }

// 		if ($lien != ''){
// 			echo '<a href="'.$lien.'&id='.$obj->getId().'"';
// 			if ($table=='site') {
// 				echo ' title="'.supprAccents($obj->getAdresse(' ')).'"';
// 			}
// 			echo ' >';
// 		}
    echo '<span style="color:';
    if ($obj->getCouleurTexte() != '') {
        echo $obj->getCouleurTexte();
    } else {
        echo 'black';
    }
    echo ';';
    if (!$obj->isActif()) {
        echo 'text-decoration:line-through;font-size:x-small;';
    }

    echo '">&nbsp;&nbsp;' . Surligne($obj->getLibelle(), $text) . '</span>';

    //echo ' couleurDeDegrade:'.$couleurDegrade;
// 		if ($lien != ''){
// 			echo '</a>';
// 		}
    //formatage (fin)
    if ($obj->getItalique() == 1) {
        echo '</i>';
    }
    if ($obj->getGras() == 1) {
        echo '</b>';
    }
    //echo '</td><td colspan="1" width="51%">'; //zone droite pour les icones de modification || cases à cocher
    echo '<td width="7%" align="center">' . $agent . '</td>';
    echo '<td width="7%" align="center">' . $mail . '</td>';
    echo '<td width="7%" align="center">' . $fix . '</td>';

    if ($mobile == 0) {
        echo '<td width="7%" align="center">' . $mobile . '</td>';
    } else {
        echo '<td width="7%" align="center"><a href="?menu=rechercher&action=rechercherTypeService&idService=' . $idService . '&typeEquip=4" >' . $mobile . '</a></td>';
    }
    if ($tab == 0) {
        echo '<td width="7%" align="center">' . $tab . '</td>';
    } else {
        echo '<td width="7%" align="center"><a href="?menu=rechercher&action=rechercherTypeService&idService=' . $idService . '&typeEquip=3" >' . $tab . '</a></td>';
    }
    if ($cle == 0) {
        echo '<td width="7%" align="center">' . $cle . '</td>';
    } else {
        echo '<td width="7%" align="center"><a href="?menu=rechercher&action=rechercherTypeService&idService=' . $idService . '&typeEquip=2" >' . $cle . '</a></td>';
    }
    if ($smartphone == 0) {
        echo '<td width="7%" align="center">' . $smartphone . '</td>';
    } else {
        echo '<td width="7%" align="center"><a href="?menu=rechercher&action=rechercherTypeService&idService=' . $idService . '&typeEquip=5" >' . $smartphone . '</a></td>';
    }
    echo '</tr>';
    echo '</table>';

    //}
}

/**
 * fonction qui affiche au format XLS une branche de l'arbre de niveau $n
 * @param $n Integer identifiant de la branche de départ
 * 			    0 pour afficher la racine
 * 				sinon id de l'élément de départ
 * @param $couleurBranche String couleur code HTML pour la branche qui sera degradé dans les sous-éléments
 */
function AfficheBrancheXLS($n, $couleurBranche) {
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();

    global $arbre;
    global $id;
    $ligCourante = 1;
    $possedeDesSousElements = false;
    foreach ($arbre[$n] as $key => $obj) {
        AfficheLigneXLS($obj, $couleurBranche);
        //if ($id==$obj->getId()){
        AfficheAgentsXLS($obj, $couleurBranche);
        //}
        if (array_key_exists($obj->getId(), $arbre)) {   // ce fils est-il un noeud ?
            $possedeDesSousElements = true;
            //			echo 'branche trouvée : '.$obj['id'].' de couleur : '.$obj['coulFond'].'<br>';
            //			echo ' dont voici les enfants : '.degradeHexa($obj['coulFond']).'<BR/>';
            $couleurDegradeePourLesEnfants = degradeHexa($obj->getCouleurFond());
            if (strlen($couleurDegradeePourLesEnfants) < 4) {
                $couleurDegradeePourLesEnfants = degradeHexa($couleurBranche);
            }
            // on affiche toutes les sous-branches récursivement
            AfficheBrancheXLS($obj->getId(), $couleurDegradeePourLesEnfants);
        }
        $ligCourante++;
    }
}

/**
 * fonction qui permet d'afficher une ligne d'objet au format XLS
 * @param $obj Object objet à afficher
 * @param $couleurDegrade String couleur de fond dégradée
 */
function AfficheLigneXLS($obj, $couleurDegrade) {
    global $id;
    if ($obj->isActif()) {

        //global $niveauMax;
        $niveauMax = 10;
        //	echo " couleur dégradée pour affichage de la ligne : ".$couleurDegrade;

        echo '<tr>';
        echo '<td width="10%"><FONT color="grey">' . $obj->getCommentaire() . '</font></td>';

        // indentation des niveaux en fonction du niveau courant et du niveauMax
        $n = $obj->getNiveau();
        echo '<td colspan="' . $n . '" width="' . (($n - 1) * 4) . '%">&nbsp;</td>';
        echo '<td colspan="' . ($niveauMax + 1 - $n) . '" width="' . (83 - ($n * 4)) . '%" style="line-height: 30px; ';

        // couleur définie en base pour l'objet courant
        if ($obj->getCouleurFond() === '' || $obj->getCouleurFond() === null || $obj->getCouleurFond() === 0) {
            // couleur non définie pour cet objet
            echo '  border-width:3px; border-style:solid; border-color:' . $couleurDegrade . ';" >';
        } else {
            echo ' border-width:3px; border-style:solid; border-color:' . $obj->getCouleurFond() . ';" >';
        }

        //formatage
        if ($obj->getGras() == 1) {
            echo '<b>';
        }
        if ($obj->getItalique() == 1) {
            echo '<i>';
        }

        echo '<span style="color:';
        if ($obj->getCouleurTexte() != '') {
            echo $obj->getCouleurTexte();
        } else {
            echo 'black';
        }
        echo ';';
        if (!$obj->isActif()) {
            echo 'text-decoration:line-through;font-size:x-small;';
        }

        echo '">&#160;&#160;' . $obj->getLibelle() . '</span>';
        //formatage (fin)
        if ($obj->getItalique() == 1) {
            echo '</i>';
        }
        if ($obj->getGras() == 1) {
            echo '</b>';
        }
        echo '</tr>';
    }
}

/**
 * fonction qui affiche les agents d un niveau hiérarchique
 * $obj objet parent (site ou service)
 * $couleurBranche couleur de la branche
 */
function AfficheAgentsXLS($obj, $couleurBranche) {

    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();

    $table = 'service';
    $niveauMax = 10;

    // recherche des agents
    $query = 'select *';
    $query = $query . '	from agent A INNER JOIN mission M ON A.AG_MIS_COD=M.MIS_ID';
    $query = $query . '	where A.ID_SERVICE=' . $obj->getId();
    $query = $query . '	AND A.AG_VISU!=0';
    $query = $query . '	AND (A.AG_TYPE=1 OR A.AG_TYPE=2 OR A.AG_TYPE=4)';
    $query = $query . ' order by M.NUM_ORDRE asc, A.AG_TYPE asc, A.AG_DOMAINE, A.AG_ALIAS asc';

    $result = $con->query($query);
    $domainePrecedent = '';
    while ($ag = $result->fetch(PDO::FETCH_OBJ)) {  // boucle sur les agents trouves
        if ($ag->AG_TYPE == 4) {
            $query = 'SELECT * FROM AGENT WHERE AG_AFFECT_SEC=' . $ag->AG_ID;
            $res2 = $con->query($query);
            $affprin = $res2->fetch(PDO::FETCH_OBJ);
        }

        $mission = mission_DAO::LoadOne($ag->AG_MIS_COD);
        $query = 'SELECT * FROM STATUT INNER JOIN AGENT_STATUT ON STATUT.ID=AGENT_STATUT.STATUT_ID
				 WHERE AGENT_STATUT.AG_ID=' . $ag->AG_ID . ' ORDER BY ID_ASSOC ASC';
        $resStatuts = $con->query($query);
        $n = $obj->getNiveau();

        // affichage du domaine
        if ($table == 'service' && $domainePrecedent != $ag->AG_DOMAINE) {
            echo '<tr><td colspan="' . ($n + 2) . '" width="' . (10 + (($n) * 4)) . '%">&nbsp;</td>';
            echo '<td colspan="' . ($niveauMax - $n) . '" width="' . (39 + ($n * 4)) . '%" bgcolor="' . $couleurBranche . '" style="line-height: 30px;" >&nbsp;&nbsp;';
            echo $ag->AG_DOMAINE;
            echo '</td>';

            echo '</tr>';
        }
        // affichage de l'agent
        echo '<tr>';
        echo '<td colspan="3" width="20%">&#160;</td>'; //colonne gauche vide
        echo '<td colspan="4" width="20%">';

        // nom -prénom
        if (!$ag->AG_VISU) {
            if ($ag->AG_DATE_ARCHIVAGE == '9999-12-31') {
                echo $ag->AG_ALIAS . ' ' . $ag->AG_PRENOM . ' (Masqué)';
            } else {
                echo '<font style="text-decoration:line-through;font-size:x-small;">' . $ag->AG_ALIAS . ' ' . $ag->AG_PRENOM . '</font> (Archivé)';
            }
        } else {
            echo $ag->AG_ALIAS . ' ' . $ag->AG_PRENOM;
        }
        echo '</td>';

        // fonction
        echo '<td colspan="5" width="40%">';
        if ($ag->AG_TYPE != 3) { // !astreinte
            // Construction du libellé affiché à coté de l'agent
            // Son poste, sinon son métier, sinon sa mission
            $lib = '';
            $poste = poste_DAO::LoadOne($ag->ID_POSTE);
            if ($poste != null) {
                $lib = $poste->getLib();
            }
            if (strlen($lib) == 0) {
                $metier = metier_DAO::LoadOne($ag->ID_METIER);
                if ($metier != null) {
                    $lib = $metier->getLib();
                }
            }
            if (strlen($lib) == 0) {
                $lib = $mission->getLib();
            }
            $lib = $lib . ' - ' . $ag->AG_TPS;
            echo $lib;
        }
        while ($stat = $resStatuts->fetch(PDO::FETCH_OBJ)) {
            $libfinal = ' ' . $stat->lib_statut;
            if ($stat->ST_DATE) {
                $libfinal = $libfinal . " - " . date_fr($stat->ST_DATE);
            }
            if ($stat->couleur) {
                echo '  	<font COLOR=' . $stat->couleur . ' size=1px>' . $libfinal . '</font>';
            } else {
                echo '  	<font size=1px>' . $libfinal . '</font>';
            }
            if ($stat->LIBELLE) {
                echo '  	<font color=red size=1px>' . $stat->LIBELLE . '</font>';
            }
        }
        echo '</td>';

        echo '</tr>';
        $domainePrecedent = $ag->AG_DOMAINE;
    }
}

function ConstruitTableauDeLibellesComplets($table, $separateur) {
    $tab = array();
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $query = 'SELECT * FROM organigramme.' . $table . ' where actif=1 and visu=1';
    $result = $con->query($query);
    while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
        if ($obj != null) {
            $tab[$obj->id] = ConstruitLibelleComplet($table, $obj->id, $separateur);
        }
    }
    asort($tab);
    return $tab;
}

/**
 * renvoie un agent sous forme de tableau
 * @param $login String le login de l'agent
 * @return array() les indexes du tableau sont les colonnes de la table agent (AG_MAIL, AG_ALIAS, AG_PRENOM...)
 */
function getAgentByLogin($login) {
    $agent = array();
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $query = 'SELECT * FROM organigramme.agent where AG_TYPE < 3 and lower(AG_LOGIN)=lower("' . $login . '") order by AG_VISU asc, AG_TYPE desc';
    $result = $con->query($query);
    while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
        if ($obj != null) {
            $agent['AG_MAIL'] = $obj->AG_MAIL;
            $agent['AG_ID'] = $obj->AG_ID;
            $agent['ID_SITE'] = $obj->ID_SITE;
            $agent['ID_SERVICE'] = $obj->ID_SERVICE;
            $agent['AG_MAT_DRH'] = $obj->AG_MAT_DRH;
            $agent['AG_AGT_ID_DRH'] = $obj->AG_AGT_ID_DRH;
            $agent['AG_MAT_AD'] = $obj->AG_MAT_AD;
            $agent['AG_ETS_COD'] = $obj->AG_ETS_COD;
            $agent['AG_NOM'] = $obj->AG_NOM;
            $agent['AG_PRENOM'] = $obj->AG_PRENOM;
            $agent['AG_ORG_COD'] = $obj->AG_ORG_COD;
            $agent['AG_LIEU_COD'] = $obj->AG_LIEU_COD;
            $agent['AG_ALIAS'] = $obj->AG_ALIAS;
            $agent['AG_MAIL'] = $obj->AG_MAIL;
            $agent['AG_LOGIN'] = $obj->AG_LOGIN;
            $agent['AG_RESP_ID'] = $obj->AG_RESP_ID;
        }
    }
    return $agent;
}

function getAgentById($agentId) {
    $agent = array();
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    if ($agentId > 0) {
        $query = 'SELECT * FROM organigramme.agent where AG_ID=' . $agentId;
        $result = $con->query($query);
        if ($result != null) {
            while ($obj = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($obj != null) {
                    $agent = $obj;
                }
            }
        }
    }
    return $agent;
}

function getAgentByMatAD($agentMatAD) {
    $agent = array();
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $query = 'SELECT * FROM organigramme.agent where AG_MAT_AD=' . $agentMatAD . ' order by AG_VISU asc, AG_TYPE desc';
    $result = $con->query($query);
    while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
        if ($obj != null) {
            $agent['AG_MAIL'] = $obj->AG_MAIL;
            $agent['AG_ID'] = $obj->AG_ID;
            $agent['ID_SITE'] = $obj->ID_SITE;
            $agent['ID_SERVICE'] = $obj->ID_SERVICE;
            $agent['AG_MAT_DRH'] = $obj->AG_MAT_DRH;
            $agent['AG_AGT_ID_DRH'] = $obj->AG_AGT_ID_DRH;
            $agent['AG_MAT_AD'] = $obj->AG_MAT_AD;
            $agent['AG_ETS_COD'] = $obj->AG_ETS_COD;
            $agent['AG_NOM'] = $obj->AG_NOM;
            $agent['AG_PRENOM'] = $obj->AG_PRENOM;
            $agent['AG_ORG_COD'] = $obj->AG_ORG_COD;
            $agent['AG_LIEU_COD'] = $obj->AG_LIEU_COD;
            $agent['AG_ALIAS'] = $obj->AG_ALIAS;
            $agent['AG_MAIL'] = $obj->AG_MAIL;
            $agent['AG_LOGIN'] = $obj->AG_LOGIN;
            $agent['AG_RESP_ID'] = $obj->AG_RESP_ID;
        }
    }
    return $agent;
}

function getListeDesAgentsActifs() {
    $tousLesAgents = array();
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $query = 'select * from agent where ag_type < 3 and ag_visu = 1
					order by ag_alias, ag_prenom';
    $result = $con->query($query);
    while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
        if ($obj != null) {
            $agent = Array();
            $agent['AG_MAIL'] = $obj->AG_MAIL;
            $agent['AG_ID'] = $obj->AG_ID;
            $agent['ID_SITE'] = $obj->ID_SITE;
            $agent['ID_SERVICE'] = $obj->ID_SERVICE;
            $agent['AG_MAT_DRH'] = $obj->AG_MAT_DRH;
            $agent['AG_AGT_ID_DRH'] = $obj->AG_AGT_ID_DRH;
            $agent['AG_MAT_AD'] = $obj->AG_MAT_AD;
            $agent['AG_ETS_COD'] = $obj->AG_ETS_COD;
            $agent['AG_NOM'] = $obj->AG_NOM;
            $agent['AG_PRENOM'] = $obj->AG_PRENOM;
            //$agent['AG_NOM']=utf8_encode($obj->AG_NOM);
            //$agent['AG_PRENOM']=utf8_encode($obj->AG_PRENOM);
            $agent['AG_ORG_COD'] = $obj->AG_ORG_COD;
            $agent['AG_LIEU_COD'] = $obj->AG_LIEU_COD;
            //$agent['AG_ALIAS']=utf8_encode($obj->AG_ALIAS);
            $agent['AG_ALIAS'] = $obj->AG_ALIAS;
            $agent['AG_MAIL'] = $obj->AG_MAIL;
            $agent['AG_LOGIN'] = $obj->AG_LOGIN;
            $agent['AG_RESP_ID'] = $obj->AG_RESP_ID;
            $tousLesAgents[$obj->AG_ID] = $agent;
        }
    }
    return $tousLesAgents;
}

function getListeDesAgents() {
    $tousLesAgents = array();
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $query = 'select * from agent where AG_DATE_ARCHIVAGE="9999-12-31" order by ag_alias, ag_prenom';
    $result = $con->query($query);
    while ($agent = $result->fetch(PDO::FETCH_ASSOC)) {
        if ($agent != null) {
            $tousLesAgents[$agent['AG_ID']] = $agent;
        }
    }
    return $tousLesAgents;
}

function getAdresse($siteId) {
    $lieu = '';
    if ($siteId > 0) {
        $db = Connexion_PDO_ORGANIGRAMME::getInstance();
        $con = $db->getDbh();
        $sql = "select concat(site.adr1, '\r', site.adr2, '\r',site.cp,' ', site.ville) as ag_lieu_travail from site where id = " . $siteId;
        $st = $con->query($sql);
        if ($st != null) {
            while ($donnee_site = $st->fetch(PDO::FETCH_OBJ)) {
                if ($donnee_site != null) {
                    $lieu = $donnee_site->ag_lieu_travail;
                }
            }
        }
    }
    return($lieu);
}

function getLignesByAgentId($agentId) {
    $tousLesLignes = array();
    $nbligne = 0;
    if ($agentId > 0) {
        $db = Connexion_PDO_ORGANIGRAMME::getInstance();
        $con = $db->getDbh();
        $query = "select * from ligne where AGENT_ID=$agentId order by NUM_ORDRE";
        $result = $con->query($query);
        if ($result != null) {
            while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
                if ($obj != null) {
                    $ligne = Array();
                    $ligne['LIG_ID'] = $obj->LIG_ID;
                    $ligne['LIG_NUM_APPEL'] = $obj->LIG_NUM_APPEL;
                    $ligne['LIG_NUM_APPEL_COURT'] = $obj->LIG_NUM_APPEL_COURT;
                    $ligne['LIG_AFF_ANNUAIRE'] = $obj->LIG_AFF_ANNUAIRE;
                    $ligne['LIG_AFF_SIGNATURE'] = $obj->LIG_AFF_SIGNATURE;
                    $ligne['COM1'] = $obj->COM1;
                    $ligne['LIG_TYPE'] = $obj->LIG_TYPE;
                    $ligne['LIG_DATE_MAJ'] = $obj->LIG_DATE_MAJ;
                    $ligne['AGENT_ID'] = $obj->AGENT_ID;
                    $ligne['SITE_ID'] = $obj->SITE_ID;
                    $ligne['LIG_NUM_TETE_ID'] = $obj->LIG_NUM_TETE_ID;
                    $ligne['LIG_NUM_TETE'] = $obj->LIG_NUM_TETE;
                    $ligne['LIG_OPERATEUR'] = $obj->LIG_OPERATEUR;
                    $ligne['SERVICE_ID'] = $obj->SERVICE_ID;
                    $tousLesLignes[$obj->LIG_ID] = $ligne;
                    $nbligne++;
                }
            }
        }
        // l'agent a t il un fixe ? si non, on affiche le numéro du site
        $agent = agent_DAO::LoadOne($agentId);
        if ($agent != null and $agent->getType() < 3) {
            /* $yaUnFixe = false;
              foreach($tousLesLignes as $ligne) {
              if ($ligne['LIG_TYPE']==1) {
              $yaUnFixe=true;
              }
              }
              if (!$yaUnFixe) {
             */
            if ($nbligne == 0 and $agent->getSiteId() != null and $agent->getSiteId() > 0) {// si aucune ligne
                // ligne du site
                $lignesSite = array();
                $query = "select * from ligne where SITE_ID=" . $agent->getSiteId() . " order by NUM_ORDRE";
                $result = $con->query($query);
                if ($result != null) {
                    while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
                        if ($obj != null) {
                            $ligne = Array();
                            $ligne['LIG_ID'] = $obj->LIG_ID;
                            $ligne['LIG_NUM_APPEL'] = $obj->LIG_NUM_APPEL;
                            $ligne['LIG_NUM_APPEL_COURT'] = $obj->LIG_NUM_APPEL_COURT;
                            $ligne['LIG_AFF_ANNUAIRE'] = $obj->LIG_AFF_ANNUAIRE;
                            $ligne['LIG_AFF_SIGNATURE'] = $obj->LIG_AFF_SIGNATURE;
                            $ligne['COM1'] = $obj->COM1;
                            $ligne['LIG_TYPE'] = $obj->LIG_TYPE;
                            $ligne['LIG_DATE_MAJ'] = $obj->LIG_DATE_MAJ;
                            $ligne['AGENT_ID'] = $obj->AGENT_ID;
                            $ligne['SITE_ID'] = $obj->SITE_ID;
                            $ligne['LIG_NUM_TETE_ID'] = $obj->LIG_NUM_TETE_ID;
                            $ligne['LIG_NUM_TETE'] = $obj->LIG_NUM_TETE;
                            $ligne['LIG_OPERATEUR'] = $obj->LIG_OPERATEUR;
                            $ligne['SERVICE_ID'] = $obj->SERVICE_ID;
                            $lignesSite[$obj->LIG_ID] = $ligne;
                        }
                    }
                }
                //$lignesAgent=$tousLesLignes;
                //$tousLesLignes=array_merge($lignesSite, $tousLesLignes);
                $tousLesLignes = $lignesSite;
            }
        }
    }
    return $tousLesLignes;
}

function getLignesByAgentIdSansNumSite($agentId) {
    $tousLesLignes = array();
    if ($agentId > 0) {
        $db = Connexion_PDO_ORGANIGRAMME::getInstance();
        $con = $db->getDbh();
        $query = "select * from ligne where AGENT_ID=$agentId order by NUM_ORDRE";
        $result = $con->query($query);
        if ($result != null) {
            while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
                if ($obj != null) {
                    $ligne = Array();
                    $ligne['LIG_ID'] = $obj->LIG_ID;
                    $ligne['LIG_NUM_APPEL'] = $obj->LIG_NUM_APPEL;
                    $ligne['LIG_NUM_APPEL_COURT'] = $obj->LIG_NUM_APPEL_COURT;
                    $ligne['LIG_AFF_ANNUAIRE'] = $obj->LIG_AFF_ANNUAIRE;
                    $ligne['LIG_AFF_SIGNATURE'] = $obj->LIG_AFF_SIGNATURE;
                    $ligne['COM1'] = $obj->COM1;
                    $ligne['LIG_TYPE'] = $obj->LIG_TYPE;
                    $ligne['LIG_DATE_MAJ'] = $obj->LIG_DATE_MAJ;
                    $ligne['AGENT_ID'] = $obj->AGENT_ID;
                    $ligne['SITE_ID'] = $obj->SITE_ID;
                    $ligne['LIG_NUM_TETE_ID'] = $obj->LIG_NUM_TETE_ID;
                    $ligne['LIG_NUM_TETE'] = $obj->LIG_NUM_TETE;
                    $ligne['LIG_OPERATEUR'] = $obj->LIG_OPERATEUR;
                    $ligne['SERVICE_ID'] = $obj->SERVICE_ID;
                    $tousLesLignes[$obj->LIG_ID] = $ligne;
                }
            }
        }
    }
    return $tousLesLignes;
}

function getTelephoneByAgentId($agentId) {
    $tel = '';
    $tels = getLignesByAgentId($agentId);
    foreach ($tels as $t) {
        if ($t['LIG_TYPE'] == 1 and $tel == '') {
            $tel = $t['LIG_NUM_APPEL'];
        }
    }
    if ($tel == '') {
        foreach ($tels as $t) {
            if ($t['LIG_TYPE'] == 2) {
                $tel = $t['LIG_NUM_APPEL'];
            }
        }
    }
    return $tel;
}

/**
 * Fonction qui renvoie le libellé du poste d'un agent
 * @param $agentId Integer identifiant de l'agent
 */
function getPosteDunAgent($agentId) {
    $poste = '';
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $query = 'SELECT poste.libelle as LIBELLE FROM poste, agent
			where agent.ID_POSTE = poste.id
			and agent.AG_ID=' . $agentId;
    $result = $con->query($query);
    if ($result != null) {
        while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
            if ($obj != null) {
                $poste = $obj->LIBELLE;
            }
        }
    }
    return $poste;
}

/**
 * Fonction qui renvoie le libellé du fichier de fiche de poste d'un agent
 * @param $agentId Integer identifiant de l'agent
 */
function getFicheDePosteDunAgent($agentId) {
    $poste = '';
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $query = 'SELECT FICHE_DE_POSTE as LIBELLE FROM agent
			where agent.AG_ID=' . $agentId;
    $result = $con->query($query);
    if ($result != null) {
        while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
            if ($obj != null) {
                $poste = $obj->LIBELLE;
            }
        }
    }
    return $poste;
}

/**
 * Fonction qui renvoie le libellé du métier d'un agent
 * @param $agentId Integer identifiant de l'agent
 */
function getMetierDunAgent($agentId) {
    $metier = '';
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $query = 'SELECT metier.libelle as LIBELLE FROM metier, agent
			where agent.ID_METIER = metier.id
			and agent.AG_ID=' . $agentId;
    $result = $con->query($query);
    if ($result != null) {
        while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
            if ($obj != null) {
                $metier = $obj->LIBELLE;
            }
        }
    }
    return $metier;
}

/**
 * Fonction qui renvoie le groupe de fonction d'un agent (table metier)
 * @param $agentId Integer identifiant de l'agent
 */
function getGroupeDeFonctionDunAgent($agentId) {
    $metier = '';
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $query = 'SELECT metier.groupe as LIBELLE FROM metier, agent
			where agent.ID_METIER = metier.id
			and agent.AG_ID=' . $agentId;
    $result = $con->query($query);
    if ($result != null) {
        while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
            if ($obj != null) {
                $metier = $obj->LIBELLE;
            }
        }
    }
    return $metier;
}

/**
 * Fonction qui renvoie le libellé du statut d'un agent
 * @param $agentId Integer identifiant de l'agent
 */
function getStatutDunAgent($agentId) {
    $statut = '';
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $query = 'SELECT lib_statut as LIBELLE FROM statut, agent_statut WHERE agent_statut.STATUT_ID = statut.id and
	agent_statut.ID_ASSOC = (SELECT max(ID_ASSOC) from agent_statut where AG_ID=' . $agentId . ')';
    $result = $con->query($query);
    if ($result != null) {
        while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
            if ($obj != null) {
                $statut = $obj->LIBELLE;
            }
        }
    }
    return $statut;
}

/**
 * Fonction qui renvoie le RIFSEEP d'un agent (A1, A2, B3...)
 * @param $agentId int identifiant de l'agent
 */
function getRifseepDunAgent($agentId) {
    $grp = '';
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $query = 'SELECT metier.groupe as LIBELLE FROM metier, agent
			where agent.ID_METIER = metier.id
			and agent.AG_ID=' . $agentId;
    $result = $con->query($query);
    if ($result != null) {
        while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
            if ($obj != null) {
                $grp = $obj->LIBELLE;
            }
        }
    }
    return $grp;
}

/**
 * Fonction qui renvoie le libellé du métier d'un agent
 * @param $collectivite string collectivité de l'agent
 * @param $matricule int matricule de l'agent
 */
function getMetierDunAgentParMatricule($collectivite, $matricule) {
    $metier = '';
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $query = "SELECT metier.libelle as LIBELLE FROM metier, agent
			where agent.ID_METIER = metier.id
			and agent.AG_ETS_COD='$collectivite' and agent.AG_MAT_DRH=$matricule";
    $result = $con->query($query);
    if ($result != null) {
        while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
            if ($obj != null) {
                $metier = $obj->LIBELLE;
            }
        }
    }
    return $metier;
}

/**
 * Fonction qui renvoie le RIFSEEP d'un agent (A1, A2, B3...)
 * @param $collectivite string collectivité de l'agent
 * @param $matricule int matricule de l'agent
 */
function getRifseepDunAgentParMatricule($collectivite, $matricule) {
    $grp = '';
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();
    $query = "SELECT metier.groupe as LIBELLE FROM metier, agent
			where agent.ID_METIER = metier.id
			and agent.AG_ETS_COD='$collectivite' and agent.AG_MAT_DRH=$matricule";
    //echo $query;
    $result = $con->query($query);
    if ($result != null) {
        while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
            if ($obj != null) {
                $grp = $obj->LIBELLE;
            }
        }
    }
    return $grp;
}

/**
 * Fonction qui renvoie les libellés de service, direction et DGA
 * @param int $serviceId
 * @return array tableau des libellés dont les clés sont 'service', direction', 'DGA'
 */
function ServiceDirectionEtDgaDunService($serviceId) {
    $returnValue = array();
    $db = Connexion_PDO_ORGANIGRAMME::getInstance();
    $con = $db->getDbh();

    // RECHERCHE DES PARENTS ET AJOUT DES PARENTS DANS L'ARBRE GLOBAL
    $parentId = $serviceId;
    $libComplet = '';
    $tabBrut = array();
    $tabSize = 0;
    $niv = 5;
    $tabBrut = array(2 => '', 3 => '', 4 => '', 5 => '');
    $doIt = true;
    if ($parentId != null) {
        while ($niv >= 2) {
            $query = 'SELECT parent_id, id, libelle, commentaire, niveau FROM organigramme.service WHERE id=' . $parentId;
            $result = $con->query($query);
            $row = $result->fetch(PDO::FETCH_OBJ);

            $tabBrut[$row->niveau] = ucfirst($row->libelle);
            $parentId = $row->parent_id;
            $niv = $row->niveau;
        }
    }
    //var_dump($tabBrut);
    //echo substr($tabBrut[3],0,27);
    if (substr($tabBrut[3], 0, 27) == 'Direction générale adjointe') {
        $returnValue['DGA'] = $tabBrut[3];
    } else {
        $returnValue['DGA'] = $tabBrut[2];
    }
    $returnValue['direction'] = $tabBrut[4];
    $returnValue['service'] = $tabBrut[5];
    return $returnValue;
}
?>







