<?php

namespace App\src\controller;
use App\src\controller\Controller;
use App\src\model\View;
use App\config\request;
use App\src\DAOCopieur\type_documentDAO;
//require_once '..\annuaire/api.php';


class TypeController extends Controller
{

    public function type()
    {

        $types = $this->type_documentDAO->getTypeDocuments();

        return $this->view->twig('listeTypes.html.twig', [
            'types' => $types
        ]);

    }

    public function typecreate()
    {
       $this->type_documentDAO->createTypeDocuments($this->post->all());
       header("Location: /copieurMVC/public/listeTypes");
    }

    public function typeUpdate()
    {
//        var_dump($this->post);
//        exit();
        $this->type_documentDAO->updateTypeDocuments($this->post->all());
        header("Location: /copieurMVC/public/listeTypes");
    }

    public function typeDelete()
    {
//        var_dump($this->post);
//        exit();
        $this->type_documentDAO->deleteTypeDocuments($this->post->all());
        header("Location: /copieurMVC/public/listeTypes");
    }
}