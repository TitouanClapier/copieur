<?php

//Constantes de configuration, non chargé par Composer car ne contient pas de classes
require '../config/dev.php';

//Centralisation de l'appel à l'autoloader
require '../vendor/autoload.php';

define('DEBUG_TIME', microtime(true));

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

// Demarrage de la session utilisateur
session_start();

$router = new App\src\Router(dirname(__DIR__) . '/templates');
$router
    ->get('/'                   , 'AccueilController#home'                  , 'home')
    ->get('/ajout_copieur'      , 'CopieurAjoutController#ajoutcopieur'     , 'ajout_copieur')
    ->get('/listecopieurs'      , 'CopieurController#fcopieur'              , 'listeCopieurs')
    ->get('/listeSites'         , 'SiteController#site'                     , 'site')
    ->get('/listeServices'      , 'ServiceController#service'               , 'listeservice')
    ->get('/listeModeles'       , 'ModeleController#modele'                 , 'modele')
    ->get('/listeTypes'         , 'TypeController#type'                     , 'type')
    ->get('/listeDocuments'     , 'DocumentController#document'             , 'document')
    ->get('/connexion'          , 'ConnexionController#affichage'           , 'connexion')
    ->get('/connexion/logout'          , 'ConnexionController#logout'       , 'logout')
    ->get('/copieurdetail'      , 'CopieurDetailController#detail'          , 'detail')
    ->get('/contact'            , 'ContactController#contact'               , 'contact')
    ->get('/contrat'            , 'ContratController#contrat'               , 'contrat')

    ->post('/listeModeles/create'          , 'ModeleController#modelecreate'           , 'modeleCreate')
    ->post('/listeModeles/update'          , 'ModeleController#modeleUpdate'           , 'modeleUpdate')
    ->post('/listeModeles/delete'          , 'ModeleController#modeleDelete'           , 'modeleDelete')

    ->post('/listeTypes/create'          , 'TypeController#typecreate'           , 'typeCreate')
    ->post('/listeTypes/update'          , 'TypeController#typeUpdate'           , 'typeUpdate')
    ->post('/listeTypes/delete'          , 'TypeController#typeDelete'           , 'typeDelete')

    ->post('/ajout_copieur/create'          , 'CopieurAjoutController#copieurcreate'           , 'copieurCreate')

    ->post('/listeDocuments'          , 'DocumentController#documentread'           , 'documentread')

    ->post('/connexion/login'          , 'ConnexionController#login'           , 'login')
//    ->match('/NomChemin','NomController#NomFonction','NomRoute')

    ->run();