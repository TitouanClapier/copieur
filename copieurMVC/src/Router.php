<?php

namespace App\src;

// use App\Security\ForbiddenException;

use App\config\Request;
use App\src\controller\BackController;
use App\src\controller\ErrorController;
use App\src\controller\FrontController;
use mysql_xdevapi\Exception;

class Router
{
    /**
     * @var string
     */
    private $viewPath;

    /**
     * @var \AltoRouter
     */
    private $router;

    private $layout;

    // NEW
//    private FrontController $frontController;
//    private ErrorController $errorController;
//    private BackController  $backController;
    //private Request $request;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new \AltoRouter();
        //TODO setBasePath mettre une constante de config ou trouver fonction qui va chercher cette emplacement
        $this->router->setBasePath('/copieurMVC/public');

        // NEW
//        $this->request = new Request();
//        $this->frontController = new FrontController();
//        $this->errorController = new ErrorController();
//        $this->backController = new BackController();
    }

    public function get(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('GET', $url, $view, $name);
        return $this;
    }


    public function post(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('POST', $url, $view, $name);
        return $this;
    }

    public function match(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('POST|GET', $url, $view, $name);
        return $this;
    }


    public function url(string $name, array $params = [])
    {
        return $this->router->generate($name, $params);
    }

    public function run()
    {
        // Recupere les infos de la route
        $match = $this->router->match();
        // Si la route demander n'existe pas on affiche une erreur 404
        if ($match === false) {

            $obj = new ErrorController();
            $obj->errorNotFound();


            exit();
        }
        $params = $match['params'];
        // On recupere le nom du controlleur et la fonction
        list($controller, $action) = explode('#', $match['target']);
        $controller = "App\src\controller\\" . $controller;
        $obj = new $controller();
        $obj->$action($params);
    }
}