<?php
if (isset($_GET['categorie'])) { // On regarde où on est pour définir le titre de la page
    if ($_GET['categorie'] == "News") {
        $title = "Liste des news";
    } else if ($_GET['categorie'] == "Jeux") {
        $title = "Liste des jeux";
    }
} else {
    $title = "Recherche";
}
include('Header.php');
?>

<script>
    autoCompletion("rechercheListe", "Tous");
</script>

<body class="background">
    <!-- Background -->
    <div class="container container-bordure animated fadeInRight bg-white">
        <!-- Container des recherche -->
        <form class="form-inline form-recherche my-2 my-lg-0 justify-content-center" action="recherche.php" method="GET">
            <div class="form-group">
                <input class="form-control recherche" type="search" value="<?php if (isset($_GET['recherche'])) echo $_GET['recherche']; ?>" placeholder="Rechercher" name="recherche" id="rechercheListe" aria-label="Rechercher"> <!-- Recherche -->
            </div>
            <?php
            ?>
            <div class="form-group">
                <select class="form-control" name="categorie" style="width: 12vh;">
                    <!-- Selection catégorie de la recherche -->
                    <option value="Jeux" <?php if (isset($_GET['categorie']) and $_GET['categorie'] == "Jeux") echo 'selected="selected"'; ?>>Jeux</option>
                    <option value="News" <?php if (isset($_GET['categorie']) and $_GET['categorie'] == "News") echo 'selected="selected"'; ?>>News</option>
                </select>
            </div>
            <?php if (isset($_GET['categorie']) and $_GET['categorie'] == "Jeux") { // Si la catégorie sélectionnée est les jeux, on affiche la sélection du type de jeu 
            ?>
                <div class="form-group">
                    <select class="form-control" name="categorie_jeu">
                        <!-- Selection catégorie de la recherche (type de jeu) -->
                        <option value="" <?php if (isset($_GET['categorie_jeu']) and $_GET['categorie_jeu'] == $donnees['nom']) echo 'selected="selected"'; ?>>Tous</option> <!-- options du select pour selectionner tous les jeux -->
                        <?php $reponse = $bdd->prepare('SELECT categorie_jeu.nom FROM categorie_jeu ORDER BY categorie_jeu.id');
                        $reponse->execute();
                        while ($donnees = $reponse->fetch()) { ?>
                            <option value="<?php echo $donnees['nom']; ?>" <?php if (isset($_GET['categorie_jeu']) and $_GET['categorie_jeu'] == $donnees['nom']) echo 'selected="selected"'; ?>><?php echo $donnees['nom']; ?></option> <!-- Les différentes options du select -->
                        <?php }
                        $reponse->closeCursor(); ?>
                    </select>
                </div>
            <?php } ?>
            <div class="form-group">
                <select class="form-control" name="tri">
                    <!-- Selection du tri de la recherche -->
                    <option value="ajoute" <?php if (isset($_GET['tri']) && $_GET['tri'] == "id") echo 'selected="selected"'; ?>>Ajouté</option>
                    <option value="nouveau" <?php if (isset($_GET['tri']) && $_GET['tri'] == "nouveau") echo 'selected="selected"'; ?>>Nouveau</option>
                    <option value="ancien" <?php if (isset($_GET['tri']) && $_GET['tri'] == "ancien") echo 'selected="selected"'; ?>>Ancien</option>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-outline-success" type="submit">Rechercher</button>
            </div>
        </form>

        <?php
        if (isset($_GET['recherche'])) { // Traitement recherche news et glitch
            $nombreJeuParPage = 5;
            $nombreNewsParPage = 5;

            if (!isset($_GET['page'])) { // Si on arrive sur la liste des jeux, la page selectionnée par défaut est la une
                $pageSelectionner = 1;
            } else {
                $pageSelectionner = $_GET['page'];
            }
        ?>

            <!-- jeu -->
            <?php if (empty($_GET['categorie']) or $_GET['categorie'] == "Jeux") { // Si on ne recherche pas de catégorie ou que la categorie selectionné est jeu, on affiche les jeux
                include('recherche_jeu.php');
            }
            ?>

            <!-- News -->
            <?php if (empty($_GET['categorie']) or $_GET['categorie'] == "News") { // Si on ne recherche pas de catégorie ou que la categorie selectionné est news, on affiche les news
                include('recherche_news.php');
            ?>
        <?php
            }
        } ?>
    </div>

    <?php
    include('footer.php');
    ?>
</body>

</html>