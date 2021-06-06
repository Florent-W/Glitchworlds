<?php
$offsetPageNews = $nombreNewsParPage * ($pageSelectionner - 1); // Offset pour dire quand on commence à prendre les jeux

if (!empty($_GET['tri'])) { // Si le choix du tri, order by de la recherche est fait, on le sélectionne
    if ($_GET['tri'] == "ajoute") {
        $tri = 1;
        $ordre_tri = "DESC";
    } else if ($_GET['tri'] == "nouveau") {
        $tri = 6;
        $ordre_tri = "DESC";
    } else if ($_GET['tri'] == "ancien") {
        $tri =  6;
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
<h3 class="text-center">News :</h3>
<ul class="list-group" style="top:100px">

    <?php
    $reponse = $bdd->prepare('SELECT COUNT(*) as nb_news FROM article WHERE titre LIKE :article'); // Nombre de news trouvée, si aucune, on n'affichera pas
    $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
    $reponse->execute();
    $donnees = $reponse->fetch();
    $nbNewsTrouver = $donnees['nb_news'];
    $reponse->closeCursor();

    if ($ordre_tri == "DESC") { // On regarde l'ordre du tri qu'on veut
        $reponse = $bdd->prepare('SELECT article.*, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, DATE_FORMAT(date_creation, "%d %M %Y à %Hh%imin%ss") AS date_news FROM article WHERE titre LIKE :article AND article.approuver = 1 ORDER BY :tri DESC LIMIT :nombreArticleParPage OFFSET :offsetPageNews'); // Sélection des news et formatage de la date à partir de la page de jeu selectionnée
        $reponse->bindValue('offsetPageNews', $offsetPageNews, PDO::PARAM_INT);
        $reponse->bindValue('nombreArticleParPage', $nombreNewsParPage, PDO::PARAM_INT);
        $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
        $reponse->bindValue('tri', $tri, PDO::PARAM_INT);
        $reponse->execute();
    } else { // On regarde l'ordre du tri qu'on veut
        $reponse = $bdd->prepare('SELECT article.*, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier, DATE_FORMAT(date_creation, "%d %M %Y à %Hh%imin%ss") AS date_news FROM article WHERE titre LIKE :article AND article.approuver = 1 ORDER BY :tri ASC LIMIT :nombreArticleParPage OFFSET :offsetPageNews'); // Sélection des news et formatage de la date à partir de la page de jeu selectionnée
        $reponse->bindValue('offsetPageNews', $offsetPageNews, PDO::PARAM_INT);
        $reponse->bindValue('nombreArticleParPage', $nombreNewsParPage, PDO::PARAM_INT);
        $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
        $reponse->bindValue('tri', $tri, PDO::PARAM_INT);
        $reponse->execute();
    }

    if ($nbNewsTrouver > 0) {
        $positionNews = 0; // On va voir la place de la news et une fois sur deux, elle sera en couleur 

        while ($donnees = $reponse->fetch()) {

    ?>
            <!-- Liste news -->
            <div class="liste-news-jeu">
            <?php
            if ($positionNews % 2 == 0) { // Un jeu sur deux sera en couleur
            ?> <a href="news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>" style="text-decoration-color: black; text-decoration: none;" class="list-group-item justify-content-center list-group-item-secondary liste-item-sans-bordure">
                    <!-- L'url est composé à l'aide de l'url rewriting, de l'url marqué dans la base de données ainsi que de l'id -->
                <?php
            } else {
                ?> <a href="news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>" style="text-decoration-color: black; text-decoration: none;" class="list-group-item justify-content-center list-group-item-light liste-item-sans-bordure">
                    <?php
                }
                    ?>
                    <img src="/Articles/<?php echo $donnees['date_article_dossier']; ?>/<?php echo $donnees['titre']; ?>/miniature/<?php echo $donnees['nom_miniature']; ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-fluid img-news img-thumbnail" style="float:left; height: 200px; background-color: transparent;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                    <div class="row">
                        <div class="col">
                            <h1 class="list-group-item-heading text-body"><?php echo $donnees['titre']; ?></h1> <!-- Titre de la news -->
                        </div>
                        <div class="col">
                            <p class="list-group-item-text pull-right text-right lead"><?php echo $donnees['date_news']; ?></p> <!-- Date de la news -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="list-group-item-text pull-right lead" style="word-wrap: break-word"><?php echo tronquerTexte(remplacementBBCode($donnees['contenu'], false, true), 150, "news/" . $donnees['url'] . "-" . $donnees['id']) ?> </p> <!-- Contenu -->
                        </div>
                    </div>
                    </a>

                    <?php if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier une news 
                    ?>
                        <div class="row text-right" style="margin-bottom: 10px; margin-top: 10px;">
                            <div class="col">
                                <form class="form" method="post" action="/modifier_news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>">
                                    <button type="submit" id="modifier_article" class="btn btn-info" title="Modifier article">Modifier Article</button> <!-- Bouton modif -->
                                </form>
                            </div>
                        </div>
                    <?php }
                    ?>
                <?php $positionNews++; // On augmente la position de news vu qu'on change de news
                ?>
                </div>
                <?php
            }
        } else { // Si aucun résultat n'a été trouvé, un message d'erreur est affiché 
                ?>
                <p class="text-center">Aucune news n'a été trouvée.</p>
            <?php
        }
        $reponse->closeCursor();
            ?>
</ul>
<script>
   // jouerSonBruitage();
</script>

<!-- Liste des pages de recherche des news -->
<!-- Pagination -->
<nav aria-label="navigation recherche" class="d-flex justify-content-center" style="margin-top: 20px;">

    <ul class="pagination pagination-circle">
        <?php
        $nbPageTotal = ceil($nbNewsTrouver / $nombreNewsParPage); // Nombre de page de recherche que peut avoir le site à l'aide du nombre d'articles (20 articles par page)

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
                </a> </li>
        <?php
        } else {
        ?>
            <li class="page-item">
                <a class="page-link changement-page" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=1" aria-label="PremierePage">
                    <span aria-hidden="true">
                        <<</span> <span class="sr-only">Premier
                    </span> <!-- Premiere page -->
                </a>
            </li>
            <li class="page-item">
                <a class="page-link changement-page" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner - 1; ?>" aria-label="Previous">
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
                <a class="page-link numero-page" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                                if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=1">1</a>
            </li>
        <?php
        } else { ?>
            <li class="page-item">
                <a class="page-link numero-page" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                                if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=1">1</a>
            </li>
        <?php }

        /*
        for ($i = 1; $i <= $nbPageTotal; $i++) { // Parcours des pages
            if ($pageSelectionner == $i) { // Si la page selectionnée est égale à la page du bouton, on rend la page du bouton active 
            ?>
                <li class="page-item active">
                    <a class="page-link numero-page" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php
            } else { ?>
                <li class="page-item">
                    <a class="page-link numero-page" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php }
        }
        */

        if ($pageSelectionner - 1 > 1 && $pageSelectionner - 1 < $nbPageTotal) { // On met la page précédente que si ce n'est pas un ou inférieur au nombre de page
        ?>
            ...
            <li class="page-item">
                <!-- Page précédente -->
                <a class="page-link numero-page" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                                if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner - 1; ?>"><?php echo $pageSelectionner - 1; ?></a>
            </li>
        <?php
        }

        if ($pageSelectionner > 1 && $pageSelectionner < $nbPageTotal) { // On met la page sélectionné si elle n'a pas été déjà mise
        ?>
            <li class="page-item active">
                <a class="page-link numero-page" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                                if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner; ?>"><?php echo $pageSelectionner; ?></a>
            </li>
        <?php
        }

        if ($pageSelectionner + 1 < $nbPageTotal && $pageSelectionner + 1 > 1) { // On met la page suivante que si ce n'est pas la dernière et que la page est au moins à un 
        ?>
            <li class="page-item">
                <!-- Page suivante -->
                <a class="page-link numero-page" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                                if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner + 1; ?>"><?php echo $pageSelectionner + 1; ?></a>
            </li>
            ...
        <?php
        }

        if ($pageSelectionner == $nbPageTotal && $nbPageTotal > 1) { // On met la dernière page, si la page selectionnée est la dernière, on rend la page du bouton active, pas besoin de remettre la page si c'est la première
            ?>
                <li class="page-item active">
                <a class="page-link numero-page" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                                if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $nbPageTotal; ?>"><?php echo $nbPageTotal; ?></a>
                </li>
            <?php
            }
             else if ($pageSelectionner <= $nbPageTotal && $nbPageTotal > 1) { // Si la page selectionné n'est pas la dernière ni la première, on ne la met pas active ?>
                <li class="page-item">
                <a class="page-link numero-page" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                                if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $nbPageTotal; ?>"><?php echo $nbPageTotal; ?></a>
                </li>
            <?php }
        

        if ($pageSelectionner >= $nbPageTotal or $nbNewsTrouver == 0) { // Si la page selectionnée est la derniere, on désactive le bouton suivant 
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
                <a class="page-link changement-page" aria-label="Next" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner + 1; ?>">
                    <span aria-hidden="true">></span>
                    <span class="sr-only">Suivant</span> <!-- Suivant -->
                </a>
            </li>
            <li class="page-item">
            <a class="page-link changement-page" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $nbPageTotal; ?>" aria-label="DernierePage">                    
                                                                                                                                        <span aria-hidden="true">
                        >></span> <span class="sr-only">Dernier
                    </span> <!-- Derniere page -->
                </a>
            </li>
        <?php }
        ?>
    </ul>
