<?php
require_once('BddConnexionClass.php');

class BddCommunClass
{
    private $pdo;

    public function __construct()
    {
        $bddConnexion = new BddConnexionClass();
        $this->pdo = $bddConnexion->getPdo();
    }
    
    /***
     * @return mixed
     */
    public function selectionCarousel() {
        $reponse = $this->pdo->prepare('SELECT * FROM carousel ORDER BY page');
        $reponse->execute();

        $donnees = $reponse->fetchAll();

        return $donnees;
    }

    /***
     * @return mixed
     */
    public function selectionNewsCarousel() {
        $reponse = $this->pdo->prepare('SELECT article.id, article.titre, article.url, article.contenu, article.nom_banniere, carousel.page, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier FROM article INNER JOIN carousel ON article.id = carousel.id_news ORDER BY page');
        $reponse->execute();

        $donnees = $reponse->fetchAll();

        return $donnees;
    }

    /***
     * @return mixed
     */
    public function selectionJeuxApprouver() {
        $reponse = $this->pdo->prepare('SELECT jeu.*, DATE_FORMAT(date_sortie, "%d %M %Y") AS date_jeu FROM jeu INNER JOIN categorie_jeu ON jeu.id_categorie = categorie_jeu.id WHERE jeu.approuver = "approuver" ORDER BY id DESC LIMIT 15');
        $reponse->execute();

        $donnees = $reponse->fetchAll();

        return $donnees;
    }

    /***
     * @return mixed
     */
    public function selectionCountArticleApprouverCategorie($selection_article_approuver) {
        $reponse = $this->pdo->prepare('SELECT COUNT(*) as nb_article FROM article WHERE approuver = :approuver AND nom_categorie = :nom_categorie');
        $reponse->bindValue('approuver', $selection_article_approuver, PDO::PARAM_STR);
        $reponse->bindValue('nom_categorie', $_GET['nom_categorie'], PDO::PARAM_STR);
        $reponse->execute();
        $donnees = $reponse->fetch();

        $nbNews = $donnees['nb_article']; // Nombre de news

        return $nbNews;
    }

    /***
     * @param $selection_article_approuver
     * @param $offsetPageNews
     * @return mixed
     */
    public function selectionArticleApprouverCategorie($selection_article_approuver, $offsetPageNews) {
        $reponse = $this->pdo->prepare('SELECT article.id, article.url, article.nom_categorie, article.nom_miniature, article.contenu, article.titre, article.description, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, DATE_FORMAT(date_creation, "%d %M %Y") AS date_article FROM article WHERE approuver = :approuver AND nom_categorie = :nom_categorie ORDER BY id DESC LIMIT 20 OFFSET :offsetPageNews'); // Sélection des news et formatage de la date à partir de la page de news selectionnée
        $reponse->bindValue('approuver', $selection_article_approuver, PDO::PARAM_STR);
        $reponse->bindValue('offsetPageNews', $offsetPageNews, PDO::PARAM_INT);
        $reponse->bindValue('nom_categorie', $_GET['nom_categorie'], PDO::PARAM_STR);
        $reponse->execute();
        $donnees = $reponse->fetchAll();

        return $donnees;
    }

    /***
     * @return mixed
     */
    public function selectionCountArticleBrouillon($selection_article_approuver) {
        $reponse = $this->pdo->prepare('SELECT COUNT(*) as nb_article FROM article JOIN utilisateurs ON article.id_auteur = utilisateurs.id WHERE approuver = :approuver');
        $reponse->bindValue('approuver', $selection_article_approuver, PDO::PARAM_STR);
        $reponse->execute();
        $donnees = $reponse->fetch();

        $nbNews = $donnees['nb_article']; // Nombre de news

        return $nbNews;
    }

    /***
     * @param $selection_article_approuver
     * @param $offsetPageNews
     * @return mixed
     */
    public function selectionArticleBrouillon($selection_article_approuver, $offsetPageNews) {
        $reponse = $this->pdo->prepare('SELECT article.id, article.url, article.nom_categorie, article.nom_miniature, article.contenu, article.titre, article.description, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, DATE_FORMAT(date_creation, "%d %M %Y") AS date_article FROM article WHERE approuver = :approuver AND nom_categorie = :nom_categorie ORDER BY id DESC LIMIT 20 OFFSET :offsetPageNews'); // Sélection des news et formatage de la date à partir de la page de news selectionnée
        $reponse->bindValue('approuver', $selection_article_approuver, PDO::PARAM_STR);
        $reponse->bindValue('offsetPageNews', $offsetPageNews, PDO::PARAM_INT);
        $reponse->execute();
        $donnees = $reponse->fetchAll();

        return $donnees;
    }

    /***
     * @return mixed
     */
    public function selectionCountArticleAutre($selection_article_approuver) {
        $reponse = $this->pdo->prepare('SELECT COUNT(*) as nb_article FROM article WHERE approuver = :approuver');
        $reponse->bindValue('approuver', $selection_article_approuver, PDO::PARAM_STR);
        $reponse->execute();
        $donnees = $reponse->fetch();

        $nbNews = $donnees['nb_article']; // Nombre de news

        return $nbNews;
    }

    /***
     * @param $selection_article_approuver
     * @param $offsetPageNews
     * @return mixed
     */
    public function selectionArticleAutre($selection_article_approuver, $offsetPageNews) {
        $reponse = $this->pdo->prepare('SELECT article.id, article.url, article.nom_categorie, article.nom_miniature, article.contenu, article.titre, article.description, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, DATE_FORMAT(date_creation, "%d %M %Y") AS date_article FROM article WHERE approuver = :approuver ORDER BY id DESC LIMIT 20 OFFSET :offsetPageNews'); // Sélection des news et formatage de la date à partir de la page de news selectionnée
        $reponse->bindValue('approuver', $selection_article_approuver, PDO::PARAM_STR);
        $reponse->bindValue('offsetPageNews', $offsetPageNews, PDO::PARAM_INT);
        $reponse->execute();
        $donnees = $reponse->fetchAll();

        return $donnees;
    }

    /***
     * @param $id_news
     * @return mixed
     */
    public function selectionCountCommentaireArtcle($id_news) {
        $reponse = $this->pdo->prepare('SELECT COUNT(commentaire.id) as nb_com FROM commentaire WHERE id_news = :id');
        $reponse->execute(array('id' => $id_news));
        $donnees = $reponse->fetchAll();

        return $donnees;
    }

    /***
     * @param $id_news
     * @return mixed
     */
    public function selectionJeuxLierArtcle($id_news) {
        $reponse = $this->pdo->prepare('SELECT jeu.nom AS jeu_lier FROM jeu INNER JOIN article_lier_jeu ON jeu.id = article_lier_jeu.id_jeu WHERE article_lier_jeu.id_article = :id_article'); // On cherche le nom des jeux lié à l'article
        $reponse->execute(array('id' => $id_news));
        $donnees = $reponse->fetchAll();

        return $donnees;
    }

}