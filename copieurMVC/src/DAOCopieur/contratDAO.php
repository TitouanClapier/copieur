<?php

namespace App\src\DAOCopieur;

use App\config\Parameter;
use App\src\model\Contrat;

class contratDAO extends DAO
{         
    private function buildObject($row)
    {
        $contrat = new Contrat();
        $contrat->setId($row['id']);
        $contrat->setNumero($row['numero']);
        $contrat->setDuree($row['duree']);
        $contrat->setNbTrimCopNoir($row['nb_trim_cop_noir']);
        $contrat->setNbTrimCopCoul($row['nb_trim_cop_coul']);
        $contrat->setNbTrimCopLogo($row['nb_trim_cop_logo']);
        $contrat->setCoutCopNoir($row['cout_cop_noir']);
        $contrat->setCoutCopCoul($row['cout_cop_coul']);
        $contrat->setCoutCopLogo($row['cout_cop_logo']);
        $contrat->setPrixTrimMaintenance($row['prix_trim_maintenance']);
        $contrat->setCopId($row['copieur_id']);

        return $contrat;
    }

    public function getContrat($id) {
        $sql = "SELECT * FROM contrat WHERE `copieur_id`=$id";

        $result = $this->createQuery($sql);
        $contrat = $result->fetch();
        $result->closeCursor();
        return $this->buildObject($contrat);
            
    }


}