</nav>


<?php if ($pageSelectionner <= $nbPageTotal) { // Si la page sélectionné est supérieure au nombre de page de résultat, on affichera pas le parcours de résultats
    $resultatsSurLaPagePremiereNews = $pageSelectionner * $nombreNewsParPage - ($nombreNewsParPage - 1); // Calcul de la position de la première news affichés sur la page (page * nombre de news par page - (nombre de news par page - la position de la news))

    if ($pageSelectionner < $nbPageTotal) { // Si la page selectionné est inférieure au nombre de page que donne la recherche, on peut faire le calcul de la position de la dernière news affichés
        $resultatsSurLaPageDerniereNews = $pageSelectionner * $nombreNewsParPage - ($nombreNewsParPage - $nombreNewsParPage); // Calcul de la position de la dernière news affichés sur la page (page * nombre de news par page - (nombre de news par page - la position de la news))
    } else if ($pageSelectionner == $nbPageTotal) { // Si la page selectionné est égale, on ne peut plus faire le calcul car si le nombre de news trouvés n'est pas un multiple du nombre de page trouvés alors il donnera pas le bonne position, à la place, il suffit de donner le nombre de news trouvés comme position de la dernière news
        $resultatsSurLaPageDerniereNews = $nbNewsTrouver;
    }
?>
    <p class="text-center">Affichage des résultats : <?php echo $resultatsSurLaPagePremiereNews; ?> - <?php echo $resultatsSurLaPageDerniereNews; ?>.</p> <!-- Affichage de la position des news de la page en cours -->
<?php } ?>
<p class="text-center">La recherche à retournée <?php echo $nbNewsTrouver; ?> news. (<?php echo $nombreNewsParPage; ?> news affichées par page)</p> <!-- Nombre de news trouvées -->