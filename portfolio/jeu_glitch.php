
<form class="form-inline my-2 my-lg-0 justify-content-center" action="/jeu/<?php echo htmlspecialchars($_GET['url']); ?>-<?php echo htmlspecialchars($_GET['id']); ?>/<?php echo htmlspecialchars($_GET['onglet_jeu']); ?>" method="POST">
            <!-- Recherche -->
            <div class="form-group" style="margin: 5px;">
                <input class="form-control" type="search" value="<?php if (isset($_POST['recherche'])) echo htmlspecialchars($_POST['recherche']); ?>" placeholder="Rechercher" name="recherche" id="recherche" aria-label="Rechercher"> <!-- Recherche -->
            </div>
            <?php
            ?>
            <button class="btn btn-outline-success" style="margin: 5px;" type="submit">Rechercher</button>
        </form>

        <?php
        if (!isset($_POST['recherche'])) { // Si on arrive sur un jeu, on prend tous les articles
            $rechercheArticle = "";
        } else {
            $rechercheArticle = $_POST['recherche'];
        }

        if (!isset($_GET['page'])) { // Si on arrive sur la liste des glitch, la page selectionnée par défaut est la une
            $pageSelectionner = 1;
        } else {
            $pageSelectionner = $_GET['page'];
        }

        $nombreGlitchParPage = 2;
        ?>

        <!-- glitch -->
        <?php if (empty($_GET['categorie']) or $_GET['categorie'] == "Glitch") { // Si on ne recherche pas de catégorie ou que la categorie selectionné est glitch, on affiche les glitch
            include('liste_glitch_colonne.php');
        }
        ?>

