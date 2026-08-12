<?php
require_once('commun/BddJeuClass.php');

// Initialisation
$bddJeu = new BddJeuClass();

$offsetPageJeu = $nombreJeuParPage * ($pageSelectionner - 1); // Offset pour dire quand on commence à prendre les jeux

if (!empty($_GET['categorie_jeu'])) { // Si la catégorie du jeu est là, on la sélectionne
    $categorie_jeu = $_GET['categorie_jeu'];
} else { // Si la catégorie du jeu n'a pas été selectionné, on l'a met à vide
    $categorie_jeu = "";
}

if (!empty($_GET['tri'])) { // Si le choix du tri, order by de la recherche est fait, on le sélectionne
    if ($_GET['tri'] == "ajoute") {
        $tri = 3;
        $ordre_tri = "DESC";
    } else if ($_GET['tri'] == "nouveau") {
        $tri = 7;
        $ordre_tri = "DESC";
    } else if ($_GET['tri'] == "ancien") {
        $tri =  7;
        $ordre_tri = "ASC";
    } else if ($_GET['tri'] == "note") {
        $tri =  1; // Colonne d'avis note
        $ordre_tri = "DESC";
    } else if ($_GET['tri'] == "plus_avis") {
        $tri =  2;
        $ordre_tri = "DESC";
    }
     else {
        $tri = 3;
        $ordre_tri = "DESC";
    }
} else { // Si un tri n'est pas séléectionné, on ordonne par l'id
    $tri = 3;
    $ordre_tri = "DESC";
}

$recherche = '';
$genre = '';
$plateforme = '';
$langue = '';
$onlyFavoris = '';
$jeuApprouver = 'approuver';

if(isset($_GET['recherche'])) {
    $recherche = '%' . $_GET['recherche'] . '%';
}
if(isset($_GET['genre'])) {
    $genre = $_GET['genre'];
}
if(isset($_GET['plateforme'])) {
    $plateforme = $_GET['plateforme'];
}
if(isset($_GET['langue'])) {
    $langue = $_GET['langue'];
}
if(isset($_GET['favoris'])) {
    $onlyFavoris = $_GET['favoris'];
}
if(isset($_POST['jeu_approuver'])) {
    if($_POST['jeu_approuver'] != 'tous') { 
        $jeuApprouver = $_POST['jeu_approuver']; // catégorie de jeu
    } else {
        $jeuApprouver = ''; // Si on sélectionne tous les jeux
    }
}
?>
<h3 class="text-center">Jeux :</h3>
    <!-- Selection du type d'article -->
    <?php if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à voir les jeux en attente 
            ?> <p>
                <form class="form-inline form-index my-2 my-lg-0 justify-content-center" method="POST">
                    <div class="form-group">
                        <select class="form-control" name="jeu_approuver">
                            <!-- Selection article approuver -->
                            <option value="approuver" <?php if (isset($_POST['jeu_approuver']) and $_POST['jeu_approuver'] == "approuver") echo 'selected="selected"'; ?>>Approuver</option>
                            <option value="préapprouver" <?php if (isset($_POST['jeu_approuver']) and $_POST['jeu_approuver'] == "préapprouver") echo 'selected="selected"'; ?>>En attente</option>
                            <option value="brouillon" <?php if (isset($_POST['jeu_approuver']) and $_POST['jeu_approuver'] == "brouillon") echo 'selected="selected"'; ?>>Brouillon</option> <!-- Les différentes options du select -->
                            <option value="tous" <?php if (isset($_POST['jeu_approuver']) and $_POST['jeu_approuver'] == "tous") echo 'selected="selected"'; ?>>Tous</option>
                        </select>
                    </div>
                    <div class="form-group ml-2">
                        <button class="btn btn-outline-success" type="submit">Rechercher</button>
                    </div>
                </form>
               </p>
            <?php }
            ?>
<ul class="list-group" style="top:100px">
    <?php 
    $listeJeux = $bddJeu->selectionListeJeux($nombreJeuParPage, $offsetPageJeu, $recherche, $tri, $genre, $plateforme, $categorie_jeu, $ordre_tri, $jeuApprouver, $onlyFavoris, $langue);
    $nbJeuTrouver = $listeJeux['nb_jeu'];
