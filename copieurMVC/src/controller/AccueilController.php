<?php

namespace App\src\controller;
use App\src\controller\Controller;
use App\src\model\View;
use App\config\request;
use App\src\DAOCopieur\copieurDAO;
require_once '../../annuaire/api.php';


class AccueilController extends Controller
{

    public function home()
    {

        $copieurs = $this->copieurDAO->getCopieurs();


        foreach ($copieurs as $leCopieur){
            
            $dateFin = date("Y-m-d", strtotime($leCopieur->getDateDebut().' + '.$leCopieur->getDuree().' year ' ));
            $leCopieur->setFinContrat($dateFin);
            
            //$ip = $leCopieur->getAdresseIp();
            //system('ping -c $ip '); // Ping IP address.
            //$ping =  exec("ping -n 1 $ip");
            //setAdresseIp($ping);

            $lib =[];
            $lib[] = API_LibelleComplet('site', $leCopieur->getidSite(), ' - ');
            $lib[] = API_Adresse($leCopieur->getidSite());
            $leCopieur->setidSite($lib);

            $lib2 =[];
            $lib2[] = API_LibelleCompletSansPremiersNiveaux('service', $leCopieur->getidService(), " - " );
            $leCopieur->setidService($lib2);
//            $leCopieur->set
//            exit();
//            $leCopieur->setleSite($lib."".API_Adresse($leCopieur->getidSite()));
//
//            $lib =  API_LibelleCompletSansPremiersNiveaux('service', $leCopieur->getidService());
//            $leCopieur->setleService($lib);

        }

        return $this->view->twig('index.html.twig', [
            'copieurs' => $copieurs,
            'nom' => $this->session->get("cop_nom"),
            'prenom' => $this->session->get("cop_prenom"),
            'role' => $this->session->get("cop_role")
        ]);

    }
}