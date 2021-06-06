<!-- Liste des pages de recherche -->
<!-- Pagination -->
<?php if ($nbArticleTrouver > 0) { // Si il n'y a pas de Article trouvé, on affiche pas la pagination 
?>
    <nav aria-label="navigation recherche" class="d-flex justify-content-center" style="margin-top: 20px;">

        <ul class="pagination pagination-circle">
            <?php
            $nbPageTotal = ceil($nbArticleTrouver / $nombreArticleParPage); // Nombre de page de recherche que peut avoir le site à l'aide du nombre d'articles (20 articles par page)
            $nom_page = $_SERVER['PHP_SELF']; // Va permettre de savoir si on est sur la page de la recherche ou la liste des Article

            if ($pageSelectionner == 1 or $pageSelectionner > $nbPageTotal) { // Si la page selectionnée est la une, on désactive le bouton précédent 
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
                    <a class="page-link changement-page" href="<?php echo '/jeu.php?url=' . $url. '&id=' . $id; ?>&recherche=<?php echo $rechercheArticle;
                                                                                                            if (isset($_GET['onglet_jeu'])) echo '&onglet_jeu=' . $_GET['onglet_jeu']; ?>&page=<?php echo $pageSelectionner - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">
                            <</span> <span class="sr-only">Précédent
                        </span> <!-- Précedent -->
                    </a>
                </li>
                <?php
            }

            for ($i = 1; $i <= $nbPageTotal; $i++) { // Parcours des pages
                if ($pageSelectionner == $i) { // Si la page selectionnée est égale à la page du bouton, on rend la page du bouton active 
                ?>
                    <li class="page-item active">
                        <a class="page-link numero-page" href="<?php echo '/jeu.php?url=' . $url . '&id=' . $id; ?>&recherche=<?php echo $rechercheArticle;
                                                                                                            if (isset($_GET['onglet_jeu'])) echo '&onglet_jeu=' . $_GET['onglet_jeu']; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php
                } else { ?>
                    <li class="page-item">
                        <a class="page-link numero-page" href="<?php echo '/jeu.php?url=' . $url . '&id=' . $id; ?>&recherche=<?php echo $rechercheArticle;
                                                                                                            if (isset($_GET['onglet_jeu'])) echo '&onglet_jeu=' . $_GET['onglet_jeu']; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php }
            }

            if ($pageSelectionner >= $nbPageTotal or $nbPageTotal == 0) { // Si la page selectionnée est la derniere, on désactive le bouton suivant 
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
                    <a class="page-link changement-page" href="<?php echo '/jeu.php?url=' . $url . '&id=' . $id; ?>&recherche=<?php echo $rechercheArticle;
                                                                                                            if (isset($_GET['onglet_jeu'])) echo '&onglet_jeu=' . $_GET['onglet_jeu']; ?>&page=<?php echo $pageSelectionner + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">></span>
                        <span class="sr-only">Suivant</span> <!-- Suivant -->
                    </a>
                </li>
            <?php }
            ?>
        </ul>
    </nav>

    <?php if ($pageSelectionner <= $nbPageTotal) { // Si la page sélectionné est supérieure au nombre de page de résultat, on affichera pas le parcours de résultats
        $resultatsSurLaPagePremierArticle = $pageSelectionner * $nombreArticleParPage - ($nombreArticleParPage - 1); // Calcul de la position du premier Article affiché sur la page (page * nombre de glith par page - (nombre de Article par page - la position du Article))

        if ($pageSelectionner < $nbPageTotal) { // Si la page selectionné est inférieure au nombre de page que donne la recherche, on peut faire le calcul de la position du dernier Article affichés
            $resultatsSurLaPageDernierArticle = $pageSelectionner * $nombreArticleParPage - ($nombreArticleParPage - $nombreArticleParPage); // Calcul de la position du dernier Article affiché sur la page (page * nombre de Article par page - (nombre de Article par page - la position du Article))
        } else if ($pageSelectionner == $nbPageTotal) { // Si la page selectionné est égale, on ne peut plus faire le calcul car si le nombre de jeux trouvés n'est pas un multiple du nombre de page trouvés alors il donnera pas le bonne position, à la place, il suffit de donner le nombre de jeux trouvés comme position du dernier jeu
            $resultatsSurLaPageDernierArticle = $nbArticleTrouver;
        }
    ?>     
    <p class="text-center">Affichage des résultats : <?php echo $resultatsSurLaPagePremierArticle; ?> - <?php echo $resultatsSurLaPageDernierArticle; ?>.</p> <!-- Affichage de la position des Article de la page en cours -->

    <?php }
    ?>
<?php } ?>
<p class="text-center">La recherche à retournée <?php echo $nbArticleTrouver; ?> article. (<?php echo $nombreArticleParPage; ?> article affichés par page)</p> <!-- Nombre de Articles trouvés -->