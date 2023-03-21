<?php


namespace App\src\DAOCopieur;

use PDO;
use Exception;

abstract class DAO
{

    private $connection;

    //Vérifie si la connexion est nulle et fait appel à getConnection()
    private function checkConnection()
    {
        //Vérifie si la connexion est nulle et fait appel à getConnection()
        if($this->connection === null) {
            return $this->getConnection();
        }
        //Si la connexion existe, elle est renvoyée, inutile de refaire une connexion
        return $this->connection;
    }

    //Méthode de connexion à la bdd
    private function getConnection()
    {

        try{
            $this->connection = new PDO(DB_HOST_1, DB_USER, DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->connection;

        }
        catch(Exception $errorConnection)
        {
            die ('Erreur de connection :'.$errorConnection->getMessage());
        }

    }

    protected function createQuery($sql, $parameters = null)
    {
        if($parameters)
        {
            $result = $this->checkConnection()->prepare($sql);
            $result->execute($parameters);
            return $result;
        }

        $result = $this->checkConnection()->query($sql);
        return $result;
    }

    protected function insertQueryLastId($sql, $parameters = null)
    {
        if($parameters)
        {
            $result = $this->checkConnection()->prepare($sql);
            $result->execute($parameters);
            $result->closeCursor();
            return $this->connection->lastInsertId();
        }

        $result = $this->checkConnection()->query($sql);
        $result->closeCursor();
        return $this->connection->lastInsertId();
    }

/*
    private function checkConnectionCoriolis()
    {
        //Vérifie si la connexion est nulle et fait appel à getConnection()
        if($this->connectionCoriolis === null) {
            return $this->getConnectionCoriolis();
        }
        //Si la connexion existe, elle est renvoyée, inutile de refaire une connexion
        return $this->connectionCoriolis;
    }

    private function getConnectionCoriolis()
    {
        //Tentative de connexion à la base de données
        try{
            $this->connectionCoriolis = new PDO('oci:dbname=CORPROD','coriolis','coriolis',$pdo_options);
            $this->connectionCoriolis->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->connectionCoriolis;
            //On renvoie un message avec le mot-clé return

        }
            //On lève une erreur si la connexion échoue
        catch(Exception $errorConnection)
        {
            return die ('Erreur de connection :'.$errorConnection->getMessage());
        }

    }

    protected function createQueryCoriolis($sql, $parameters = null)
    {
        if($parameters)
        {
            $result = $this->checkConnectionCoriolis()->prepare($sql);
            $result->execute($parameters);
            return $result;
        }
        $result = $this->checkConnectionCoriolis()->query($sql);
        return $result;
    }


*/







}