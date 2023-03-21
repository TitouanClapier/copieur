<?php

	class site_DAO {
		private $con; //variable de connexion	
	
		public static function LoadOne($id){
			$db = Connexion_PDO_ORGANIGRAMME::getInstance();
			$con = $db->getDbh();
		
		
			$sql="SELECT * FROM site WHERE id='$id'";
			$res = $con->query($sql);

			$ligne = $res->fetch(PDO::FETCH_OBJ);

			if($ligne){
				$site = new Site($ligne->parent_id, 
								$ligne->id, 
								$ligne->libelle, 
								$ligne->niveau, 
								$ligne->commentaire, 
								$ligne->couleur_fond, 
								$ligne->couleur_texte, 
								$ligne->gras,
								$ligne->italique, 
								$ligne->num_ordre, 
								$ligne->actif,
								/*$ligne->visu,*/
								$ligne->adr1, 
								$ligne->adr2, 
								$ligne->cp, 
								$ligne->ville, 
								$ligne->latitude,
								$ligne->longitude,
								$ligne->pool_id);
				return $site;
			}else{
				return null;
			}
		}
		public static function LoadSites($where){
			$db = Connexion_PDO_ORGANIGRAMME::getInstance();
			$con = $db->getDbh();
			$sql="SELECT * FROM site ".$where;			
			$res = $con->query($sql);
			$sites=array();
			while ($ligne = $res->fetch(PDO::FETCH_OBJ)){
				$site = new Site($ligne->parent_id, 
								$ligne->id, 
								$ligne->libelle, 
								$ligne->niveau, 
								$ligne->commentaire, 
								$ligne->couleur_fond, 
								$ligne->couleur_texte, 
								$ligne->gras,
								$ligne->italique, 
								$ligne->num_ordre, 
								$ligne->actif,
								/*$ligne->visu,*/
								$ligne->adr1, 
								$ligne->adr2, 
								$ligne->cp, 
								$ligne->ville, 
								$ligne->latitude,
								$ligne->longitude,
								$ligne->pool_id);
				$sites[]=$site;
			}
			return $sites;
		}
		public static function GetLatitude($id){
			$db = Connexion_PDO_ORGANIGRAMME::getInstance();
			$con = $db->getDbh();
			$sql="SELECT * FROM site WHERE id='$id'";
			$res = $con->query($sql);
			$ligne = $res->fetch(PDO::FETCH_OBJ);
			$latitude='';
			while($ligne && $latitude==''){
				if (strlen($ligne->latitude)>0) {
					$latitude = $ligne->latitude;
				} else {
					if ($ligne->parent_id > 0) {
						$latitude = site_DAO::GetLatitude($ligne->parent_id);
					}
				}
			}
			return $latitude;
		}
		public static function GetLongitude($id){
			$db = Connexion_PDO_ORGANIGRAMME::getInstance();
			$con = $db->getDbh();
			$sql="SELECT * FROM site WHERE id='$id'";
			$res = $con->query($sql);
			$ligne = $res->fetch(PDO::FETCH_OBJ);
			$latitude='';
			while($ligne && $longitude==''){
				if (strlen($ligne->longitude)>0) {
					$latitude = $ligne->longitude;
				} else {
					if ($ligne->parent_id > 0) {
						$longitude = site_DAO::GetLongitude($ligne->parent_id);
					}
				}
			}
			return $longitude;
		}
		public static function GetNumAppel($id){
			$numappel="";
			$db = Connexion_PDO_ORGANIGRAMME::getInstance();
			$con = $db->getDbh();
			$sql="SELECT * FROM site WHERE id='$id'";
			$res = $con->query($sql);
			$site = $res->fetch(PDO::FETCH_OBJ);
			if ($site) {
				$sql_ligne="SELECT * FROM ligne WHERE site_id='$id'";
				$res_ligne = $con->query($sql_ligne);
				$ligne = $res_ligne->fetch(PDO::FETCH_OBJ);
				if ($ligne) {
					$numappel=$ligne->LIG_NUM_APPEL;
				} elseif ($site->parent_id > 0) {
					$numappel = site_DAO::GetNumAppel($site->parent_id);
				} else {
					$numappel="";
				}
			}
			return $numappel;
		}	
		public static function GetNumAppelAbrege($id){
			$numappel="";
			$db = Connexion_PDO_ORGANIGRAMME::getInstance();
			$con = $db->getDbh();
			$sql="SELECT * FROM site WHERE id='$id'";
			$res = $con->query($sql);
			$site = $res->fetch(PDO::FETCH_OBJ);
			if ($site) {
				$sql_ligne="SELECT * FROM ligne WHERE site_id='$id'";
				$res_ligne = $con->query($sql_ligne);
				$ligne = $res_ligne->fetch(PDO::FETCH_OBJ);
				if ($ligne) {
					$numappel = $ligne->LIG_NUM_APPEL_COURT;
				} elseif ($site->parent_id > 0) {
					$numappel = site_DAO::GetNumAppelAbrege($site->parent_id);
				} else {
					$numappel="";
				}
			}
			return $numappel;
		}
		public static function GetNumPool($id){
			$numPool = -1;
			$db = Connexion_PDO_ORGANIGRAMME::getInstance();
			$con = $db->getDbh();
			$sql="SELECT * FROM site WHERE id='$id'";
			$res = $con->query($sql);
			$site = $res->fetch(PDO::FETCH_OBJ);
			if ($site) {
				$numPool=$site->pool_id;
				//echo 'table:'.$numPool;
				if ($numPool == NULL) {
					$numPool=site_DAO::GetNumPool($site->parent_id);
				}
			}
			return $numPool;
		}
		
	}
	
	class service_DAO {
		private $con; //variable de connexion
		
		public static function LoadOne($id){
		
			$db = Connexion_PDO_ORGANIGRAMME::getInstance();
			$con = $db->getDbh();
			
			$sql="SELECT * FROM service WHERE id='$id'";
			$res = $con->query($sql);

			$ligne = $res->fetch(PDO::FETCH_OBJ);

			if($ligne){
				$service = new Service($ligne->parent_id, 
								$ligne->id, 
								$ligne->libelle, 
								$ligne->niveau, 
								$ligne->commentaire, 
								$ligne->couleur_fond, 
								$ligne->couleur_texte, 
								$ligne->gras,
								$ligne->italique, 
								$ligne->num_ordre, 
								$ligne->actif/*,
								$ligne->visu*/);
				$service->setAlias($ligne->alias);
				return $service;
			}else{
				return null;
			}
		}
		public static function LoadServices($where){
			$db = Connexion_PDO_ORGANIGRAMME::getInstance();
			$con = $db->getDbh();
			$sql="SELECT * FROM service ".$where;
			$res = $con->query($sql);
			$services=array();
			while ($ligne = $res->fetch(PDO::FETCH_OBJ)){
				$service = new Service($ligne->parent_id,
						$ligne->id,
						$ligne->libelle,
						$ligne->niveau,
						$ligne->commentaire,
						$ligne->couleur_fond,
						$ligne->couleur_texte,
						$ligne->gras,
						$ligne->italique,
						$ligne->num_ordre,
						$ligne->actif/*,
						/*$ligne->visu,
						$ligne->adr1,
						$ligne->adr2,
						$ligne->cp,
						$ligne->ville,
						$ligne->latitude,
						$ligne->longitude*/
						);
				$service->setAlias($ligne->alias);
				//echo $ligne->alias;
				$services[]=$service;
			}
			return $services;
		}
		
		/**
		 * Renvoie le nombre d'enfants directs d'un noeud
		 * @param unknown $id
		 * @return number
		 */
		public static function GetNbEnfants($id){
			$db = Connexion_PDO_ORGANIGRAMME::getInstance();
			$con = $db->getDbh();
				
			$sql="SELECT count(*) as nb FROM service WHERE parent_id='$id'";
			$res = $con->query($sql);
		
			$ligne = $res->fetch(PDO::FETCH_OBJ);
			$nb=0;
			if($ligne) {
				$nb = $ligne->nb;
			}
			return $nb;
		}
		
	}
	
?>