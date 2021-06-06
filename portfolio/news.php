<?php
session_start(); // Lance variable de session
$id = $_GET['id'];

include('connexion_base_donnee.php');

$reponse = $bdd->prepare('SELECT article.titre, article.contenu, article.nom_categorie, article.nom_miniature, article.nom_banniere AS article_nom_banniere, utilisateurs.pseudo, utilisateurs.nom_photo_profil, jeu.id, jeu.url, jeu.nom_banniere, jeu.nom, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, DATE_FORMAT(date_creation, "%d %M %Y") AS date_article FROM article LEFT JOIN utilisateurs ON article.id_auteur = utilisateurs.id LEFT JOIN jeu ON article.id_jeu = jeu.id WHERE article.id = :id AND article.approuver = 1'); // Récupération de la news
$reponse->execute(array('id' => $id));
$donnees = $reponse->fetch();

$title = $donnees['titre']; // On met le titre de l'article
include('Header.php');
?>

<body class="background">
    <div class="container container-news bg-white">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: transparent;">
                <li class="breadcrumb-item"><a href="/portfolio/index.php">Accueil</a></li>
                <!-- Si l'article fait parti d'un jeu, on affiche le nom et on met l'url du jeu pour sa categorie -->
                <?php if (isset($donnees['nom'])) { ?>
                    <li class="breadcrumb-item"><a href="/jeu/<?php echo htmlspecialchars($donnees['url']); ?>-<?php echo htmlspecialchars($donnees['id']); ?>"><?php echo htmlspecialchars($donnees['nom']); ?></a></li>
                    <li class="breadcrumb-item"><a href="/jeu/<?php echo htmlspecialchars($donnees['url']); ?>-<?php echo htmlspecialchars($donnees['id']); ?>/<?php echo strtolower(htmlspecialchars($donnees['nom_categorie'])); ?>"><?php echo htmlspecialchars($donnees['nom_categorie']); ?></a></li>
                <?php } else {
                ?>
                    <li class="breadcrumb-item"><a href="/portfolio/index.php"><?php echo htmlspecialchars($donnees['nom_categorie']); ?></a></li>
                <?php
                }
                ?>
                <li class="breadcrumb-item" aria-current="page"><?php echo htmlspecialchars($donnees['titre']); ?></li>
            </ol>
        </nav>

        <h1 class="d-flex justify-content-center" style="font-size: 1.8em;"><?php echo htmlspecialchars($donnees['titre']); ?></h1>
        <div>
            <p class="d-flex justify-content-center"><em>Publié le <?php echo htmlspecialchars($donnees['date_article']); ?></em></p>
        </div>
        <div class="d-flex justify-content-center">
           <?php if(isset($donnees['article_nom_banniere'])) { // Si on met un bandeau
           ?>
            <img src="/portfolio/Articles/<?php echo htmlspecialchars($donnees['date_article_dossier']); ?>/<?php echo htmlspecialchars($donnees['titre']); ?>/bandeaux/<?php echo htmlspecialchars($donnees['article_nom_banniere']); ?>" onerror="this.oneerror=null; this.src='/banniere.jpg';" class="d-block img-fluid" style="width:100%; height:auto; max-height: 500px; margin-bottom:1vh; margin-top:1vh; border: 3px solid;">
           <?php }
           else { // Sinon on met le bandeau du jeu
           ?>
            <img src="/Jeux/<?php echo htmlspecialchars($donnees['nom']); ?>/bandeaux/<?php echo htmlspecialchars($donnees['nom_banniere']); ?>" onerror="this.oneerror=null; this.src='/banniere.jpg';" class="d-block img-fluid" style="width:100%; height:auto; max-height: 500px; margin-bottom:1vh; margin-top:1vh; border: 3px solid;">
           <?php
        }
           ?>
            </div>
        <!-- <img src="/miniature/<?php echo htmlspecialchars($donnees['nom_miniature']); ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="d-block img-fluid" style="width:800vh; height:50vh; margin:1vh"> -->
        <p class="justify-content-center text-break text-justify">
            <div class="contenu-news"> <?php echo remplacementBBCode(nl2br(htmlspecialchars($donnees['contenu'])), true, false); ?></div>
        </p>
        <?php
        $reponse->closeCursor();
        ?>

        <hr> <!-- Trait -->

        <?php if (isset($donnees['pseudo'])) {
        ?>
            <!-- Auteur de la news -->
            <div class="col-md-7 cadre" style="display: flex; align-items: center;">
                <div class="col-md-6">
                    <img src="/portfolio/photo_profil/<?php echo htmlspecialchars($donnees['nom_photo_profil']); ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="float-left img-fluid img-thumbnail" style="height: 20vh; width: 15vh;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                </div>
                <div class="text-center col-md-4">
                    Ecrit par <em><?php echo htmlspecialchars($donnees['pseudo']); ?></em></div>
            </div>
            <hr> <!-- Trait -->
        <?php
        }
        ?>

        <?php
        if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier une news 
        ?>
            <div class="row text-right">
                <div class="col">
                    <form class="form" method="post" action="/portfolio/modifier_news/<?php echo htmlspecialchars($_GET['url']); ?>-<?php echo htmlspecialchars($_GET['id']); ?>">
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
        /*
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
    <?php
    $reponse = $bdd->prepare('SELECT article.id, article.url FROM article LEFT JOIN utilisateurs ON article.id_auteur = utilisateurs.id LEFT JOIN jeu ON article.id_jeu = jeu.id WHERE article.id < :id AND article.approuver = 1 ORDER BY id DESC LIMIT 1'); // Récupération de la news précédente
    $reponse->execute(array('id' => $id));
    $nbPagePrecedente = $reponse->rowCount();
    $donnees = $reponse->fetch();

    $pagePrecedente = "/news" . "/" . $donnees['url'] . '-' . $donnees['id'];
    $reponse->closeCursor();

    $reponse = $bdd->prepare('SELECT article.id, article.url FROM article LEFT JOIN utilisateurs ON article.id_auteur = utilisateurs.id LEFT JOIN jeu ON article.id_jeu = jeu.id WHERE article.id > :id AND article.approuver = 1 ORDER BY id ASC LIMIT 1'); // Récupération de la news suivante
    $reponse->execute(array('id' => $id));
    $nbPageSuivante = $reponse->rowCount();
    $donnees = $reponse->fetch();

    $pageSuivante = "/news" . "/" . $donnees['url'] . '-' . $donnees['id'];
    $reponse->closeCursor();
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