<?php
$offsetPageJeu = $nombreJeuParPage * ($pageSelectionner - 1); // Offset pour dire quand on commence à prendre les jeux

if (!empty($_GET['categorie_jeu'])) { // Si la catégorie du jeu est là, on la sélectionne
    $categorie_jeu = $_GET['categorie_jeu'];
} else { // Si la catégorie du jeu n'a pas été selectionné, on l'a met à vide
    $categorie_jeu = "";
}

if (!empty($_GET['tri'])) { // Si le choix du tri, order by de la recherche est fait, on le sélectionne
    if ($_GET['tri'] == "ajoute") {
        $tri = 1;
        $ordre_tri = "DESC";
    } else if ($_GET['tri'] == "nouveau") {
        $tri = 5;
        $ordre_tri = "DESC";
    } else if ($_GET['tri'] == "ancien") {
        $tri =  5;
        $ordre_tri = "ASC";
    } else {
        $tri = 1;
        $ordre_tri = "DESC";
    }
} else { // Si un tri n'est pas séléectionné, on ordonne par l'id
    $tri = 1;
    $ordre_tri = "DESC";
}
?>
<h3 class="text-center">Jeux :</h3>
<ul class="list-group" style="top:100px">
    <?php
    if (!empty($_GET['categorie_jeu'])) { // Si la catégorie du jeu est là, on la sélectionne
        $reponse = $bdd->prepare('SELECT COUNT(*) as nb_jeu FROM jeu INNER JOIN categorie_jeu ON jeu.id_categorie = categorie_jeu.id WHERE jeu.nom LIKE :article and categorie_jeu.nom = :nom_categorie_jeu'); // Nombre de jeux trouvés, si aucun, on n'affichera pas
        $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
        $reponse->bindValue('nom_categorie_jeu', $_GET['categorie_jeu'], PDO::PARAM_STR);
        $reponse->execute();
        $donnees = $reponse->fetch();
        $nbJeuTrouver = $donnees['nb_jeu'];
        $reponse->closeCursor();

        if ($ordre_tri == "DESC") { // On regarde l'ordre du tri qu'on veut
            $reponse = $bdd->prepare('SELECT jeu.*, DATE_FORMAT(date_sortie, "%d %M %Y") AS date_jeu FROM jeu INNER JOIN categorie_jeu ON jeu.id_categorie = categorie_jeu.id WHERE jeu.nom LIKE :article and categorie_jeu.nom = :nom_categorie_jeu ORDER BY :tri DESC LIMIT :nombreJeuParPage OFFSET :offsetPageJeu'); // Sélection des jeux et formatage de la date à partir de la page de jeu selectionnée

            /* $reponse = $bdd->prepare('(SELECT jeu.*, DATE_FORMAT(date_sortie, "%d %M %Y à %Hh%imin%ss") AS date_news FROM jeu WHERE jeu.nom LIKE :article ORDER BY id DESC)
                                UNION (SELECT news.* FROM news WHERE news.titre LIKE :article ORDER BY id DESC)'); */ // Sélection des jeux et formatage de la date à partir de la page de jeu selectionnée
            $reponse->bindValue('offsetPageJeu', $offsetPageJeu, PDO::PARAM_INT);
            $reponse->bindValue('nombreJeuParPage', $nombreJeuParPage, PDO::PARAM_INT);
            $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
            $reponse->bindValue('nom_categorie_jeu', $_GET['categorie_jeu'], PDO::PARAM_STR);
            $reponse->bindValue('tri', $tri, PDO::PARAM_INT);
            $reponse->execute();
        } else {
            $reponse = $bdd->prepare('SELECT jeu.*, DATE_FORMAT(date_sortie, "%d %M %Y") AS date_jeu FROM jeu INNER JOIN categorie_jeu ON jeu.id_categorie = categorie_jeu.id WHERE jeu.nom LIKE :article and categorie_jeu.nom = :nom_categorie_jeu ORDER BY :tri ASC LIMIT :nombreJeuParPage OFFSET :offsetPageJeu'); // Sélection des jeux et formatage de la date à partir de la page de jeu selectionnée

            /* $reponse = $bdd->prepare('(SELECT jeu.*, DATE_FORMAT(date_sortie, "%d %M %Y à %Hh%imin%ss") AS date_news FROM jeu WHERE jeu.nom LIKE :article ORDER BY id DESC)
                                    UNION (SELECT news.* FROM news WHERE news.titre LIKE :article ORDER BY id DESC)'); */ // Sélection des jeux et formatage de la date à partir de la page de jeu selectionnée
            $reponse->bindValue('offsetPageJeu', $offsetPageJeu, PDO::PARAM_INT);
            $reponse->bindValue('nombreJeuParPage', $nombreJeuParPage, PDO::PARAM_INT);
            $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
            $reponse->bindValue('nom_categorie_jeu', $_GET['categorie_jeu'], PDO::PARAM_STR);
            $reponse->bindValue('tri', $tri, PDO::PARAM_INT);
            $reponse->execute();
        }
    } else { // Si la catégorie du jeu n'a pas été selectionné, on l'a met à vide
        if ($ordre_tri == "DESC") { // On regarde l'ordre du tri qu'on veut
            $reponse = $bdd->prepare('SELECT COUNT(*) as nb_jeu FROM jeu INNER JOIN categorie_jeu ON jeu.id_categorie = categorie_jeu.id WHERE jeu.nom LIKE :article'); // Nombre de jeux trouvés, si aucun, on n'affichera pas
            $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
            $reponse->execute();
            $donnees = $reponse->fetch();
            $nbJeuTrouver = $donnees['nb_jeu'];
            $reponse->closeCursor();

            $reponse = $bdd->prepare('SELECT jeu.*, DATE_FORMAT(date_sortie, "%d %M %Y") AS date_jeu FROM jeu INNER JOIN categorie_jeu ON jeu.id_categorie = categorie_jeu.id WHERE jeu.nom LIKE :article ORDER BY :tri DESC LIMIT :nombreJeuParPage OFFSET :offsetPageJeu'); // Sélection des jeux et formatage de la date à partir de la page de jeu selectionnée
            $reponse->bindValue('nombreJeuParPage', $nombreJeuParPage, PDO::PARAM_INT);
            $reponse->bindValue('offsetPageJeu', $offsetPageJeu, PDO::PARAM_INT);
            $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
            $reponse->bindValue('tri', $tri, PDO::PARAM_INT);
            $reponse->execute();
        } else {
            $reponse = $bdd->prepare('SELECT COUNT(*) as nb_jeu FROM jeu INNER JOIN categorie_jeu ON jeu.id_categorie = categorie_jeu.id WHERE jeu.nom LIKE :article'); // Nombre de jeux trouvés, si aucun, on n'affichera pas
            $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
            $reponse->execute();
            $donnees = $reponse->fetch();
            $nbJeuTrouver = $donnees['nb_jeu'];
            $reponse->closeCursor();

            $reponse = $bdd->prepare('SELECT jeu.*, DATE_FORMAT(date_sortie, "%d %M %Y") AS date_jeu FROM jeu INNER JOIN categorie_jeu ON jeu.id_categorie = categorie_jeu.id WHERE jeu.nom LIKE :article ORDER BY :tri ASC LIMIT :nombreJeuParPage OFFSET :offsetPageJeu'); // Sélection des jeux et formatage de la date à partir de la page de jeu selectionnée
            $reponse->bindValue('nombreJeuParPage', $nombreJeuParPage, PDO::PARAM_INT);
            $reponse->bindValue('offsetPageJeu', $offsetPageJeu, PDO::PARAM_INT);
            $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
            $reponse->bindValue('tri', $tri, PDO::PARAM_INT);
            $reponse->execute();
        }
    }
