<?php

namespace App\src\model;

class CopieurSite {

    private $Id;
    private $CopId;
    private $SiteId;
    private $DateArrivee;
    private $DateDepart;
    private $Plan;
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
     * Get the value of SiteId
     */ 
    public function getSiteId()
    {
        return $this->SiteId;
    }

    /**
     * Set the value of SiteId
     *
     * @return  self
     */ 
    public function setSiteId($SiteId)
    {
        $this->SiteId = $SiteId;

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
     * Get the value of Plan
     */ 
    public function getPlan()
    {
        return $this->Plan;
    }

    /**
     * Set the value of Plan
     *
     * @return  self
     */ 
    public function setPlan($Plan)
    {
        $this->Plan = $Plan;

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