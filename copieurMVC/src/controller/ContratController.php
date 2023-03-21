<?php

namespace App\src\controller;
use App\src\controller\Controller;
use App\src\model\View;
use App\config\request;
use App\src\DAOCopieur\contratDAO;



require_once '../../annuaire/api.php';


class ContratController extends Controller
{

    public function contrat()
    {
        
        $id_copieur = 1;
        $this->contratDAO->getContrat($id_copieur);

    }
}