<?php
//Connection LDAP
function AD_connectAdmin() {
	global $AD_dn;
	global $AD_ds;
	global $AD_identification;
	$serv = "10.1.251.1"; // serveur AD
	$rootdn = "devacces@cg28.local";
    $rootpw= '';
	$AD_dn = "DC=cg28,DC=local";
	//Connection AD
	$AD_ds = ldap_connect($serv, 389) or die("La connection a échoué !");
	//	protocole version et bind
	ldap_set_option($AD_ds, LDAP_OPT_PROTOCOL_VERSION, 3) or die ("Protocole Ldap V1 inapplicable");
	ldap_set_option($AD_ds, LDAP_OPT_REFERRALS, 0) or die ("Protocole Ldap V2 inapplicable");
	$AD_identification = @ldap_bind($AD_ds, $rootdn, $rootpw);
}
function AD_connect($login, $password) {
	global $AD_dn;
	global $AD_ds;
	global $AD_identification;
	$serv = "10.1.251.1"; // serveur AD
	$AD_dn = "DC=cg28,DC=local";
	//Connection AD
	$AD_ds = ldap_connect($serv, 389) or die("La connection a échoué !");
	//	protocole version et bind
	ldap_set_option($AD_ds, LDAP_OPT_PROTOCOL_VERSION, 3) or die ("Protocole Ldap V1 inapplicable");
	ldap_set_option($AD_ds, LDAP_OPT_REFERRALS, 0) or die ("Protocole Ldap V2 inapplicable");
	//var_dump($_POST);
	$rootdn = $login."@cg28.local";
	$rootpw = utf8_encode($password);
	$AD_identification = ldap_bind($AD_ds, $rootdn, $rootpw);
}
function AD_getSignatureMailByMat($MAT_AD) {
	global $AD_dn;
	global $AD_ds;
	global $AD_identification;
	include_once 'include.php';
	$db = Connexion_PDO_ORGANIGRAMME::getInstance();
	$con = $db->getDbh();
	$justthese = array("mail", "sAMAccountName", "extensionattribute1"); // liste des éléments recherchés dans le l'ActiveDirectory
	$sr = ldap_search($AD_ds, $AD_dn, "title=".$MAT_AD, $justthese);
	$info = ldap_get_entries($AD_ds, $sr);
	//var_dump($info[0]);
	// si on ne trouve pas avec le $MAT_AD, et que le nombre de chiffres du matricule est inférieur à 4
	// on effectue une seconde recherche en complétant avec des zéros (ex : 104 --> 0104)
	if($info["count"]==0 && strlen($MAT_AD)<4) {
		$zero="";
		$longueur=strlen($MAT_AD);
		while ($longueur < 4) {
			$zero=$zero."0";
			$longueur++;
		}
		$MAT_AD=$zero.$MAT_AD;
		$sr = ldap_search($AD_ds, $AD_dn, "title=".$MAT_AD, $justthese);
		$info = ldap_get_entries($AD_ds, $sr);
		//var_dump($info[0]);
	}
	//on récupère les infos
	$FCT='';
	if ($info["count"] > 0) {
		$FCT=utf8_decode($info[0]["extensionattribute1"][0]);
	}
	return $FCT;
}
/**
 * renvoie un tableau d'éléments par matricule
 * @param String $MAT_AD
 * @return array (keys : mail, sAMAccountName, extensionAttribute1)
 */
function AD_getElementsByMat($MAT_AD) {
	global $AD_dn;
	global $AD_ds;
	global $AD_identification;
	$justthese = array("mail", "sAMAccountName", "company", "department", "description", "extensionattribute1", "extensionattribute2", "extensionattribute3", "extensionattribute4", "extensionattribute5", "extensionattribute6", "extensionattribute7", "facsimiletelephonenumber", "l", "mobile", "physicaldeliveryofficeName", "postalcode", "streetaddress", "telephonenumber", "givenname" ,"sn"); // liste des éléments recherchés dans le l'ActiveDirectory
	$sr = ldap_search($AD_ds, $AD_dn, "title=".$MAT_AD, $justthese);
	$info = ldap_get_entries($AD_ds, $sr);
	//var_dump($info[0]);
	// si on ne trouve pas avec le $MAT_AD, et que le nombre de chiffres du matricule est inférieur à 4
	// on effectue une seconde recherche en complétant avec des zéros (ex : 104 --> 0104)
	if($info["count"]==0 && strlen($MAT_AD)<4) {
		$zero="";
		$longueur=strlen($MAT_AD);
		while ($longueur < 4) {
			$zero=$zero."0";
			$longueur++;
		}
		$MAT_AD=$zero.$MAT_AD;
		$sr = ldap_search($AD_ds, $AD_dn, "title=".$MAT_AD, $justthese);
		$info = ldap_get_entries($AD_ds, $sr);
		//var_dump($info[0]);
	}
	//on récupère les infos
	$retour=array();
	if ($info["count"] > 0) {
		//var_dump($info[0]);
		$retour['company']=utf8_decode($info[0]["company"][0]);
		$retour['department']=utf8_decode($info[0]["department"][0]);
		$retour['description']=utf8_decode($info[0]["description"][0]);
		$retour['extensionAttribute1']=utf8_decode($info[0]["extensionattribute1"][0]);
		$retour['extensionAttribute2']=utf8_decode($info[0]["extensionattribute2"][0]);
		$retour['extensionAttribute3']=utf8_decode($info[0]["extensionattribute3"][0]);
		$retour['extensionAttribute4']=utf8_decode($info[0]["extensionattribute4"][0]);
		$retour['extensionAttribute5']=utf8_decode($info[0]["extensionattribute5"][0]);
		$retour['extensionAttribute6']=utf8_decode($info[0]["extensionattribute6"][0]);
		$retour['extensionAttribute7']=utf8_decode($info[0]["extensionattribute7"][0]);
		$retour['facsimileTelephoneNumber']=utf8_decode($info[0]["facsimiletelephonenumber"][0]);
		$retour['givenName']=utf8_decode($info[0]["givenname"][0]);
		$retour['l']=utf8_decode($info[0]["l"][0]);
		$retour['mail']=$info[0]["mail"][0];
		$retour['mobile']=utf8_decode($info[0]["mobile"][0]);
		$retour['physicalDeliveryOfficeName']=utf8_decode($info[0]["physicaldeliveryofficename"][0]);
		$retour['postalcode']=utf8_decode($info[0]["postalcode"][0]);
		$retour['sAMAccountName']=$info[0]["samaccountname"][0];
		$retour['sn']=utf8_decode($info[0]["sn"][0]);
		$retour['streetAddress']=utf8_decode($info[0]["streetaddress"][0]);
		$retour['telephoneNumber']=utf8_decode($info[0]["telephonenumber"][0]);
		$retour['title']=$MAT_AD;
	}
	return $retour;
}
/**
 * renvoie un tableau d'éléments par login
 * @param String $login
 * @return array (keys : mail, sAMAccountName, extensionAttribute1)
 */
