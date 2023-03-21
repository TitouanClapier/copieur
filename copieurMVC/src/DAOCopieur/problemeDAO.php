<?php

namespace App\src\DAOCopieur;

use App\config\Parameter;
use App\src\model\Probleme;

class problemeDAO extends DAO
{         
    private function buildObject($row)
    {
        $probleme = new Probleme();
        $probleme->setId($row['id']);
        $probleme->setCopId($row['copieur_id']);
        $probleme->setDate($row['date']);
        $probleme->setDescription($row['description']);

        return $probleme;
    }


    public function getProblemes($id) {
        $sql = "SELECT * FROM probleme WHERE `copieur_id`=$id";
        $result = $this->createQuery($sql);
        $problemes = [];
        foreach ($result as $row){
            $problemeId = $row['id'];
            $problemes[$problemeId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $problemes  ;
    }



}