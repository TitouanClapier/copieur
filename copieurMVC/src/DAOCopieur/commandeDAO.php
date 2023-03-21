<?php

namespace App\src\DAOCopieur;

use App\config\Parameter;
use App\src\model\Commande;

class commandeDAO extends DAO
{         
    private function buildObject($row)
    {
        $commande = new Commande();
        $commande->setId($row['id']);
        $commande->setCopId($row['copieur_id']);
        $commande->setDateEnvoie($row['date_envoi']);
        $commande->setTonerNoir($row['toner_noir']);
        $commande->setTonerJaune($row['toner_jaune']);
        $commande->setTonerMagenta($row['toner_magenta']);
        $commande->setTonerCyan($row['toner_cyan']);
        $commande->setBacRecup($row['bac_recup']);
        $commande->setAgrafe($row['agrafe']);
        $commande->setCommentaires($row['commentaires']?? null);
        $commande->setCompteurNoir($row['compteur_noir']);
        $commande->setCompteurCouleur($row['compteur_couleur']);

        return $commande;
    }

    public function getCommandes($id) {
        $sql = "SELECT * FROM commande WHERE `copieur_id`=$id";
        $result = $this->createQuery($sql);
        $commandes = [];
        foreach ($result as $row){
            $commandeId = $row['id'];
            $commandes[$commandeId] = $this->buildObject($row);
        }

        $result->closeCursor();
        return $commandes  ;
    }


}