function AD_getElementsByLogin($login) {
	global $AD_dn;
	global $AD_ds;
	global $AD_identification;
	$justthese = array("mail", "sAMAccountName", "company", "department", "description", "extensionattribute1", "extensionattribute2", "extensionattribute3", "extensionattribute4", "extensionattribute5", "extensionattribute6", "extensionattribute7", "facsimiletelephonenumber", "l", "mobile", "physicaldeliveryofficeName", "postalcode", "streetaddress", "telephonenumber", "title", "givenname" ,"sn"); // liste des éléments recherchés dans le l'ActiveDirectory
	$sr = ldap_search($AD_ds, $AD_dn, "sAMAccountName=".$login, $justthese);
	$info = ldap_get_entries($AD_ds, $sr);
	//var_dump($info[0]);
	// si on ne trouve pas avec le $MAT_AD, et que le nombre de chiffres du matricule est inférieur à 4
	// on effectue une seconde recherche en complétant avec des zéros (ex : 104 --> 0104)
	if($info["count"]==0 && strlen($MAT_AD)<4) {
		$zero="";
		$longueur=strlen($MAT_AD);
		while ($longueur < 4) {
			$zero=$zero."0";
			$longueur++;
		}
		$MAT_AD=$zero.$MAT_AD;
		$sr = ldap_search($AD_ds, $AD_dn, "title=".$MAT_AD, $justthese);
		$info = ldap_get_entries($AD_ds, $sr);
		//var_dump($info[0]);
	}
	//on récupère les infos
	$retour=array();
	if ($info["count"] > 0) {
		//var_dump($info[0]);
		$retour['company']=utf8_decode($info[0]["company"][0]);
		$retour['department']=utf8_decode($info[0]["department"][0]);
		$retour['description']=utf8_decode($info[0]["description"][0]);
		$retour['extensionAttribute1']=utf8_decode($info[0]["extensionattribute1"][0]);
		$retour['extensionAttribute2']=utf8_decode($info[0]["extensionattribute2"][0]);
		$retour['extensionAttribute3']=utf8_decode($info[0]["extensionattribute3"][0]);
		$retour['extensionAttribute4']=utf8_decode($info[0]["extensionattribute4"][0]);
		$retour['extensionAttribute5']=utf8_decode($info[0]["extensionattribute5"][0]);
		$retour['extensionAttribute6']=utf8_decode($info[0]["extensionattribute6"][0]);
		$retour['extensionAttribute7']=utf8_decode($info[0]["extensionattribute7"][0]);
		$retour['facsimileTelephoneNumber']=utf8_decode($info[0]["facsimiletelephonenumber"][0]);
		$retour['givenName']=utf8_decode($info[0]["givenname"][0]);
		$retour['l']=utf8_decode($info[0]["l"][0]);
		$retour['mail']=$info[0]["mail"][0];
		$retour['mobile']=utf8_decode($info[0]["mobile"][0]);
		$retour['physicalDeliveryOfficeName']=utf8_decode($info[0]["physicaldeliveryofficename"][0]);
		$retour['postalcode']=utf8_decode($info[0]["postalcode"][0]);
		$retour['sAMAccountName']=$info[0]["samaccountname"][0];
		$retour['sn']=utf8_decode($info[0]["sn"][0]);
		$retour['streetAddress']=utf8_decode($info[0]["streetaddress"][0]);
		$retour['telephoneNumber']=utf8_decode($info[0]["telephonenumber"][0]);
		$retour['title']=$info[0]["title"][0];
	}
	return $retour;
}

