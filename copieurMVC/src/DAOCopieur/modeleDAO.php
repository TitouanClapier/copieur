<?php

namespace App\src\DAOCopieur;

use App\config\Parameter;
use App\src\model\Modele;

class modeleDAO extends DAO
{
    private function buildObject($row)
    {
        $modele = new Modele();
        $modele->setId($row['id'] ?? null);
        $modele->setLibelle($row['libelle'] ?? null);
        $modele->setLien($row['lien'] ?? null);
        $modele->setCompte($row['nombre']?? null);


        return $modele;
    }


    public function getModeles()
    {
        $sql = "SELECT id, libelle FROM modele ";
        $result = $this->createQuery($sql);
        $modele = [];
        foreach ($result as $row) {
            $modeleId = $row['id'];
            $modele[$modeleId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $modele;
    }

    public function getModele($id)
    {
        $sql = "SELECT * FROM modele WHERE `id`= $id";
        $result = $this->createQuery($sql);
        $modele = [];
        foreach ($result as $row) {
            $modeleId = $row['id'];
            $modele[$modeleId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $modele;
    }

    public function setModele($post)
    {
        $sql = "INSERT INTO modele (libelle, lien) VALUES (?, ?);";

        $result = $this->createQuery(
            $sql,
            [
                $post['libelle'],
                $post['lien']
            ]
        );
        $result->closeCursor();
    }

    public function delModele($post)
    {
        $sql = "DELETE FROM `modele` WHERE `id`=?;";
        $this->createQuery($sql, [
            $post['id']
        ]);
    }

    public function getNbModeles()
    {
        $sql = "SELECT libelle, lien, modele.id as id , count(modele_id) as nombre, modele_id FROM modele
        left JOIN copieur on modele_id = modele.id
        group by id;";
        $result = $this->createQuery($sql);
        $modeles = [];
        foreach ($result as $row) {
            $modeleId = $row['id'];
            $modeles[$modeleId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $modeles;
    }

    public function updModele($post)
    {
        $sql = "UPDATE `modele` SET `libelle`=?, `lien`=? WHERE `id`=?;";
        $this->createQuery($sql, [
            $post['libelle'],
            $post['lien'],
            $post['id']
        ]);
    }
}
