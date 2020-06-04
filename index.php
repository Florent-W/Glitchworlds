<?php
include('Header.php');
?>

<body>
    <div id="carousel" class="carousel slide d-none d-lg-block" data-ride="carousel" data-interval="5000" style="width:100%; object-fit: contain; height: 51vh; top:20px">
        <!-- Le carousel est présent sur les écrans grands et moyens -->
        <!-- carousel -->
        <ol class="carousel-indicators">
            <?php $reponse = $bdd->prepare("SELECT * FROM carousel ORDER BY page");
            $reponse->execute();
            while ($donnees = $reponse->fetch()) {
                if ($donnees['page'] == "1") { ?>
                    <li data-target="#carousel" data-slide-to="0" class="active"></li> <!-- Pointillé pour choisir la page en bas -->
                <?php
                } else {
                    $page = $donnees['page'] - 1;
                ?>
                    <li data-target="#carousel" data-slide-to="<?php echo $page; ?>"></li>
                <?php
                }
                ?>

            <?php }
            $reponse->closeCursor();
            ?>
        </ol>

        <div class="carousel-inner">
            <?php $reponse = $bdd->prepare('SELECT * FROM article INNER JOIN carousel ON article.id = carousel.id_news ORDER BY page'); // Sélection des news du carousel
            $reponse->execute();
            while ($donnees = $reponse->fetch()) {

                if ($donnees['page'] == "1") { // Seule la première page est active au début
            ?>
                    <div class="carousel-item active">
                    <?php } else { ?>
                        <div class="carousel-item">
                        <?php
                    } ?>
                        <!-- slider -->
                        <img src="miniature/<?php echo $donnees['nom_miniature'] ?>" onerror="this.oneerror=null; this.src='1.jpg';" class="d-block w-100 img-fluid" style="min-height: 20vh; max-height: 43vh; object-fit: contain;">
                        <div class="carousel-caption d-none d-md-block">
                            <h5><?php echo $donnees['titre']; ?></h5>
                            <p><?php echo $donnees['contenu']; ?></p>
                        </div>
                        </div>
                    <?php
                }
                $reponse->closeCursor();
                    ?>

                    </div>

                    <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                        <!-- Bouton précédent pour revenir au slider précédent -->
                        <span class="fa fa-chevron-left" aria-hidden="true" style="color: black; font-size: 3vh"></span>
                        <span class="sr-only">Précédent</span>
                    </a>
                    <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                        <!-- Bouton suivant pour revenir au slider suivant -->
                        <span class="fa fa-chevron-right" aria-hidden="true" style="color: black; font-size: 3vh;"></span>
                        <span class="sr-only">Suivant</span>
                    </a>
        </div>

        <ul class="list-group" style="top:100px">
            <?php
            if (!isset($_GET['page'])) { // Si on arrive sur l'accueil, la page selectionnée par défaut est la une
                $pageNewsSelectionner = 1;
            } else {
                $pageNewsSelectionner = $_GET['page'];
            }

            $offsetPageNews = 20 * ($pageNewsSelectionner - 1); // Offset pour dire quand on commence à prendre les news

            $reponse = $bdd->prepare('SELECT COUNT(*) as nb_article FROM article');
            $reponse->execute();
            $donnees = $reponse->fetch();

            $nbNews = $donnees['nb_article']; // Nombre de news
            $reponse->closeCursor();

            $reponse = $bdd->prepare('SELECT *, DATE_FORMAT(date_creation, "%d %M %Y à %Hh%imin%ss") AS date_article FROM article ORDER BY id DESC LIMIT 20 OFFSET :offsetPageNews'); // Sélection des news et formatage de la date à partir de la page de news selectionnée
            $reponse->bindValue('offsetPageNews', $offsetPageNews, PDO::PARAM_INT);
            $reponse->execute();

            while ($donnees = $reponse->fetch()) {
                // Liste news
                if ($donnees['id'] % 2 == 0) { // Une news sur deux sera en couleur
            ?>

                    <div class="list-group-item list-group-item-secondary"> <!-- News en couleur -->
                    <?php
                } else { ?>
                        <div class="list-group-item list-group-item-light">
                        <?php
                    }
                        ?>
                        <img src="/miniature/<?php echo $donnees['nom_miniature'] ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-thumbnail" style="float:left; height: 200px"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                        <div class="row">
                            <div class="col">
                                <a href="news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>" style="text-decoration-color: black">
                                    <!-- L'url est composé à l'aide de l'url rewriting, de l'url marqué dans la base de données ainsi que de l'id -->
                                    <h1 class="list-group-item-heading text-body"><?php echo $donnees['titre']; ?></h1> <!-- Titre -->
                                </a>
                            </div>
                            <div class="col">
                                <p class="list-group-item-text pull-right text-right lead"><?php echo $donnees['date_article']; ?></p> <!-- Date de la news -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="list-group-item-text pull-right lead" style="word-wrap: break-word"><?php echo remplacementBBCode(nl2br(tronquerTexte($donnees['contenu'], 150, "news/" . $donnees['url'] . "-" . $donnees['id']))) ?> </p> <!-- Contenu -->
                            </div>
                            <?php if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier une news 
                            ?>
                                <div class="col">
                                    <a href="modifier_news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>">
                                        <p class="list-group-item-text pull-right text-right lead">Modifier</p> <!-- Modification de la news -->
                                    </a>
                                </div>
                            <?php }
                            ?>
                        </div>
                        </div>
                    <?php
                }
                $reponse->closeCursor();
                    ?>
        </ul>

        <nav aria-label="navigation news" class="d-flex justify-content-center" style="margin-top: 20px;">
            <!-- Liste des pages de news -->
            <!-- Pagination -->
            <ul class="pagination">
                <?php
                $nbPageTotal = ceil($nbNews / 20); // Nombre de page de news que peut avoir le site à l'aide du nombre de news (20 news par page)

                if ($pageNewsSelectionner == 1) { // Si la page selectionnée est la une, on désactive le bouton précédent 
                ?>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Précédent</a>
                    </li>
                <?php
                } else {
                ?>
                    <li class="page-item">
                        <a class="page-link" href="/index.php?page=<?php echo $pageNewsSelectionner - 1; ?>">Précédent</a>
                    </li>
                    <?php
                }

                for ($i = 1; $i <= $nbPageTotal; $i++) { // Parcours des pages

                    if ($pageNewsSelectionner == $i) { // Si la page selectionnée est égale à la page du bouton, on rend la page du bouton active 
                    ?>
                        <li class="page-item active">
                            <a class="page-link" href="/index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php
                    } else { ?>
                        <li class="page-item">
                            <a class="page-link" href="/index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php }
                }

                if ($pageNewsSelectionner == $nbPageTotal) { // Si la page selectionnée est la derniere, on désactive le bouton suivant 
                    ?>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Suivant</a>
                    </li>
                <?php
                } else { ?>
                    <li class="page-item">
                        <a class="page-link" href="/index.php?page=<?php echo $pageNewsSelectionner + 1; ?>">Suivant</a>
                    </li>
                <?php } ?>
            </ul>
        </nav>

</body>

</html>