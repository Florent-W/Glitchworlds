<?php

class BddConnexionClass
{
    private $serveur = 'mysql:host=db5000315646.hosting-data.io';
    private $db = 'dbname=dbs308204';
    private $user = 'dbu566316';
    private $mdp = '96pvBbDE8DUyw@N';
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO($this->serveur . ';' . $this->db, $this->user, $this->mdp);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec("SET lc_time_names = 'fr_FR'"); // Conversion date en français

            return true;
        } catch (PDOException $erreur) {
            echo "erreur de connexion : " . $erreur->getMessage() . "<br>";
            return false;
        }
    }

    /**
     * Méthode destructeur appelée dés qu'il n'y a plus de référence sur un
     * objet donné, ou dans n'importe quel ordre pendant la séquence d'arrèt.
     */
    public function __destruct()
    {
        $this->pdo = null;
    }

    /**
     * Fonction qui créer l'unique instance de la classe
     *
     * @return l'unique objet de la classe
     */
    public function getPdo()
    {
        if ($this->pdo == null) {
            $this->pdo = new BddConnexionClass();
        }

        return $this->pdo;
    }
}