/**
 * renvoie un tableau de tableaux d'éléments par matricule, des éléments ayant un matricule
 * Il est beaucoup plus rapide de ramener tous les éléments de l'AD en une seule fois
 * plutôt que de faire une boucle qui fait n recherches dans l'AD
 * @return array of arrays (keys : [matricule] [mail, sAMAccountName, extensionAttribute1...])
 */
function AD_getElementsHavingMat() {
	global $AD_dn;
	global $AD_ds;
	global $AD_identification;
	$justthese = array("title", "mail", "sAMAccountName", "company", "department", "description", "extensionattribute1", "extensionattribute2", "extensionattribute3", "extensionattribute4", "extensionattribute5", "extensionattribute6", "extensionattribute7", "facsimiletelephonenumber", "l", "mobile", "physicaldeliveryofficeName", "postalcode", "streetaddress", "telephonenumber", "givenname" ,"sn"); // liste des éléments recherchés dans le l'ActiveDirectory
	$sr = ldap_search($AD_ds, $AD_dn, "title=*", $justthese);
	$info = ldap_get_entries($AD_ds, $sr);
	//var_dump($info);
	//on récupère les infos
 	$complet=array();
	for ($i=0; $i < $info["count"]; $i++) {
    	$element=array();

    	$matricule=$info[$i]["title"][0];
//     	if(strlen($matricule)<4) {
//     		$zero="";
//     		$longueur=strlen($matricule);
//     		while ($longueur < 4) {
//     			$zero=$zero."0";
//     			$longueur++;
//     		}
//     		$matricule=$zero.$matricule;
//     	}

    	$element['title']=$matricule;
    	$element['mail']='';
    	if (isset($info[$i]["mail"])) {
    	   $element['mail']=$info[$i]["mail"][0];
    	}
    	$element['sAMAccountName']=$info[$i]["samaccountname"][0];
    	$element['company']='';
    	if (isset($info[$i]["company"])) {
		  $element['company']=utf8_decode($info[$i]["company"][0]);
    	}
    	$element['department']='';
    	if (isset($info[$i]["department"])) {
		  $element['department']=utf8_decode($info[$i]["department"][0]);
    	}
    	$element['description']='';
		if (isset($info[$i]["description"])) {
		  $element['description']=utf8_decode($info[$i]["description"][0]);
		}
		$element['extensionAttribute1']='';
		if (isset($info[$i]["extensionattribute1"])) {
		  $element['extensionAttribute1']=utf8_decode($info[$i]["extensionattribute1"][0]);
		}
		$element['extensionAttribute2']='';
		if (isset($info[$i]["extensionattribute2"])) {
		  $element['extensionAttribute2']=utf8_decode($info[$i]["extensionattribute2"][0]);;
		}
		$element['extensionAttribute3']='';
		if (isset($info[$i]["extensionattribute3"])) {
		  $element['extensionAttribute3']=utf8_decode($info[$i]["extensionattribute3"][0]);;
		}
		$element['extensionAttribute4']='';
		if (isset($info[$i]["extensionattribute4"])) {
		  $element['extensionAttribute4']=utf8_decode($info[$i]["extensionattribute4"][0]);;
		}
		$element['extensionAttribute5']='';
		if (isset($info[$i]["extensionattribute5"])) {
    	   $element['extensionAttribute5']=utf8_decode($info[$i]["extensionattribute5"][0]);;
		}
		$element['extensionAttribute6']='';
		if (isset($info[$i]["extensionattribute6"])) {
    	   $element['extensionAttribute6']=utf8_decode($info[$i]["extensionattribute6"][0]);;
		}
		$element['extensionAttribute7']='';
    	if (isset($info[$i]["extensionattribute7"])) {
    	   $element['extensionAttribute7']=utf8_decode($info[$i]["extensionattribute7"][0]);;
    	}
    	$element['facsimileTelephoneNumber']='';
    	if (isset($info[$i]["facsimiletelephonenumber"])) {
    	   $element['facsimileTelephoneNumber']=utf8_decode($info[$i]["facsimiletelephonenumber"][0]);;
    	}
    	$element['l']='';
    	if (isset($info[$i]["l"])) {
    	   $element['l']=utf8_decode($info[$i]["l"][0]);;
    	}
    	$element['mobile']='';
    	if (isset($info[$i]["mobile"])) {
    	   $element['mobile']=utf8_decode($info[$i]["mobile"][0]);;
    	}
    	$element['physicalDeliveryOfficeName']='';
    	if (isset($info[$i]["physicaldeliveryofficename"])) {
    	   $element['physicalDeliveryOfficeName']=utf8_decode($info[$i]["physicaldeliveryofficename"][0]);;
    	}
    	$element['postalcode']='';
    	if (isset($info[$i]["postalcode"])) {
    	   $element['postalcode']=utf8_decode($info[$i]["postalcode"][0]);;
    	}
    	$element['streetAddress']='';
    	if (isset($info[$i]["streetaddress"])) {
    	   $element['streetAddress']=utf8_decode($info[$i]["streetaddress"][0]);;
    	}
    	$element['telephoneNumber']='';
    	if (isset($info[$i]["telephonenumber"])) {
    	   $element['telephoneNumber']=utf8_decode($info[$i]["telephonenumber"][0]);;
    	}
    	$element['givenName']='';
    	if (isset($info[$i]["givenname"])) {
    	   $element['givenName']=utf8_decode($info[$i]["givenname"][0]);;
    	}
    	$element['sn']='';
    	if (isset($info[$i]["sn"])) {
    	   $element['sn']=utf8_decode($info[$i]["sn"][0]);;
    	}

    	$complet[$matricule]=$element;
	}
 	return $complet;
}
function AD_getDoublonsMatricule() {
	global $AD_dn;
	global $AD_ds;
	global $AD_identification;
	$justthese = array("title", "mail", "sAMAccountName", "company", "department", "description", "extensionattribute1", "extensionattribute2", "extensionattribute3", "extensionattribute4", "extensionattribute5", "extensionattribute6", "extensionattribute7", "facsimiletelephonenumber", "l", "mobile", "physicaldeliveryofficeName", "postalcode", "streetaddress", "telephonenumber", "givenname" ,"sn"); // liste des éléments recherchés dans le l'ActiveDirectory
	$sr = ldap_search($AD_ds, $AD_dn, "title=*", $justthese);
	$info = ldap_get_entries($AD_ds, $sr);
	//var_dump($info);
	//on récupère les infos
	$complet=array();
	$doublons=array();
	for ($i=0; $i < $info["count"]; $i++) {
		$element=array();
		$matricule=$info[$i]["title"][0];
		if(strlen($matricule)<4) {
			$zero="";
			$longueur=strlen($matricule);
			while ($longueur < 4) {
				$zero=$zero."0";
				$longueur++;
			}
			$matricule=$zero.$matricule;
		}
		$element['title']=$matricule;
		$element['mail']=$info[$i]["mail"][0];
		$element['sAMAccountName']=$info[$i]["samaccountname"][0];
		$element['company']=utf8_decode($info[$i]["company"][0]);
		$element['department']=utf8_decode($info[$i]["department"][0]);
		$element['description']=utf8_decode($info[$i]["description"][0]);
		$element['extensionAttribute1']=utf8_decode($info[$i]["extensionattribute1"][0]);;
		$element['extensionAttribute2']=utf8_decode($info[$i]["extensionattribute2"][0]);;
		$element['extensionAttribute3']=utf8_decode($info[$i]["extensionattribute3"][0]);;
		$element['extensionAttribute4']=utf8_decode($info[$i]["extensionattribute4"][0]);;
		$element['extensionAttribute5']=utf8_decode($info[$i]["extensionattribute5"][0]);;
		$element['extensionAttribute6']=utf8_decode($info[$i]["extensionattribute6"][0]);;
		$element['extensionAttribute7']=utf8_decode($info[$i]["extensionattribute7"][0]);;
		$element['facsimileTelephoneNumber']=utf8_decode($info[$i]["facsimiletelephonenumber"][0]);;
		$element['l']=utf8_decode($info[$i]["l"][0]);;
		$element['mobile']=utf8_decode($info[$i]["mobile"][0]);;
		$element['physicalDeliveryOfficeName']=utf8_decode($info[$i]["physicaldeliveryofficename"][0]);;
		$element['postalcode']=utf8_decode($info[$i]["postalcode"][0]);;
		$element['streetAddress']=utf8_decode($info[$i]["streetaddress"][0]);;
		$element['telephoneNumber']=utf8_decode($info[$i]["telephonenumber"][0]);;
		$element['givenName']=utf8_decode($info[$i]["givenname"][0]);;
		$element['sn']=utf8_decode($info[$i]["sn"][0]);;
		$matricule=$info[$i]["title"][0];

		if (!array_key_exists($matricule,$complet)) {
			$complet[$matricule]=$element;
		} else {
			$doublons[]=$complet[$matricule];
			$doublons[]=$element;
		}
	}
	return $doublons;
}
function AD_getComptesAD() {
	global $AD_dn;
	global $AD_ds;
	global $AD_identification;
	$justthese = array("title", "mail", "sAMAccountName", "company", "department", "description", "extensionattribute1", "extensionattribute2", "extensionattribute3", "extensionattribute4", "extensionattribute5", "extensionattribute6", "extensionattribute7", "facsimiletelephonenumber", "l", "mobile", "physicaldeliveryofficeName", "postalcode", "streetaddress", "telephonenumber", "givenname" ,"sn"); // liste des éléments recherchés dans le l'ActiveDirectory
	$sr = ldap_search($AD_ds, "OU=Conseil General 28, DC=cg28,DC=local", "sAMAccountName=*",$justthese);
	$info = ldap_get_entries($AD_ds, $sr);
	//var_dump($info);
	//on récupère les infos
	$complet=array();
	for ($i=0; $i < $info["count"]; $i++) {
		$element=array();
		$matricule=$info[$i]["title"][0];
		if(strlen($matricule)<4) {
			$zero="";
			$longueur=strlen($matricule);
			while ($longueur < 4) {
				$zero=$zero."0";
				$longueur++;
			}
			$matricule=$zero.$matricule;
		}
		$element['title']=$matricule;
		$element['mail']=$info[$i]["mail"][0];
		$element['sAMAccountName']=$info[$i]["samaccountname"][0];
		$element['company']=utf8_decode($info[$i]["company"][0]);
		$element['department']=utf8_decode($info[$i]["department"][0]);
		$element['description']=utf8_decode($info[$i]["description"][0]);
		$element['extensionAttribute1']=utf8_decode($info[$i]["extensionattribute1"][0]);;
		$element['extensionAttribute2']=utf8_decode($info[$i]["extensionattribute2"][0]);;
		$element['extensionAttribute3']=utf8_decode($info[$i]["extensionattribute3"][0]);;
		$element['extensionAttribute4']=utf8_decode($info[$i]["extensionattribute4"][0]);;
		$element['extensionAttribute5']=utf8_decode($info[$i]["extensionattribute5"][0]);;
		$element['extensionAttribute6']=utf8_decode($info[$i]["extensionattribute6"][0]);;
		$element['extensionAttribute7']=utf8_decode($info[$i]["extensionattribute7"][0]);;
		$element['facsimileTelephoneNumber']=utf8_decode($info[$i]["facsimiletelephonenumber"][0]);;
		$element['l']=utf8_decode($info[$i]["l"][0]);;
		$element['mobile']=utf8_decode($info[$i]["mobile"][0]);;
		$element['physicalDeliveryOfficeName']=utf8_decode($info[$i]["physicaldeliveryofficename"][0]);;
		$element['postalcode']=utf8_decode($info[$i]["postalcode"][0]);;
		$element['streetAddress']=utf8_decode($info[$i]["streetaddress"][0]);;
		$element['telephoneNumber']=utf8_decode($info[$i]["telephonenumber"][0]);;
		$element['givenName']=utf8_decode($info[$i]["givenname"][0]);;
		$element['sn']=utf8_decode($info[$i]["sn"][0]);;
		$complet[]=$element;
	}
	return $complet;
}
function AD_getGroupesAD() {
	global $AD_dn;
	global $AD_ds;
	global $AD_identification;
	$groupes=array();
	$justthese = array("sAMAccountName"); // liste des éléments recherchés dans le l'ActiveDirectory
	$sr = ldap_search($AD_ds, "OU=Conseil General 28, DC=cg28,DC=local", "sAMAccountName=*", $justthese);
	$info = ldap_get_entries($AD_ds, $sr);
//	var_dump($info[0]['samaccountname'][0]);
	//on récupère les infos
	for ($i=0; $i < $info["count"]; $i++) {
		$login=utf8_decode($info[$i]['samaccountname'][0]);
		//var_dump($login);
		if (strlen(trim($login))>0) {
			$sr2 = ldap_search($AD_ds, "OU=Conseil General 28, DC=cg28,DC=local", "sAMAccountName=".$login);
			$info2 = ldap_get_entries($AD_ds, $sr2);
			//var_dump($info2[0]["memberof"]);//.'<br>';
			for ($j=0; $j < $info2[0]["memberof"]["count"]; $j++) {
				$grp = utf8_decode($info2[0]["memberof"][$j]);
				if (!in_array($grp, $groupes)) {
					$groupes[]=$grp;
					//echo $grp.'<br>';
				}
			}
		}
	}
	array_unique($groupes);
	return $groupes;
}

