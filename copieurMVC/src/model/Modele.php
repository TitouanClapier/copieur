<?php

namespace App\src\model;

class Modele
{

    private $Id;
    private $Libelle;
    private $Lien;
    private $Compte;


    /**
     * Get the value of Id
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * Set the value of Id
     *
     * @return  self
     */
    public function setId($Id)
    {
        $this->Id = $Id;

        return $this;
    }

    /**
     * Get the value of Libelle
     */
    public function getLibelle()
    {
        return $this->Libelle;
    }

    /**
     * Set the value of Libelle
     *
     * @return  self
     */
    public function setLibelle($Libelle)
    {
        $this->Libelle = $Libelle;

        return $this;
    }

    /**
     * Get the value of Lien
     */
    public function getLien()
    {
        return $this->Lien;
    }

    /**
     * Set the value of Lien
     *
     * @return  self
     */
    public function setLien($Lien)
    {
        $this->Lien = $Lien;

        return $this;
    }

    /**
     * Get the value of Compte
     */ 
    public function getCompte()
    {
        return $this->Compte;
    }

    /**
     * Set the value of Compte
     *
     * @return  self
     */ 
    public function setCompte($Compte)
    {
        $this->Compte = $Compte;

        return $this;
    }
}
