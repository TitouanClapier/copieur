<?php

namespace App\src\controller;
use App\src\controller\Controller;
use App\src\model\View;
use App\config\request;
use App\src\DAOCopieur\copieur_serviceDAO;
require_once '../../annuaire/api.php';


class serviceController extends Controller
{

    public function service()
    {

        $services = $this->copieur_serviceDAO->getNbCopieurService();

        foreach ($services as $leservice){
            
            $lib2 =[];
            $lib2[] = API_LibelleCompletSansPremiersNiveaux('service', $leservice->getServId(), ' - ');
            $leservice->setServId($lib2);

        }

        return $this->view->twig('listeservices.html.twig', [
            'services' => $services
        ]);

    }
}