<?php

namespace App\src\model;

class Document {

    private $DocId;
    private $CopMatricule;
    private $CopModId;
    private $ModLibelle;
    private $numero;
    private $date_reception;
    private $numero_mandat;
    private $numero_engagement;
    private $montant_ttc;
    private $type_doc_id;
    private $libellePeriode;
    private $LibelleType;
    private $PeriodeId;
    




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
     * Get the value of CopMatricule
     */ 
    public function getCopMatricule()
    {
        return $this->CopMatricule;
    }

    /**
     * Set the value of CopMatricule
     *
     * @return  self
     */ 
    public function setCopMatricule($CopMatricule)
    {
        $this->CopMatricule = $CopMatricule;

        return $this;
    }

    /**
     * Get the value of CopModId
     */ 
    public function getCopModId()
    {
        return $this->CopModId;
    }

    /**
     * Set the value of CopModId
     *
     * @return  self
     */ 
    public function setCopModId($CopModId)
    {
        $this->CopModId = $CopModId;

        return $this;
    }

    /**
     * Get the value of ModLibelle
     */ 
    public function getModLibelle()
    {
        return $this->ModLibelle;
    }

    /**
     * Set the value of ModLibelle
     *
     * @return  self
     */ 
    public function setModLibelle($ModLibelle)
    {
        $this->ModLibelle = $ModLibelle;

        return $this;
    }

    /**
     * Get the value of numero
     */ 
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set the value of numero
     *
     * @return  self
     */ 
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get the value of date_reception
     */ 
    public function getDate_reception()
    {
        return $this->date_reception;
    }

    /**
     * Set the value of date_reception
     *
     * @return  self
     */ 
    public function setDate_reception($date_reception)
    {
        $this->date_reception = $date_reception;

        return $this;
    }

    /**
     * Get the value of numero_mandat
     */ 
    public function getNumero_mandat()
    {
        return $this->numero_mandat;
    }

    /**
     * Set the value of numero_mandat
     *
     * @return  self
     */ 
    public function setNumero_mandat($numero_mandat)
    {
        $this->numero_mandat = $numero_mandat;

        return $this;
    }

    /**
     * Get the value of numero_engagement
     */ 
    public function getNumero_engagement()
    {
        return $this->numero_engagement;
    }

    /**
     * Set the value of numero_engagement
     *
     * @return  self
     */ 
    public function setNumero_engagement($numero_engagement)
    {
        $this->numero_engagement = $numero_engagement;

        return $this;
    }

    /**
     * Get the value of montant_ttc
     */ 
    public function getMontant_ttc()
    {
        return $this->montant_ttc;
    }

    /**
     * Set the value of montant_ttc
     *
     * @return  self
     */ 
    public function setMontant_ttc($montant_ttc)
    {
        $this->montant_ttc = $montant_ttc;

        return $this;
    }

    /**
     * Get the value of type_doc_id
     */ 
    public function getType_doc_id()
    {
        return $this->type_doc_id;
    }

    /**
     * Set the value of type_doc_id
     *
     * @return  self
     */ 
    public function setType_doc_id($type_doc_id)
    {
        $this->type_doc_id = $type_doc_id;

        return $this;
    }

    /**
     * Get the value of libellePeriode
     */ 
    public function getLibellePeriode()
    {
        return $this->libellePeriode;
    }

    /**
     * Set the value of libellePeriode
     *
     * @return  self
     */ 
    public function setLibellePeriode($libellePeriode)
    {
        $this->libellePeriode = $libellePeriode;

        return $this;
    }

    /**
     * Get the value of LibelleType
     */ 
    public function getLibelleType()
    {
        return $this->LibelleType;
    }

    /**
     * Set the value of LibelleType
     *
     * @return  self
     */ 
    public function setLibelleType($LibelleType)
    {
        $this->LibelleType = $LibelleType;

        return $this;
    }

    /**
     * Get the value of PeriodeId
     */ 
    public function getPeriodeId()
    {
        return $this->PeriodeId;
    }

    /**
     * Set the value of PeriodeId
     *
     * @return  self
     */ 
    public function setPeriodeId($PeriodeId)
    {
        $this->PeriodeId = $PeriodeId;

        return $this;
    }
}