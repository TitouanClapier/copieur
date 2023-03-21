<?php

namespace App\src\model;

class Compteur {

    private $Id;
    private $CopId;
    private $NbCopNoir;
    private $NbCopCoul;
    private $NbCopLogo;
    private $DateReleve;



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
     * Get the value of NbCopNoir
     */ 
    public function getNbCopNoir()
    {
        return $this->NbCopNoir;
    }

    /**
     * Set the value of NbCopNoir
     *
     * @return  self
     */ 
    public function setNbCopNoir($NbCopNoir)
    {
        $this->NbCopNoir = $NbCopNoir;

        return $this;
    }

    /**
     * Get the value of NbCopCoul
     */ 
    public function getNbCopCoul()
    {
        return $this->NbCopCoul;
    }

    /**
     * Set the value of NbCopCoul
     *
     * @return  self
     */ 
    public function setNbCopCoul($NbCopCoul)
    {
        $this->NbCopCoul = $NbCopCoul;

        return $this;
    }

    /**
     * Get the value of NbCopLogo
     */ 
    public function getNbCopLogo()
    {
        return $this->NbCopLogo;
    }

    /**
     * Set the value of NbCopLogo
     *
     * @return  self
     */ 
    public function setNbCopLogo($NbCopLogo)
    {
        $this->NbCopLogo = $NbCopLogo;

        return $this;
    }

    /**
     * Get the value of DateReleve
     */ 
    public function getDateReleve()
    {
        return $this->DateReleve;
    }

    /**
     * Set the value of DateReleve
     *
     * @return  self
     */ 
    public function setDateReleve($DateReleve)
    {
        $this->DateReleve = $DateReleve;

        return $this;
    }
}