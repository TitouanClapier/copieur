<?php
/* Cette API (interface de programmation) fournit toutes les fonctions liées à l'annuaire 
 * qui peuvent être utilisées par les autres applications.
 * Les autres applications ne devraient donc connaitre et utiliser QUE ce fichier api.php de l'annuaire. 
 * Les autres applications ne devraient donc pas utiliser Connexion_PDO_ORGANIGRAMME.php
 * 
 * pour tester ces fonctions : http://srvphptest/test/testApiAnnuaire.php
 *  */


/**
 * Fonction qui renvoie la liste de tous les services ou sites, sous forme de tableau dont l'indice est l'id
 * 
 * Paramètres :
 * 1. $table = nom de la table (site/service)
 * 2. $separateur = chaine de caractère à insérer entre les différents niveaux ('<br>', ' - ', '  ' par exemple) 
 */
function API_TableauDeLibellesComplets($table, $separateur) {
	require_once 'fonctions_arbre.php';
	return ConstruitTableauDeLibellesComplets($table, $separateur);
}


/** fonction qui construit le libellé d'un objet avec tous ses niveaux parents 
 * 
 * Paramètres :
 * 1. $tabl = nom de la table (site/service)
 * 2. $objetId = integer identifiant de l'objet
 * 3. $separateur = chaine de caractère à insérer entre les différents niveaux ('<br>', ' - ', '  ' par exemple) 
 * */
function API_LibelleComplet($table, $objetId, $separateur) {
	require_once 'fonctions_arbre.php';
	return ConstruitLibelleComplet($table, $objetId, $separateur);
}

/** fonction qui construit le libellé d'un objet avec tous ses niveaux parents sauf les premiers niveaux
 *
 * Paramètres :
 * 1. $tabl = nom de la table (site/service)
 * 2. $objetId = integer identifiant de l'objet
 * 3. $separateur = chaine de caractère à insérer entre les différents niveaux ('<br>', ' - ', '  ' par exemple)
 * */
function API_LibelleCompletSansPremiersNiveaux($table, $objetId, $separateur) {
	require_once 'fonctions_arbre.php';
	return ConstruitLibelleCompletSansPremierNiveau($table, $objetId, $separateur);
}

/** fonction qui recherche le libellé d'un objet, simplement, sans les niveaux parents
 * Paramètres :
 * 1. $tabl = nom de la table (site/service)
 * 2. $objetId = integer identifiant de l'objet
 * */
function API_LibelleSimple($table, $objetId) {
	require_once 'fonctions_arbre.php';
	return ConstruitLibelleSimple($table, $objetId);
}

/**
 * Fonction qui renvoie les libellés de service, direction et DGA
 * @param int $serviceId
 * @return array tableau des libellés dont les clés sont 'service', direction', 'DGA'
 */
function API_ServiceDirectionEtDgaDunService($serviceId) {
	require_once 'fonctions_arbre.php';
	return ServiceDirectionEtDgaDunService($serviceId);
}

/**
 * fonction qui renvoie un agent sous forme de tableau
 * les indexes du tableau sont les colonnes de la table agent (AG_MAIL, AG_ALIAS, AG_PRENOM...) 
 * @param $login le login de l'agent
 */
function API_AgentByLogin($login) {
	require_once 'fonctions_arbre.php';
	return getAgentByLogin($login);
}

/**
 * fonction qui renvoie un agent sous forme de tableau
 * les indexes du tableau sont les colonnes de la table agent (AG_MAIL, AG_ALIAS, AG_PRENOM...)
 * @param $agentId l'ID de l'agent
 */
function API_AgentById($agentId) {
	require_once 'fonctions_arbre.php';
	return getAgentById($agentId);
}

/**
 * fonction qui renvoie un agent sous forme de tableau
 * les indexes du tableau sont les colonnes de la table agent (AG_MAIL, AG_ALIAS, AG_PRENOM...)
 * @param $agentMatAD le matricule AD de l'agent
 */
function API_AgentByMatAD($agentMatAD) {
	require_once 'fonctions_arbre.php';
	return getAgentByMatAD($agentMatAD);
}

/**
 * fonction qui renvoie la liste des agents actifs sous forme d'un tableau de tableau
 * l'indice du premier tableau est l'ID de l'agent
 * dans les sous tableaux, les indices sont : AG_MAT_DRH, AG_ID, AG_ETS_COD, AG_NOM, AG_PRENOM, AG_ALIAS
 */
function API_ListeDesAgentsActifs() {
	require_once 'fonctions_arbre.php';
	return getListeDesAgentsActifs();
}

/**
 * fonction qui renvoie la liste de tous les agents sous forme d'un tableau de tableau
 * l'indice du premier tableau est l'ID de l'agent
 * dans les sous tableaux, les indices sont les colonnes de la table AGENT : AG_MAT_DRH, AG_ID, AG_ETS_COD, AG_NOM, AG_PRENOM, AG_ALIAS
 */
function API_ListeDesAgents() {
	require_once 'fonctions_arbre.php';
	return getListeDesAgents();
}

/** fonction qui recherche le libellé du service d'un agent à un niveau n 
 * @param unknown $agentId ID de l'agent
 * @param unknown $niveau niveau 1 = CD28, Niveau 2 = DGS ...
 */
function API_ServiceDunAgent($agentId, $niveau){
	require_once 'fonctions_arbre.php';
	return GetServiceDunAgent($agentId, $niveau);
}

