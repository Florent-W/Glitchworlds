<?php
require_once('BddConnexionClass.php');

class BddCommunClass
{
    protected $pdo;

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
       * Sélection des derniers commentaires sur les articles et les jeux
     * @return mixed
     */
    public function selectionDerniersCommentaire() {
        $reponse = $this->pdo->prepare('SELECT *
        FROM (
            SELECT "commentaire" as source, commentaire.contenu, commentaire.date_commentaire AS date_commentaire, DATE_FORMAT(commentaire.date_commentaire, "%d %M %Y à %Hh%imin%ss") AS date_commentaire_message, utilisateurs.pseudo, article.id as article_id, article.url as article_url, article.titre as article_titre, article.nom_miniature, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") as date_article_dossier FROM commentaire INNER JOIN article ON commentaire.id_news = article.id INNER JOIN utilisateurs ON commentaire.id_utilisateur = utilisateurs.id WHERE article.approuver = "Approuver"
            UNION
            SELECT "commentaire_jeu" as source, commentaire_jeu.contenu, commentaire_jeu.date_commentaire AS date_commentaire, DATE_FORMAT(commentaire_jeu.date_commentaire, "%d %M %Y à %Hh%imin%ss") AS date_commentaire_message, utilisateurs.pseudo, jeu.id as jeu_id, jeu.url as jeu_url, jeu.nom as jeu_nom, jeu.nom_miniature, DATE_FORMAT(date_sortie, "%Y/%M/%d/%kh%i") as date_sortie FROM commentaire_jeu INNER JOIN jeu ON commentaire_jeu.id_jeu = jeu.id INNER JOIN utilisateurs ON commentaire_jeu.id_utilisateur = utilisateurs.id WHERE jeu.approuver = "approuver"
        ) AS commentaires
        ORDER BY date_commentaire DESC        
        LIMIT 15');
        $reponse->execute();

        $donnees = $reponse->fetchAll();

        return $donnees;
    }

    /***
     * @return mixed
     */
    public function selectionCountArticleApprouverCategorie($selection_article_approuver, $nom_categorie) {
        $reponse = $this->pdo->prepare('SELECT COUNT(*) as nb_article FROM article WHERE approuver = :approuver AND nom_categorie = :nom_categorie');
        $reponse->bindValue('approuver', $selection_article_approuver, PDO::PARAM_STR);
        $reponse->bindValue('nom_categorie', $nom_categorie, PDO::PARAM_STR);
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
    public function selectionArticleApprouverCategorie($selection_article_approuver, $offsetPageNews, $nomCategorie) {
        $reponse = $this->pdo->prepare('SELECT article.id, article.url, article.nom_categorie, article.nom_miniature, article.contenu, article.titre, article.description, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, DATE_FORMAT(date_creation, "%d %M %Y") AS date_article FROM article WHERE approuver = :approuver AND nom_categorie = :nom_categorie ORDER BY id DESC LIMIT 20 OFFSET :offsetPageNews'); // Sélection des news et formatage de la date à partir de la page de news selectionnée
        $reponse->bindValue('approuver', $selection_article_approuver, PDO::PARAM_STR);
        $reponse->bindValue('offsetPageNews', $offsetPageNews, PDO::PARAM_INT);
        $reponse->bindValue('nom_categorie', $nomCategorie, PDO::PARAM_STR);
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
        $reponse = $this->pdo->prepare('SELECT jeu.nom AS jeu_lier, jeu.nom_banniere, jeu.url, jeu.id FROM jeu INNER JOIN article_lier_jeu ON jeu.id = article_lier_jeu.id_jeu WHERE article_lier_jeu.id_article = :id_article'); // On cherche les jeux lié à l'article
        $reponse->bindValue('id_article', $id_news, PDO::PARAM_INT);
        $reponse->execute();
        $donnees = $reponse->fetchAll();

        return $donnees;
    }
    
    /**
     * selectionParametresMusiqueUtilisateur
     *
     * @param  mixed $pseudo
     * @param  mixed $id
     * @return array
     */
    public function selectionParametresMusiqueUtilisateur($pseudo, $id) {
        $reponse = $this->pdo->prepare('SELECT utilisateurs.activer_video_background, utilisateurs.activer_son_video_background FROM utilisateurs WHERE utilisateurs.pseudo = :pseudo AND utilisateurs.id = :id'); // Récupération de l'option de musique d'utilisateur
        $reponse->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
        $reponse->bindValue('id', $id, PDO::PARAM_INT);
        $reponse->execute();
        $donnees = $reponse->fetch();

        return $donnees;
    }

}