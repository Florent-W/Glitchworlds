<body style="<?php if (!isset($_SESSION['nom_image_background'])) {
                    echo "background-image: url('/background.jpg');";
                } else {
                    echo "background-image: url('/utilisateurs/" . $_SESSION['id'] . "/background_site/" . $_SESSION['nom_image_background'] . "');";
                } ?> background-repeat: no-repeat; background-attachment: fixed; background-size: cover; background-position: center center; overflow-x: hidden;">
    <?php
    if ($texteNews->articleSelectionner['presentation'] == "conteneur") { // Si on a choisit comme type de présentation un conteneur
    ?> <div class="container container-news bg-white"><?php
                                                    } else if ($texteNews->articleSelectionner['presentation'] == "section") {  ?><div style="text-align: center;" id="menuPlacement">
            </div><?php ?>
            <div id="fullpage">
                <div class="section">b</div>
            <?php
                                                    } else {
            ?> <div class="bg-white"><?php
                                                    }
                                                    if ($texteNews->articleSelectionner['presentation'] != "section") { ?>
                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> <!-- Publicité -->
                    <ins class="adsbygoogle" style="display:block; text-align:center; margin-top: 10px;" data-ad-layout="in-article" data-ad-format="fluid" data-ad-client="ca-pub-1179428382006278" data-full-width-responsive="false" data-ad-slot="4494542924"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                    <!-- Si l'article fait parti d'un jeu, on affiche le nom et on met l'url du jeu pour sa categorie -->
                    <?php
                                                        for ($i = 0; $i<count($texteNews->jeuxLierArticle); $i++) { // On assemble tous les jeux liés
                                                            $jeuLier = $texteNews->jeuxLierArticle[$i];
                                                            if (!empty($jeuLier['jeu_lier'])) { ?>
                            <nav aria-label="breadcrumb" style="margin-bottom: -20px; margin-top: 10px;">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/index.php">Accueil</a></li>
                                    <li class="breadcrumb-item"><a href="/jeu/<?php echo htmlspecialchars($jeuLier['url']); ?>-<?php echo htmlspecialchars($jeuLier['id']); ?>"><?php echo htmlspecialchars($jeuLier['jeu_lier']); ?></a></li>
                                    <li class="breadcrumb-item"><a href="/jeu/<?php echo htmlspecialchars($jeuLier['url']); ?>-<?php echo htmlspecialchars($jeuLier['id']); ?>/<?php echo strtolower(htmlspecialchars($texteNews->articleSelectionner['nom_categorie'])); ?>"><?php echo htmlspecialchars($texteNews->articleSelectionner['nom_categorie']); ?></a></li>
                                    <li class="breadcrumb-item" aria-current="page"><?php echo htmlspecialchars($texteNews->articleSelectionner['titre']); ?></li>
                                </ol>
                            </nav>
                        <?php } else {
                        ?>
                            <nav aria-label="breadcrumb" class="breadcrumb" style="margin-bottom: -20px; margin-top: 10px;">
                                <ol class="breadcrumb" style="background-color: transparent;">
                                    <li class="breadcrumb-item"><a href="/index.php">Accueil</a></li>
                                    <li class="breadcrumb-item"><a href="/index.php"><?php echo htmlspecialchars($texteNews->articleSelectionner['nom_categorie']); ?></a></li>
                                    <li class="breadcrumb-item" aria-current="page"><?php echo htmlspecialchars($texteNews->articleSelectionner['titre']); ?></li>
                                </ol>
                            </nav>
                        <?php
                                                            }
                                                        }
                    ?>

                    <h1 class="d-flex justify-content-center" id="titreArticle" style="font-size: 1.8em; margin-top: 20px;"><?php echo htmlspecialchars($texteNews->articleSelectionner['titre']); ?></h1>
                    <div>
                        <p class="d-flex justify-content-center"><em>Publié le <span id="dateArticle"><?php echo htmlspecialchars($texteNews->articleSelectionner['date_article']); ?></span></em></p>
                    </div>
                    <div class="d-flex justify-content-center">
                        <?php if (isset($texteNews->articleSelectionner['article_nom_banniere'])) { // Si on met un bandeau
                        ?>
                            <img src="/Articles/<?php echo htmlspecialchars($texteNews->articleSelectionner['date_article_dossier']); ?>/<?php echo htmlspecialchars($texteNews->articleSelectionner['article_url']); ?>/bandeaux/<?php echo htmlspecialchars($texteNews->articleSelectionner['article_nom_banniere']); ?>" onerror="this.onerror=null; this.src='/banniere.jpg';" class="d-block img-fluid" style="width:100%; height:auto; max-height: 500px; margin-bottom:1vh; margin-top:1vh; border: 3px solid;">
                        <?php } else { // Sinon on met le bandeau du jeu
                        ?>
                            <?php
                                                                if (!empty($texteNews->jeuxLierArticle[0]['jeu_lier'])) { ?>
                                    <img src="/Jeux/<?php echo htmlspecialchars($texteNews->jeuxLierArticle[0]['url']); ?>/bandeaux/<?php echo htmlspecialchars($texteNews->jeuxLierArticle[0]['nom_banniere']); ?>" onerror="this.onerror=null; this.src='/banniere.jpg';" class="d-block img-fluid" style="width:100%; height:auto; max-height: 500px; margin-bottom:1vh; margin-top:1vh; border: 3px solid;">
                            <?php
                                                                }
                            ?>
                        <?php
                                                        }
                        ?>
                    </div>
                <?php }
                ?>
                <?php if ($texteNews->articleSelectionner['presentation'] == "section") { // Si c'est une section, on charge les sections 
                ?>
                    <?php echo remplacementBBCode(htmlspecialchars($texteNews->articleSelectionner['contenu']), true, false); ?>
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
                    <div class="contenu-news"> <?php echo remplacementBBCode(nl2br(htmlspecialchars($texteNews->articleSelectionner['contenu'])), true, false); ?></div>
                    </p>
                <?php }
            
                if ($texteNews->articleSelectionner['presentation'] == "section") {
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
                    if (isset($texteNews->articlePrecedent) && ($texteNews->articlePrecedent != false)) { // On affiche l'article précédent si il y en a un
                        $pagePrecedente = "/news" . "/" . $texteNews->articlePrecedent['url'] . '-' . $texteNews->articlePrecedent['id'];
                        ?>
                        <div class='col float-left text-left' style="margin-left: 1.5%;">
                        <div class="row justify-content-start">
                            <?php echo "<a href=" . $pagePrecedente . ">"; ?><img src="/Articles/<?php echo $texteNews->articlePrecedent['date_article_dossier']; ?>/<?php echo $texteNews->articlePrecedent['url']; ?>/miniature/<?php echo $texteNews->articlePrecedent['nom_miniature']; ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-fluid img-news img-thumbnail" style="float:left; height: 200px; background-color:transparent;"></a>
                        </div>
                        <div class="row justify-content-start">< Article Précédent</div>
                        <div class="row justify-content-start">
                        <?php
                            echo "<a href=" . $pagePrecedente . ">" . $texteNews->articlePrecedent['titre'] . "</a>";
                        ?>
                        </div>
                        </div>
                    <?php  }

                    if(isset($texteNews->articleSuivant) && ($texteNews->articleSuivant != false)) {
                    $pageSuivante = "/news" . "/" . $texteNews->articleSuivant['url'] . '-' . $texteNews->articleSuivant['id'];
                        ?>
                        <div class='col float-right text-right' style="margin-right: 1.5%;">
                        <div class="row justify-content-end">
                         <?php echo "<a href=" . $pageSuivante . ">"; ?><img src="/Articles/<?php echo $texteNews->articleSuivant['date_article_dossier']; ?>/<?php echo $texteNews->articleSuivant['url']; ?>/miniature/<?php echo $texteNews->articleSuivant['nom_miniature']; ?>" onerror="this.onerror=null; this.src='/1.png';" class="img-fluid img-news img-thumbnail" style="float:left; height: 200px; background-color:transparent;"></a>
                        </div>
                        <div class="row justify-content-end">Article Suivant ></div>
                        <div class="row justify-content-end">
                        <?php
                            echo "<a href=" . $pageSuivante . " >" . $texteNews->articleSuivant['titre'] . "</a>";
                    ?>             
                        </div>       
                    </div>
                    <?php }
                  ?>
                    </div>
                    <hr> <!-- Trait -->

                    <?php if (isset($pseudo)) {
                    ?>
                        <!-- Auteur de la news -->
                        <div class="col-md-7 cadre" style="display: flex; align-items: center;">
                            <div class="col-md-6">
                                <img src="/utilisateurs/<?php echo htmlspecialchars($utilisateur_id); ?>/photo_profil/<?php echo htmlspecialchars($nom_photo_profil); ?>" onerror="this.onerror=null; this.src='/1.jpg';" class="float-left img-fluid img-thumbnail" style="height: 20vh; width: 15vh;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                            </div>
                            <div class="text-center col-md-4">
                                Ecrit par <em id="auteurArticle"><?php echo htmlspecialchars($pseudo); ?></em></div>
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
                </div>
                <button class="btn btn-primary fixed-bottom btn-haut-page" id="btnHautPage"><i class="fas fa-arrow-up"></i></button>
                <script>
                    $("#btnHautPage").click(function() {
                        $("html, body").animate({
                            scrollTop: 0
                        }, 500);
                    });

                </script>

            <?php
            $reponse = $bdd->prepare('SELECT article.id, article.url FROM article WHERE id < :id AND article.approuver = "Approuver" ORDER BY id DESC LIMIT 1');    
            $reponse->execute(array('id' => $id));

            $donnees = $reponse->fetch();

            if (!empty($donnees)) {
                $pagePrecedente = "/news/" . $donnees['url'] . '-' . $donnees['id'];
                $nbPagePrecedente = 1;
            } else {
                $pagePrecedente = '';
                $nbPagePrecedente = 0;
            }

            $reponse->closeCursor();

            $reponse = $bdd->prepare('SELECT article.id, article.url FROM article WHERE id > :id AND article.approuver = "Approuver" ORDER BY id ASC LIMIT 1');
            $reponse->execute(array('id' => $id));
            $donnees = $reponse->fetch();

            if (!empty($donnees)) {
                $pageSuivante = "/news/" . $donnees['url'] . '-' . $donnees['id'];
                $nbPageSuivante = 1;
            } else {
                $pageSuivante = '';
                $nbPageSuivante = 0;
            }

            $reponse->closeCursor();
            ?>
                <script>
                pagePrecedente = '<?php echo $pagePrecedente; ?>';
                pageSuivante = '<?php echo $pageSuivante; ?>';
                nbPagePrecedente = '<?php echo $nbPagePrecedente; ?>';
                nbPageSuivante = '<?php echo $nbPageSuivante; ?>';

                changerPage(pagePrecedente, pageSuivante, nbPagePrecedente, nbPageSuivante);

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