?>
        <?php
    // Si les jeu sont trouvé, on les affiche
    if ($nbJeuTrouver > 0) {
        $positionJeu = 0; // On va voir la place du jeu et une fois sur deux, il sera en couleur 

        while ($donnees = $reponse->fetch()) {
    ?>
            <!-- Liste jeu -->
            <div class="liste-news-jeu">
            <?php
            if ($positionJeu % 2 == 0) { // Un jeu sur deux sera en couleur
            ?>
                <a href="/jeu/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>" style="text-decoration-color: black; text-decoration: none;" class="list-group-item justify-content-center list-group-item-secondary liste-item-sans-bordure">
                    <!-- L'url est composé à l'aide de l'url rewriting, de l'url marqué dans la base de données ainsi que de l'id -->
                <?php } else {
                ?>
                    <a href="/jeu/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>" style="text-decoration-color: black; text-decoration: none;" class="list-group-item justify-content-center list-group-item-light liste-item-sans-bordure">
                    <?php
                } ?>
                    <img src="/Jeux/<?php echo $donnees['nom']; ?>/miniature/<?php echo $donnees['nom_miniature']; ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-thumbnail img-fluid float-md-left" style="width:auto; height: auto; max-width:200 px; max-height:320px; background-color:transparent;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                    <?php /*
                    if (!file_exists('Jeux' . '/' . $donnees['nom'] . '/' . 'miniature')) {
                        mkdir('Jeux' . '/' . $donnees['nom'] . '/' . 'miniature', 0777, true);
                    }
                    rename("miniature" . "/" . $donnees['nom_miniature'], 'Jeux' . '/' . $donnees['nom'] . '/' . 'miniature' . '/' . $donnees['nom_miniature']); // Bouge l'image sans la redimensionner, il faudra faire en sorte qu'elle ne dépasse pas une taille
                    */
                    ?>
                    <div class="row">
                        <div class="col">
                            <?php /* ?><a href="news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>" style="text-decoration-color: black"> <?php */ ?>
                            <h1 class="list-group-item-heading text-body"><?php echo $donnees['nom']; ?></h1> <!-- Nom du jeu -->
                        </div>
                        <div class="col">
                            <p class="list-group-item-text pull-right text-right lead"><?php echo $donnees['date_jeu']; ?></p> <!-- Date du jeu -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="list-group-item-text pull-right lead" style="word-wrap: break-word"><?php echo tronquerTexte(remplacementBBCode($donnees['contenu'], false, true), 150, "jeu/" . $donnees['url'] . "-" . $donnees['id']) ?> </p> <!-- Contenu, on veut supprimer aussi les balises -->
                        </div>
                    </div>
                    </a>

                    <?php if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier une news 
                    ?>
                        <?php /* ?> <a href="modifier_news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>">  <?php */ ?>
                        <div class="row text-right" style="margin-bottom: 10px; margin-top: 10px;">
                            <div class="col">
                                <form class="form" method="post" action="/modifier_jeu/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>">
                                    <button type="submit" id="modifier_jeu" class="btn btn-info" title="Modifier jeu">Modifier Jeu</button> <!-- Bouton modif -->
                                </form>
                            </div>
                        </div>
                    <?php }
                    ?>

                <?php
                $positionJeu++; // On augmente la position du jeu vu qu'on change de jeu
                ?>
                </div>
                <?php
            }
        } else { // Si aucun résultat n'a été trouvé, un message d'erreur est affiché 
                ?>
                <p class="text-center">Aucun jeu n'a été trouvé.</p>
            <?php
        }
        $reponse->closeCursor();
            ?>
