<?php

namespace App\src\DAOCopieur;

use App\config\Parameter;
use App\src\model\TypeDocument;

class type_documentDAO extends DAO
{         
    private function buildObject($row)
    {
        $copieur = new TypeDocument();
        $copieur->setId($row["id"]?? null);
        $copieur->setNumOrdre($row["num_ordre"]?? null);
        $copieur->setLibelle($row["libelle"]?? null);
        $copieur->setInvestFonct($row["invest_fonct"]?? null);

        return $copieur;
    }

    public function createTypeDocuments($post) {
        $sql="INSERT INTO type_document (libelle, num_ordre, invest_fonct) VALUES ( ?, ?, ?);";
        $this->createQuery($sql, [
            $post['libelle'],
            $post['NumOrdre'],
            $post['invest_fonct']
        ]);
    }

    public function deleteTypeDocuments($post) {
        $sql = "DELETE FROM `type_document` WHERE `id`= ? ;";
        $this->createQuery($sql, [
            $post['id'],
        ]);
    }

    public function getTypeDocuments() {
        $sql = "SELECT libelle, num_ordre, id, invest_fonct FROM type_document order by num_ordre ";
        $result = $this->createQuery($sql);
        $typeDocuments = [];
        foreach ($result as $row){
            $typeDocumentId = $row["id"];
            $typeDocuments[$typeDocumentId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $typeDocuments  ;
    }

    public function updateTypeDocumentsOrdre($post) {
        $sql = "UPDATE `type_document` SET  `num_ordre`=? WHERE `id`=?;";
        $this->createQuery($sql, [
            $post['NumOrdre'],
            $post['id']
            
        ]);
        
        
    }

    public function updateTypeDocuments($post) {
        $sql = "UPDATE `type_document` SET `libelle`=?, `num_ordre`=?, `invest_fonct`=? WHERE `id`=?;";
        $this->createQuery($sql, [
            
            $post['libelle'],
            $post['NumOrdre'],
            $post['invest_fonct'],
            $post['id']
        ]);

        
    
    }


}