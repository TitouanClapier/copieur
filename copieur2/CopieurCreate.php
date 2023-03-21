<html lang="fr">
  <head>  
    <?php 
      session_start();
      include_once 'head.php';
      include_once 'Connexion_PDO_COPIEUR.php';
      $db = Connexion_PDO_COPIEUR::getInstance();
      $db = $db->getDbh();

      include_once 'Connexion_PDO_ORGANIGRAMME.php';
      $dborg = Connexion_PDO_ORGANIGRAMME::getInstance();
      $dborg = $dborg->getDbh();
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      
    <title>Ajout d'un copieur</title>
    
  </head>
  <body>
    <!--Navigation bar-->
    <?php  include_once 'nav.php'; ?>
    
    <div style="padding: 5px;">
    <h1>Ajout copieur</h1>
    Les champs annotés d'un <font color="red">*</font> sont obligatoires. L'envoi du formulaire ne se fera pas tant que ces champs ne seront pas remplis.
    <?php 
            $sql = "SELECT id, libelle from modele";
            $query = $db->prepare($sql);
            $query->execute();
            $modeles = $query->fetchAll(PDO::FETCH_ASSOC);   

      if(isset($_POST)){
        if ( isset($_POST['modele_id']) && !(empty($_POST['modele_id']))  
        && isset($_POST['choixSiteId'])  && !(empty($_POST['choixSiteId'])) 
        && isset($_POST['choixServiceId'])  && !(empty($_POST['choixServiceId'])) 
        && isset($_POST['matricule'])  && !(empty($_POST['matricule'])) 
        && isset($_POST['numero_dossier'])  && !(empty($_POST['numero_dossier'])) 
        && isset($_POST['prix_achat_ht'])  && !(empty($_POST['prix_achat_ht'])) 
        && isset($_POST['prix_achat_ttc'])   && !(empty($_POST['prix_achat_ttc'])) 
        && isset($_POST['numero_ugap'])  && !(empty($_POST['numero_ugap'])) 
        && isset($_POST['date_achat'])  && !(empty($_POST['date_achat'])) 
        && isset($_POST['date_livraison'])  && !(empty($_POST['date_livraison'])) 
        && isset($_POST['numero'])  && !(empty($_POST['numero'])) 
        && isset($_POST['duree'])  && !(empty($_POST['duree'])) 
        && isset($_POST['cout_cop_noir'])  && !(empty($_POST['cout_cop_noir'])) 
        && isset($_POST['cout_cop_coul'])  && !(empty($_POST['cout_cop_coul'])) 
        && isset($_POST['cout_cop_logo'])  && !(empty($_POST['cout_cop_logo'])) 
        && isset($_POST['date_debut'])  && !(empty($_POST['date_debut'])) 
        && isset($_POST['prix_trim_maintenance'])  && !(empty($_POST['prix_trim_maintenance'])) 
        && isset($_POST['nb_trim_cop_noir'])  && !(empty($_POST['nb_trim_cop_noir'])) 
        && isset($_POST['nb_trim_cop_coul'])  && !(empty($_POST['nb_trim_cop_coul'])) 
        && isset($_POST['nb_trim_cop_logo']) && !(empty($_POST['nb_trim_cop_logo'])) 
        ){
        
              $modele_id = $_POST['modele_id'];
              $site_id = $_POST['choixSiteId'];
              $service_id = $_POST['choixServiceId'];
              $site = $_POST['choixSite'];
              $service = $_POST['choixService'];
              $matricule = $_POST['matricule'];
              $numero_dossier = $_POST['numero_dossier'];
              $prix_achat_ht = $_POST['prix_achat_ht'];
              $prix_achat_ttc = $_POST['prix_achat_ttc'];
              $numero_ugap = $_POST['numero_ugap'];
              $date_achat = $_POST['date_achat'];
              $date_livraison = $_POST['date_livraison'];

              if (isset($_POST['recto_verso'])) $recto_verso =1;  else $recto_verso= 0;
              if (isset($_POST['a3'])) $a3 =1;  else $a3= 0;
              if (isset($_POST['fax'])) $fax =1;  else $fax= 0;
              if (isset($_POST['couleur'])) $couleur =1;  else $couleur= 0;
              if (isset($_POST['logo'])) $logo =1;  else $logo= 0;
              if (isset($_POST['lecteur'])) $lecteur_badge =1;  else $lecteur_badge= 0;

              $point=".";
              $adresse_ip1=$_POST['adresse_ip1'];
              $adresse_ip2=$_POST['adresse_ip2'];
              $adresse_ip3=$_POST['adresse_ip3'];
              $adresse_ip4=$_POST['adresse_ip4'];

              if (isset($_POST['adresse_ip1']) and !(empty($_POST['adresse_ip1'])) and isset($_POST['adresse_ip2']) and !(empty($_POST['adresse_ip2']))  and isset($_POST['adresse_ip3']) and !(empty($_POST['adresse_ip3']))  and isset($_POST['adresse_ip4']) and !(empty($_POST['adresse_ip4'])) ) {$adresse_ip = "$adresse_ip1$point$adresse_ip2$point$adresse_ip3$point$adresse_ip4";} else $adresse_ip="";
              $finisseur = $_POST['finisseur'];
              $numero = $_POST['numero'];
              $duree = $_POST['duree'];
              $cout_cop_noir = $_POST['cout_cop_noir'];
              $cout_cop_coul = $_POST['cout_cop_coul'];
              $cout_cop_logo = $_POST['cout_cop_logo'];
              $date_debut = $_POST['date_debut'];
              $prix_trim_maintenance = $_POST['prix_trim_maintenance'];
              $nb_trim_cop_noir = $_POST['nb_trim_cop_noir'];
              $nb_trim_cop_coul = $_POST['nb_trim_cop_coul'];
              $nb_trim_cop_logo = $_POST['nb_trim_cop_logo'];
              $commentaire = $_POST['commentaire'];

              $sql="INSERT INTO copieur 
              ( modele_id, matricule, numero_dossier, prix_achat_ht, prix_achat_ttc, numero_ugap, date_achat, date_livraison, recto_verso, a3, fax, adresse_ip, finisseur, couleur, logo, lecteur_badge, commentaire ) 
              VALUES 
              (' $modele_id', '$matricule', '$numero_dossier', '$prix_achat_ht', '$prix_achat_ttc', '$numero_ugap', '$date_achat', '$date_livraison', '$recto_verso', '$a3', '$fax', '$adresse_ip', '$finisseur', '$couleur', '$logo', '$lecteur_badge', '$commentaire' );";
              // VALUES ( int,  string(), int, int, int, int, date(y-m-d) , date(y-m-d), bin, bin, bin, string(), bin, bin, bin, bin, string() );

              
              $query = $db->prepare($sql);
              $tab=array('modele_id'=>$modele_id, 'matricule'=>$matricule, 'numero_dossier'=>$numero_dossier, 'prix_achat_ht'=>$prix_achat_ht, 'prix_achat_ttc'=>$prix_achat_ttc, 'numero_ugap'=>$numero_ugap, 'date_achat'=>$date_achat, 'date_livraison'=>$date_livraison, 'recto_verso'=>$recto_verso, 'a3'=>$a3, 'fax'=>$fax, 'adresse_ip'=>$adresse_ip, 'finisseur'=>$finisseur, 'couleur'=>$couleur, 'logo'=>$logo, 'lecteur'=>$lecteur_badge, 'commentaire'=>$commentaire );
              $query->execute($tab);
              $copieur_id=$db->lastInsertId();
              
              $sql="INSERT INTO copieur_site ( site_id, copieur_id, date_arrivee ) 
              VALUES ( '$site_id', '$copieur_id', '$date_livraison');";

              $query = $db->prepare($sql);
              $tab=array('site_id'=>$site_id, 'copieur_id'=>$copieur_id, 'date_arrivee'=>$date_livraison);
              $query->execute($tab);

              
              $sql="INSERT INTO copieur_service ( service_id, copieur_id, date_arrivee) 
              VALUES ( '$service_id', '$copieur_id', '$date_livraison');";

              $query = $db->prepare($sql);
              $tab=array('service_id'=>$service_id, 'copieur_id'=>$copieur_id, 'date_arrivee'=>$date_livraison);
              $query->execute($tab);
              
              $sql="INSERT INTO contrat (copieur_id, numero, duree, cout_cop_noir, cout_cop_coul, cout_cop_logo, date_debut, prix_trim_maintenance, nb_trim_cop_noir, nb_trim_cop_coul,  nb_trim_cop_logo ) 
              VALUES ( '$copieur_id', '$numero', '$duree', '$cout_cop_noir', '$cout_cop_coul', '$cout_cop_logo', '$date_debut', '$prix_trim_maintenance', '$nb_trim_cop_noir', '$nb_trim_cop_coul', '$nb_trim_cop_logo' );";
              
              $query = $db->prepare($sql);
              $tab=array('copieur_id'=>$copieur_id, 'numero'=>$numero,  'duree'=>$duree, 'cout_cop_noir'=>$cout_cop_noir, 'cout_cop_coul'=>$cout_cop_coul, 'cout_cop_logo'=>$cout_cop_logo, 'date_debut'=>$date_debut, 'prix_trim_maintenance'=>$prix_trim_maintenance, 'nb_trim_cop_noir'=>$nb_trim_cop_noir, 'nb_trim_cop_coul'=>$nb_trim_cop_coul, 'nb_trim_cop_logo'=>$nb_trim_cop_logo);
              $query->execute($tab);

              echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
        
        }
      
      }

        ?>
      <div class="row g-3 needs-validation" novalidate style="padding: 1px; margin-left: 50; margin-right : 50;">
      <form method="post" name="origine">
      
    <!------------------------------><br>

    <div class="row">
      <div class="col-1">
        <label for="modele" class="form-label">modele</label><font color="red">*</font>
      </div>
      <div class="col-2">
        <input name="modele_id" id="modele_id" type="hidden"/> 
        <select class="form-control" onclick="this.form.modele_id.value=value;">
        <option value=" -1 "> Choisir...</option>
        <?php foreach($modeles as $modele): ;?>
        <option name="modele_id" value="<?php echo $modele['id']; ?>"><?php echo $modele['libelle'];?></option>
        <?php endforeach ;?>
        </select>
      </div>
      <div class="col-1">
        <label for="site">site</label><font color="red">*</font>
      </div>
      <div class="col-2">
        <input type="hidden" name="choixSiteId"/><!-- cette zone va recevoir l'id du site choisi -->
        <textarea class="form-control" name="choixSite" cols="50" rows="2" readonly></textarea>
        <?php $link='/annuaire/choixObjet.php?table=site';
        echo '<a href="JavaScript:popup(\''.$link.'\',\'Choix_du_site\',900,650);">Choisir</a>';?>
      </div>
      <div class="col-1">
        <label for="service">service</label>&nbsp;<font color="red">*</font>
      </div>
      <div class="col-2">
        <input type="hidden" name="choixServiceId"/>
        <textarea class="form-control" name="choixService" cols="50" rows="2" readonly></textarea>
        <?php $link='/annuaire/choixObjet.php?table=service';
        echo '</input><a href="JavaScript:popup(\''.$link.'\',\'Choix_du_service\',900,680);">Choisir</a>';?> 			
      </div>
    </div>

    <!------------------------------><br>

    <div class="row justify-content-start">
      <div class="col-1">
        <label for="matricule" class="form-label">matricule</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="text" class="form-control" name="matricule" id="matricule" required value="<?php if ( isset($_POST['matricule']) ) echo $_POST['matricule'];?>">
      </div>
      <div class="col-1 offset-md-1">
        <label for="numero ugap" >n° ugap</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="number" class="form-control" name="numero_ugap" id="numero_ugap" required value="<?php if ( isset($_POST['numero_ugap']) ) echo $_POST['numero_ugap'];?>">
      </div>
      <div class="col-1 offset-md-1">
      <label for="numero_dossier">n° dossier</label><font color="red">*</font>
      </div>
      <div class="col-1">
      <input type="number"  class="form-control" name="numero_dossier" id="numero_dossier" required value="<?php if ( isset($_POST['numero_dossier']) ) echo $_POST['numero_dossier'];?>">
      </div>
    </div>

    <!------------------------------><br>

    <div class="row">
      <div class="col-1">
        <label for="prix_achat_ht">prix d'achat ht</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="number" class="form-control" name="prix_achat_ht" id="prix_achat_ht"  required value="<?php if ( isset($_POST['prix_achat_ht']) ) echo $_POST['prix_achat_ht'];?>"  >
      </div>
      <div class="col-1 offset-md-1">
        <label for="prix_achat_ttc">prix d'achat ttc</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="number" class="form-control" name="prix_achat_ttc" id="prix_achat_ttc" required value="<?php if ( isset($_POST['prix_achat_ttc']) ) echo $_POST['prix_achat_ttc'];?>">
      </div>
      <div class="col-1 offset-md-1">
        <label for="date_achat">date achat</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="date"  class="form-control" name="date_achat" id="date_achat" required value="<?php if ( isset($_POST['date_achat']) ) echo $_POST['date_achat'];?>">
      </div>
      <div class="col-1 offset-md-1">
        <label for="date_livraison">date livraison</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="date"  class="form-control" name="date_livraison" id="date_livraison"  required value="<?php if ( isset($_POST['date_livraison']) ) echo $_POST['date_livraison'];?>">
      </div>
    </div>

    <!------------------------------><br>

    <div class="row">
      <div class="col-1">
        <label for="recto_verso">recto_verso</label>
      </div>
      <div class="col-1">
        <input type="checkbox" name="recto_verso" id="recto_verso" value="<?php if ( isset($_POST['recto_verso']) ) 1;?>">
      </div>
      <div class="col-1 offset-md-1">
        <label for="finisseur">finisseur</label>
      </div>
      <div class="col-1">
      <input type="text"  class="form-control" name="finisseur" id="finisseur" value="<?php if ( isset($_POST['finisseur']) ) echo $_POST['finisseur'];?>">
      </div>
      <div class="col-1 offset-md-1">
        <label for="a3">format A3</label>
      </div>
      <div class="col-1">
        <input type="checkbox" name="a3" id="a3" value="<?php if ( isset($_POST['a3']) ) 1;?>">
      </div>
      <div class="col-1 offset-md-1">
        <label for="couleur">couleur</label>
      </div>
      <div class="col-1">
        <input type="checkbox" name="couleur" id="couleur" value="<?php if ( isset($_POST['couleur']) ) 1;?>">
      </div>
    </div>

    <!------------------------------><br>

    <div class="row">
      <div class="col-1">
        <label for="fax">fax</label>
      </div>
      <div class="col-1">
        <input type="checkbox" name="fax" id="fax" value="<?php if ( isset($_POST['fax']) ) 1;?>">
      </div>
      <div class="col-1 offset-md-1">
        <label for="logo">logo</label>
      </div>
      <div class="col-1">
        <input type="checkbox" name="logo" id="logo" value="<?php if ( isset($_POST['logo']) ) 1;?>">
      </div>
      <div class="col-1 offset-md-1">
        <label for="adresse_ip">adresse ip</label> 
      </div>
      <div class="col-2">
        <input type="number" name="adresse_ip1" id="adresse_ip1" value="<?php if ( isset($_POST['adresse_ip1']) ) echo $_POST['adresse_ip1'];?>" style="width:40px;"> .
        <input type="number" name="adresse_ip2" id="adresse_ip2" value="<?php if ( isset($_POST['adresse_ip2']) ) echo $_POST['adresse_ip2'];?>"style="width:40px;"> .
        <input type="number" name="adresse_ip3" id="adresse_ip3" value="<?php if ( isset($_POST['adresse_ip3']) ) echo $_POST['adresse_ip3'];?>"style="width:40px;"> .
        <input type="number" name="adresse_ip4" id="adresse_ip4" value="<?php if ( isset($_POST['adresse_ip4']) ) echo $_POST['adresse_ip4'];?>"style="width:40px;">
                    
      </div>
      <div class="col-1">
        <label for="lecteur">lecteur</label>
      </div>
      <div class="col-1">
        <input type="checkbox" name="lecteur" id="lecteur" value="<?php if ( isset($_POST['lecteur']) ) 1;?>">
      </div>
    </div>

    <!------------------------------><br>


    <div class="row">
      <div class="col-1">
        <label for="numero">numero contrat</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="number"  class="form-control" name="numero" id="numero" required value="<?php if ( isset($_POST['numero']) ) echo $_POST['numero'];?>">
      </div>
      <div class="col-1 offset-md-1">
        <label for="date_debut">date contrat</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="date"  class="form-control" name="date_debut" id="date_debut" required value="<?php if ( isset($_POST['date_debut']) ) echo $_POST['date_debut'];?>">
      </div>
      <div class="col-1 offset-md-1">
        <label for="duree">duree contrat</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="number"  class="form-control" name="duree" id="duree" required value="<?php if ( isset($_POST['duree']) ) echo $_POST['duree'];?>">
      </div>
      <div class="col-1 offset-md-1">
        <label for="prix_trim_maintenance">prix trimestrielle</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="number"  class="form-control" name="prix_trim_maintenance" id="prix_trim_maintenance" required value="<?php if ( isset($_POST['prix_trim_maintenance']) ) echo $_POST['prix_trim_maintenance'];?>">
      </div>
    </div>

    <!------------------------------><br>

    <div class="row">
      <div class="col-1">
        <label for="cout_cop_noir">cout copie noir</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="number"  class="form-control" name="cout_cop_noir" id="cout_cop_noir" required value="<?php if ( isset($_POST['cout_cop_noir']) ) echo $_POST['cout_cop_noir'];?>">
      </div>
      <div class="col-2">
        <label for="nb_trim_cop_noir">nombre trimestriel de copie noir</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="number"  class="form-control" name="nb_trim_cop_noir" id="nb_trim_cop_noir" required value="<?php if ( isset($_POST['nb_trim_cop_noir']) ) echo $_POST['nb_trim_cop_noir'];?>">
      </div>
    </div>

    <!------------------------------><br>

    <div class="row">
      <div class="col-1">
        <label for="cout_cop_coul">cout copie couleur<font color="red">*</font></label>
      </div>
      <div class="col-1">
        <input type="number"  class="form-control" name="cout_cop_coul" id="cout_cop_coul" required value="<?php if ( isset($_POST['cout_cop_coul']) ) echo $_POST['cout_cop_coul'];?>">
      </div>
      <div class="col-2">
        <label for="nb_trim_cop_coul">nombre trimestriel de copie couleur</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="number"  class="form-control" name="nb_trim_cop_coul" id="nb_trim_cop_coul" required value="<?php if ( isset($_POST['nb_trim_cop_coul']) ) echo $_POST['nb_trim_cop_coul'];?>">
      </div>
    </div>

    <!------------------------------><br>

    <div class="row">
      <div class="col-1">
        <label for="cout_cop_logo">cout copie logo</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="number"  class="form-control" name="cout_cop_logo" id="cout_cop_logo" required value="<?php if ( isset($_POST['cout_cop_logo']) ) echo $_POST['cout_cop_logo'];?>"></td>
      </div>
      <div class="col-2">
        <label for="nb_trim_cop_logo">nombre trimestriel de copie logo</label><font color="red">*</font>
      </div>
      <div class="col-1">
        <input type="number"  class="form-control" name="nb_trim_cop_logo" id="nb_trim_cop_logo" required value="<?php if ( isset($_POST['nb_trim_cop_logo']) ) echo $_POST['nb_trim_cop_logo'];?>"></td>
      </div>
    </div>


    <!------------------------------><br>
    <div class="row">
      <div class="col-1">
      <label for="commentaire">commentaire</label>
      </div>
      <div class="col-5">
      <textarea class="form-control" name="commentaire" id="commentaire" > <?php if ( isset($_POST['commentaire']) ) echo $_POST['commentaire'];?></textarea>
      </div>
    </div>

      <button class="btn btn-primary" type="submit" >Enregistrer</button>

        
      </form>  
    </div>
  </div>  
  </body>