/**
 * fonction qui recherche le libellé de la DGA d'un agent
 * @param unknown $agentId ID de l'agent
 * @return string
 */
function API_DGADunAgent($agentId){
	require_once 'fonctions_arbre.php';
	$premierNiveau=mb_strtoupper(GetServiceDunAgent($agentId,1), 'UTF-8');
	if (substr($premierNiveau, 0, 7) == "CONSEIL") {
		$dga=strtoupper(GetServiceDunAgent($agentId,3)); 
		if ($dga=='') {
			$dga=strtoupper(GetServiceDunAgent($agentId,2));
		} 
	} else {
		// satellites
		$dga='';
	}
	return $dga;
}

/**
 * fonction qui recherche le libellé de la direction d'un agent
 * @param unknown $agentId ID de l'agent
 * @return string
 */
function API_DirectionDunAgent($agentId){
	$premierNiveau=strtoupper(GetServiceDunAgent($agentId,1));
	if (substr($premierNiveau, 0, 7) == "CONSEIL") {
		$direction=GetServiceDunAgent($agentId,4); //echo '$direction : '.$direction;
	} else {
		// satellites
		$direction=GetServiceDunAgent($agentId,2);
	}
	return $direction;
}

/**
 * fonction qui recherche le libellé du service d'un agent
 * @param unknown $agentId ID de l'agent
 * @return string
 */
function API_ServDunAgent($agentId){
	$premierNiveau=strtoupper(GetServiceDunAgent($agentId,1));
	if (substr($premierNiveau, 0, 7) == "CONSEIL") {
		$service=GetServiceDunAgent($agentId,5); //echo '$service : '.$service.'<br>';
	} else {
		$service=GetServiceDunAgent($agentId,3);
	}
	return $service;	
}

/**
 * Fonction qui renvoie l'adresse d'un site 
 * @param $siteId identifiant du site
 */
function API_Adresse($siteId) {
	require_once 'fonctions_arbre.php';
	return getAdresse($siteId);
}

/**
 * Fonction qui renvoie la liste des lignes d'un agent
 * @param $agentId identifiant de l'agent
 */
function API_LignesByAgentId($agentId) {
	require_once 'fonctions_arbre.php';
	return getLignesByAgentId($agentId);
}

/**
 * Fonction qui renvoie le téléphone d'un agent
 * En principe un Fixe, sinon son mobile, sinon une chaine vide
 * @param $agentId identifiant de l'agent
 */
function API_TelephoneByAgentId($agentId) {
	require_once 'fonctions_arbre.php';
	return getTelephoneByAgentId($agentId);
}

/**
 * Fonction qui renvoie le libellé du poste d'un agent
 * @param $agentId identifiant de l'agent
 */
function API_PosteDunAgent($agentId) {
	require_once 'fonctions_arbre.php';
	return getPosteDunAgent($agentId);
}

/**
 * Fonction qui renvoie le libellé de fichier de fiche poste d'un agent
 * @param $agentId identifiant de l'agent
 */
function API_FicheDePosteDunAgent($agentId) {
    require_once 'fonctions_arbre.php';
    return getFicheDePosteDunAgent($agentId);
}
/**
 * Fonction qui renvoie le libellé du métier d'un agent
 * @param $agentId identifiant de l'agent
 */
function API_MetierDunAgent($agentId) {
	require_once 'fonctions_arbre.php';
	return getMetierDunAgent($agentId);
}

/**
 * Fonction qui renvoie le groupe de fonction d'un agent (table metier)
 * @param $agentId identifiant de l'agent
 */
function API_GroupeDeFonctionDunAgent( $agentId) {
	require_once 'fonctions_arbre.php';
	return getGroupeDeFonctionDunAgent($agentId);
}

/**
 * Fonction qui renvoie le libellé du statut d'un agent
 * @param $agentId identifiant de l'agent
 */
function API_StatutDunAgent($agentId) {
	require_once 'fonctions_arbre.php';
	return getStatutDunAgent($agentId);
}
/**
 * Fonction qui renvoie le libellé du métier d'un agent
 * @param $collectivite string collectivité de l'agent
 * @param $matricule int matricule de l'agent
 */
function API_MetierDunAgentParMatricule($collectivite, $matricule) {
	require_once 'fonctions_arbre.php';
	return getMetierDunAgentParMatricule($collectivite, $matricule);
}
/**
 * Fonction qui renvoie le RIFSEEP d'un agent
 * @param $collectivite string collectivité de l'agent
 * @param $matricule int matricule de l'agent
 */
function API_RifseepDunAgentParMatricule($collectivite, $matricule) {
	require_once 'fonctions_arbre.php';
	return getRifseepDunAgentParMatricule($collectivite, $matricule);
}

/**
 * Fonction qui renvoie le numéro de téléphone d'un site
 * @param $siteId identifiant du site
 */
function API_TelephoneDunSite($siteId) {
	require_once 'fonctions_arbre.php';
	$numappel=site_DAO::GetNumAppel($siteId);
	return $numappel;
}

/**
 * Fonction qui renvoie le numéro du pool correspondant auvéhicule du site
 * @param $siteId identifiant du site
 */
function API_PoolDunSite($siteId) {
	require_once 'fonctions_arbre.php';
	$poolId=site_DAO::GetNumPool($siteId);
	return $poolId;
}
?>