<div class="view" style="<?php if (!isset($_SESSION['nom_image_background'])) {
                                echo "background-image: url('/background.jpg'); background-repeat: no-repeat; background-size: cover; background-position: center center;";
                            } else {
                                echo "background-image: url('/utilisateurs/" . $_SESSION['id'] . "/background_site/" . $_SESSION['nom_image_background'] . "'); background-repeat: no-repeat; background-attachment: fixed; background-size: cover; background-position: center center;";
                            } ?>"> <!-- On change de background si l'utilisateur le veut -->
    <!-- Background -->
    <div class="groupement-news">
        <!-- Permet que la page des news ne prenne pas toute la page -->
        <div class="col-xl-3 d-none d-xl-block justify-content-center" style="float: right; height: auto; width: 25%;">
            <div class="liste-derniers-jeux animation fadeRight list-group-item-light" style="text-decoration-color: black; text-decoration: none;">
                <!-- Liste déroulante des derniers jeux ajoutés -->
                <h5 class="text-center">Derniers Jeux Ajoutés</h5>
                <aside class="demo" style="margin: 10px;">
                    <ul class="list-group">
                        <?php for($i=0; $i<count($texteListeNews->derniersJeuxAjoutes); $i++) {
                           $jeu = $texteListeNews->derniersJeuxAjoutes[$i];
                        ?>
                            <a href="/jeu/<?php echo $jeu['url']; ?>-<?php echo $jeu['id']; ?>" style="border: 1px solid black; text-decoration: none;">
                                <div class="row">
                                    <div class="col-5">
                                        <img src="/Jeux/<?php echo $jeu['url']; ?>/miniature/<?php echo $jeu['nom_miniature']; ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-thumbnail img-fluid" style="width:auto; height: auto; min-width:100%; max-width:100%; max-height:100%; border-style: none !important; background-color:transparent;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                                    </div>
                                    <div class="col-6 d-flex align-items-center justify-content-center text-center">
                                        <div style="color: black; font-size: 2vh;"><?php echo $jeu['nom']; ?></div>
                                    </div>
                                </div>
                                <?php /*  <p class="list-group-item-text pull-right lead" style="word-wrap: break-word"><?php echo tronquerTexte(remplacementBBCode($donnees['contenu'], false, true), 150, "jeu/" . $donnees['url'] . "-" . $donnees['id']) ?> </p> <!-- Contenu, on veut supprimer aussi les balises --> */ ?>
                            </a>
                        <?php } ?>
                    </ul>
                </aside>
                <div class="list-group" style="margin-bottom: 10px;">
                    <input type="button" class="list-group-item btnUp" value="Monter">
                    <input type="button" class="list-group-item btnDown" value="Descendre">
                </div>
            </div>

            <div class="liste-derniers-jeux animation fadeRight list-group-item-light" style="text-decoration-color: black; text-decoration: none;">
                <!-- Liste déroulante des derniers commentaires ajoutés -->
                <h5 class="text-center">Derniers Commentaires</h5>
                <aside class="demo" style="margin: 10px;">
                    <ul class="list-group">
                        <?php for($i=0; $i<count($texteListeNews->derniersCommentaires); $i++) {
                           $commentaire = $texteListeNews->derniersCommentaires[$i];

                           if($commentaire['source'] == "commentaire") { ?>
                                <a href="/news/<?php echo $commentaire['article_url']; ?>-<?php echo $commentaire['article_id']; ?>" style="border: 1px solid black; text-decoration: none;">
                            <?php } else { ?>
                                <a href="/jeu/<?php echo $commentaire['article_url']; ?>-<?php echo $commentaire['article_id']; ?>" style="border: 1px solid black; text-decoration: none;">
                            <?php } ?>
                            <div class="row">
                                <div class="col text-right">
                                <div style="color: black; font-size: 1.41vh;"><?php echo htmlspecialchars($commentaire['date_commentaire_message']); ?></div>
                            </div>
                            </div>
                            <div class="row d-flex align-items-center text-center">
                                <div class="col-12">
                                    <div style="color: black; font-size: 1.31vh;"><?php echo nl2br(tronquerTexte(remplacementBBCode(htmlspecialchars($commentaire['contenu']), false, true), 200, "")); ?></div>
                                </div>
                                <div class="col-12" style="color: black; font-size: 1.61vh;"><em><?php echo htmlspecialchars($commentaire['pseudo']); ?></em></div>
                            </div>
                            <div class="row d-flex align-items-center text-center">
                                <div class="col-12"> 
                                    <?php if($commentaire['source'] == "commentaire") { ?>
                                    <img src="/Articles/<?php echo $commentaire['date_article_dossier']; ?>/<?php echo $commentaire['article_url']; ?>/miniature/<?php echo $commentaire['nom_miniature']; ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-fluid img-news img-thumbnail" style="width:auto; height: auto; min-width:50%; max-width:62%; max-height:50%; border-style: none !important; background-color:transparent;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                                    <?php }
                                    else {
                                        ?>
                                        <img src="/Jeux/<?php echo $commentaire['article_url']; ?>/miniature/<?php echo $commentaire['nom_miniature']; ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-thumbnail img-fluid" style="width:auto; height: auto; min-width:50%; max-width:62%; max-height:50%; border-style: none !important; background-color:transparent;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                                   <?php } ?>
                                </div>
                                <div class="col-12">
                                        <div style="color: black; font-size: 1.41vh;"><strong><?php echo $commentaire['article_titre']; ?></strong></div>
                                </div>
                            </div>
                            <?php /*  <p class="list-group-item-text pull-right lead" style="word-wrap: break-word"><?php echo tronquerTexte(remplacementBBCode($donnees['contenu'], false, true), 150, "jeu/" . $donnees['url'] . "-" . $donnees['id']) ?> </p> <!-- Contenu, on veut supprimer aussi les balises --> */ ?>
                        </a>
                        <?php } ?>
                    </ul>
                </aside>
                <div class="list-group" style="margin-bottom: 10px;">
                    <input type="button" class="list-group-item btnUp" value="Monter">
                    <input type="button" class="list-group-item btnDown" value="Descendre">
                </div>
            </div>
          
            <div class="animation fadeRight" style="text-decoration-color: black; text-decoration: none; margin-top: 200px; margin-right: 10px;">
                <!-- Pub -->
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- Publicité carré -->
                <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-1179428382006278" data-ad-slot="1720205774" data-ad-format="auto" data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        </div>

        <ul class="list-group col-xl-9 col-lg-12" style="margin:auto;">
            <?php
        
            ?>

            <!-- Selection du type d'article -->
            <?php if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à voir les articles en attente 
            ?>
                <form class="form-inline form-index my-2 my-lg-0 justify-content-center" method="POST">
                    <div class="form-group">
                        <select class="form-control" name="article_approuver">
                            <!-- Selection article approuver -->
                                <option value="Approuver" <?php if (isset($_POST['article_approuver']) and $_POST['article_approuver'] == "Approuver") echo 'selected="selected"'; ?>>Approuver</option>
                            <option value="Préapprouver" <?php if (isset($_POST['article_approuver']) and $_POST['article_approuver'] == "Préapprouver") echo 'selected="selected"'; ?>>En attente</option>
                            <option value="Brouillon" <?php if (isset($_POST['article_approuver']) and $_POST['article_approuver'] == "Brouillon") echo 'selected="selected"'; ?>>Brouillon</option> <!-- Les différentes options du select -->
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-outline-success" type="submit">Rechercher</button>
                    </div>
                </form>
            <?php }
            ?>

            <?php                                        
            for($i=0; $i<count($texteListeNews->listeArticles); $i++) {
                // Liste news
                $article = $texteListeNews->listeArticles[$i];
                // Nombre de commentaire
                $nbCommentaires = $bddCommun->selectionCountCommentaireArtcle($article['id']);
                $jeuxLier = $bddCommun->selectionJeuxLierArtcle($article['id']);

            ?>
                <div class="liste-news">
                    <?php
                    if ($i % 2 == 0) { // Une news sur deux sera en couleur
                    ?>
                        <a href="news/<?php echo htmlspecialchars($article['url']); ?>-<?php echo htmlspecialchars($article['id']); ?>" style="text-decoration-color: black; text-decoration: none;" class="list-group-item justify-content-center list-group-item-secondary animation fadeLeft liste-item-news">
                            <!-- News en couleur -->
                        <?php
                    } else { ?>
                            <a href="news/<?php echo htmlspecialchars($article['url']); ?>-<?php echo htmlspecialchars($article['id']); ?>" style="text-decoration-color: black; text-decoration: none;" class="list-group-item justify-content-center list-group-item-light animation fadeLeft liste-item-news">
                                <!-- L'url est composé à l'aide de l'url rewriting, de l'url marqué dans la base de données ainsi que de l'id -->
                            <?php
                        }
                            ?>
                            <img src="/Articles/<?php echo $article['date_article_dossier']; ?>/<?php echo $article['url']; ?>/miniature/<?php echo $article['nom_miniature']; ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-fluid img-news img-thumbnail" style="float:left; height: 200px; background-color:transparent;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->

                            <div class="row">
                                <div class="col">
                                    <h1 class="list-group-item-heading text-body texte-titre text-break"><?php echo $article['titre']; ?></h1> <!-- Titre -->
                                </div>
                                <div class="col d-none d-lg-block">
                                    <!-- S'affiche que sur grand écran -->
                                    <p class="list-group-item-text pull-right text-right lead"><?php echo $article['date_article']; ?></p> <!-- Date de la news -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="list-group-item-text pull-right lead" style="word-wrap: break-word"><?php if ($article['description'] != "") {
                                                                                                                        echo htmlspecialchars($article['description']);
                                                                                                                    } else {
                                                                                                                        echo nl2br(tronquerTexte(remplacementBBCode(htmlspecialchars($article['contenu']), false, true), 150, "news/" . $article['url'] . "-" . $article['id']));
                                                                                                                    } ?> </p> <!-- On met la description sauf si il y en a pas dans ce cas, on prend les premiers mots de la description -->
                                </div>
                            </div>


                            <hr class="d-lg-none">
                            <div class="row">
                                <div class="col d-lg-none">
                                    <p class="list-group-item-text pull-left text-left date_article_index lead"><?php echo htmlspecialchars($article['date_article']); ?></p> <!-- Date de la news -->
                                </div>
                                <div class="col">
                                    <p class="list-group-item-text pull-right text-right lead"> <span class="fa-stack fa-lg">
                                            <i class="fas fa-comment fa-stack-2x"></i>
                                            <i class="fa fa-inverse fa-stack-1x">
                                                <?php echo htmlspecialchars($nbCommentaires[0]['nb_com']); ?></i></span></p>
                                </div>

                            </div>
                            <?php
                            for($j=0; $j<count($jeuxLier); $j++) { // On assemble tous les jeux liés
                                if (!empty($jeuxLier[$j])) { ?>
                                    <div class="row" style="margin: 1px;">
                                        <h5><em><u>
                                                    <?php echo $jeuxLier[$j]['jeu_lier']; ?>
                                                </u></em></h5>
                                    </div>
                            <?php
                                }
                            } 
                            ?>
                            </a>
                </div>
            <?php
            }
            include('modification_article.php'); // Appel fenêtre modification article
            ?>

        </ul>

        <nav aria-label="navigation news" class="d-flex justify-content-center" style="margin-top: 20px;">
            <!-- Liste des pages de news -->
            <!-- Pagination -->
            <ul class="pagination pagination-circle">
                <?php
                $nbPageTotal = ceil($texteListeNews->nbArticles / 20); // Nombre de page de news que peut avoir le site à l'aide du nombre de news (20 news par page)

                if ($pageNewsSelectionner == 1) { // Si la page selectionnée est la une, on désactive le bouton précédent 
                ?>
                    <li class="page-item disabled">
                        <a class="page-link changement-page" aria-label="Previous" href="#" tabindex="-1">
                            <span aria-hidden="true"><</span>
                            <span class="sr-only">Précédent</span> <!-- Précedent -->
                        </a>
                    </li>
                <?php
                } else {
                ?>
                    <li class="page-item">
                        <a class="page-link changement-page" aria-label="Previous" href="/index.php?page=<?php echo htmlspecialchars($pageNewsSelectionner) - 1; ?>">
                            <span aria-hidden="true"><</span><span class="sr-only">Précédent</span> <!-- Précedent -->
                        </a>
                    </li>
                    <?php
                }

                for ($i = 1; $i <= $nbPageTotal; $i++) { // Parcours des pages

                    if ($pageNewsSelectionner == $i) { // Si la page selectionnée est égale à la page du bouton, on rend la page du bouton active 
                    ?>
                        <li class="page-item active">
                            <a class="page-link numero-page" href="/index.php?page=<?php echo htmlspecialchars($i); ?>"><?php echo htmlspecialchars($i); ?></a>
                        </li>
                    <?php
                    } else { ?>
                        <li class="page-item">
                            <a class="page-link numero-page" href="/index.php?page=<?php echo htmlspecialchars($i); ?>"><?php echo htmlspecialchars($i); ?></a>
                        </li>
                    <?php }
                }

                if ($pageNewsSelectionner == $nbPageTotal || $texteListeNews->nbArticles == 0) { // Si la page selectionnée est la derniere, on désactive le bouton suivant 
                    ?>
                    <li class="page-item disabled">
                        <a class="page-link changement-page" aria-label="Next" href="#" tabindex="-1">
                            <span aria-hidden="true">></span>
                            <span class="sr-only">Suivant</span> <!-- Suivant -->
                        </a>
                    </li>
                <?php
                } else { ?>
                    <li class="page-item">
                        <a class="page-link changement-page" aria-label="Next" href="/index.php?page=<?php echo htmlspecialchars($pageNewsSelectionner) + 1; ?>">
                            <span aria-hidden="true">></span>
                            <span class="sr-only">Suivant</span> <!-- Suivant -->
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
    <!-- </div> -->
</div>