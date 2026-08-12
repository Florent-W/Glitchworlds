<?php
session_start(); // Lance variable de session

require_once('commun/BddArticleClass.php');
require_once('commun/TexteNewsClass.php');
require_once('fonctions_php.php');

// Initialisation
$bddArticle = new BddArticleClass();
$texteNews = new TexteNewsClass();

$id = $_GET['id'];

$texteNews->articleSelectionner = $bddArticle->selectionArticle($id);

if (isset($texteNews->articleSelectionner) && $texteNews->articleSelectionner) { // On regarde si un article existe pour cet id
    $texteNews->jeuxLierArticle = $bddArticle->selectionJeuxLierArtcle($id);

    $texteNews->articlePrecedent = $bddArticle->selectionArticlePrecedent($id);
    $texteNews->articleSuivant = $bddArticle->selectionArticleSuivant($id);
    $pseudo = $texteNews->articleSelectionner['pseudo'];
    $utilisateur_id = $texteNews->articleSelectionner['utilisateurs_id'];
    $nom_photo_profil = $texteNews->articleSelectionner['nom_photo_profil'];

    if ($_GET['url'] != $texteNews->articleSelectionner["article_url"]) { // On redirige si l'url n'est pas pas la meme que l'article
        header("Status: 301 Moved Permanently", false, 301);
        header("Location: " . $texteNews->articleSelectionner['article_url'] . "-" . $id);
        exit();
    }

    if (isset($_SESSION['id'])) {
        $texteNews->parametresMusiqueUtilisateur = $bddArticle->selectionParametresMusiqueUtilisateur($_SESSION['pseudo'], $_SESSION['id']);

        $activer_video_background = $texteNews->parametresMusiqueUtilisateur['activer_video_background'];
        $activer_son_video_background = $texteNews->parametresMusiqueUtilisateur['activer_son_video_background'];
    }

    $title = $texteNews->articleSelectionner['titre']; // On met le titre de l'article

    if (($texteNews->articleSelectionner['description']) != "") {
        $meta_description = $texteNews->articleSelectionner['description'];
    } else {
        $meta_description = tronquerTexte(remplacementBBCode($texteNews->articleSelectionner['contenu'], false, true), 200, "");
    }

    include('Header.php');

    if ($texteNews->articleSelectionner['presentation'] == "section") { ?>
        <script>
            activer_son_video_background = "<?php echo $activer_son_video_background; ?>";
            // Chargement du script des sections
            fullPage(activer_son_video_background);
        </script>
<?php
    }
    include('news_vue.php');
} else { // Si aucune news trouvé, envoi sur la page d'accueil
    http_response_code(404);
    header("Location: /");
    exit();
}