function AD_getUserNameByLogin($ln) {
	global $AD_ds;
	$sr = ldap_search($AD_ds, "OU=Conseil General 28, DC=cg28,DC=local", "sAMAccountName=".$ln);
	$info = ldap_get_entries($AD_ds, $sr);
	$userName=utf8_decode($info[0]["cn"][0]);
	return $userName;
}

function AD_getGroupsByLogin($ln) {
	global $AD_ds;
	$sr = ldap_search($AD_ds, "OU=Conseil General 28, DC=cg28,DC=local", "sAMAccountName=".$ln);
	$info = ldap_get_entries($AD_ds, $sr);
	return $info[0]["memberof"];
}
function AD_getLoginsByGroup($grp) {
	global $AD_ds;
	$sr = ldap_search($AD_ds, "OU=Groupes,DC=cg28,DC=local", "CN=$grp");
	$info = ldap_get_entries($AD_ds, $sr);
	$dnMembers=$info[0]["member"];
	$logins=array();
	if ($dnMembers != null) {
		foreach ($dnMembers as $dn) {
			$filter="(objectclass=*)"; // this command requires some filter
			$justthese = array("ou", "sn", "givenname", "samaccountname"); //the attributes to pull, which is much more efficient than pulling all attributes if you don't do this
			$sr=ldap_read($AD_ds, $dn, $filter, $justthese);
			$entry = ldap_get_entries($AD_ds, $sr);
			if (isset($entry[0]["samaccountname"])) {
				$login = $entry[0]["samaccountname"][0];
				if (strlen(trim($login)) > 0) {
					$logins[] = $login;
				}
			}
		}
		sort($logins);
	}
	return $logins;
}

