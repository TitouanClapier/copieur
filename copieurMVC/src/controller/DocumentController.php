<?php

namespace App\src\controller;

//use App\src\controller\Controller;
//use App\src\model\View;
//use App\config\request;
//use App\src\DAOCopieur\documentDAO;
//use App\src\DAOCopieur\type_documentDAO;
//require_once '..\annuaire/api.php';


class DocumentController extends Controller
{

    public function document()
    {
        $types = $this->type_documentDAO->getTypeDocuments();
        return $this->view->twig('listeDocuments.html.twig', [
            'types' => $types
        ]);
    
}

public function documentread()
{
    $documents = $this->documentDAO->getDocumentsByType($_GET['annee']);
    $types = $this->type_documentDAO->getTypeDocuments();
    return $this->view->twig('listeDocuments.html.twig', [
        'documents' => $documents,
        'types' => $types
    ]);
}
}
