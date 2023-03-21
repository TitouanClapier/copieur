<?php

namespace App\src\controller;
use App\src\controller\Controller;
//use App\src\model\View;
//use App\config\request;
//use App\src\DAOCopieur\copieurDAO;
//require_once '..\annuaire/api.php';


class CopieurAjoutController extends Controller
{

    public function ajoutcopieur()
    {

        $copieurs = $this->copieurDAO->getCopieurs();

        $modeles = $this->modeleDAO->getNbModeles();


        return $this->view->twig('ajoutCopieur.html.twig', [
            'copieurs' => $copieurs,
            'modeles' => $modeles
        ]); 

    }

    public function copieurcreate()
    {
        $this->copieurDAO->copieurcreate($this->post->all());
        
        header("Location: /copieurMVC/public/");
    }
}