function AD_addLoginInGroup($ln, $grp) {
	global $AD_ds;
	$sr = ldap_search($AD_ds, "OU=Conseil General 28, DC=cg28,DC=local", "sAMAccountName=$ln");
	$first = ldap_first_entry($AD_ds, $sr);
	$dn = ldap_get_dn($AD_ds, $first);
	//echo "<br>The desired DN is: ".utf8_decode($dn);
	$member=array('member'=>$dn);
	ldap_mod_add($AD_ds, $grp, $member);
}
function AD_removeLoginFromGroup($ln, $grp) {
	global $AD_ds;
	$sr = ldap_search($AD_ds, "OU=Conseil General 28, DC=cg28,DC=local", "sAMAccountName=$ln");
	$first = ldap_first_entry($AD_ds, $sr);
	$dn = ldap_get_dn($AD_ds, $first);
	//echo "<br>The desired DN is: ".utf8_decode($dn);
	$member=array('member'=>$dn);
	ldap_mod_del($AD_ds, $grp, $member);
}

function AD_getSansMatricule() {
	global $AD_ds;
	$sr = ldap_search($AD_ds, "OU=Conseil General 28, DC=cg28,DC=local", "Company=CG28");
	$info = ldap_get_entries($AD_ds, $sr);
	$all=array();
	foreach ($info as $i) {
		$qui = utf8_decode($i["sn"][0]).' '.utf8_decode($i["givenname"][0]);
		$mat = $i["title"][0];
		if ($mat==0 and strlen(trim($qui))>0) {
			$all[]=$qui;
		}
	}
	asort($all);
	return $all;
}
function AD_getAvecMatriculeSansFiche() {
	global $AD_ds;
	$sr = ldap_search($AD_ds, "OU=Conseil General 28, DC=cg28,DC=local", "Company=CG28");
	$info = ldap_get_entries($AD_ds, $sr);
	$all=array();
	foreach ($info as $i) {
		$qui = utf8_decode($i["sn"][0]).' '.utf8_decode($i["givenname"][0]);
		$mat = $i["title"][0];
		if ($mat>0 and strlen(trim($qui))>0) {
			$all[$mat]=$qui;
		}
	}
	return $all;
}
function AD_getDoublonsMatriculeOld() {
	global $AD_ds;
	$sr = ldap_search($AD_ds, "OU=Conseil General 28, DC=cg28,DC=local", "Company=CG28");
	$info = ldap_get_entries($AD_ds, $sr);
	$all=array();
	$doublons=array();
	foreach ($info as $i) {
		$qui = utf8_decode($i["givenname"][0]).' '.utf8_decode($i["sn"][0]);
		$mat = $i["title"][0];
		if ($mat>0) {
			if (!isset($all[$mat])) {
				$all[$mat] = $qui;
			} else {
				$doublons[$mat]=$all[$mat].' - '.$qui;
				$all[$mat]=$doublons[$mat];
			}
		}
	}
	return $doublons;
}
function AD_modify($matAD, $key, $value){
	//echo "MAJ ".$matAD." key ".$key." value ".$value ;
	global $AD_dn;
	global $AD_ds;
	$justthese = array("mail", "sAMAccountName", "extensionAttribute1", "extensionAttribute2", "extensionAttribute3", "extensionAttribute4", "extensionAttribute5", "extensionAttribute6", "extensionAttribute7"); // liste des éléments recherchés dans le l'ActiveDirectory
	$sr = ldap_search($AD_ds, $AD_dn, "title=".$matAD, $justthese);
	$info = ldap_get_entries($AD_ds, $sr);
	//var_dump($info[0]);
	// si on ne trouve pas avec le $MAT_AD, et que le nombre de chiffres du matricule est inférieur à 4
	// on effectue une seconde recherche en complétant avec des zéros (ex : 104 --> 0104)
	if($info["count"]==0 && strlen($matAD)<4) {
		$zero="";
		$longueur=strlen($matAD);
		while ($longueur < 4) {
			$zero=$zero."0";
			$longueur++;
		}
		$matAD=$zero.$matAD;
		$sr = ldap_search($AD_ds, $AD_dn, "title=".$matAD, $justthese);
		$info = ldap_get_entries($AD_ds, $sr);
		//var_dump($info[0]);
	}
	if($info["count"]==0) {
		echo 'Matricule AD '.$matAD.' non trouvé<br>';
	} else {
		// Mise à jour dans l'AD
		//echo ' Poste actuel : '.utf8_decode($info[0]["extensionAttribute1"][0]);
		$mydn=$info[0]["dn"];
		// ECRITURE DANS L'AD
		$monTableau=array();
		if (strlen($value)==0) {
			$value=' '; // bug si on écrit une chaîne vide dans l'AD
		}
		$value = str_replace(",", "", $value); // bug si on écrit une chaîne contenant une virgule
		$monTableau[$key]=utf8_encode($value);
		//if ($key=='department') {
		//	echo '<br>ce qu on écrit dans l AD';
		//	var_dump($monTableau);
		//	var_dump($mydn);
		ldap_mod_replace($AD_ds, $mydn, $monTableau);
	}
}
function AD_close() {
	global $AD_ds;
	ldap_close($AD_ds);
}

