<?php

namespace App\src\DAOCopieur;

use App\config\Parameter;
use App\src\model\Copieur;

class copieurDAO extends DAO
{
    private function buildObject($row)
    {
        $copieur = new Copieur();
        $copieur->setId($row['id']);
        $copieur->setMatricule($row['matricule']);
        $copieur->setModele($row['libelModele']);
        $copieur->setNumeroUgap($row['numero_ugap'] ?? null);
        $copieur->setNumeroDossier($row['numero_dossier']);
        $copieur->setFileAttente($row['file_attente']);
        $copieur->setDateAchat($row['date_achat']);
        $copieur->setDateLivraison($row['date_livraison'] ?? null);
        $copieur->setDateReforme($row['date_reforme'] ?? null);
        $copieur->setPrixAchatTtc($row['prix_achat_ttc'] ?? null);
        $copieur->setPrixAchatHt($row['prix_achat_ht'] ?? null);
        $copieur->setA3($row['a3'] ?? null);
        $copieur->setFinisseur($row['finisseur'] ?? null);
        $copieur->setCouleur($row['couleur'] ?? null);
        $copieur->setLogo($row['logo'] ?? null);
        $copieur->setFaxe($row['faxe'] ?? null);
        $copieur->setRectoVerso($row['recto_verso'] ?? null);
        $copieur->setAdresseIp($row['adresse_ip'] ?? null);
        $copieur->setCommentaire($row['commentaire'] ?? null);
        $copieur->setLecteurBadge($row['lecteur_badge'] ?? null);
        $copieur->setDateDebut($row['date_debut'] ?? null);
        $copieur->setDuree($row['duree'] ?? null);
        $copieur->setidService($row['idService'] ?? null);
        $copieur->setidSite($row['idSite'] ?? null);
        $copieur->setdateReleve($row['dateReleve'] ?? null);
        $copieur->setchemin($row['chemin'] ?? null);
        $copieur->setDepenseReel(null);
        //$copieur->setchemin($row['Fin_Contrat']?? null);

        return $copieur;
    }

