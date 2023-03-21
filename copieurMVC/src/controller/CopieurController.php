<?php

namespace App\src\controller;
use App\src\controller\Controller;
use App\src\model\View;
use App\config\request;
use App\src\DAOCopieur\copieurDAO;
require_once '../../annuaire/api.php';


class CopieurController extends Controller
{

    public function fcopieur()
    {

        $copieurs = $this->copieurDAO->getCopieurs();



        foreach ($copieurs as $leCopieur){
            
            $dateFin = date("Y-m-d", strtotime($leCopieur->getDateDebut().' + '.$leCopieur->getDuree().' year ' ));
            $leCopieur->setFinContrat($dateFin);
            

            $lib =[];
            $lib[] = API_LibelleComplet('site', $leCopieur->getidSite(), ' - ');
            $lib[] = API_Adresse($leCopieur->getidSite());
            $leCopieur->setidSite($lib);


        }

        return $this->view->twig('listeCopieurs.html.twig', [
            'copieurs' => $copieurs
        ]);

    }
}