/**
 * Fonction qui met à jour un agent dans l'AD
 * @param Integer $agentId identifiant de l'agent dans la table AGENT
 */
function AD_update_agent($agentId) {
	AD_connectAdmin();
	include_once 'include.php';
	$agent=agent_DAO::LoadOne($agentId);
	$company=" ";
	$department=" ";
	$description=" ";
	$extensionAttribute1=$agent->getSignFct();
	$extensionAttribute2=" ";
	$extensionAttribute3=" ";
	$extensionAttribute4=" ";
	$extensionAttribute5=" ";
	$extensionAttribute6=" ";
	$extensionAttribute7=" ";
	$facsimileTelephoneNumber=" ";
	$l=" ";
	$mobile=" ";
	$physicalDeliveryOfficeName=" ";
	$postalCode=" ";
	$streetAddress=" ";
	$telephoneNumber=" ";

	// department (=la direction)
	$department=GetServiceDunAgent($agent->getId(), 4);
	if ($department == ''){$department=GetServiceDunAgent($agent->getId(), 3);}
	if ($department == ''){$department=GetServiceDunAgent($agent->getId(), 2);}
	if ($department == ''){$department=GetServiceDunAgent($agent->getId(), 1);}

	// extensionAttribute6 (=le service)
	$extensionAttribute6=GetServiceDunAgent($agent->getId(), 5);
	if ($extensionAttribute6 == ''){$extensionAttribute6=$department;}
	if (GetServiceDunAgent($agent->getId(), 6) == "Assistants familiaux") {
		$description = 'Assistant familial';
		$department = '';
		$extensionAttribute6 = '';
	}

	if (GetServiceDunAgent($agent->getId(), 6) == "Assistants familiaux") {
		//$company = 'ASE';
		$description = 'Assistant familial';
		$department = '';
//	} elseif (GetServiceDunAgent($agent->getId(), 1) == "Conseil départemental d'Eure-et-Loir") {
//		$company = 'CG28';
// 	} elseif (GetServiceDunAgent($agent->getId(), 2) == "Agence technique départementale") {
// 		$company = 'ATD';
// 		$description = GetServiceDunAgent($agent->getId(), 2);
// 	} elseif (GetServiceDunAgent($agent->getId(), 2) == "Eure-et-Loir Numérique") {
// 		$company = 'SMO';
// 		$description = GetServiceDunAgent($agent->getId(), 2);
	}
	// company
	$company=$agent->getMatriculeHoroquartz();
	if ($agent->getDateArchivage()!='9999-12-31') {
		$extensionAttribute7 = 'Agent archivé le '.date_fr($agent->getDateArchivage());
	}

	// site étage bureau
	$site=Site_Dao::LoadOne($agent->getSiteId());
	if ($agent->getEtage() != '') {
		$extensionAttribute4 = $agent->getEtage();
	}
	if ($agent->getNumBureau() != '') {
		$extensionAttribute5 = 'Bureau '.$agent->getNumBureau();
	}
	// service
	$sce=Service_Dao::LoadOne($agent->getServiceId());

	// description (abrégé de la direction ou service)
	$niveau=12;
	while ($sce!=null and $description == " " and $niveau >1) {
		if ($sce->getCommentaire() !='') {
			$description=$sce->getCommentaire();
		}else {
			$sce=Service_Dao::LoadOne($sce->getParentId());
			$niveau=$sce->getNiveau(); // pour éviter une boucle infinie si on n'a pas de commentaire
		}
	}
	// lignes
	$lignes = getLignesByAgentId($agent->getId());
	//var_dump($lignes);
	$nbligne=0;
	if (sizeof($lignes) > 0) {
		foreach ($lignes as $ligne) {
			$num = trim(chunk_split($ligne['LIG_NUM_APPEL'],2,' '));
			switch ($ligne['LIG_TYPE']) {
				case 1 :
					//fixe
					$telephoneNumber=$num;
					break;
				case 2 :
					//mobile
					$mobile=$num;
					break;
				case 3 :
					//fax
					$facsimileTelephoneNumber=$num;
					break;
			}
			if ($ligne['LIG_AFF_SIGNATURE'] == 1) { // signature mail que pour les lignes visibles
				$nbligne++;
				switch ($ligne['LIG_TYPE']) {
					case 1 :
						//fixe
						$myNum='Tél. '.$num;
						break;
					case 2 :
						//mobile
						$myNum='Port. '.$num;
						break;
					case 3 :
						//fax
						$myNum='Fax '.$num;
						break;
				}
				if ($nbligne==1) $extensionAttribute2=$myNum;
				if ($nbligne==2) $extensionAttribute2=$extensionAttribute2.' / '.$myNum;
				if ($nbligne==3) $extensionAttribute3=$myNum;
				if ($nbligne==4) $extensionAttribute3=$extensionAttribute3.' / '.$myNum;
			}
		}
	}
	// numéro du site
	if (strlen($telephoneNumber)<1) {
		$numappel=site_DAO::GetNumAppel($agent->getSiteId());
		if ($numappel) {
			$LONG = chunk_split($numappel,2,' ');
			$telephoneNumber = $LONG;
		}
	}

	$physicalDeliveryOfficeName=$site->getLibelle();
	$streetAddress=$site->getAdr1();
	$postalCode=$site->getCp();
	$l=$site->getVille().' - '.$physicalDeliveryOfficeName;
	$ad = AD_getElementsByMat($agent->getMatAd());
	
	// company
	//echo 'company : '. $company.'<br>';
	if ($company != $ad["company"]) {
		//echo '---->(actuel ='.$ad["company"].')<br>';
		AD_modify($agent->getMatAd(), 'company', $company);
	}
	//department
	//echo 'department : '. $department.'<br>';
	if ($department != $ad["department"]) {
		echo '---->(actuel ='.$ad["department"].')<br>';
		AD_modify($agent->getMatAd(), 'department', $department);
	}
	//description
	//echo 'description : '. $description.'<br>';
	if ($description != $ad["description"]) {
		//echo'(actuel ='.$ad["description"].')<br>';
		AD_modify($agent->getMatAd(), 'description', $description);
	}
	//extensionAttribute1
	//echo 'extensionAttribute1 : '.$extensionAttribute1.'<br>';
	if ($extensionAttribute1 != $ad["extensionAttribute1"]) {
		//echo '---->(actuel ='.$ad["extensionAttribute1"].')<br>';
		//echo '---->(nouveau ='.$extensionAttribute1.')<br>';
		AD_modify($agent->getMatAd(), 'extensionAttribute1', $extensionAttribute1);
	}
	//extensionAttribute2
	//echo 'extensionAttribute2 : '.$extensionAttribute2.'<br>';
	if ($extensionAttribute2 != $ad["extensionAttribute2"]) {
		//echo '---->(actuel ='.$ad["extensionAttribute2"].')<br>';
		AD_modify($agent->getMatAd(), 'extensionAttribute2', $extensionAttribute2);
	}
	//extensionAttribute3
	if ($extensionAttribute3 != $ad["extensionAttribute3"]) {
		//echo '---->(actuel ='.$ad["extensionAttribute3"].')<br>';
		//var_dump($extensionAttribute3);
		AD_modify($agent->getMatAd(), "extensionAttribute3", $extensionAttribute3);
		//echo 'toto';
	}
	//extensionAttribute4
	//echo 'extensionAttribute4 : '.$extensionAttribute4.'<br>';
	if ($extensionAttribute4 != $ad["extensionAttribute4"]) {
		//echo '---->(actuel ='.$ad["extensionAttribute4"].')<br>';
		AD_modify($agent->getMatAd(), 'extensionAttribute4', $extensionAttribute4);
	}
	//extensionAttribute5
	//echo 'extensionAttribute5 : '.$extensionAttribute5.'<br>';
	if ($extensionAttribute5 != $ad["extensionAttribute5"]) {
		//echo '---->(actuel ='.$ad["extensionAttribute5"].')<br>';
		AD_modify($agent->getMatAd(), 'extensionAttribute5', $extensionAttribute5);
	}
	//extensionAttribute6
	//echo 'extensionAttribute6 : '.$extensionAttribute6.'<br>';
	if ($extensionAttribute6 != $ad["extensionAttribute6"]) {
		//echo '---->(actuel ='.$ad["extensionAttribute6"].')<br>';
		AD_modify($agent->getMatAd(), 'extensionAttribute6', $extensionAttribute6);
	}
	//extensionAttribute7
	//echo 'extensionAttribute7 : '.$extensionAttribute7.'<br>';
	if ($extensionAttribute7 != $ad["extensionAttribute7"]) {
		//echo '---->(actuel ='.$ad["extensionAttribute7"].')<br>';
		AD_modify($agent->getMatAd(), 'extensionAttribute7', $extensionAttribute7);
	}
	//facsimileTelephoneNumber
	//echo 'facsimileTelephoneNumber : '.$facsimileTelephoneNumber.'<br>';
	if ($facsimileTelephoneNumber != $ad["facsimileTelephoneNumber"]) {
		//echo '---->(actuel ='.$ad["facsimileTelephoneNumber"].')<br>';
		AD_modify($agent->getMatAd(), 'facsimileTelephoneNumber', $facsimileTelephoneNumber);
	}
	//l
	//echo 'l : '.$l.'<br>';
	if ($l != $ad["l"]) {
		//echo '---->(actuel ='.$ad["l"].')<br>';
		AD_modify($agent->getMatAd(), 'l', $l);
	}
	//mobile
	//echo 'mobile : '.$mobile.'<br>';
	if ($mobile != $ad["mobile"]) {
		//echo '---->(actuel ='.$ad["mobile"].')<br>';
		AD_modify($agent->getMatAd(), 'mobile', $mobile);
	}
	//postalcode
	//echo 'postalcode : '.$postalCode.'<br>';
	if ($postalCode != $ad["postalcode"]) {
		//echo '---->(actuel ='.$ad["postalcode"].')<br>';
		AD_modify($agent->getMatAd(), 'postalcode', $postalCode);
	}
	//streetAddress
	//echo 'streetAddress :'.$streetAddress.'<br>';
	if ($streetAddress != $ad["streetAddress"]) {
		//echo '---->(actuel ='.$ad["streetAddress"].')<br>';
		AD_modify($agent->getMatAd(), 'streetAddress', $streetAddress);
	}
	//telephoneNumber
	//echo 'telephoneNumber :'.$telephoneNumber.'<br>';
	if ($telephoneNumber != $ad["telephoneNumber"]) {
		//echo '---->(actuel ='.$ad["telephoneNumber"].')<br>';
		AD_modify($agent->getMatAd(), 'telephoneNumber', $telephoneNumber);
	}
	//echo "<br>";

	//var_dump($ad);
	//	}//

	AD_close();
}
