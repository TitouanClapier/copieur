<?php
class Element // Classe Element de base.
{
	private $_parent_id;
	private $_id;
	private $_libelle;
	private $_niveau;
	private $_commentaire;
	private $_couleur_fond;
	private $_couleur_texte;
	private $_gras;
	private $_italique;
	private $_num_ordre;
	private $_libelle_long; // libellés des niveaux 1 à n, séparés par ' - ' (utile pour les tris notamment)
	private $_libelle_tableau_par_niveau; // tableau des libellés, indexés par niveau
	private $_nbCop;
	private $_actif; // 1=object actif  0=objet supprimé 

	public function __construct($parentid,$id, $libelle, $niveau, $commentaire, $couleur_fond, $couleur_texte, $gras, $italique, $num_ordre, $actif/*, $visu*/) {
			$this->_parent_id = $parentid;
			$this->_id=$id;
			$this->_libelle = $libelle;
			$this->_niveau = $niveau;
			$this->_commentaire = $commentaire;
			$this->_couleur_fond = $couleur_fond;
			$this->_couleur_texte = $couleur_texte;
			$this->_gras = $gras;
			$this->_italique = $italique;
			$this->_num_ordre = $num_ordre;
			$this->_actif = $actif;
	    }
	    
	    public function getParentId(){
	    	return $this->_parent_id;
	    }
	    public function setParentId($parentid){
	    	$this->_parent_id = $parentid;
	    }	    
	    public function getId(){
	    	return $this->_id;
	    }
	    public function setId($unid){
	    	$this->_id = $unid;
	    }	    
	    public function getLibelle(){
	    	return $this->_libelle;
	    }
	    public function setLibelle($libelle){
	    	$this->_libelle = $libelle;
	    }	    
	    public function getNiveau(){
	    	return $this->_niveau;
	    }
	    public function setNiveau($niveau){
	    	$this->_niveau = $niveau;
	    }	    
	    public function getCommentaire(){
	    	return $this->_commentaire;
	    }
	    public function setCommentaire($commentaire){
	    	$this->_commentaire = $commentaire;
	    }	    
	    public function getCouleurFond(){
	    	return $this->_couleur_fond;
	    }
	    public function setCouleurFond($couleurFond){
	    	$this->_couleur_fond = $couleurFond;
	    }	    
	    public function getCouleurTexte(){
	    	return $this->_couleur_texte;
	    }
	    public function setCouleurTexte($couleurTexte){
	    	$this->_couleur_texte = $couleurTexte;
	    }	    
	    public function getGras(){
	    	return $this->_gras;
	    }
	    public function setGras($gras){
	    	$this->_gras = $gras;
	    }	    
	    public function getItalique(){
	    	return $this->_italique;
	    }
	    public function setItalique($italique){
	    	$this->_italique = $italique;
	    }	    
	    public function getNumOrdre(){
	    	return $this->_num_ordre;
	    }
	    public function setNumOrdre($unNumOrdre){
	    	$this->_num_ordre = $unNumOrdre;
	    }
		public function getLibelleLong(){
			return $this->_libelle_long;
		}	
		public function setLibelleLong($lib){
			$this->_libelle_long=$lib;
		} 

		public function getLibelleTableau(){
			return $this->_libelle_tableau_par_niveau;
		}
		
		public function getLibelleDuNiveau($niveau){
			$lib='';
			if (isset($this->_libelle_tableau_par_niveau[$niveau])) {
				$lib = $this->_libelle_tableau_par_niveau[$niveau];
			}
			if ($lib==NULL){
				$lib='';
			}
			return $lib;
		}
		
		public function getLibelleLongSeparateur($separateur){
			$libComplet = '';
			foreach($this->_libelle_tableau_par_niveau as $monlib) {
				if (strlen($monlib) > 0) {
					if ($libComplet != ''){
						$libComplet=$libComplet.$separateur;
					}
					$libComplet=$libComplet.$monlib;
				}
			}
			return $libComplet;
		}
		
		public function setLibelleTableau($tableau) {
			$this->_libelle_tableau_par_niveau=$tableau;
		}
		
		public function isActif(){
			return $this->_actif;
		}
		public function setActif($actif){
			$this->_actif=$actif;
		}
		
		public function getNbCop(){
	    	return $this->_nbCop;
	    }
	    public function setNbCop($unNb){
	    	$this->_nbCop = $unNb;
	    }
   	// fonction utilisee pour trier
	public function compare($a, $b){
		if ($a->getLibelleLong() == $b->getLibelleLong()) 
			return 0;
	    return ($a->getLibelleLong() < $b->getLibelleLong()) ? -1 : 1;
	}


}
 
class Service extends Element // Classe Service héritant de Element.
{
	private $_alias;
	
