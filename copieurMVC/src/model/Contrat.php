<?php

namespace App\src\model;

class Contrat {

    private $DocId;
    private $Id;
    private $Numero;
    private $Duree;
    private $NbTrimCopNoir;
    private $NbTrimCopCoul;
    private $NbTrimCopLogo;
    private $CoutCopNoir;
    private $CoutCopCoul;
    private $CoutCopLogo;
    private $PrixTrimMaintenance;
    private $CopId;
    



    /**
     * Get the value of DocId
     */ 
    public function getDocId()
    {
        return $this->DocId;
    }

    /**
     * Set the value of DocId
     *
     * @return  self
     */ 
    public function setDocId($DocId)
    {
        $this->DocId = $DocId;

        return $this;
    }

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
     * Get the value of Numero
     */ 
    public function getNumero()
    {
        return $this->Numero;
    }

    /**
     * Set the value of Numero
     *
     * @return  self
     */ 
    public function setNumero($Numero)
    {
        $this->Numero = $Numero;

        return $this;
    }

    /**
     * Get the value of Duree
     */ 
    public function getDuree()
    {
        return $this->Duree;
    }

    /**
     * Set the value of Duree
     *
     * @return  self
     */ 
    public function setDuree($Duree)
    {
        $this->Duree = $Duree;

        return $this;
    }

    /**
     * Get the value of NbTrimCopNoir
     */ 
    public function getNbTrimCopNoir()
    {
        return $this->NbTrimCopNoir;
    }

    /**
     * Set the value of NbTrimCopNoir
     *
     * @return  self
     */ 
    public function setNbTrimCopNoir($NbTrimCopNoir)
    {
        $this->NbTrimCopNoir = $NbTrimCopNoir;

        return $this;
    }

    /**
     * Get the value of NbTrimCopCoul
     */ 
    public function getNbTrimCopCoul()
    {
        return $this->NbTrimCopCoul;
    }

    /**
     * Set the value of NbTrimCopCoul
     *
     * @return  self
     */ 
    public function setNbTrimCopCoul($NbTrimCopCoul)
    {
        $this->NbTrimCopCoul = $NbTrimCopCoul;

        return $this;
    }

    /**
     * Get the value of NbTrimCopLogo
     */ 
    public function getNbTrimCopLogo()
    {
        return $this->NbTrimCopLogo;
    }

    /**
     * Set the value of NbTrimCopLogo
     *
     * @return  self
     */ 
    public function setNbTrimCopLogo($NbTrimCopLogo)
    {
        $this->NbTrimCopLogo = $NbTrimCopLogo;

        return $this;
    }

    /**
     * Get the value of CoutCopNoir
     */ 
    public function getCoutCopNoir()
    {
        return $this->CoutCopNoir;
    }

    /**
     * Set the value of CoutCopNoir
     *
     * @return  self
     */ 
    public function setCoutCopNoir($CoutCopNoir)
    {
        $this->CoutCopNoir = $CoutCopNoir;

        return $this;
    }

    /**
     * Get the value of CoutCopCoul
     */ 
    public function getCoutCopCoul()
    {
        return $this->CoutCopCoul;
    }

    /**
     * Set the value of CoutCopCoul
     *
     * @return  self
     */ 
    public function setCoutCopCoul($CoutCopCoul)
    {
        $this->CoutCopCoul = $CoutCopCoul;

        return $this;
    }

    /**
     * Get the value of CoutCopLogo
     */ 
    public function getCoutCopLogo()
    {
        return $this->CoutCopLogo;
    }

    /**
     * Set the value of CoutCopLogo
     *
     * @return  self
     */ 
    public function setCoutCopLogo($CoutCopLogo)
    {
        $this->CoutCopLogo = $CoutCopLogo;

        return $this;
    }

    /**
     * Get the value of PrixTrimMaintenance
     */ 
    public function getPrixTrimMaintenance()
    {
        return $this->PrixTrimMaintenance;
    }

    /**
     * Set the value of PrixTrimMaintenance
     *
     * @return  self
     */ 
    public function setPrixTrimMaintenance($PrixTrimMaintenance)
    {
        $this->PrixTrimMaintenance = $PrixTrimMaintenance;

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
}