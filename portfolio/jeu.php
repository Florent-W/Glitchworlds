<?php
session_start(); // Lance variable de session
$url = htmlspecialchars($_GET['url']);
$id = htmlspecialchars($_GET['id']);

if (isset($_GET['onglet_jeu'])) {
    $onglet_jeu = htmlspecialchars($_GET['onglet_jeu']);
} else {
    $onglet_jeu = "";
}

include_once('connexion_base_donnee.php');
$reponse = $bdd->prepare('SELECT *, DATE_FORMAT(date_sortie, "%d %M %Y") AS date_jeu FROM jeu WHERE id = :id'); // Récupération du jeu
$reponse->execute(array('id' => $id));
$donnees = $reponse->fetch();

$title = $donnees['nom'];
include('Header.php');

$nom_jeu = $donnees['nom'];
?>

<body class="background">
    <!-- Affichage jeu -->
    <div class="container container-bordure animated fadeInRight bg-white">
        <div class="d-flex justify-content-center">
            <img src="/Jeux/<?php echo $donnees['nom']; ?>/bandeaux/<?php echo htmlspecialchars($donnees['nom_banniere']); ?>" onerror="this.oneerror=null; this.src='/banniere.jpg';" class="d-block img-fluid" style="width: 100%; height:auto; max-height: 500px; margin-bottom:1vh; margin-top:1vh; border: 3px solid;">
        </div>
        <?php /* if (!file_exists('Jeux' . '/' . $donnees['nom'] . '/' . 'bandeaux')) {
                        mkdir('Jeux' . '/' . $donnees['nom'] . '/' . 'bandeaux', 0777, true);
                    }
                    rename("bandeaux" . "/" . $donnees['nom_banniere'], 'Jeux' . '/' . $donnees['nom'] . '/' . 'bandeaux' . '/' . $donnees['nom_banniere']); // Bouge l'image sans la redimensionner, il faudra faire en sorte qu'elle ne dépasse pas une taille
                   */ ?>
        <h1 class="d-flex justify-content-center" style="font-size: 1.8em;"><?php echo htmlspecialchars($donnees['nom']); ?></h1>
        <?php /* <script>
            $("#typing").ghosttyper({ // Pour révéler texte
                messages: ['<?php echo $donnees['nom']; ?>', '<?php echo $donnees['nom']; ?>'],
                timeWrite: 100,
                timePause: 1000,
            });
        </script> */ ?>
        <hr>
        <div class="text-center">
            <?php if ($onglet_jeu == "" || ($onglet_jeu != "news" && $onglet_jeu != "glitch")) { // On regarde l'onglet du jeu pour le selectionner dans le menu
            ?><a href="/jeu/<?php echo $url; ?>-<?php echo $id; ?>" class="btn btn-outline-primary active" style="margin-right: 5px;">Présentation</a> <!-- Lien vers présentation du jeu -->
            <?php } else {
            ?> <a href="/jeu/<?php echo $url; ?>-<?php echo $id; ?>" class="btn btn-outline-primary" style="margin-right: 5px;">Présentation</a> <!-- On regarde si une page est sélectionné sinon on met la page de présentation active -->
            <?php }

            if ($onglet_jeu == "news") {
            ?><a href="/jeu/<?php echo $url; ?>-<?php echo $id; ?>/news" class="btn btn-outline-info active" style="margin-right: 5px;">News</a> <!-- Lien vers articles du jeu -->
            <?php } else { ?>
                <a href="/jeu/<?php echo $url; ?>-<?php echo $id; ?>/news" class="btn btn-outline-info" style="margin-right: 5px;">News</a> <!-- Lien vers articles du jeu -->
            <?php  }

            if ($onglet_jeu == "glitch") {
            ?> <a href="/jeu/<?php echo $url; ?>-<?php echo $id; ?>/glitch" class="btn btn-outline-secondary active" style="margin-right: 5px;">Glitchs</a> <!-- Lien vers glitch -->
            <?php } else {
            ?> <a href="/jeu/<?php echo $url; ?>-<?php echo $id; ?>/glitch" class="btn btn-outline-secondary" style="margin-right: 5px;">Glitchs</a> <!-- Lien vers glitch -->
            <?php }
            ?>
            <hr>
        </div>

        <?php
        switch ($onglet_jeu) { // On inclut l'onglet sur lequel on est    
            default:
                include('jeu_presentation.php');
                break;
            case "news":
                include('jeu_article.php');
                break;

            case "glitch":
                include('jeu_glitch.php');
                break;
        } ?>

        <?php
        if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier un jeu 
        ?>
            <hr>
            <div class="row text-right" style="margin-bottom: 15px;">
                <div class="col">
                    <form class="form" method="post" action="/modifier_jeu/<?php echo $url; ?>-<?php echo $id ?>">
                        <button type="submit" id="modifier_jeu" class="btn btn-info" title="Modifier jeu">Modifier Jeu</button> <!-- Bouton modif -->
                    </form>
                </div>
            </div>
        <?php
        }
        ?>
        <?php /*
        <form class="form-inline my-2 my-lg-0 justify-content-center" method="GET">
            <!-- Recherche -->
            <div class="form-group" style="margin: 5px;">
                <input type="hidden" name="id" value="<?php echo "" . $id . "" ?>"></input>
                <input class="form-control" type="search" value="<?php if (isset($_GET['recherche'])) echo $_GET['recherche']; ?>" placeholder="Rechercher" name="recherche" id="recherche" aria-label="Rechercher"> <!-- Recherche -->
            </div>
            <?php
            ?>
            <div class="form-group">
                <select class="form-control" name="categorie" style="margin: 5px;">
                    <!-- Selection catégorie de la recherche -->
                    <option value="Glitch" <?php if (isset($_GET['categorie']) and $_GET['categorie'] == "Glitch") echo 'selected="selected"'; ?>>Glitch</option>
                    <option value="News" <?php if (isset($_GET['categorie']) and $_GET['categorie'] == "News") echo 'selected="selected"'; ?>>News</option>
                </select>
            </div>
            <button class="btn btn-outline-success" style="margin: 5px;" type="submit">Rechercher</button>
        </form>

        <?php
        if (!isset($_GET['recherche'])) { // Si on arrive sur un jeu, on prend tous les articles
            $rechercheArticle = "";
        } else {
            $rechercheArticle = $_GET['recherche'];
        }

        if (!isset($_GET['page'])) { // Si on arrive sur la liste des glitch, la page selectionnée par défaut est la une
            $pageSelectionner = 1;
        } else {
            $pageSelectionner = $_GET['page'];
        }

        $nombreGlitchParPage = 2;
        $nombreNewsParPage = 2;
        ?>

        <!-- glitch -->
        <?php if (empty($_GET['categorie']) or $_GET['categorie'] == "Glitch") { // Si on ne recherche pas de catégorie ou que la categorie selectionné est glitch, on affiche les glitch
            include('liste_glitch_colonne.php');
        }
        ?>

        <!-- news -->
        <?php if (empty($_GET['categorie']) or $_GET['categorie'] == "News") { // Si on ne recherche pas de catégorie ou que la categorie selectionné est news, on affiche les news
            include('liste_news_colonne.php');
        }
        ?>

    */
        ?>
    <?php     $type_commentaire = "commentaire_jeu";
    include('liste_commentaire.php'); ?>
    </div>

    <?php
    $reponse = $bdd->prepare('SELECT jeu.id, jeu.url FROM jeu WHERE id < :id ORDER BY id DESC LIMIT 1'); // Récupération de la news précédente
    $reponse->execute(array('id' => $id));
    $nbPagePrecedente = $reponse->rowCount();
    $donnees = $reponse->fetch();

    $pagePrecedente = "/jeu" . "/" . $donnees['url'] . '-' . $donnees['id'];
    $reponse->closeCursor();

    $reponse = $bdd->prepare('SELECT jeu.id, jeu.url FROM jeu WHERE id > :id ORDER BY id ASC LIMIT 1'); // Récupération de la news suivante
    $reponse->execute(array('id' => $id));
    $nbPageSuivante = $reponse->rowCount();
    $donnees = $reponse->fetch();

    $pageSuivante = "/jeu" . "/" . $donnees['url'] . '-' . $donnees['id'];
    $reponse->closeCursor();
    ?>

    <script>
        pagePrecedente = '<?php echo $pagePrecedente; ?>';
        pageSuivante = '<?php echo $pageSuivante; ?>';
        nbPagePrecedente = '<?php echo $nbPagePrecedente; ?>';
        nbPageSuivante = '<?php echo $nbPageSuivante; ?>';

        changerPage(pagePrecedente, pageSuivante, nbPagePrecedente, nbPageSuivante);
    </script>

    <?php
    include('ajout_commentaire_traitement.php');
    ?>
    <?php
    include('ajout_aime_commentaire_traitement.php');
    ?>
    <?php
    include('footer.php');
    ?>

</body>

</html>