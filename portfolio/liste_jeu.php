<?php
$title = "Liste des jeux";
include('Header.php');
?>

<form class="form-inline my-2 my-lg-0 justify-content-center" method="GET">
    <div class="form-group" style="margin: 5px;">
        <input class="form-control" type="search" value="<?php if (isset($_GET['recherche'])) echo $_GET['recherche']; ?>" placeholder="Rechercher" name="recherche" id="recherche" aria-label="Rechercher"> <!-- Recherche -->
    </div>
    <div class="form-group">
        <select class="form-control" name="categorie_jeu" style="margin: 5px;">
            <!-- Selection catégorie de la recherche (type de jeu) -->
            <?php $reponse = $bdd->prepare('SELECT categorie_jeu.nom FROM categorie_jeu ORDER BY categorie_jeu.id');
            $reponse->execute();
            while ($donnees = $reponse->fetch()) { ?>
                <option value="<?php echo $donnees['nom']; ?>" <?php if (isset($_GET['categorie_jeu']) and $_GET['categorie_jeu'] == $donnees['nom']) echo 'selected="selected"'; ?>><?php echo $donnees['nom']; ?></option> <!-- Les différentes options du select -->
            <?php }
            $reponse->closeCursor(); ?>
        </select>
    </div>
    <button class="btn btn-outline-success" style="margin: 5px;" type="submit">Rechercher</button>
</form>

<?php
if (isset($_GET['recherche'])) { // Traitement recherche jeu
    $nombreJeuParPage = 1;

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
}
?>
</body>

</html>