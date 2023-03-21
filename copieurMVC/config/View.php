<?php

namespace App\config;

/**
 * Class View s'occupe de la gestion des vues
 * @package App\src\model
 */
#[\AllowDynamicProperties]
class View
{
    private string $file;
    private string $title;
    private Request $request;
    private Session $session;


    public function __construct()
    {
        $this->request = new Request();
        $this->session = $this->request->getSession();
    }

    public function twig($fichier, $data = [])
    {
        $data['debugTime'] =  round(1000 * (microtime(true) - DEBUG_TIME));
        $this->loader = new \Twig\Loader\FilesystemLoader('../templates');
        $this->twig = new \Twig\Environment($this->loader, ['debug' => true]);
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());

        echo $this->twig->render($fichier, $data);
    }

    /**
     * Création de la vue associée au template demandé,
     * données à afficher dans array data.
     *
     * @param $template string Nom du template à afficher dans la vue
     * @param array $data Données à afficher dans le template
     */





}