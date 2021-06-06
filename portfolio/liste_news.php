<div class="view" style="background-image: url('background.jpg'); background-repeat: no-repeat; background-size: cover; background-position: center center;">
    <!-- Background -->
    <div class="groupement-news">
        <!-- Permet que la page des news ne prenne pas toute la page -->
        <?php
        $reponse = $bdd->prepare('SELECT article.id, article.url, article.nom_categorie, article.nom_miniature, article.contenu, article.titre, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, DATE_FORMAT(date_creation, "%d %M %Y") AS date_article FROM article WHERE approuver = 1 ORDER BY id DESC LIMIT 15'); // Sélection des articles
        /* $reponse = $bdd->prepare('(SELECT jeu.*, DATE_FORMAT(date_sortie, "%d %M %Y à %Hh%imin%ss") AS date_news FROM jeu WHERE jeu.nom LIKE :article ORDER BY id DESC)
        UNION (SELECT news.* FROM news WHERE news.titre LIKE :article ORDER BY id DESC)'); */ // Sélection des jeux et formatage de la date à partir de la page de jeu selectionnée
        $reponse->execute();
        ?>
        <div class="liste-derniers-jeux col-xl-2 d-none d-xl-block animated fadeInRight justify-content-center list-group-item-light" style="float: right; height: auto; text-decoration-color: black; text-decoration: none;">
         <!-- Liste déroulante des derniers jeux ajoutés -->
        <h5 class="text-center">Derniers Articles Ajoutés</h5>
        <aside class="demo" style="margin: 10px;">
            <ul class="list-group"> 
                <?php while ($donnees = $reponse->fetch()) {
                ?>
                    <a href="news/<?php echo htmlspecialchars($donnees['url']); ?>-<?php echo htmlspecialchars($donnees['id']); ?>" style="border: 1px solid black; text-decoration: none;">
                        <div class="row">
                        <div class="col-5">
                        <img src="/portfolio/Articles/<?php echo $donnees['date_article_dossier']; ?>/<?php echo $donnees['titre']; ?>/miniature/<?php echo $donnees['nom_miniature']; ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-thumbnail img-fluid" style="width:auto; height: auto; min-width:100%; max-width:100%; max-height:100%; border-style: none !important; background-color:transparent;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                        </div>
                        <div class="col-7 d-flex align-items-center">
                        <div style="color: black; font-size: 1vh;"><?php echo $donnees['titre']; ?></div>
                        </div> </div>
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
        <?php $reponse->closeCursor();
        ?>
        <ul class="list-group col-xl-9 col-lg-12" style="margin:auto;">
            <?php
            if (!isset($_GET['page'])) { // Si on arrive sur l'accueil, la page selectionnée par défaut est la une
                $pageNewsSelectionner = 1;
            } else {
                $pageNewsSelectionner = $_GET['page'];
            }

            ?>

            <!-- Selection du type d'article -->
            <?php if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à voir les articles en attente 
            ?>
                <form class="form-inline form-index my-2 my-lg-0 justify-content-center" method="GET">
                    <div class="form-group">
                        <select class="form-control" name="article_approuver">
                            <!-- Selection article approuver -->
                            <option value="1" <?php if (isset($_GET['article_approuver']) and $_GET['article_approuver'] == "1") echo 'selected="selected"'; ?>>Approuver</option>
                            <option value="0" <?php if (isset($_GET['article_approuver']) and $_GET['article_approuver'] == "0") echo 'selected="selected"'; ?>>En attente</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-outline-success" type="submit">Rechercher</button>
                    </div>
                </form>
            <?php }
            ?>

            <?php
            $offsetPageNews = 20 * ($pageNewsSelectionner - 1); // Offset pour dire quand on commence à prendre les news

            if (isset($_GET['article_approuver'])) {
                $selection_article_approuver = $_GET['article_approuver']; // Si 1, les news selectionnées sont celles approuvés, sinon celle pas encore approuvés
            } else {
                $selection_article_approuver = 1;
            }

            $reponse = $bdd->prepare('SELECT COUNT(*) as nb_article FROM article WHERE approuver = :approuver');
            $reponse->bindValue('approuver', $selection_article_approuver, PDO::PARAM_INT);
            $reponse->execute();
            $donnees = $reponse->fetch();

            $nbNews = $donnees['nb_article']; // Nombre de news
            $reponse->closeCursor();

            $reponse = $bdd->prepare('SELECT article.id, article.url, jeu.nom as nom_jeu, article.nom_categorie, article.nom_miniature, article.contenu, article.titre, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, DATE_FORMAT(date_creation, "%d %M %Y") AS date_article FROM article LEFT JOIN jeu ON article.id_jeu = jeu.id WHERE approuver = :approuver ORDER BY id DESC LIMIT 20 OFFSET :offsetPageNews'); // Sélection des news et formatage de la date à partir de la page de news selectionnée
            $reponse->bindValue('offsetPageNews', $offsetPageNews, PDO::PARAM_INT);
            $reponse->bindValue('approuver', $selection_article_approuver, PDO::PARAM_INT);
            $reponse->execute();

            $positionNews = 0; // On va voir quelle news à quelle place et une fois sur deux, elle sera en couleur 

            while ($donnees = $reponse->fetch()) {
                // Liste news
            ?>
                <div class="liste-news">
                    <?php
                    if ($positionNews % 2 == 0) { // Une news sur deux sera en couleur
                    ?>
                        <a href="news/<?php echo htmlspecialchars($donnees['url']); ?>-<?php echo htmlspecialchars($donnees['id']); ?>" style="text-decoration-color: black; text-decoration: none;" class="list-group-item justify-content-center list-group-item-secondary animated fadeInLeft liste-item-news">
                            <!-- News en couleur -->
                        <?php
                    } else { ?>
                            <a href="news/<?php echo htmlspecialchars($donnees['url']); ?>-<?php echo htmlspecialchars($donnees['id']); ?>" style="text-decoration-color: black; text-decoration: none;" class="list-group-item justify-content-center list-group-item-light animated fadeInLeft liste-item-news">
                                <!-- L'url est composé à l'aide de l'url rewriting, de l'url marqué dans la base de données ainsi que de l'id -->
                            <?php
                        }
                            ?>
                            <img src="/portfolio/Articles/<?php echo $donnees['date_article_dossier']; ?>/<?php echo $donnees['titre']; ?>/miniature/<?php echo $donnees['nom_miniature']; ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-fluid img-news img-thumbnail" style="float:left; height: 200px; background-color:transparent;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                            
                            <?php
                            /*
                        if (!file_exists('Articles' . '/' . $donnees['date_article_dossier'] . "/" . $donnees['titre'] . '/' . "miniature")) {
                            mkdir('Articles' . '/' . $donnees['date_article_dossier'] . "/" . $donnees['titre'] . '/' . "miniature", 0777, true);
                         }
                        rename("miniature" . "/" . $donnees['nom_miniature'], 'Articles' . '/' . $donnees['date_article_dossier'] . "/" . $donnees['titre'] . '/' . "miniature" . '/' . $donnees['nom_miniature']); // Bouge l'image sans la redimensionner, il faudra faire en sorte qu'elle ne dépasse pas une taille
                        */
                            ?>
                            <div class="row">
                                <div class="col">
                                    <h1 class="list-group-item-heading text-body texte-titre text-break"><?php echo $donnees['titre']; ?></h1> <!-- Titre -->
                                </div>
                                <div class="col d-none d-lg-block">
                                    <!-- S'affiche que sur grand écran -->
                                    <p class="list-group-item-text pull-right text-right lead"><?php echo $donnees['date_article']; ?></p> <!-- Date de la news -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="list-group-item-text pull-right lead" style="word-wrap: break-word"><?php echo nl2br(tronquerTexte(remplacementBBCode(htmlspecialchars($donnees['contenu']), false, true), 150, "news/" . $donnees['url'] . "-" . $donnees['id'])); ?> </p> <!-- Contenu -->
                                </div>
                            </div>

                            <?php
                            // Nombre de commentaire
                            $reponse2 = $bdd->prepare('SELECT COUNT(commentaire.id) as nb_com FROM commentaire WHERE id_news = :id');
                            $reponse2->execute(array('id' => $donnees['id']));
                            $donnees2 = $reponse2->fetch();
                            $reponse2->closeCursor();
                            ?>

                            <hr class="d-lg-none">
                            <div class="row">
                                <div class="col d-lg-none">
                                    <p class="list-group-item-text pull-left text-left date_article_index lead"><?php echo htmlspecialchars($donnees['date_article']); ?></p> <!-- Date de la news -->
                                </div>
                                <div class="col">
                                    <p class="list-group-item-text pull-right text-right lead"> <span class="fa-stack fa-lg">
                                            <i class="fas fa-comment fa-stack-2x"></i>
                                            <i class="fa fa-inverse fa-stack-1x">
                                                <?php echo htmlspecialchars($donnees2['nb_com']); ?></i></span></p>
                                </div>

                            </div>
                            <?php if ($donnees['nom_jeu'] != "") { ?>
                                <div class="row" style="margin: 1px;">
                                    <h5><em><u><?php echo htmlspecialchars($donnees['nom_jeu']); // On affiche le nom du jeu si il existe
                                                ?></u></em></h5>
                                </div>
                            <?php }
                            ?>
                            </a>

                            <?php /*
                            if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier une news 
                            ?>
                                <div class="row" style="margin-top: 1vh;">
                                    <div class="col text-right">
                                        <button type="button" data-placement="top" class="btn btn-primary" onclick="remplirChampModificationArticle('<?php echo htmlspecialchars($donnees['id']); ?>', '<?php echo htmlspecialchars($donnees['titre']); ?>', '<?php echo htmlspecialchars($donnees['contenu']); ?>', '<?php echo htmlspecialchars($donnees['nom_categorie']); ?>', '<?php echo htmlspecialchars($donnees['nom_jeu']); ?>', '/gerer_article.php?url=<?php echo htmlspecialchars($donnees['url']); ?>&id=<?php echo htmlspecialchars($donnees['id']); ?>&action=supprimer_article')" title="Modifier article" data-toggle="modal" data-target="#modalModifierArticle"><i class="fas fa-edit"></i></button> <!-- Bouton qui va activer la fenetre de modification -->
                                    </div>
                                    <?php /*
                                <a href="modifier_news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>">
                                    <p class="list-group-item-text pull-right text-right lead">Modifier</p> <!-- Modification de la news -->
                                </a>
                                <a href="/gerer_article.php?url=<?php echo $donnees['url']; ?>&id=<?php echo $donnees['id']; ?>&action=supprimer_article">
                                    <p class="list-group-item-text pull-right text-right lead">Supprimer</p> <!-- Suppression de l'article -->
                                </a>
                                    ?>
                              </div>
                            <?php 
                            } */
                            $positionNews++; // On augmente la position de news vu qu'on change de news
                            ?>
                </div>
            <?php
            }
            include('modification_article.php'); // Appel fenêtre modification article

            $reponse->closeCursor();
            ?>

        </ul>
        <script>
            // jouerSonBruitage();
        </script>
        <nav aria-label="navigation news" class="d-flex justify-content-center" style="margin-top: 20px;">
            <!-- Liste des pages de news -->
            <!-- Pagination -->
            <ul class="pagination pagination-circle">
                <?php
                $nbPageTotal = ceil($nbNews / 20); // Nombre de page de news que peut avoir le site à l'aide du nombre de news (20 news par page)

                if ($pageNewsSelectionner == 1) { // Si la page selectionnée est la une, on désactive le bouton précédent 
                ?>
                    <li class="page-item disabled">
                        <a class="page-link changement-page" aria-label="Previous" href="#" tabindex="-1">
                            <span aria-hidden="true">
                                <</span> <span class="sr-only">Précédent
                            </span> <!-- Précedent -->
                        </a>
                    </li>
                <?php
                } else {
                ?>
                    <li class="page-item">
                        <a class="page-link changement-page" aria-label="Previous" href="/index.php?page=<?php echo htmlspecialchars($pageNewsSelectionner) - 1; ?>">
                            <span aria-hidden="true">
                                <</span> <span class="sr-only">Précédent
                            </span> <!-- Précedent -->
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

                if ($pageNewsSelectionner == $nbPageTotal) { // Si la page selectionnée est la derniere, on désactive le bouton suivant 
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