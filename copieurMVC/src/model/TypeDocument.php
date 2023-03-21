<?php

namespace App\src\model;

class TypeDocument {


    private $Id;
    private $NumOrdre;
    private $Libelle;
    private $InvestFonct;

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
     * Get the value of NumOrdre
     */ 
    public function getNumOrdre()
    {
        return $this->NumOrdre;
    }

    /**
     * Set the value of NumOrdre
     *
     * @return  self
     */ 
    public function setNumOrdre($NumOrdre)
    {
        $this->NumOrdre = $NumOrdre;

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
     * Get the value of InvestFonct
     */ 
    public function getInvestFonct()
    {
        return $this->InvestFonct;
    }

    /**
     * Set the value of InvestFonct
     *
     * @return  self
     */ 
    public function setInvestFonct($InvestFonct)
    {
        $this->InvestFonct = $InvestFonct;

        return $this;
    }
}