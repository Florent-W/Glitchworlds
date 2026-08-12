<?php
require_once('BddCommunClass.php');

class BddArticleClass extends BddCommunClass {
        
    /**
     * selectionArticle
     *
     * @param  mixed $id_article
     * @return array
     */
    public function selectionArticle($id_article) {
        $reponse = $this->pdo->prepare('SELECT article.titre, article.description, article.contenu, article.nom_categorie, article.presentation, article.nom_miniature, article.url AS article_url, article.nom_banniere AS article_nom_banniere, utilisateurs.id AS utilisateurs_id, utilisateurs.pseudo, utilisateurs.nom_photo_profil, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, DATE_FORMAT(date_creation, "%d %M %Y à %Hh%imin%ss") AS date_article FROM article LEFT JOIN utilisateurs ON article.id_auteur = utilisateurs.id WHERE article.id = :id AND (article.approuver = "Approuver" OR article.approuver = "Brouillon")'); // Récupération de la news
        $reponse->bindValue('id', $id_article, PDO::PARAM_INT);
        $reponse->execute();
        $donnees = $reponse->fetch();

        return $donnees;
    }
    
    /**
     * selectionArticlePrecedent
     *
     * @param  mixed $id_article
     * @return array
     */
    public function selectionArticlePrecedent($id_article) {
        $reponse = $this->pdo->prepare('SELECT article.id, article.url, article.nom_miniature, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, article.titre FROM article LEFT JOIN utilisateurs ON article.id_auteur = utilisateurs.id WHERE article.id < :id AND article.approuver = "approuver" ORDER BY id DESC LIMIT 1'); // Récupération de la news précédente
        $reponse->bindValue('id', $id_article, PDO::PARAM_INT);
        $reponse->execute();
        $donnees = $reponse->fetch();

        return $donnees;
    }
    
    /**
     * selectionArticleSuivant
     *
     * @param  mixed $id_article
     * @return array
     */
    public function selectionArticleSuivant($id_article) {
        $reponse = $this->pdo->prepare('SELECT article.id, article.url, article.nom_miniature, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, article.titre FROM article LEFT JOIN utilisateurs ON article.id_auteur = utilisateurs.id WHERE article.id > :id AND article.approuver = "approuver" ORDER BY id ASC LIMIT 1'); // Récupération de la news suivante
        $reponse->bindValue('id', $id_article, PDO::PARAM_INT);
        $reponse->execute();
        $donnees = $reponse->fetch();

        return $donnees;
    }
}