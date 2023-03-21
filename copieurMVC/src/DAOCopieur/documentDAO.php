<?php

namespace App\src\DAOCopieur;

use App\config\Parameter;
use App\src\model\Document;

class documentDAO extends DAO
{         
    private function buildObject($row)
    {
        $document = new Document();
        $document->setDocId($row['DocId']);
        $document->setCopMatricule($row['CopMatricule']);
        $document->setCopModId($row['CopModId']);
        $document->setModLibelle($row['ModLibelle']);
        $document->setNumero($row['numero']);
        $document->setDate_reception($row['date_reception']);
        $document->setNumero_mandat($row['numero_mandat']);
        $document->setNumero_engagement($row['numero_engagement']);
        $document->setMontant_ttc($row['montant_ttc']);
        $document->setType_doc_id($row['type_doc_id']);
        $document->setLibelleType($row['LibelleType']);
        $document->setlibellePeriode($row['libellePeriode']);
        $document->setPeriodeId($row['PeriodeId']);
        
        

        return $document;
    }
    

    public function getDocuments($id) {
        $sql = "SELECT copieur_id, document.id as DocId, periode.libelle as libellePeriode, 
        copieur.matricule as CopMatricule, copieur.modele_id as CopModId, modele.libelle as ModLibelle, 
        numero, date_reception, numero_mandat, numero_engagement, montant_ttc, type_doc_id, type_document.libelle  as LibelleType, periode.id as PeriodeId
        FROM document 
        left JOIN copieur on copieur.id  = copieur_id
        left JOIN modele on modele.id  = modele_id
        inner JOIN periode on periode_id=periode.id 
        left JOIN type_document on type_document.id  = type_doc_id 
        where copieur_id = $id ";
        $result = $this->createQuery($sql);

        $documents = [];
        foreach ($result as $row){
            $documentId = $row['DocId'];
            $documents[$documentId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $documents  ;
    }


/////////////////////////////////////////////

public function getDocumentsByType($post) {
    $sql = "SELECT document.id, copieur.matricule, copieur.modele_id, modele.libelle, numero, date_reception, numero_mandat, numero_engagement, montant_ttc, type_doc_id  FROM document 
    left JOIN copieur on copieur.id  = copieur_id
    left JOIN modele on modele.id  = modele_id
    left JOIN type_document on type_document.id  = type_doc_id
    Where  Year(date_reception) = '?' ";
            $result = $this->createQuery(
                $sql,
                [
                    //$post['typedoc']??Null, type_doc_id = '?' and
                    $post['annee']??Null
                ]
            );

    $documents = [];
    foreach ($result as $row){
        $documentId = $row['id'];
        $documents[$documentId] = $this->buildObject($row);
    }

    $result->closeCursor();
    return $documents  ;
}

    
}