<?php


namespace App\src\controller;

use App\config\Parameter;
use App\config\Request;
use App\config\Session;
use App\config\View;
use App\src\DAOCopieur\copieurDAO;
use App\src\DAOCopieur\commandeDAO;
use App\src\DAOCopieur\compteurDAO;
use App\src\DAOCopieur\consommationDAO;
use App\src\DAOCopieur\contratDAO;
use App\src\DAOCopieur\contactDAO;
use App\src\DAOCopieur\copieur_serviceDAO;
use App\src\DAOCopieur\copieur_siteDAO;
use App\src\DAOCopieur\documentDAO;
use App\src\DAOCopieur\modeleDAO;
use App\src\DAOCopieur\problemeDAO;
use App\src\DAOCopieur\type_documentDAO;


/**
 * Class Controller abstraite, généralisation de  l'usage des controlleurs
 * @package App\src\controller
 */
abstract class Controller
{

    protected copieurDAO $copieurDAO;
    protected modeleDAO $modeleDAO;
    protected commandeDAO $commandeDAO;
    protected compteurDAO $compteurDAO;
    protected consommationDAO $consommationDAO;
    protected contratDAO $contratDAO;
    protected copieur_serviceDAO $copieur_serviceDAO;
    protected copieur_siteDAO $copieur_siteDAO;
    protected documentDAO $documentDAO;
    protected problemeDAO $problemeDAO;
    protected contactDAO $contactDAO;
    protected type_documentDAO $type_documentDAO;

    protected View $view;
    private Request $request;
    protected Parameter $get;
    protected Parameter $post;
    protected Session $session;
    protected $router;

    public function __construct()
    {
        global $router;
        $this->router = $router;
        $this->copieurDAO = new copieurDAO();
        $this->modeleDAO = new modeleDAO();
        $this->commandeDAO = new commandeDAO();
        $this->compteurDAO = new compteurDAO();
        $this->consommationDAO = new consommationDAO();
        $this->copieur_serviceDAO = new copieur_serviceDAO();
        $this->copieur_siteDAO = new copieur_siteDAO();
        $this->contratDAO = new contratDAO();
        $this->documentDAO = new documentDAO();
        $this->problemeDAO = new problemeDAO();
        $this->contactDAO = new contactDAO();
        $this->type_documentDAO = new type_documentDAO();



        $this->view = new View();
        $this->request = new Request();
        $this->get = $this->request->getGet();
        $this->post = $this->request->getPost();
        $this->session = $this->request->getSession();
    }
}