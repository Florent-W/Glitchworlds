<?php
try {
    $bdd = new PDO('mysql:host=db5000315646.hosting-data.io;dbname=dbs308204;charset:utf8', 'dbu566316', '96pvBbDE8DUyw@N'); // Connexion à la base de données
    // $bdd = new PDO('mysql:host=localhost:3306;dbname=glitchworld;charset:utf8', 'root', ''); // Connexion à la base de données
    $reponse = $bdd->prepare("SET lc_time_names = 'fr_FR'"); // Conversion date en français
    $reponse->execute();
    $reponse->fetch();
    $reponse = $bdd->prepare("SET NAMES UTF8"); // Conversion date en français
    $reponse->execute();
    $reponse->closeCursor();
} catch (Exception $erreur) {
    die('erreur : ' . $erreur->getMessage());
}
