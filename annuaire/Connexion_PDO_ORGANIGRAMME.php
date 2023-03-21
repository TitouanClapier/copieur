<?php
class Connexion_PDO_ORGANIGRAMME
{
    /**
     * Instance de la classe connexion    
     */
    private static $instance;
    private $dbh;

    /**
     * Lance la connexion à la base de donnée en le mettant dans un objet PDO qui est stocké dans la variable $dbh
     */
    private function __construct()
    {
        try
		{
            $this->dbh = new PDO('mysql:host=localhost;dbname=organigramme', 'root', 'a48b7z5', array(PDO::ATTR_PERSISTENT => false));
        }
        catch(PDOException $e)
		{
            echo "<div >Erreur !: ".$e->getMessage()."</div>";
            die();
        }
    }

    /**
     * Regarde si un objet connexion a déjà été instancié,
     * si c'est le cas alors il retourne l'objet déjà existant
     * sinon il en créer un autre.
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof self)
        {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Permet de récuprer l'objet PDO permettant de manipuler la base de donnée
     */
    public function getDbh()
    {
        return $this->dbh;
    }
}
?>