</html>

<script type="text/javascript">
    // Popup affiché centré 
    //     url   : lien à ouvrir
    //     titre : titre du popup 
    //     largeur (ex: 800)
    //     hauteur (ex: 600)
    function popup(url,titre,largeur,hauteur) {
	  var top=(screen.height-hauteur)/2;
	  var left=(screen.width-largeur)/2;
	  var options="top="+top+",left="+left+",width="+largeur+",height="+hauteur+",resizable=yes,scrollbars=no,toolbar=yes,menubar=no,location=no,directories=no,status=no";
	  var pw = window.open(url,titre,options);
    }

// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
</script>


<!--
      
      if (   isset($_POST['site']) && !empty($_POST['site'])){}
      if (   isset($_POST['service']) && !empty($_POST['service'])){}
      if (   isset($_POST['matricule']) && !empty($_POST['matricule'])){}
      if (   isset($_POST['numero_dossier']) && !empty($_POST['numero_dossier'])){}
      if (   isset($_POST['prix_achat_ht']) && !empty($_POST['prix_achat_ht'])){}
      if (   isset($_POST['prix_achat_ttc']) && !empty($_POST['prix_achat_ttc'])){}
      if (   isset($_POST['numero_ugap']) && !empty($_POST['numero_ugap'])){}
      if (   isset($_POST['date_achat']) && !empty($_POST['date_achat'])){}
      if (   isset($_POST['date_livraison']) && !empty($_POST['date_livraison'])){}
      if (   isset($_POST['numero']) && !empty($_POST['numero'])){}
      if (   isset($_POST['duree']) && !empty($_POST['duree'])){}
      if (   isset($_POST['cout_cop_noir']) && !empty($_POST['cout_cop_noir'])){}
      if (   isset($_POST['cout_cop_coul']) && !empty($_POST['cout_cop_coul'])){}
      if (   isset($_POST['cout_cop_logo']) && !empty($_POST['cout_cop_logo'])){}
      if (   isset($_POST['date_debut']) && !empty($_POST['date_debut'])){}
      if (   isset($_POST['prix_trim_maintenance']) && !empty($_POST['prix_trim_maintenance'])){}
      if (   isset($_POST['nb_trim_cop_noir']) && !empty($_POST['nb_trim_cop_noir'])){}
      if (   isset($_POST['nb_trim_cop_coul']) && !empty($_POST['nb_trim_cop_coul'])){}
      if (   isset($_POST['nb_trim_cop_logo']) && !empty($_POST['nb_trim_cop_logo'])){}



      isset($_POST['modele']) && !empty($_POST['modele']) 
        && isset($_POST['site']) && !empty($_POST['site'])
        && isset($_POST['service']) && !empty($_POST['service'])
        && isset($_POST['matricule']) && !empty($_POST['matricule'])
        && isset($_POST['numero_dossier']) && !empty($_POST['numero_dossier'])
        && isset($_POST['prix_achat_ht']) && !empty($_POST['prix_achat_ht'])
        && isset($_POST['prix_achat_ttc']) && !empty($_POST['prix_achat_ttc'])
        && isset($_POST['numero_ugap']) && !empty($_POST['numero_ugap'])
        && isset($_POST['date_achat']) && !empty($_POST['date_achat'])
        && isset($_POST['date_livraison']) && !empty($_POST['date_livraison'])
        && isset($_POST['numero']) && !empty($_POST['numero'])
        && isset($_POST['duree']) && !empty($_POST['duree'])
        && isset($_POST['cout_cop_noir']) && !empty($_POST['cout_cop_noir'])
        && isset($_POST['cout_cop_coul']) && !empty($_POST['cout_cop_coul'])
        && isset($_POST['cout_cop_logo']) && !empty($_POST['cout_cop_logo'])
        && isset($_POST['date_debut']) && !empty($_POST['date_debut'])
        && isset($_POST['prix_trim_maintenance']) && !empty($_POST['prix_trim_maintenance'])
        && isset($_POST['nb_trim_cop_noir']) && !empty($_POST['nb_trim_cop_noir'])
        && isset($_POST['nb_trim_cop_coul']) && !empty($_POST['nb_trim_cop_coul'])
        && isset($_POST['nb_trim_cop_logo']) && !empty($_POST['nb_trim_cop_logo'])