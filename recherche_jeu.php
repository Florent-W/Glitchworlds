<?php
$offsetPageJeu = $nombreJeuParPage * ($pageSelectionner - 1); // Offset pour dire quand on commence à prendre les jeux

if (!empty($_GET['categorie_jeu'])) { // Si la catégorie du jeu est là, on la sélectionne
    $categorie_jeu = $_GET['categorie_jeu'];
} else { // Si la catégorie du jeu n'a pas été selectionné, on l'a met à vide
    $categorie_jeu = "";
}
?>
<h3 class="text-center">Jeux</h3>
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

        $reponse = $bdd->prepare('SELECT jeu.*, DATE_FORMAT(date_sortie, "%d %M %Y") AS date_jeu FROM jeu INNER JOIN categorie_jeu ON jeu.id_categorie = categorie_jeu.id WHERE jeu.nom LIKE :article and categorie_jeu.nom = :nom_categorie_jeu ORDER BY id DESC LIMIT 1 OFFSET :offsetPageJeu'); // Sélection des jeux et formatage de la date à partir de la page de jeu selectionnée

        /* $reponse = $bdd->prepare('(SELECT jeu.*, DATE_FORMAT(date_sortie, "%d %M %Y à %Hh%imin%ss") AS date_news FROM jeu WHERE jeu.nom LIKE :article ORDER BY id DESC)
                                UNION (SELECT news.* FROM news WHERE news.titre LIKE :article ORDER BY id DESC)'); */ // Sélection des jeux et formatage de la date à partir de la page de jeu selectionnée
        $reponse->bindValue('offsetPageJeu', $offsetPageJeu, PDO::PARAM_INT);
        $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
        $reponse->bindValue('nom_categorie_jeu', $_GET['categorie_jeu'], PDO::PARAM_STR);
        $reponse->execute();
    } else { // Si la catégorie du jeu n'a pas été selectionné, on l'a met à vide
        $reponse = $bdd->prepare('SELECT COUNT(*) as nb_jeu FROM jeu INNER JOIN categorie_jeu ON jeu.id_categorie = categorie_jeu.id WHERE jeu.nom LIKE :article'); // Nombre de jeux trouvés, si aucun, on n'affichera pas
        $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
        $reponse->execute();
        $donnees = $reponse->fetch();
        $nbJeuTrouver = $donnees['nb_jeu'];
        $reponse->closeCursor();

        $reponse = $bdd->prepare('SELECT jeu.*, DATE_FORMAT(date_sortie, "%d %M %Y") AS date_jeu FROM jeu INNER JOIN categorie_jeu ON jeu.id_categorie = categorie_jeu.id WHERE jeu.nom LIKE :article ORDER BY id DESC LIMIT 1 OFFSET :offsetPageJeu'); // Sélection des jeux et formatage de la date à partir de la page de jeu selectionnée
        $reponse->bindValue('offsetPageJeu', $offsetPageJeu, PDO::PARAM_INT);
        $reponse->bindValue('article', '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
        $reponse->execute();
    }

    // Si les jeu sont trouvé, on les affiche
    if ($nbJeuTrouver > 0) {
        while ($donnees = $reponse->fetch()) {
    ?>
            <!-- Liste jeu -->
            <div class="list-group-item">
                <img src="/miniature/<?php echo $donnees['nom_miniature'] ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-thumbnail" style="float:left; height: 200px"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                <div class="row">
                    <div class="col">
                        <?php /* ?><a href="news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>" style="text-decoration-color: black"> <?php */ ?>
                        <a href="/jeu.php?id=<?php echo $donnees['id']; ?>" style="text-decoration-color: black">
                            <!-- L'url est composé à l'aide de l'url rewriting, de l'url marqué dans la base de données ainsi que de l'id -->
                            <h1 class="list-group-item-heading text-body"><?php echo $donnees['nom']; ?></h1> <!-- Nom du jeu -->
                        </a>
                    </div>
                    <div class="col">
                        <p class="list-group-item-text pull-right text-right lead"><?php echo $donnees['date_jeu']; ?></p> <!-- Date du jeu -->
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="list-group-item-text pull-right lead" style="word-wrap: break-word"><?php echo tronquerTexte($donnees['contenu'], 150, "news/" . $donnees['url'] . "-" . $donnees['id']) ?> </p> <!-- Contenu -->
                    </div>
                    <?php if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier une news 
                    ?>
                        <div class="col">
                            <?php /* ?> <a href="modifier_news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>">  <?php */ ?>
                            <a href="/modifier_jeu.php?id=<?php echo $donnees['id']; ?>">
                                <p class="list-group-item-text pull-right text-right lead">Modifier</p> <!-- Modification de la page des jeux -->
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
        <p class="text-center">Aucun jeu n'a été trouvé.</p>
    <?php
    }
    $reponse->closeCursor();
    ?>
</ul>

<!-- Liste des pages de recherche des jeux -->
<!-- Pagination -->
<nav aria-label="navigation recherche" class="d-flex justify-content-center" style="margin-top: 20px;">

    <ul class="pagination">
        <?php
        $nbPageTotal = ceil($nbJeuTrouver / $nombreJeuParPage); // Nombre de page de recherche que peut avoir le site à l'aide du nombre d'articles (20 articles par page)
        $nom_page = $_SERVER['PHP_SELF']; // Va permettre de savoir si on est sur la page de la recherche ou la liste des jeux

        if ($pageSelectionner == 1 or $pageSelectionner > $nbPageTotal) { // Si la page selectionnée est la une, on désactive le bouton précédent 
        ?>
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Précédent</a>
            </li>
        <?php
        } else {
        ?>
            <li class="page-item">
                <a class="page-link" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                            else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?>&page=<?php echo $pageSelectionner - 1; ?>">Précédent</a>
            </li>
            <?php
        }

        for ($i = 1; $i <= $nbPageTotal; $i++) { // Parcours des pages
            if ($pageSelectionner == $i) { // Si la page selectionnée est égale à la page du bouton, on rend la page du bouton active 
            ?>
                <li class="page-item active">
                    <a class="page-link" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                            if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php
            } else { ?>
                <li class="page-item">
                    <a class="page-link" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                            if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php }
        }

        if ($pageSelectionner >= $nbPageTotal or $nbPageTotal == 0) { // Si la page selectionnée est la derniere, on désactive le bouton suivant 
            ?>
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Suivant</a>
            </li>
        <?php
        } else { ?>
            <li class="page-item">
                <a class="page-link" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                            else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?>&page=<?php echo $pageSelectionner + 1; ?>">Suivant</a>
            </li>
        <?php }
        ?>
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