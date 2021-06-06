<?php
header('Content-type: application/json');

include_once('connexion_base_donnee.php');

if ($_POST['categorieRecherche'] != "Articles" && $_POST['categorieRecherche'] != "Plateformes" && $_POST['categorieRecherche'] != "Genres") {
    $reponse = $bdd->prepare('SELECT jeu.nom, jeu.url, jeu.id, jeu.nom_miniature FROM jeu WHERE nom LIKE :recherche'); // Sélection des jeux et formatage de la date à partir de la page de jeu selectionnée
    $reponse->bindValue('recherche', '%' . $_POST['recherche'] . '%', PDO::PARAM_STR);
    $reponse->execute();
    $resultats = array(); // Création d'un tableau
    while ($donnees = $reponse->fetch()) {
        $resultats[] = array("value" => $donnees['nom'], "url" => $donnees['url'], "id" => $donnees['id'], "image" => $donnees['nom_miniature'], "category" => "Jeux");
    }
    $reponse->closeCursor();
}

if ($_POST['categorieRecherche'] != "Jeux" && $_POST['categorieRecherche'] != "Plateformes" && $_POST['categorieRecherche'] != "Genres") { // Si on est sur la page de création de news, on a besoin que des jeux
    $reponse = $bdd->prepare('SELECT article.titre, article.url, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, article.id, article.nom_miniature FROM article WHERE titre LIKE :recherche AND article.approuver = "Approuver"'); // Sélection des articles et formatage de la date à partir de la page de jeu selectionnée
    $reponse->bindValue('recherche', '%' . $_POST['recherche'] . '%', PDO::PARAM_STR);
    $reponse->execute();
    while ($donnees = $reponse->fetch()) {
        $resultats[] = array("value" => $donnees['titre'], "url" => $donnees['url'], "date" => $donnees['date_article_dossier'], "id" => $donnees['id'], "image" => $donnees['nom_miniature'], "category" => "Articles");
    }
    $reponse->closeCursor();
}

if ($_POST['categorieRecherche'] == "Plateformes") {
    $reponse = $bdd->prepare('SELECT plateformes.id, plateformes.nom_plateforme, plateformes.nom_image FROM plateformes WHERE nom_plateforme LIKE :recherche'); // Sélection des plateformes
    $reponse->bindValue('recherche', '%' . $_POST['recherche'] . '%', PDO::PARAM_STR);
    $reponse->execute();
    while ($donnees = $reponse->fetch()) {
        $resultats[] = array("value" => $donnees['nom_plateforme'], "id" => $donnees['id'], "image" => $donnees['nom_image'], "category" => "Plateformes");
    }
    $reponse->closeCursor();
}

if ($_POST['categorieRecherche'] == "Genres") {
    $reponse = $bdd->prepare('SELECT genres.id, genres.genre, genres.nom_image FROM genres WHERE genre LIKE :recherche'); // Sélection des genres
    $reponse->bindValue('recherche', '%' . $_POST['recherche'] . '%', PDO::PARAM_STR);
    $reponse->execute();
    while ($donnees = $reponse->fetch()) {
        $resultats[] = array("value" => $donnees['genre'], "id" => $donnees['id'], "image" => $donnees['nom_image'], "category" => "Genres");
    }
    $reponse->closeCursor();
}

echo json_encode($resultats); // On retourne les résultats
