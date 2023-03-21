<?php

namespace App\src\model;

class Contact {

    private $Id;
    private $Nom;
    private $Prenom;
    private $Tel;
    private $DateDepart;
    private $DateArrivee;


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
     * Get the value of Nom
     */ 
    public function getNom()
    {
        return $this->Nom;
    }

    /**
     * Set the value of Nom
     *
     * @return  self
     */ 
    public function setNom($Nom)
    {
        $this->Nom = $Nom;

        return $this;
    }

    /**
     * Get the value of Prenom
     */ 
    public function getPrenom()
    {
        return $this->Prenom;
    }

    /**
     * Set the value of Prenom
     *
     * @return  self
     */ 
    public function setPrenom($Prenom)
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    /**
     * Get the value of Tel
     */ 
    public function getTel()
    {
        return $this->Tel;
    }

    /**
     * Set the value of Tel
     *
     * @return  self
     */ 
    public function setTel($Tel)
    {
        $this->Tel = $Tel;

        return $this;
    }

    /**
     * Get the value of DateDepart
     */ 
    public function getDateDepart()
    {
        return $this->DateDepart;
    }

    /**
     * Set the value of DateDepart
     *
     * @return  self
     */ 
    public function setDateDepart($DateDepart)
    {
        $this->DateDepart = $DateDepart;

        return $this;
    }

    /**
     * Get the value of DateArrivee
     */ 
    public function getDateArrivee()
    {
        return $this->DateArrivee;
    }

    /**
     * Set the value of DateArrivee
     *
     * @return  self
     */ 
    public function setDateArrivee($DateArrivee)
    {
        $this->DateArrivee = $DateArrivee;

        return $this;
    }
}