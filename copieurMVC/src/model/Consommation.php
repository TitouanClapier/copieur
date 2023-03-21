<?php

namespace App\src\model;

class Consommation {

    private $Id;
    private $CopId;
    private $VolNoir;
    private $VolCouleur;
    private $VolLogo;


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
     * Get the value of CopId
     */ 
    public function getCopId()
    {
        return $this->CopId;
    }

    /**
     * Set the value of CopId
     *
     * @return  self
     */ 
    public function setCopId($CopId)
    {
        $this->CopId = $CopId;

        return $this;
    }

    /**
     * Get the value of VolNoir
     */ 
    public function getVolNoir()
    {
        return $this->VolNoir;
    }

    /**
     * Set the value of VolNoir
     *
     * @return  self
     */ 
    public function setVolNoir($VolNoir)
    {
        $this->VolNoir = $VolNoir;

        return $this;
    }

    /**
     * Get the value of VolCouleur
     */ 
    public function getVolCouleur()
    {
        return $this->VolCouleur;
    }

    /**
     * Set the value of VolCouleur
     *
     * @return  self
     */ 
    public function setVolCouleur($VolCouleur)
    {
        $this->VolCouleur = $VolCouleur;

        return $this;
    }

    /**
     * Get the value of VolLogo
     */ 
    public function getVolLogo()
    {
        return $this->VolLogo;
    }

    /**
     * Set the value of VolLogo
     *
     * @return  self
     */ 
    public function setVolLogo($VolLogo)
    {
        $this->VolLogo = $VolLogo;

        return $this;
    }
}