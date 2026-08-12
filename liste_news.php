<?php
require_once('commun/TexteListeNewsClass.php');

// Initialisation
$bddCommun = new BddCommunClass();
$texteListeNews = new TexteListeNewsClass();

$texteListeNews->derniersJeuxAjoutes = $bddCommun->selectionJeuxApprouver();
$texteListeNews->derniersCommentaires = $bddCommun->selectionDerniersCommentaire();

if (!isset($_GET['page'])) { // Si on arrive sur l'accueil, la page selectionnée par défaut est la une
    $pageNewsSelectionner = 1;
} else {
    $pageNewsSelectionner = $_GET['page'];
}

$offsetPageNews = 20 * ($pageNewsSelectionner - 1); // Offset pour dire quand on commence à prendre les news

if (isset($_POST['article_approuver'])) {
    $selection_article_approuver = $_POST['article_approuver']; // Si approuver, les news selectionnées sont celles approuvés, sinon celle pas encore approuvés
} else {
    $selection_article_approuver = "Approuver";
}

if (isset($_GET['nom_categorie']) && ($_GET['nom_categorie'] == "news" || $_GET['nom_categorie'] == "glitchs" || $_GET['nom_categorie'] == "tutoriels")) { // Si on a sélectionné une categorie d'article, on sélectionne que ces articles
    $texteListeNews->nbArticles = $bddCommun->selectionCountArticleApprouverCategorie($selection_article_approuver, $_GET['nom_categorie']);
    $texteListeNews->listeArticles = $bddCommun->selectionArticleApprouverCategorie($selection_article_approuver, $offsetPageNews, $_GET['nom_categorie']);

} else if (isset($_POST['article_approuver']) && $_POST['article_approuver'] == "Brouillon") {
    $texteListeNews->nbArticles = $bddCommun->selectionCountArticleBrouillon($selection_article_approuver);
    $texteListeNews->listeArticles = $bddCommun->selectionArticleBrouillon($selection_article_approuver, $offsetPageNews);
    
} else {
    $texteListeNews->nbArticles = $bddCommun->selectionCountArticleAutre($selection_article_approuver);
    $texteListeNews->listeArticles = $bddCommun->selectionArticleAutre($selection_article_approuver, $offsetPageNews);
}

include('liste_news_vue.php');
   
/*
include('Header.php');
include('index_view.php');
include('liste_news.php');
include('footer.php');
*/