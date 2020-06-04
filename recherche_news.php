<?php
$offsetPageNews = $nombreNewsParPage * ($pageSelectionner - 1); // Offset pour dire quand on commence à prendre les jeux
?>
<h3 class="text-center">News</h3>
<ul class="list-group" style="top:100px">

    <?php
    $reponse = $bdd->prepare('SELECT COUNT(*) as nb_news FROM article WHERE titre LIKE :article'); // Nombre de news trouvée, si aucune, on n'affichera pas
    $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
    $reponse->execute();
    $donnees = $reponse->fetch();
    $nbNewsTrouver = $donnees['nb_news'];
    $reponse->closeCursor();

    $reponse = $bdd->prepare('SELECT article.*, DATE_FORMAT(date_creation, "%d %M %Y à %Hh%imin%ss") AS date_news FROM article WHERE titre LIKE :article ORDER BY id DESC LIMIT 5 OFFSET :offsetPageNews'); // Sélection des news et formatage de la date à partir de la page de jeu selectionnée
    $reponse->bindValue('offsetPageNews', $offsetPageNews, PDO::PARAM_INT);
    $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
    $reponse->execute();

    if ($nbNewsTrouver > 0) {
        while ($donnees = $reponse->fetch()) {

    ?>
            <!-- Liste news -->
            <div class="list-group-item">
                <img src="/miniature/<?php echo $donnees['nom_miniature'] ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-thumbnail" style="float:left; height: 200px"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                <div class="row">
                    <div class="col">
                        <a href="news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>" style="text-decoration-color: black">
                            <!-- L'url est composé à l'aide de l'url rewriting, de l'url marqué dans la base de données ainsi que de l'id -->
                            <h1 class="list-group-item-heading text-body"><?php echo $donnees['titre']; ?></h1> <!-- Titre de la news -->
                        </a>
                    </div>
                    <div class="col">
                        <p class="list-group-item-text pull-right text-right lead"><?php echo $donnees['date_news']; ?></p> <!-- Date de la news -->
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="list-group-item-text pull-right lead" style="word-wrap: break-word"><?php echo tronquerTexte($donnees['contenu'], 150, "news/" . $donnees['url'] . "-" . $donnees['id']) ?> </p> <!-- Contenu -->
                    </div>
                    <?php if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier une news 
                    ?>
                        <div class="col">
                            <a href="modifier_news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>">
                                <p class="list-group-item-text pull-right text-right lead">Modifier</p> <!-- Modification de la page des news -->
                            </a>
                        </div>
                    <?php }
                    ?>
                </div>
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


<!-- Liste des pages de recherche des news -->
<!-- Pagination -->
<nav aria-label="navigation recherche" class="d-flex justify-content-center" style="margin-top: 20px;">

    <ul class="pagination">
        <?php
        $nbPageTotal = ceil($nbNewsTrouver / $nombreNewsParPage); // Nombre de page de recherche que peut avoir le site à l'aide du nombre d'articles (20 articles par page)

        if ($pageSelectionner == 1 or $pageSelectionner > $nbPageTotal) { // Si la page selectionnée est la une, on désactive le bouton précédent 
        ?>
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Précédent</a>
            </li>
        <?php
        } else {
        ?>
            <li class="page-item">
                <a class="page-link" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?>&page=<?php echo $pageSelectionner - 1; ?>">Précédent</a>
            </li>
            <?php
        }

        for ($i = 1; $i <= $nbPageTotal; $i++) { // Parcours des pages
            if ($pageSelectionner == $i) { // Si la page selectionnée est égale à la page du bouton, on rend la page du bouton active 
            ?>
                <li class="page-item active">
                    <a class="page-link" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php
            } else { ?>
                <li class="page-item">
                    <a class="page-link" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php }
        }

        if ($pageSelectionner >= $nbPageTotal or $nbNewsTrouver == 0) { // Si la page selectionnée est la derniere, on désactive le bouton suivant 
            ?>
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Suivant</a>
            </li>
        <?php
        } else { ?>
            <li class="page-item">
                <a class="page-link" href="/recherche.php?recherche=<?php echo $_GET['recherche'];
                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?>&page=<?php echo $pageSelectionner + 1; ?>">Suivant</a>
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