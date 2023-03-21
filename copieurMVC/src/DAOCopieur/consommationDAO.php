<?php

namespace App\src\DAOCopieur;

use App\config\Parameter;
use App\src\model\Consommation;

class consommationDAO extends DAO
{         
    private function buildObject($row)
    {
        $consommation = new Consommation();
        $consommation->setId($row['id']);
        $consommation->setCopId($row['copieur_id']);
        $consommation->setVolNoir($row['volume_noir']);
        $consommation->setVolCouleur($row['volume_couleur']);
        $consommation->setVolLogo($row['volume_logo']);
        
        return $consommation;
    }

    public function getConsommations($id) {
        $sql = "SELECT * FROM consommation WHERE `copieur_id`=$id";
        $result = $this->createQuery($sql);
        $consommations = [];
        foreach ($result as $row){
            $consommationId = $row['id'];
            $consommations[$consommationId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $consommations  ;
    }


}