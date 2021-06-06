<?php
header('Content-type: application/json');

include_once('connexion_base_donnee.php');

/*
$reponse = $bdd->prepare('SELECT jeu.nom, jeu.url, jeu.id, jeu.nom_miniature FROM jeu WHERE nom LIKE :recherche'); // Sélection des jeux et formatage de la date à partir de la page de jeu selectionnée
$reponse->bindValue('recherche', '%' . $_POST['recherche'] . '%', PDO::PARAM_STR);
$reponse->execute();
$resultats = array(); // Création d'un tableau
while ($donnees = $reponse->fetch()) {
    $resultats[] = array("value" => $donnees['nom'], "url" => $donnees['url'], "id" => $donnees['id'], "image" => $donnees['nom_miniature'], "category" => "Jeux");
}
$reponse->closeCursor();
*/
if($_POST['categorieRecherche'] != "Jeux") { // Si on est sur la page de création de news, on a besoin que des jeux
$reponse = $bdd->prepare('SELECT article.titre, article.url, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, article.id, article.nom_miniature FROM article WHERE titre LIKE :recherche AND article.approuver = 1'); // Sélection des jeux et formatage de la date à partir de la page de jeu selectionnée
$reponse->bindValue('recherche', '%' . $_POST['recherche'] . '%', PDO::PARAM_STR);
$reponse->execute();
while ($donnees = $reponse->fetch()) {
    $resultats[] = array("value" => $donnees['titre'], "url" => $donnees['url'], "date" => $donnees['date_article_dossier'], "id" => $donnees['id'], "image" => $donnees['nom_miniature'], "category" => "Articles");
}
$reponse->closeCursor();
}

echo json_encode($resultats); // On retourne les résultats