</ul>
<script>
   // jouerSonBruitage();
</script>

<!-- Liste des pages de recherche des jeux -->
<!-- Pagination -->
<nav aria-label="navigation recherche" class="d-flex justify-content-center" style="margin-top: 20px;">

    <ul class="pagination pagination-circle">
        <?php
        $nbPageTotal = ceil($nbJeuTrouver / $nombreJeuParPage); // Nombre de page de recherche que peut avoir le site à l'aide du nombre d'articles (20 articles par page)
        $nom_page = $_SERVER['PHP_SELF']; // Va permettre de savoir si on est sur la page de la recherche ou la liste des jeux

        if ($pageSelectionner == 1 or $pageSelectionner > $nbPageTotal) { // Si la page selectionnée est la une, on désactive le bouton précédent 
        ?>
            <li class="page-item disabled">
                <a class="page-link changement-page" aria-label="PremierePage" href="#" tabindex="-1">
                    <span aria-hidden="true">
                        <<</span> <span class="sr-only">Premier
                    </span> <!-- Premiere page -->
                </a>
            </li>

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
                <a class="page-link changement-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                            else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=1" aria-label="PremierePage">
                    <span aria-hidden="true">
                        <<</span> <span class="sr-only">Premier
                    </span> <!-- Premiere page -->
                </a>
            </li>
            <li class="page-item">
                <a class="page-link changement-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                            else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner - 1; ?>" aria-label="Previous">
                    <span aria-hidden="true">
                        <</span> <span class="sr-only">Précédent
                    </span> <!-- Précedent -->
                </a>
            </li>
        <?php
        }

        if ($pageSelectionner == 1) { // On met la première page, si la page selectionnée est la première, on rend la page du bouton active 
        ?>
            <li class="page-item active">
                <a class="page-link numero-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                        else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=1">1</a>
            </li>
        <?php
        } else { ?>
            <li class="page-item">
                <a class="page-link numero-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                        else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=1">1</a>
            </li>
        <?php }
        /*
        for ($i = 1; $i <= $nbPageTotal && $i < 5; $i++) { // Parcours des pages et si c'est plus grand que 5, on arrete
            if ($pageSelectionner == $i) { // Si la page selectionnée est égale à la page du bouton, on rend la page du bouton active 
            ?>
                <li class="page-item active">
                    <a class="page-link numero-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                            else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php
            } else { ?>
                <li class="page-item">
                    <a class="page-link numero-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                            else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php }
        }
        
*/
        if ($pageSelectionner - 1 > 1 && $pageSelectionner - 1 < $nbPageTotal) { // On met la page précédente que si ce n'est pas un ou inférieur au nombre de page
        ?>
            ...
            <li class="page-item">
                <!-- Page précédente -->
                <a class="page-link numero-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                        else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner - 1; ?>"><?php echo $pageSelectionner - 1; ?></a>
            </li>
        <?php
        }

        if ($pageSelectionner > 1 && $pageSelectionner < $nbPageTotal) { // On met la page sélectionné si elle n'a pas été déjà mise
        ?>
            <li class="page-item active">
                <a class="page-link numero-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                        else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner; ?>"><?php echo $pageSelectionner; ?></a>
            </li>
        <?php
        }

        if ($pageSelectionner + 1 < $nbPageTotal && $pageSelectionner + 1 > 1) { // On met la page suivante que si ce n'est pas la dernière et que la page est au moins à un 
        ?>
            <li class="page-item">
                <!-- Page suivante -->
                <a class="page-link numero-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                        else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner + 1; ?>"><?php echo $pageSelectionner + 1; ?></a>
            </li>
            ...
        <?php
        }

        if ($pageSelectionner == $nbPageTotal && $nbPageTotal > 1) { // On met la dernière page, si la page selectionnée est la dernière, on rend la page du bouton active, pas besoin de remettre la page si c'est la première
        ?>
            <li class="page-item active">
                <a class="page-link numero-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                        else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $nbPageTotal; ?>"><?php echo $nbPageTotal; ?></a>
            </li>
        <?php
        } else if ($pageSelectionner <= $nbPageTotal && $nbPageTotal > 1) { // Si la page selectionné n'est pas la dernière ni la première, on ne la met pas active 
        ?>
            <li class="page-item">
                <a class="page-link numero-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                        else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $nbPageTotal; ?>"><?php echo $nbPageTotal; ?></a>
            </li>
        <?php }

        if ($pageSelectionner >= $nbPageTotal or $nbPageTotal == 0) { // Si la page selectionnée est la derniere, on désactive le bouton suivant 
        ?>
            <li class="page-item disabled">
                <a class="page-link changement-page" aria-label="Next" href="#" tabindex="-1">
                    <span aria-hidden="true">></span>
                    <span class="sr-only">Suivant</span> <!-- Suivant -->
                </a>
            </li>
            <li class="page-item disabled">
                <a class="page-link changement-page" aria-label="DernierePage" href="#" tabindex="-1">
                    <span aria-hidden="true">
                        >></span> <span class="sr-only">Dernier
                    </span> <!-- Derniere page -->
                </a>
            </li>
        <?php
        } else { ?>
            <li class="page-item">
                <a class="page-link changement-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                            else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner + 1; ?>" aria-label="Next">
                    <span aria-hidden="true">></span>
                    <span class="sr-only">Suivant</span> <!-- Suivant -->
                </a>
            </li>
            <li class="page-item">
                <a class="page-link changement-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                            else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $nbPageTotal; ?>" aria-label="DernierePage">
                    <span aria-hidden="true">
                        >></span> <span class="sr-only">Dernier
                    </span> <!-- Derniere page -->
                </a>
            </li>
        <?php }
        ?>

        <div class="form-group">
            <input class="form-control" type="number" min="1" max="<?php echo $nbPageTotal; ?>" value="<?php if (isset($pageSelectionner)) echo $pageSelectionner; ?>" placeholder="Page" name="page" id="page" aria-label="RechercherPage"> <!-- Recherche page -->
            <!-- Le script va servir à une fois que le numéro de la page à été entrer et que la touche entrée est pressé, va etre rediger vers la page demandé -->
            <script>
                inputPageRecherche();
            </script>
        </div>
    </ul>
