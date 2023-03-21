<?php

namespace App\src\controller;

use App\src\controller\Controller;
use App\src\DAOCopieur\contactDAO;


require_once '../../annuaire/api.php';


class CopieurDetailController extends Controller
{

    public function detail()
    {
        $id_copieur = $_GET['id'];

//        $leCopieur = $this->copieurDAO->getLeCopieur($id_copieur);

        $InfoCopieur = $this->copieurDAO->getCopieur($id_copieur);

        //$modeles = $this->modeleDAO->getModele($id);

        $problemes = $this->problemeDAO->getProblemes($id_copieur);

        $documents = $this->documentDAO->getDocuments($id_copieur);
        $compteurs = $this->compteurDAO->getCompteurs($id_copieur);
        $sites = $this->copieur_siteDAO->getSite($id_copieur);
        $contrat = $this->contratDAO->getContrat($id_copieur);
        $contact = $this->contactDAO->getContact($id_copieur);
        $services = $this->copieur_serviceDAO->getService($id_copieur);
        $commandes = $this->commandeDAO->getCommandes($id_copieur);
        //$contacts = $this->contactDAO->getcontacts($id_copieur);

        foreach ($sites as $leSite){
            

            $lib =[];
            $lib[] = API_LibelleComplet('site', $leSite->getSiteId(), ' - ');
            $lib[] = API_Adresse($leSite->getSiteId());
            $leSite->setSiteId($lib);

        }

        foreach ($services as $leservice){
            
            $lib2 =[];
            $lib2[] = API_LibelleCompletSansPremiersNiveaux('service', $leservice->getServId(), ' - ');
            $leservice->setServId($lib2);

        }

        $dateFin = date("Y-m-d", strtotime($InfoCopieur->getDateDebut() . ' + ' . $InfoCopieur->getDuree() . ' year '));
        $InfoCopieur->setFinContrat($dateFin);

        $DepenseReel = [];
        foreach ($documents as $document){
            $typedoc = $document->getType_doc_id();
            $periode = $document->getPeriodeId();
            $annee = 1 + intdiv($periode, 4) ;
            if ($typedoc == 3) {
                if (!isset($DepenseReel[$annee])){
                    $DepenseReel[$annee] = 0;
        }
            $DepenseReel[$annee] += $document->getMontant_ttc();
        
        }
        var_dump($DepenseReel);
        
        $InfoCopieur->setDepenseReel($DepenseReel);
        }




        return $this->view->twig('CopieurDetails.html.twig', [
            'InfoCopieur' => $InfoCopieur,
            'contrat' => $contrat,
            'contact' => $contact,
            'problemes' => $problemes,
            'documents' => $documents,
            'compteurs' => $compteurs,
            'sites' => $sites,
            'commandes' => $commandes,
            'services' => $services
            

        ]);
    }
}