?>
    <?php
    // Si les jeu sont trouvé, on les affiche
    if ($nbJeuTrouver > 0) {
        $positionJeu = 0; // On va voir la place du jeu et une fois sur deux, il sera en couleur 

        foreach($listeJeux['donnees'] as $jeu) {
    ?>
            <!-- Liste jeu -->
            <div class="liste-news-jeu">
            <?php
            if ($positionJeu % 2 == 0) { // Un jeu sur deux sera en couleur
            ?>
                <a href="/jeu/<?php echo $jeu['url']; ?>-<?php echo $jeu['id']; ?>" style="text-decoration-color: black; text-decoration: none;" class="list-group-item justify-content-center list-group-item-secondary liste-item-sans-bordure">
                    <!-- L'url est composé à l'aide de l'url rewriting, de l'url marqué dans la base de données ainsi que de l'id -->
                <?php } else {
                ?>
                    <a href="/jeu/<?php echo $jeu['url']; ?>-<?php echo $jeu['id']; ?>" style="text-decoration-color: black; text-decoration: none;" class="list-group-item justify-content-center list-group-item-light liste-item-sans-bordure">
                    <?php
                } ?>
                    <img src="/Jeux/<?php echo $jeu['url']; ?>/miniature/<?php echo $jeu['nom_miniature']; ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-thumbnail img-fluid float-md-left" style="width:auto; height: auto; max-width:200 px; max-height:320px; background-color:transparent;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
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
                            <h1 class="list-group-item-heading text-body"><?php echo $jeu['nom']; ?></h1> <!-- Nom du jeu -->
                        </div>
                        <div class="col">
                            <p class="list-group-item-text pull-right text-right lead"><?php echo $jeu['date_jeu']; ?></p> <!-- Date du jeu -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="list-group-item-text pull-right lead" style="word-wrap: break-word"><?php if($jeu['description'] != "") { echo htmlspecialchars($listeJeux['description']); } else { echo tronquerTexte(remplacementBBCode($jeu['contenu'], false, true), 150, "jeu/" . $jeu['url'] . "-" . $jeu['id']); } ?> </p> <!-- Contenu, on veut supprimer aussi les balises -->
                        </div>
                    </div>
                    <?php if(isset($jeu['moyenne_note'])) { ?>
                    <div class="row">
                        <div class="col">
                            <p class="list-group-item-text pull-right text-right lead"><span style="border-radius: 50%; border: solid; background-color: LightGreen; padding: 8px; width: 51px; height: 51px; display: inline-block; text-align: center;"><?php echo round($jeu['moyenne_note'], 1); ?></span>  <?php if($jeu['nombre_note'] > 1 ) echo $jeu['nombre_note'] . ' notes'; else echo $jeu['nombre_note'] . ' note'; ?></p> <!-- Moyenne des notes arrondis si il y en a pour un jeu -->
                        </div>
                    </div>
                    <?php } ?>
                    </a>

                    <?php if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur" || isset($_SESSION['id']) && $_SESSION['id'] == $jeu['id_auteur_presentation']) { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier une news 
                    ?>
                        <div class="row" style="margin-bottom: 10px; margin-top: 10px;">
                        <form class="form" method="post" action="/modifier_jeu/<?php echo $jeu['url']; ?>-<?php echo $jeu['id']; ?>">
                            <div class="col text-left"></div>
                        </form>    
                        <div class="col text-right">
                                <form class="form" method="post" action="/modifier_jeu/<?php echo $jeu['url']; ?>-<?php echo $jeu['id']; ?>">
                                    <button id="approuver_jeu" class="btn btn-primary"><?php if($jeu['approuver'] == 'approuver') { echo 'Approuver'; } else if($jeu['approuver'] == 'préapprouver') { echo 'En attente'; } else { echo 'Brouillon'; } ?></button>
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
                                                                                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['plateforme'])) echo '&plateforme=' . $_GET['plateforme']; ?><?php if (isset($_GET['genre'])) echo '&genre=' . $_GET['genre']; ?><?php if(isset($_GET['langue'])) echo '&langue=' . $_GET['langue']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=1" aria-label="PremierePage">
                    <span aria-hidden="true">
                        <<</span> <span class="sr-only">Premier
                    </span> <!-- Premiere page -->
                </a>
            </li>
            <li class="page-item">
                <a class="page-link changement-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                            else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['plateforme'])) echo '&plateforme=' . $_GET['plateforme']; ?><?php if (isset($_GET['genre'])) echo '&genre=' . $_GET['genre']; ?><?php if(isset($_GET['langue'])) echo '&langue=' . $_GET['langue']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner - 1; ?>" aria-label="Previous">
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
                                                                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['plateforme'])) echo '&plateforme=' . $_GET['plateforme']; ?><?php if (isset($_GET['genre'])) echo '&genre=' . $_GET['genre']; ?><?php if(isset($_GET['langue'])) echo '&langue=' . $_GET['langue']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=1">1</a>
            </li>
        <?php
        } else { ?>
            <li class="page-item">
                <a class="page-link numero-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                        else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['plateforme'])) echo '&plateforme=' . $_GET['plateforme']; ?><?php if (isset($_GET['genre'])) echo '&genre=' . $_GET['genre']; ?><?php if(isset($_GET['langue'])) echo '&langue=' . $_GET['langue']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=1">1</a>
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
                                                                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['plateforme'])) echo '&plateforme=' . $_GET['plateforme']; ?><?php if (isset($_GET['genre'])) echo '&genre=' . $_GET['genre']; ?><?php if(isset($_GET['langue'])) echo '&langue=' . $_GET['langue']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner - 1; ?>"><?php echo $pageSelectionner - 1; ?></a>
            </li>
        <?php
        }

        if ($pageSelectionner > 1 && $pageSelectionner < $nbPageTotal) { // On met la page sélectionné si elle n'a pas été déjà mise
        ?>
            <li class="page-item active">
                <a class="page-link numero-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                        else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['plateforme'])) echo '&plateforme=' . $_GET['plateforme']; ?><?php if (isset($_GET['genre'])) echo '&genre=' . $_GET['genre']; ?><?php if(isset($_GET['langue'])) echo '&langue=' . $_GET['langue']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner; ?>"><?php echo $pageSelectionner; ?></a>
            </li>
        <?php
        }

        if ($pageSelectionner + 1 < $nbPageTotal && $pageSelectionner + 1 > 1) { // On met la page suivante que si ce n'est pas la dernière et que la page est au moins à un 
        ?>
            <li class="page-item">
                <!-- Page suivante -->
                <a class="page-link numero-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                        else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['plateforme'])) echo '&plateforme=' . $_GET['plateforme']; ?><?php if (isset($_GET['genre'])) echo '&genre=' . $_GET['genre']; ?><?php if(isset($_GET['langue'])) echo '&langue=' . $_GET['langue']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner + 1; ?>"><?php echo $pageSelectionner + 1; ?></a>
            </li>
            ...
        <?php
        }

        if ($pageSelectionner == $nbPageTotal && $nbPageTotal > 1) { // On met la dernière page, si la page selectionnée est la dernière, on rend la page du bouton active, pas besoin de remettre la page si c'est la première
        ?>
            <li class="page-item active">
                <a class="page-link numero-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                        else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['plateforme'])) echo '&plateforme=' . $_GET['plateforme']; ?><?php if (isset($_GET['genre'])) echo '&genre=' . $_GET['genre']; ?><?php if(isset($_GET['langue'])) echo '&langue=' . $_GET['langue']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $nbPageTotal; ?>"><?php echo $nbPageTotal; ?></a>
            </li>
        <?php
        } else if ($pageSelectionner <= $nbPageTotal && $nbPageTotal > 1) { // Si la page selectionné n'est pas la dernière ni la première, on ne la met pas active 
        ?>
            <li class="page-item">
                <a class="page-link numero-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                        else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['plateforme'])) echo '&plateforme=' . $_GET['plateforme']; ?><?php if (isset($_GET['genre'])) echo '&genre=' . $_GET['genre']; ?><?php if(isset($_GET['langue'])) echo '&langue=' . $_GET['langue']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $nbPageTotal; ?>"><?php echo $nbPageTotal; ?></a>
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
                                                                                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['plateforme'])) echo '&plateforme=' . $_GET['plateforme']; ?><?php if (isset($_GET['genre'])) echo '&genre=' . $_GET['genre']; ?><?php if(isset($_GET['langue'])) echo '&langue=' . $_GET['langue']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $pageSelectionner + 1; ?>" aria-label="Next">
                    <span aria-hidden="true">></span>
                    <span class="sr-only">Suivant</span> <!-- Suivant -->
                </a>
            </li>
            <li class="page-item">
                <a class="page-link changement-page" href="<?php if ($nom_page == "/recherche.php") echo "/recherche.php";
                                                            else if ($nom_page == "/liste_jeu.php") echo "/liste_jeu.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                                                                                        if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?><?php if (isset($_GET['plateforme'])) echo '&plateforme=' . $_GET['plateforme']; ?><?php if (isset($_GET['genre'])) echo '&genre=' . $_GET['genre']; ?><?php if(isset($_GET['langue'])) echo '&langue=' . $_GET['langue']; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?>&page=<?php echo $nbPageTotal; ?>" aria-label="DernierePage">
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