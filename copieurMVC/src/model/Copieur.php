<?php

namespace App\src\model;

class Copieur {

    private $id;

    private $matricule;

    private $modele;

    private $numero_ugap;

    private $numero_dossier;

    private $file_attente;

    private $date_achat;

    private $date_livraison;

    private $date_reforme;

    private $prix_achat_ttc;

    private $prix_achat_ht;

    private $a3;

    private $finisseur;

    private $couleur;

    private $logo;

    private $faxe;

    private $recto_verso;

    private $adresse_ip;

    private $commentaire;

    private $lecteur_badge;

    private $date_debut;

    private $Fin_Contrat;

    private $duree;

    private $idSite;

    private $idService;

    private $dateReleve;

    private $chemin;

    private $DepenseReel;


    /**
     * @return mixed
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * @param mixed $duree
     */
    public function setDuree($duree): self
    {
        $this->duree = $duree;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDateDebut()
    {
        return $this->date_debut;
    }

    /**
     * @param mixed $date_debut
     */
    public function setDateDebut($date_debut): self
    {
        $this->date_debut = $date_debut;
        return $this;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * @param mixed $matricule
     */
    public function setMatricule($matricule): self
    {
        $this->matricule = $matricule;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * @param mixed $modele
     */
    public function setModele($modele): self
    {
        $this->modele = $modele;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumeroUgap()
    {
        return $this->numero_ugap;
    }

    /**
     * @param mixed $numero_ugap
     */
    public function setNumeroUgap($numero_ugap): self
    {
        $this->numero_ugap = $numero_ugap;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumeroDossier()
    {
        return $this->numero_dossier;
    }

    /**
     * @param mixed $numero_dossier
     */
    public function setNumeroDossier($numero_dossier): self
    {
        $this->numero_dossier = $numero_dossier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileAttente()
    {
        return $this->file_attente;
    }

    /**
     * @param mixed $file_attente
     */
    public function setFileAttente($file_attente): self
    {
        $this->file_attente = $file_attente;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateAchat()
    {
        return $this->date_achat;
    }

    /**
     * @param mixed $date_achat
     */
    public function setDateAchat($date_achat): self
    {
        $this->date_achat = $date_achat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateLivraison()
    {
        return $this->date_livraison;
    }

    /**
     * @param mixed $date_livraison
     */
    public function setDateLivraison($date_livraison): self
    {
        $this->date_livraison = $date_livraison;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateReforme()
    {
        return $this->date_reforme;
    }

    /**
     * @param mixed $date_reforme
     */
    public function setDateReforme($date_reforme): self
    {
        $this->date_reforme = $date_reforme;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrixAchatTtc()
    {
        return $this->prix_achat_ttc;
    }

    /**
     * @param mixed $prix_achat_ttc
     */
    public function setPrixAchatTtc($prix_achat_ttc): self
    {
        $this->prix_achat_ttc = $prix_achat_ttc;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrixAchatHt()
    {
        return $this->prix_achat_ht;
    }

    /**
     * @param mixed $prix_achat_ht
     */
    public function setPrixAchatHt($prix_achat_ht): self
    {
        $this->prix_achat_ht = $prix_achat_ht;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getA3()
    {
        return $this->a3;
    }

    /**
     * @param mixed $a3
     */
    public function setA3($a3): self
    {
        $this->a3 = $a3;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFinisseur()
    {
        return $this->finisseur;
    }

    /**
     * @param mixed $finisseur
     */
    public function setFinisseur($finisseur): self
    {
        $this->finisseur = $finisseur;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * @param mixed $couleur
     */
    public function setCouleur($couleur): self
    {
        $this->couleur = $couleur;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo): self
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFaxe()
    {
        return $this->faxe;
    }

    /**
     * @param mixed $faxe
     */
    public function setFaxe($faxe): self
    {
        $this->faxe = $faxe;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRectoVerso()
    {
        return $this->recto_verso;
    }

    /**
     * @param mixed $recto_verso
     */
    public function setRectoVerso($recto_verso): self
    {
        $this->recto_verso = $recto_verso;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdresseIp()
    {
        return $this->adresse_ip;
    }

    /**
     * @param mixed $adresse_ip
     */
    public function setAdresseIp($adresse_ip): self
    {
        $this->adresse_ip = $adresse_ip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param mixed $commentaire
     */
    public function setCommentaire($commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLecteurBadge()
    {
        return $this->lecteur_badge;
    }

    /**
     * @param mixed $lecteur_badge
     */
    public function setLecteurBadge($lecteur_badge): self
    {
        $this->lecteur_badge = $lecteur_badge;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFinContrat()
    {
        return $this->Fin_Contrat;
    }

    /**
     * @param mixed $Fin_Contrat
     */ 
    public function setFinContrat($Fin_Contrat): self
    {
        $this->Fin_Contrat = $Fin_Contrat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getidSite()
    {
        return $this->idSite;
    }

    /**
     * @param mixed $idSite
     */ 
    public function setidSite($idSite): self
    {
        $this->idSite = $idSite;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getidService()
    {
        return $this->idService;
    }

    /**
     * @param mixed $idService
     */ 
    public function setidService($idService): self
    {
        $this->idService = $idService;
        return $this;
    }

 /**
     * @return mixed
     */
    public function getdateReleve()
    {
        return $this->dateReleve;
    }

    /**
     * @param mixed $dateReleve
     */ 
    public function setdateReleve($dateReleve): self
    {
        $this->dateReleve = $dateReleve;
        return $this;
    }

    
 /**
     * @return mixed
     */
    public function getchemin()
    {
        return $this->chemin;
    }

    /**
     * @param mixed $chemin
     */ 
    public function setchemin($chemin): self
    {
        $this->chemin = $chemin;
        return $this;
    }



    /**
     * Get the value of DepenseReel
     */ 
    public function getDepenseReel()
    {
        return $this->DepenseReel;
    }

    /**
     * Set the value of DepenseReel
     *
     * @return  self
     */ 
    public function setDepenseReel($DepenseReel)
    {
        $this->DepenseReel = $DepenseReel;

        return $this;
    }
}