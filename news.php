<?php
session_start(); // Lance variable de session
$id = $_GET['id'];
include('connexion_base_donnee.php');
$reponse = $bdd->prepare('SELECT article.titre, article.description, article.contenu, article.nom_categorie, article.presentation, article.nom_miniature, article.url AS article_url, article.nom_banniere AS article_nom_banniere, utilisateurs.id AS utilisateurs_id, utilisateurs.pseudo, utilisateurs.nom_photo_profil, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, DATE_FORMAT(date_creation, "%d %M %Y à %Hh%imin%ss") AS date_article FROM article LEFT JOIN utilisateurs ON article.id_auteur = utilisateurs.id WHERE article.id = :id AND (article.approuver = "Approuver" OR article.approuver = "Brouillon")'); // Récupération de la news
$reponse->execute(array('id' => $id));
$donnees = $reponse->fetch();
if ($_GET['url'] != $donnees["article_url"]) { // On redirige si l'url n'est pas pas la meme que l'article
    header("Status: 301 Moved Permanently", false, 301);
    header("Location: " . $donnees['article_url'] . "-" . $_GET['id']);
    exit();
}

$reponse2 = $bdd->prepare('SELECT utilisateurs.activer_video_background, utilisateurs.activer_son_video_background FROM utilisateurs WHERE utilisateurs.pseudo = :pseudo AND utilisateurs.id = :id'); // Récupération de l'option de musique d'utilisateur
$reponse2->execute(array('pseudo' => $_SESSION['pseudo'], 'id' => $_SESSION['id']));
$donnees2 = $reponse2->fetch();
$activer_video_background = $donnees2['activer_video_background'];
$activer_son_video_background = $donnees2['activer_son_video_background'];
$reponse2->closeCursor();

$title = $donnees['titre']; // On met le titre de l'article

include_once('fonctions_php.php');

if (($donnees['description']) != "") {
    $meta_description = $donnees['description'];
} else {
    $meta_description = tronquerTexte(remplacementBBCode($donnees['contenu'], false, true), 150, "");
}

include('Header.php');
?>
<script>
</script>
<?php
if ($donnees['presentation'] == "section") { ?><script>
        activer_son_video_background = "<?php echo $activer_son_video_background; ?>";
        // Chargement du script des sections
        fullPage(activer_son_video_background);
    </script>
<?php
}
?>

