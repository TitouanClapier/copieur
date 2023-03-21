<?php

namespace App\src\DAOCopieur;

use App\config\Parameter;
use App\src\model\CopieurService;

class copieur_serviceDAO extends DAO
{         
    private function buildObject($row)
    {
        $copieur_service = new CopieurService();
        $copieur_service->setId($row['id']?? null);
        $copieur_service->setCopId($row['copieur_id']?? null);
        $copieur_service->setServId($row['service_id']?? null);
        $copieur_service->setDateArrivee($row['date_arrivee']?? null);
        $copieur_service->setDateDepart($row['date_depart'])?? null;
        $copieur_service->setCompte($row['nbcop']?? null);

        return $copieur_service;
    }


    public function setCopieurService($service_id, $copieur_id, $date_livraison) {
        $sql="INSERT INTO copieur_service ( service_id, copieur_id, date_arrivee) 
        VALUES ( '$service_id', '$copieur_id', '$date_livraison');";
        $this->createQuery($sql);
        
    }

    
    public function getCopieurServicesId($id) {
        $sql = "SELECT * FROM copieur_service WHERE `copieur_id`=$id";
        $result = $this->createQuery($sql);
        $copieurservices = [];
        foreach ($result as $row){
            $copieurserviceId = $row['id'];
            $copieurservices[$copieurserviceId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $copieurservices  ;
    }


    public function getService($id) {
        $sql = "SELECT * FROM copieur_service 
                WHERE `copieur_id`=$id";
        $result = $this->createQuery($sql);
        $services = [];
        foreach ($result as $row){
            $serviceId = $row['id'];
            $services[$serviceId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $services  ;
    }

    public function getNbCopieurService() {
        $sql = "SELECT count(copieur_service.id) as nbcop,  copieur_service.service_id, libelle, copieur_service.id, date_depart FROM copieur_service 
        left JOIN organigramme.service on  copieur_service.service_id = service.id 
        WHERE copieur_service.date_depart IS NULL
        group by service_id";
        $result = $this->createQuery($sql);
        $nbservices = [];
        foreach ($result as $row){
            $serviceId = $row['id'];
            $nbservices[$serviceId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $nbservices  ;
    }



}