	public function __construct($parentid,$id, $libelle, $niveau, $commentaire, $couleur_fond, $couleur_texte, $gras, $italique, $num_ordre, $actif) {
			parent::__construct($parentid, $id, $libelle, $niveau, $commentaire, $couleur_fond, $couleur_texte, $gras, $italique, $num_ordre, $actif);	
			require_once 'fonctions_arbre.php';
			$t=ConstruitTableauParents('service', $id);
			parent::setLibelleTableau($t);
			$l=parent::getLibelleLongSeparateur(' - ');
			parent::setLibelleLong($l);
    }
    public function getAlias(){
    	return $this->_alias;
    }
    public function setAlias($alias){
    	$this->_alias = $alias;
    }
    
	/**
	 * avec tous les niveaux parents, séparés par " - ", mais sans "Conseil départemental d'Eure et Loir"
	 */
	public function getLibelleLongSansCG(){
		require_once $_SERVER['DOCUMENT_ROOT'].'/TOOLS/Fonction.php';
		$lg=$this->getLibelleLong();
		$nvx=explode(' - ', $lg);
		$deb=0;
		if ($nvx[0]=="Conseil départemental d'Eure-et-Loir"){
			if (startsWith($nvx[2], 'Direction générale adjointe')) {
				$deb=2;
			} else {
				$deb = 1;
			}
		}
		if ($nvx[0]=='Structures satellites et partenaires') {
			$deb = 1;
		}
		$lg='';
		for ($i=$deb;$i<sizeof($nvx);$i++) {
			if (strlen($lg)>0) {
				$lg=$lg.' - ';
			}
			$lg=$lg.$nvx[$i];
		}
		return $lg;
	}    
}
 
class Site extends Element // Classe Site héritant de Element.
{
	private $_adr1;
	private $_adr2;
	private $_cp;
	private $_ville;
	private $_latitude;
	private $_longitude;
	private $_pool_id;

	public function __construct($parentid,$id, $libelle, $niveau, $commentaire, $couleur_fond, $couleur_texte, $gras, $italique, $num_ordre, $actif, $adr1, $adr2, $cp, $ville, $latitude, $longitude, $pool_id) {
			parent::__construct($parentid, $id, $libelle, $niveau, $commentaire, $couleur_fond, $couleur_texte, $gras, $italique, $num_ordre, $actif);	
			$this->_adr1 = $adr1;
			$this->_adr2 = $adr2;
			$this->_cp = $cp;
			$this->_ville = $ville;
			$this->_latitude = $latitude;
			$this->_longitude = $longitude;
			$this->_pool_id = $pool_id;
			require_once 'fonctions_arbre.php';
			$t=ConstruitTableauParents('site', $id);
			parent::setLibelleTableau($t);
			$l=parent::getLibelleLongSeparateur(' - ');
			parent::setLibelleLong($l);		
	    }
	    /**
	     * 
	     * @param string $separateur élément à insérer entre les éléments de l'adresse (<br> \n ' ' ...) 
	     * @return string
	     */
 		public function getAdresse($separateur){
 			$adresse=$this->_adr1;
 			if ($this->_adr2 != null && $this->_adr2 !=''){
 			 	if (strlen($adresse) > 0) {
 					$adresse = $adresse.$separateur;
 				}
 				$adresse = $adresse.$this->_adr2;
 			}
 			if ($this->_cp != null && $this->_cp !=''){
 				if (strlen($adresse) > 0) {
 					$adresse = $adresse.$separateur;
 				}
 				$adresse = $adresse.$this->_cp;
 			}
 			if ($this->_ville != null && $this->_ville !=''){
 				$adresse = $adresse.' '.$this->_ville;
 			}
	    	return $adresse;
	    }
	    // pour éviter les CS de adr 2 dans la géoloc
 		public function getAdresseSimple(){
 			$adresse=$this->_adr1;
 			if ($this->_cp != null && $this->_cp !=''){
 				$adresse = $adresse.' '.$this->_cp;
 			}
 			if ($this->_ville != null && $this->_ville !=''){
 				$adresse = $adresse.' '.$this->_ville;
 			}
	    	return $adresse;
	    }
	    
	    public function getAdr1(){
	    	return $this->_adr1;
	    }
	    public function setAdr1($adr1){
	    	$this->_adr1 = $adr1;
	    }
	
	    public function getAdr2(){
	    	return $this->_adr2;
	    }
	    public function setAdr2($adr2){
	    	$this->_adr2 = $adr2;
	    }

	    public function getCp(){
	    	return $this->_cp;
	    }
	    public function setCp($cp){
	    	$this->_cp = $cp;
	    }
	    
	    public function getVille(){
	    	return $this->_ville;
	    }
	    public function setVille($ville){
	    	$this->_ville = $ville;
	    }
	    public function getLongitude() { 
			return  $this->_longitude;
		}
		public function setLongitude($lng) {
			$this->_longitude=$lng;
		}
		public function getLatitude() { 
			return $this->_latitude; 
		}
		public function setLatitude($lat) {
			$this->_latitude=$lat;
		}
		public function getPoolId() { 
			return $this->_pool_id; 
		}
		public function setPoolId($id) {
			$this->_pool_id=$id;
		}
		public function getGeoloc() { 
			return $this->_latitude.','.$this->_longitude; 
		}
}
?>