    public function getCopieurs()
    {
        $sql = 'SELECT copieur.id , matricule, numero_dossier, adresse_ip, modele_id, file_attente, service_id as idService, copieur_site.site_id as idSite, date_achat, modele.libelle as libelModele, chemin, date_debut, duree, max(date_releve) as dateReleve,  site.libelle as libelSite, service.libelle as libelService, adr1, adr2, cp, ville, chemin, fax, lecteur_badge, a3, couleur, recto_verso, logo, finisseur FROM copieur 
        left JOIN modele on modele.id  = modele_id
        left JOIN copieur_site on copieur.id  = copieur_id
        left JOIN copieur_service on  copieur_service.copieur_id = copieur.id 
        left JOIN organigramme.site on  copieur_site.site_id = site.id 
        left JOIN organigramme.service on  copieur_service.service_id = service.id
        left JOIN plan_site on plan_site.site_id  = copieur_site.site_id 
        left JOIN contrat on contrat.copieur_id  = copieur.id 
        left JOIN compteur on compteur.copieur_id  = copieur.id 
        WHERE copieur_service.date_depart IS NULL
        AND copieur_site.date_depart IS NULL
        AND date_reforme IS NULL
        group by copieur.id';
        $result = $this->createQuery($sql);
        $copieurs = [];
        foreach ($result as $row) {
            $copieurId = $row['id'];
            $copieurs[$copieurId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $copieurs;
    }



    ///////////////////////////////////////////////////////////////////////////////////////

    public function getCopieur(int $id)
    {
        $sql = "SELECT copieur.* , matricule, numero_dossier, adresse_ip, modele_id, file_attente, service_id as idService, copieur_site.site_id as idSite,
        date_achat, modele.libelle as libelModele, chemin, date_debut,
        duree, max(date_releve) as dateReleve FROM copieur 
        left JOIN modele on modele.id  = modele_id
        left JOIN copieur_site on copieur.id  = copieur_id
        left JOIN copieur_service on  copieur_service.copieur_id = copieur.id 
        left JOIN organigramme.site on  copieur_site.site_id = site.id 
        left JOIN organigramme.service on  copieur_service.service_id = service.id
        left JOIN plan_site on plan_site.site_id  = copieur_site.site_id 
        left JOIN contrat on contrat.copieur_id  = copieur.id 
        left JOIN compteur on compteur.copieur_id  = copieur.id 
        WHERE copieur_service.date_depart IS NULL
        AND copieur_site.date_depart IS NULL
        AND date_reforme IS NULL
        AND copieur.id = $id
        group by copieur.id";
        $result = $this->createQuery($sql);
        $copieur = $result->fetch();
        $result->closeCursor();
        return $this->buildObject($copieur);
    }

        ///////////////////////////////////////////////////////////////////////////////////////

        public function getLeCopieur(int $id)
        {
            $sql = "SELECT * FROM copieur 
            WHERE  copieur.id = $id ";
            $result = $this->createQuery($sql);
            $copieur = $result->fetch();
            $result->closeCursor();
            return $copieur;
        }

    ///////////////////////////////////////////////////////////////////////////////////////

    public function copieurcreate($post)
    {
        if ("$post[adresse_ip1]" and "$post[adresse_ip1]" and "$post[adresse_ip1]" and "$post[adresse_ip1]"){
            $adresseip = "$post[adresse_ip1].$post[adresse_ip2].$post[adresse_ip3].$post[adresse_ip4]";}
        $sql = "INSERT INTO copieur 
            ( modele_id, matricule, numero_dossier, prix_achat_ht, prix_achat_ttc, numero_ugap, date_achat,
            date_livraison, recto_verso, a3, fax, adresse_ip, finisseur, couleur, logo, lecteur_badge, commentaire ) 
            VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? );";
        $lastInsert = $this->insertQueryLastId(
            $sql,
            [
                $post['listemodele'],
                $post['matricule'],
                $post['numero_dossier'],
                $post['prix_achat_ht'],
                $post['prix_achat_ttc'],
                $post['numero_ugap'],
                $post['date_achat'],
                $post['date_livraison'],
                $post['recto_verso'],
                $post['a3'],
                $post['fax'],
                $adresseip??Null,
                $post['finisseur']??Null,
                $post['couleur'],
                $post['logo'],
                $post['lecteur_badge'],
                $post['commentaire']??Null
            ]
        );
        

        

        $sql = "INSERT INTO copieur_site ( site_id, copieur_id, date_arrivee ) 
            VALUES ( ?, ?, ?);";
        $result = $this->createQuery(
            $sql,
            [
                $post['choixSiteId'],
                $lastInsert,
                $post['date_livraison']

            ]
        );
        $result->closeCursor();

        $sql = "INSERT INTO copieur_service ( service_id, copieur_id, date_arrivee) 
    VALUES ( ?, ?, ?);";
        $result = $this->createQuery(
            $sql,
            [
                $post['choixServiceId'],
                $lastInsert,
                $post['date_livraison']

            ]
        );
        $result->closeCursor();

        $sql = "INSERT INTO contrat (copieur_id, numero, duree, cout_cop_noir, cout_cop_coul, cout_cop_logo, date_debut, prix_trim_maintenance, nb_trim_cop_noir, nb_trim_cop_coul,  nb_trim_cop_logo ) 
VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

        $result = $this->createQuery(
            $sql,
            [
                $lastInsert,
                $post['numero'],
                $post['duree'],
                $post['cout_cop_noir'],
                $post['cout_cop_coul'],
                $post['cout_cop_logo'],
                $post['date_debut'],
                $post['prix_trim_maintenance'],
                $post['nb_trim_cop_noir'],
                $post['nb_trim_cop_coul'],
                $post['nb_trim_cop_logo']
            
            ]
        );
        $result->closeCursor();
    }
}
