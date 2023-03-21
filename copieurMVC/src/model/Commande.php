<?php

namespace App\src\model;

class Commande {

    private $Id;
    private $CopId;
    private $DateEnvoie;
    private $TonerNoir;
    private $TonerJaune;
    private $TonerMagenta;
    private $TonerCyan;
    private $BacRecup;
    private $Agrafe;
    private $Commentaires;
    private $CompteurNoir;
    private $CompteurCouleur;


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
     * Get the value of DateEnvoie
     */ 
    public function getDateEnvoie()
    {
        return $this->DateEnvoie;
    }

    /**
     * Set the value of DateEnvoie
     *
     * @return  self
     */ 
    public function setDateEnvoie($DateEnvoie)
    {
        $this->DateEnvoie = $DateEnvoie;

        return $this;
    }

    /**
     * Get the value of TonerNoir
     */ 
    public function getTonerNoir()
    {
        return $this->TonerNoir;
    }

    /**
     * Set the value of TonerNoir
     *
     * @return  self
     */ 
    public function setTonerNoir($TonerNoir)
    {
        $this->TonerNoir = $TonerNoir;

        return $this;
    }

    /**
     * Get the value of TonerJaune
     */ 
    public function getTonerJaune()
    {
        return $this->TonerJaune;
    }

    /**
     * Set the value of TonerJaune
     *
     * @return  self
     */ 
    public function setTonerJaune($TonerJaune)
    {
        $this->TonerJaune = $TonerJaune;

        return $this;
    }

    /**
     * Get the value of TonerMagenta
     */ 
    public function getTonerMagenta()
    {
        return $this->TonerMagenta;
    }

    /**
     * Set the value of TonerMagenta
     *
     * @return  self
     */ 
    public function setTonerMagenta($TonerMagenta)
    {
        $this->TonerMagenta = $TonerMagenta;

        return $this;
    }

    /**
     * Get the value of TonerCyan
     */ 
    public function getTonerCyan()
    {
        return $this->TonerCyan;
    }

    /**
     * Set the value of TonerCyan
     *
     * @return  self
     */ 
    public function setTonerCyan($TonerCyan)
    {
        $this->TonerCyan = $TonerCyan;

        return $this;
    }

    /**
     * Get the value of BacRecup
     */ 
    public function getBacRecup()
    {
        return $this->BacRecup;
    }

    /**
     * Set the value of BacRecup
     *
     * @return  self
     */ 
    public function setBacRecup($BacRecup)
    {
        $this->BacRecup = $BacRecup;

        return $this;
    }

    /**
     * Get the value of Agrafe
     */ 
    public function getAgrafe()
    {
        return $this->Agrafe;
    }

    /**
     * Set the value of Agrafe
     *
     * @return  self
     */ 
    public function setAgrafe($Agrafe)
    {
        $this->Agrafe = $Agrafe;

        return $this;
    }

    /**
     * Get the value of Commentaires
     */ 
    public function getCommentaires()
    {
        return $this->Commentaires;
    }

    /**
     * Set the value of Commentaires
     *
     * @return  self
     */ 
    public function setCommentaires($Commentaires)
    {
        $this->Commentaires = $Commentaires;

        return $this;
    }

    /**
     * Get the value of CompteurNoir
     */ 
    public function getCompteurNoir()
    {
        return $this->CompteurNoir;
    }

    /**
     * Set the value of CompteurNoir
     *
     * @return  self
     */ 
    public function setCompteurNoir($CompteurNoir)
    {
        $this->CompteurNoir = $CompteurNoir;

        return $this;
    }

    /**
     * Get the value of CompteurCouleur
     */ 
    public function getCompteurCouleur()
    {
        return $this->CompteurCouleur;
    }

    /**
     * Set the value of CompteurCouleur
     *
     * @return  self
     */ 
    public function setCompteurCouleur($CompteurCouleur)
    {
        $this->CompteurCouleur = $CompteurCouleur;

        return $this;
    }
}