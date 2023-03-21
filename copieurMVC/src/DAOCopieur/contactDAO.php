<?php

namespace App\src\DAOCopieur;

use App\config\Parameter;
use App\src\model\Contact;

class contactDAO extends DAO
{         
    private function buildObject($row)
    {
        $contact = new Contact();
        $contact->setId($row['id']);
        $contact->setNom($row['nom']);
        $contact->setPrenom($row['prenom']);
        $contact->setTel($row['telephone']);
        $contact->setDateArrivee($row['date_arrivee']);
        $contact->setDateDepart($row['date_depart']);

        return $contact;
    }

    public function getContact($id) {
        $sql = "SELECT * FROM personne 
        Left join contacter on personne_id = personne.id
        WHERE copieur_id =$id ";
        $result = $this->createQuery($sql);
        $contacts = [];
        foreach ($result as $row){
            $contactId = $row['id'];
            $contacts[$contactId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $contacts;
            
    }


}