<?php

namespace App\src\DAOCopieur;

use App\config\Parameter;
use App\src\model\CopieurSite;

class copieur_siteDAO extends DAO
{         
    private function buildObject($row)
    {
        $copieur_site = new CopieurSite();
        $copieur_site->setId($row['id']?? null);
        $copieur_site->setCopId($row['copieur_id']?? null);
        $copieur_site->setSiteId($row['site_id']?? null);
        $copieur_site->setDateArrivee($row['date_arrivee']?? null);
        $copieur_site->setDateDepart($row['date_depart']?? null);
        $copieur_site->setPlan($row['chemin']?? null);
        $copieur_site->setCompte($row['nbcop']?? null);

        return $copieur_site;
    }


    public function setCopieurSite($site_id, $copieur_id, $date_livraison) {
        $sql="INSERT INTO copieur_site ( site_id, copieur_id, date_arrivee) 
        VALUES ( '$site_id', '$copieur_id', '$date_livraison');";
        $this->createQuery($sql);
        
    }

    
    public function getCopieurSiteId($id) {
        $sql = "SELECT * FROM copieur_site WHERE `copieur_id`=$id";
        $result = $this->createQuery($sql);
        $copieursites = [];
        foreach ($result as $row){
            $copieursiteId = $row['id'];
            $copieursites[$copieursiteId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $copieursites  ;
    }


    public function getSite($id) {
        $sql = "SELECT * FROM copieur_site
         WHERE `copieur_id`=$id";
        $result = $this->createQuery($sql);

        $sites = [];
        foreach ($result as $row){
            $siteId = $row['id'];
            $sites[$siteId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $sites  ;
    }

    public function getNbCopieurSite() {
        $sql = "SELECT count(copieur_site.id) as nbcop,  copieur_site.site_id, libelle, copieur_site.id, chemin FROM copieur_site 
        left JOIN organigramme.site on  copieur_site.site_id = site.id 
        left JOIN plan_site on plan_site.site_id  = copieur_site.site_id 
        WHERE copieur_site.date_depart IS NULL
        group by site_id";
        $result = $this->createQuery($sql);
        $nbsites = [];
        foreach ($result as $row){
            $siteId = $row['id'];
            $nbsites[$siteId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $nbsites  ;
    }



}