<?php
    try {
        // $bdd = new PDO('mysql:host=db5000567038.hosting-data.io;dbname=dbs544301;charset:utf8', 'dbu416705', 'tRpYNa24Vq.7JWu'); // Connexion à la base de données
        $bdd = new PDO('mysql:host=localhost:3309;dbname=portfolio;charset:utf8', 'root', ''); // Connexion à la base de données
        $reponse = $bdd->prepare("SET lc_time_names = 'fr_FR'"); // Conversion date en français
        $reponse->execute();
        $reponse->fetch();
        $reponse->closeCursor();
    } catch (Exception $erreur) {
        die('erreur : ' . $erreur->getMessage());
    }
    ?>