<body style="<?php if (!isset($_SESSION['nom_image_background'])) {
                    echo "background-image: url('/background.jpg');";
                } else {
                    echo "background-image: url('/utilisateurs/" . $_SESSION['id'] . "/background_site/" . $_SESSION['nom_image_background'] . "');";
                } ?> background-repeat: no-repeat; background-attachment: fixed; background-size: cover; background-position: center center; overflow-x: hidden;">
    <?php
    if ($donnees['presentation'] == "conteneur") { // Si on a choisit comme type de présentation un conteneur
    ?> <div class="container container-news bg-white"><?php
                                                    } else if ($donnees['presentation'] == "section") {  ?><div style="text-align: center;" id="menuPlacement">
            </div><?php ?>
            <div id="fullpage">
                <div class="section">b</div>
            <?php
                                                    } else {
            ?> <div class="bg-white"><?php
                                                    }
                                                    if ($donnees['presentation'] != "section") { ?>
                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> <!-- Publicité -->
                    <ins class="adsbygoogle" style="display:block; text-align:center; margin-top: 10px;" data-ad-layout="in-article" data-ad-format="fluid" data-ad-client="ca-pub-1179428382006278" data-full-width-responsive="false" data-ad-slot="4494542924"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                    <!-- Si l'article fait parti d'un jeu, on affiche le nom et on met l'url du jeu pour sa categorie -->
                    <?php
                                                        $reponse2 = $bdd->prepare('SELECT jeu.nom AS jeu_lier, jeu.nom_banniere, jeu.url, jeu.id FROM jeu INNER JOIN article_lier_jeu ON jeu.id = article_lier_jeu.id_jeu WHERE article_lier_jeu.id_article = :id_article'); // On cherche le nom des jeux lié à l'article
                                                        $reponse2->execute(array('id_article' => $id));
                    ?>
                    <?php
                                                        while ($donnees2 = $reponse2->fetch()) { // On assemble tous les jeux liés
                                                            if (!empty($donnees2['jeu_lier'])) { ?>
                            <nav aria-label="breadcrumb" style="margin-bottom: -20px; margin-top: 10px;">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/index.php">Accueil</a></li>
                                    <li class="breadcrumb-item"><a href="/jeu/<?php echo htmlspecialchars($donnees2['url']); ?>-<?php echo htmlspecialchars($donnees2['id']); ?>"><?php echo htmlspecialchars($donnees2['jeu_lier']); ?></a></li>
                                    <li class="breadcrumb-item"><a href="/jeu/<?php echo htmlspecialchars($donnees2['url']); ?>-<?php echo htmlspecialchars($donnees2['id']); ?>/<?php echo strtolower(htmlspecialchars($donnees['nom_categorie'])); ?>"><?php echo htmlspecialchars($donnees['nom_categorie']); ?></a></li>
                                    <li class="breadcrumb-item" aria-current="page"><?php echo htmlspecialchars($donnees['titre']); ?></li>
                                </ol>
                            </nav>
                        <?php } else {
                        ?>
                            <nav aria-label="breadcrumb" class="breadcrumb" style="margin-bottom: -20px; margin-top: 10px;">
                                <ol class="breadcrumb" style="background-color: transparent;">
                                    <li class="breadcrumb-item"><a href="/index.php">Accueil</a></li>
                                    <li class="breadcrumb-item"><a href="/index.php"><?php echo htmlspecialchars($donnees['nom_categorie']); ?></a></li>
                                    <li class="breadcrumb-item" aria-current="page"><?php echo htmlspecialchars($donnees['titre']); ?></li>
                                </ol>
                            </nav>
                        <?php
                                                            }
                        ?>
                    <?php
                                                        }
                                                        $reponse2->closeCursor();
                    ?>

                    <h1 class="d-flex justify-content-center" id="titreArticle" style="font-size: 1.8em; margin-top: 20px;"><?php echo htmlspecialchars($donnees['titre']); ?></h1>
                    <div>
                        <p class="d-flex justify-content-center"><em>Publié le <span id="dateArticle"><?php echo htmlspecialchars($donnees['date_article']); ?></span></em></p>
                    </div>
                    <div class="d-flex justify-content-center">
                        <?php if (isset($donnees['article_nom_banniere'])) { // Si on met un bandeau
                        ?>
                            <img src="/Articles/<?php echo htmlspecialchars($donnees['date_article_dossier']); ?>/<?php echo htmlspecialchars($donnees['article_url']); ?>/bandeaux/<?php echo htmlspecialchars($donnees['article_nom_banniere']); ?>" onerror="this.oneerror=null; this.src='/banniere.jpg';" class="d-block img-fluid" style="width:100%; height:auto; max-height: 500px; margin-bottom:1vh; margin-top:1vh; border: 3px solid;">
                        <?php } else { // Sinon on met le bandeau du jeu

                                                            $reponse2 = $bdd->prepare('SELECT jeu.nom AS jeu_lier, jeu.url, jeu.nom_banniere FROM jeu INNER JOIN article_lier_jeu ON jeu.id = article_lier_jeu.id_jeu WHERE article_lier_jeu.id_article = :id_article LIMIT 1'); // On cherche le nom des jeux lié à l'article
                                                            $reponse2->execute(array('id_article' => $id));
                        ?>
                            <?php
                                                            while ($donnees2 = $reponse2->fetch()) { // On assemble tous les jeux liés
                                                                if (!empty($donnees2['jeu_lier'])) { ?>
                                    <img src="/Jeux/<?php echo htmlspecialchars($donnees2['url']); ?>/bandeaux/<?php echo htmlspecialchars($donnees2['nom_banniere']); ?>" onerror="this.oneerror=null; this.src='/banniere.jpg';" class="d-block img-fluid" style="width:100%; height:auto; max-height: 500px; margin-bottom:1vh; margin-top:1vh; border: 3px solid;">
                            <?php
                                                                }
                                                            }
                                                            $reponse2->closeCursor();
                            ?>
                        <?php
                                                        }
                        ?>
                    </div>
                <?php }
                ?>
                <!-- <img src="/miniature/<?php echo htmlspecialchars($donnees['nom_miniature']); ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="d-block img-fluid" style="width:800vh; height:50vh; margin:1vh"> -->
                <?php if ($donnees['presentation'] == "section") { // Si c'est une section, on charge les sections 
                ?>
                    <?php echo remplacementBBCode(htmlspecialchars($donnees['contenu']), true, false); ?>
                    <script>
                        var countSection = 0;
                        $('.section').each(function() { // On renomme chaque player pour pas qu'ils aient le même id
                            countSection++;
                            $(this).children('.player').prop('id', 'bgndVideo' + countSection);

                            var countSlide = 0;

                            $(this).children('.slide').each(function() { // On parcours chaque slide de section
                                countSlide++;
                                $(this).children('.player').prop('id', 'bgndVideo' + countSection + '_' + countSlide);
                            });
                        })
                    </script>
                <?php
                } else {   ?> <p class="justify-content-center text-break text-justify">
                    <div class="contenu-news"> <?php echo remplacementBBCode(nl2br(htmlspecialchars($donnees['contenu'])), true, false); ?></div>
                    </p>
                <?php }
                ?>

                <?php
                $reponse->closeCursor();
                ?>

                <?php
                if ($donnees['presentation'] == "section") {
                ?><div class="section container container-news bg-white d-none d-sm-block"><?php // On rajoute la section des commentaires
                                                                                            if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier une news 
                                                                                            ?>
                            <div class="row text-right" style="position: fixed; left:92%; top:91%;">
                                <div class="col">
                                    <form class="form" method="post" action="/modifier_news/<?php echo htmlspecialchars($_GET['url']); ?>-<?php echo htmlspecialchars($_GET['id']); ?>">
                                        <button type="submit" id="modifier_article" class="btn btn-info" title="Modifier article">Modifier Article</button> <!-- Bouton modif -->
                                    </form>
                                </div>
                            </div>
                            <hr>
                    <?php
                                                                                            }
                                                                                        }
                    ?>
                    <hr>
                    <div class="row">
                    <?php // Page précédente et suivante
                    $reponse = $bdd->prepare('SELECT article.id, article.url, article.nom_miniature, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, article.titre FROM article LEFT JOIN utilisateurs ON article.id_auteur = utilisateurs.id WHERE article.id < :id AND article.approuver = "approuver" ORDER BY id DESC LIMIT 1'); // Récupération de la news précédente
                    $reponse->execute(array('id' => $id));
                    $nbPagePrecedente = $reponse->rowCount();
                    $donnees = $reponse->fetch();

                    $pagePrecedente = "/news" . "/" . $donnees['url'] . '-' . $donnees['id'];

                    if ($nbPagePrecedente > 0) { // On affiche l'article précédent si il y en a un
                        ?>
                        <div class='col float-left text-left' style="margin-left: 1.5%;">
                        <div class="row justify-content-start">
                            <?php echo "<a href=" . $pagePrecedente . ">"; ?><img src="/Articles/<?php echo $donnees['date_article_dossier']; ?>/<?php echo $donnees['url']; ?>/miniature/<?php echo $donnees['nom_miniature']; ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-fluid img-news img-thumbnail" style="float:left; height: 200px; background-color:transparent;"></a>
                        </div>
                        <div class="row justify-content-start">< Article Précédent</div>
                        <div class="row justify-content-start">
                        <?php
                            echo "<a href=" . $pagePrecedente . ">" . $donnees['titre'] . "</a>";
                        ?>
                        </div>
                        </div>
                    <?php  }

                    $reponse->closeCursor();

                    $reponse = $bdd->prepare('SELECT article.id, article.url, article.nom_miniature, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, article.titre FROM article LEFT JOIN utilisateurs ON article.id_auteur = utilisateurs.id WHERE article.id > :id AND article.approuver = "approuver" ORDER BY id ASC LIMIT 1'); // Récupération de la news suivante
                    $reponse->execute(array('id' => $id));
                    $nbPageSuivante = $reponse->rowCount();
                    $donnees = $reponse->fetch();

                    $pageSuivante = "/news" . "/" . $donnees['url'] . '-' . $donnees['id'];

                    if ($nbPageSuivante > 0) {
                        ?>
                        <div class='col float-right text-right' style="margin-right: 1.5%;">
                        <div class="row justify-content-end">
                         <?php echo "<a href=" . $pageSuivante . ">"; ?><img src="/Articles/<?php echo $donnees['date_article_dossier']; ?>/<?php echo $donnees['url']; ?>/miniature/<?php echo $donnees['nom_miniature']; ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-fluid img-news img-thumbnail" style="float:left; height: 200px; background-color:transparent;"></a>
                        </div>
                        <div class="row justify-content-end">Article Suivant ></div>
                        <div class="row justify-content-end">
                        <?php
                            echo "<a href=" . $pageSuivante . " >" . $donnees['titre'] . "</a>";
                    ?>             
                        </div>       
                    </div>
                    <?php }
                    $reponse->closeCursor();
                  ?>
                    </div>
                    <hr> <!-- Trait -->

                    <?php if (isset($donnees['pseudo'])) {
                    ?>
                        <!-- Auteur de la news -->
                        <div class="col-md-7 cadre" style="display: flex; align-items: center;">
                            <div class="col-md-6">
                                <img src="/utilisateurs/<?php echo htmlspecialchars($donnees['utilisateurs_id']); ?>/photo_profil/<?php echo htmlspecialchars($donnees['nom_photo_profil']); ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="float-left img-fluid img-thumbnail" style="height: 20vh; width: 15vh;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                            </div>
                            <div class="text-center col-md-4">
                                Ecrit par <em id="auteurArticle"><?php echo htmlspecialchars($donnees['pseudo']); ?></em></div>
                        </div>
                        <hr> <!-- Trait -->
                    <?php
                    }
                    ?>

                    <script>
                        pagePrecedente = '<?php echo $pagePrecedente; ?>';
                        pageSuivante = '<?php echo $pageSuivante; ?>';
                        nbPagePrecedente = '<?php echo $nbPagePrecedente; ?>';
                        nbPageSuivante = '<?php echo $nbPageSuivante; ?>';

                        changerPage(pagePrecedente, pageSuivante, nbPagePrecedente, nbPageSuivante);

                        /*
                                $('body').each(function(){
                                var hammertime = new Hammer(this);
                                hammertime.on('swipeleft', function(e) {
                                    alert('b');
                                });
                                hammertime.on('swiperight', function(e) {
                                    alert('b');
                                })
                               }); 
                               */
                    </script>
                    <?php
                    if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier une news 
                    ?>
                        <div class="row text-right">
                            <div class="col">
                                <form class="form" method="post" action="/modifier_news/<?php echo htmlspecialchars($_GET['url']); ?>-<?php echo htmlspecialchars($_GET['id']); ?>">
                                    <button type="submit" id="modifier_article" class="btn btn-info" title="Modifier article">Modifier Article</button> <!-- Bouton modif -->
                                </form>
                            </div>
                        </div>
                        <hr>
                    <?php
                    }
                    ?>

                    <!-- Affichage des commentaires -->
                    <?php
                    $type_commentaire = 'commentaire';
                    include('liste_commentaire.php'); ?>
                    <?php
                    if ($donnees['presentation'] == "section") { ?>
                    </div> <?php } /*    ?>     
            ?>
        <hr> <!-- Trait -->
        <!-- Affichage des articles précédents si c'est une news -->
        <div id="carousel" class="carousel slide" data-ride="carousel" data-interval="5000" style="width:100%; object-fit: contain;">
            <!-- carousel -->

            <div class="list-group" style="top:100px;">
                <div class="list-group-item liste-item-news-similaire bg-info">

                    <h3 class="text-center" style="margin-bottom : 20px;">Autres articles :</h3>

                    <?php $nombrePageCarouselArticle = 3; // Nombre de page du carousel
                    ?>

                    <div class="carousel-inner" style="margin-bottom: 20px;">

                        <?php $offsetArticleSimilaire = 0; // Le premier article de la colonne d'article similaire, servira à dire à quel article commencer 
                        ?>
                        <div class="carousel-item active">
                            <!-- La colonne de news qu'on voit par défaut -->
                            <div class="row">
                                <?php
                                $reponse = $bdd->prepare('SELECT article.id, article.titre, article.nom_miniature, article.url, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier FROM article WHERE id < :id ORDER BY id DESC LIMIT 3 OFFSET :offsetArticleSimilaire'); // Récupération des news précédentes
                                $reponse->bindValue('id', $id, PDO::PARAM_INT);
                                $reponse->bindValue('offsetArticleSimilaire', $offsetArticleSimilaire, PDO::PARAM_INT);
                                $reponse->execute();

                                while ($donnees = $reponse->fetch()) {
                                ?>
                                    <div class="col d-flex justify-content-center">
                                        <a href="/news/<?php echo htmlspecialchars($donnees['url']); ?>-<?php echo htmlspecialchars($donnees['id']); ?>" style="text-decoration-color: black">
                                            <div class="row">
                                                <img src="/Articles/<?php echo htmlspecialchars($donnees['date_article_dossier']); ?>/<?php echo htmlspecialchars($donnees['titre']); ?>/miniature/<?php echo htmlspecialchars($donnees['nom_miniature']); ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-thumbnail" style="height: 20vh; width: 15vh;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                                            </div>
                                            <h3 class="list-group-item-heading text-center text-body"><?php echo htmlspecialchars($donnees['titre']); ?></h3>
                                        </a>
                                    </div>
                                <?php }
                                $reponse->closeCursor();
                                ?>
                            </div>
                        </div>

                        <?php
                        for ($i = 1; $i < $nombrePageCarouselArticle; $i++) // Création des pages autre que celles par défaut
                        {
                            $offsetArticleSimilaire = ($i * 3); // L'offset est le premier article d'une colonne
                        ?>

                            <div class="carousel-item">
                                <div class="row">
                                    <?php
                                    $reponse = $bdd->prepare('SELECT article.id, article.titre, article.nom_miniature, article.url, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier FROM article WHERE id < :id ORDER BY id DESC LIMIT 3 OFFSET :offsetArticleSimilaire'); // Récupération des news précédentes
                                    $reponse->bindValue('id', $id, PDO::PARAM_INT);
                                    $reponse->bindValue('offsetArticleSimilaire', $offsetArticleSimilaire, PDO::PARAM_INT);
                                    $reponse->execute();

                                    while ($donnees = $reponse->fetch()) {
                                    ?>
                                        <div class="col d-flex justify-content-center">
                                            <a href="/news/<?php echo htmlspecialchars($donnees['url']); ?>-<?php echo htmlspecialchars($donnees['id']); ?>" style="text-decoration-color: black">
                                                <div class="row">
                                                    <img src="/Articles/<?php echo htmlspecialchars($donnees['date_article_dossier']); ?>/<?php echo htmlspecialchars($donnees['titre']); ?>/miniature/<?php echo htmlspecialchars($donnees['nom_miniature']); ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-thumbnail" style="height: 20vh; width: 15vh;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                                                </div>
                                                <h3 class="list-group-item-heading text-center text-body"><?php echo htmlspecialchars($donnees['titre']); ?></h3>
                                            </a>
                                        </div>
                                    <?php }
                                    $reponse->closeCursor();
                                    ?>
                                </div>
                            </div>
                        <?php }
                        ?>

                        <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                            <!-- Bouton précédent pour revenir au slider précédent -->
                            <span class="fa fa-chevron-left" aria-hidden="true" style="color: black; font-size: 3vh; margin-right: 90px;"></span>
                            <span class="sr-only">Précédent</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                            <!-- Bouton suivant pour revenir au slider suivant -->
                            <span class="fa fa-chevron-right" aria-hidden="true" style="color: black; font-size: 3vh; margin-left: 90px;"></span>
                            <span class="sr-only">Suivant</span>
                        </a>
                    </div>

                    <ol class="carousel-indicators">
                        <li data-target="#carousel" data-slide-to="0" class="active"></li> <!-- Pointillé pour choisir la page en bas -->

                        <?php

                        for ($i = 1; $i < $nombrePageCarouselArticle; $i++) {  // Création des pointillés autre que celle par défaut
                        ?>

                            <li data-target="#carousel" data-slide-to="<?php echo htmlspecialchars($i); ?>"></li>
                        <?php }
                        ?>
                    </ol>
                </div>
            </div>
        </div>
        <hr> <!-- Trait -->
        */
                            ?>
                </div>
                <button class="btn btn-primary fixed-bottom btn-haut-page" id="btnHautPage"><i class="fas fa-arrow-up"></i></button>
                <script>
                    $("#btnHautPage").click(function() {
                        $("html, body").animate({
                            scrollTop: 0
                        }, 500);
                    });
                </script>

                <!-- Balisage JSON-LD généré par l'outil d'aide au balisage de données structurées de Google -->
                <script>
                    var json_balisage = document.createElement('script');
                    json_balisage.type = 'application/ld+json';
                    json_balisage.text = JSON.stringify({
                        "@context": "http://schema.org",
                        "@type": "Article",
                        "name": $('#titreArticle').text(),
                        "author": {
                            "@type": "Person",
                            "name": $('#auteurArticle').text()
                        },
                        "datePublished": $('#dateArticle').text()
                    });
                    document.querySelector('body').appendChild(json_balisage);
                    // console.log(json_balisage);
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