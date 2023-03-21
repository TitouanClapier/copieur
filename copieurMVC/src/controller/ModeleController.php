<?php

namespace App\src\controller;

use App\src\model\View;



class ModeleController extends Controller
{

    public function modele()
    {

        $modeles = $this->modeleDAO->getNbModeles();

        return $this->view->twig('listeModeles.html.twig', [
            'modeles' => $modeles
        ]);
    }

    public function modelecreate()
    {
        $this->modeleDAO->setModele($this->post->all());
        header("Location: /copieurMVC/public/listeModeles");
    }

    public function modeleUpdate()
    {
//        var_dump($this->post);
//        exit();
        $this->modeleDAO->updModele($this->post->all());
        header("Location: /copieurMVC/public/listeModeles");
    }

    public function modeleDelete()
    {
//        var_dump($this->post);
//        exit();
        $this->modeleDAO->delModele($this->post->all());
        header("Location: /copieurMVC/public/listeModeles");
    }
}