</nav>

<?php if ($pageSelectionner <= $nbPageTotal) { // Si la page sélectionné est supérieure au nombre de page de résultat, on affichera pas le parcours de résultats
    $resultatsSurLaPagePremierJeu = $pageSelectionner * $nombreJeuParPage - ($nombreJeuParPage - 1); // Calcul de la position du premier jeu affiché sur la page (page * nombre de jeu par page - (nombre de jeu par page - la position du jeu))

    if ($pageSelectionner < $nbPageTotal) { // Si la page selectionné est inférieure au nombre de page que donne la recherche, on peut faire le calcul de la position du dernier jeu affichés
        $resultatsSurLaPageDernierJeu = $pageSelectionner * $nombreJeuParPage - ($nombreJeuParPage - $nombreJeuParPage); // Calcul de la position du dernier jeu affiché sur la page (page * nombre de jeux par page - (nombre de jeux par page - la position du jeu))
    } else if ($pageSelectionner == $nbPageTotal) { // Si la page selectionné est égale, on ne peut plus faire le calcul car si le nombre de jeux trouvés n'est pas un multiple du nombre de page trouvés alors il donnera pas le bonne position, à la place, il suffit de donner le nombre de jeux trouvés comme position du dernier jeu
        $resultatsSurLaPageDernierJeu = $nbJeuTrouver;
    }
?>
    <p class="text-center">Affichage des résultats : <?php echo $resultatsSurLaPagePremierJeu; ?> - <?php echo $resultatsSurLaPageDernierJeu; ?>.</p> <!-- Affichage de la position des jeux de la page en cours -->
<?php } ?>
<p class="text-center">La recherche à retournée <?php echo $nbJeuTrouver; ?> jeux. (<?php echo $nombreJeuParPage; ?> jeux affichés par page)</p> <!-- Nombre de jeux trouvés -->