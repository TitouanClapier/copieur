<?php

namespace App\src\DAOCopieur;

use App\config\Parameter;
use App\src\model\Compteur;

class compteurDAO extends DAO
{         
    private function buildObject($row)
    {
        $compteur = new Compteur();
        $compteur->setId($row['id']);
        $compteur->setCopId($row['copieur_id']);
        $compteur->setNbCopNoir($row['nb_cop_noir']);
        $compteur->setNbCopCoul($row['nb_cop_coul']);
        $compteur->setNbCopLogo($row['nb_cop_logo']);
        $compteur->setDateReleve($row['date_releve']);
        

        return $compteur;
    }

    public function getCompteurs($id) {
        $sql = "SELECT * FROM compteur WHERE `copieur_id`=$id";
        $result = $this->createQuery($sql);
        $compteurs = [];
        foreach ($result as $row){
            $compteurId = $row['id'];
            $compteurs[$compteurId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $compteurs  ;
    }


}