<?php

namespace App\src\controller;
use App\src\controller\Controller;
use App\src\model\View;
use App\config\request;
use App\src\DAOCopieur\copieur_siteDAO;
require_once '../../annuaire/api.php';


class SiteController extends Controller
{

    public function site()
    {

        $sites = $this->copieur_siteDAO->getNbCopieurSite();

        foreach ($sites as $leSite){
            

            $lib =[];
            $lib[] = API_LibelleComplet('site', $leSite->getSiteId(), ' - ');
            $lib[] = API_Adresse($leSite->getSiteId());
            $leSite->setSiteId($lib);

        }

        return $this->view->twig('listeSites.html.twig', [
            'sites' => $sites
        ]);

    }
}