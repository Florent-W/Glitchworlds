<?php
if (isset($_GET['categorie'])) { // On regarde où on est pour définir le titre de la page
    if ($_GET['categorie'] == "News") {
        $title = "Liste des news";
    } else if ($_GET['categorie'] == "Jeux") {
        $title = "Liste des ";
        if(isset($_GET['categorie_jeu']) && $_GET['categorie_jeu'] != "") {
            if($_GET['categorie_jeu'] != "Officiels") {
                $title = $title . $_GET['categorie_jeu']; // Categorie du jeu          
                
                if($_GET['categorie_jeu'] == "Rom hacks") { // Meta description selon la categorie
                    $meta_description = "Voici la liste des Rom hacks répertoriées sur le site, vous trouverez des modifications de nombreuses sagas telles que Pokémon... Une Rom hack est une modification d'un jeu permettant de modifier son gameplay, son histoire, ses graphismes ou encore ses lieux !";
                }
                else if($_GET['categorie_jeu'] == "Fan games") {
                    $meta_description = "Voici la liste des Fan games répertoriés sur le site, vous trouverez des modifications de nombreuses sagas telles que Pokémon, Mario, Sonic... Un fangame est un jeu fait par des fans reprenant des licences bien connus et sur le moteur de leur choix, de quoi intégrer ce que l'on veut (du online, de nouvelles histoires, de nouveaux lieux, de nouveaux scénarios, de nouveaux graphismes !";
                }
            }
            else {
                $title = $title . "jeux " . $_GET['categorie_jeu']; // Categorie du jeu
            }
        }
        else {
            $title = $title . "Jeux";
        }
        if(isset($_GET['recherche']) && $_GET['recherche'] != "") { // Si il y a une recherche, on ajoute le mot dans le titre
            if($_GET['categorie_jeu'] != "") {
                $title = $title . " de " . $_GET['recherche']; // On met un bon titre
            }
            else {
                $title = $title . " " . $_GET['recherche'];
            }
        }
    }
} else {
    $title = "Recherche";
}
include('Header.php');
?>

<script>
    autoCompletion("rechercheListe", "Tous");
</script>

<body style="<?php if(!isset($_SESSION['nom_image_background'])) { echo "background-image: url('/background.jpg');"; } else { echo "background-image: url('/utilisateurs/" . $_SESSION['id'] . "/background_site/" . $_SESSION['nom_image_background'] . "');"; } ?> background-repeat: no-repeat; background-attachment: fixed; background-size: cover; background-position: center center; overflow-x: hidden;">
    <!-- Background -->
    <div class="container container-bordure animation fadeRight bg-white">
        <!-- Container des recherche -->
        <form class="form-inline form-recherche my-2 my-lg-0 justify-content-center" action="/recherche.php" method="GET">
            <div class="form-group">
                <input class="form-control recherche" type="search" value="<?php if (isset($_GET['recherche'])) echo $_GET['recherche']; ?>" placeholder="Rechercher" name="recherche" id="rechercheListe" aria-label="Rechercher"> <!-- Recherche -->
            </div>
            <?php
            ?>
            <div class="form-group">
                <select class="form-control" id="categorie" name="categorie" style="width: 12vh;">
                    <!-- Selection catégorie de la recherche -->
                    <option value="Jeux" <?php if (isset($_GET['categorie']) and $_GET['categorie'] == "Jeux") echo 'selected="selected"'; ?>>Jeux</option>
                    <option value="News" <?php if (isset($_GET['categorie']) and $_GET['categorie'] == "News") echo 'selected="selected"'; ?>>News</option>
                </select>
            </div>
            <?php // if (isset($_GET['categorie']) and $_GET['categorie'] == "Jeux") { // Si la catégorie sélectionnée est les jeux, on affiche la sélection du type de jeu 
            ?>
                <div class="form-group" id="categorie_jeu_group" <?php if (isset($_GET['categorie']) && $_GET['categorie'] != "Jeux") { echo "style='display: none;'"; } // Si la catégorie sélectionnée est les jeux, on affiche les categories de jeu ?>>
                    <select class="form-control" id="categorie_jeu" name="categorie_jeu">
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
            <?php // } ?>
            <?php // if (isset($_GET['categorie']) and $_GET['categorie'] == "Jeux") { // Si la catégorie sélectionnée est les jeux, on affiche les plateformes
            ?>
                <div class="form-group" id="plateforme_group" <?php if (isset($_GET['categorie']) && $_GET['categorie'] != "Jeux") { echo "style='display: none;'"; } // Si la catégorie sélectionnée est les jeux, on affiche les plateformes ?>>
                    <select class="form-control" name="plateforme">
                        <!-- Selection plateforme de la recherche -->
                        <option value="" <?php if (isset($_GET['plateforme']) and $_GET['plateforme'] == $donnees['nom_plateforme']) echo 'selected="selected"'; ?>>Tous</option> <!-- options du select pour selectionner tous les jeux -->
                        <?php $reponse = $bdd->prepare('SELECT plateformes.nom_plateforme FROM plateformes ORDER BY plateformes.id');
                        $reponse->execute();
                        while ($donnees = $reponse->fetch()) { ?>
                            <option value="<?php echo $donnees['nom_plateforme']; ?>" <?php if (isset($_GET['plateforme']) and $_GET['plateforme'] == $donnees['nom_plateforme']) echo 'selected="selected"'; ?>><?php echo $donnees['nom_plateforme']; ?></option> <!-- Les différentes options du select -->
                        <?php }
                        $reponse->closeCursor(); ?>
                    </select>
                </div>
            <?php // } ?>

            <div class="form-group" id="genre_group" <?php if (isset($_GET['categorie']) && $_GET['categorie'] != "Jeux") { echo "style='display: none;'"; } // Si la catégorie sélectionnée est les jeux, on affiche les categories de jeu ?>>
                <select class="form-control" name="genre">
                    <!-- Selection des genres de la recherche -->
                    <option value="" <?php if (isset($_GET['genre']) and $_GET['genre'] == $donnees['genre']) echo 'selected="selected"'; ?>>Tous</option> <!-- options du select pour selectionner tous les jeux -->
                        <?php $reponse = $bdd->prepare('SELECT genres.genre FROM genres ORDER BY genres.id');
                        $reponse->execute();
                        while ($donnees = $reponse->fetch()) { ?>
                            <option value="<?php echo $donnees['genre']; ?>" <?php if (isset($_GET['genre']) and $_GET['genre'] == $donnees['genre']) echo 'selected="selected"'; ?>><?php echo $donnees['genre']; ?></option> <!-- Les différentes options du select -->
                        <?php }
                        $reponse->closeCursor(); ?>                </select>
            </div>

            <div class="form-group" id="tri_group">
                <select class="form-control" name="tri">
                    <!-- Selection du tri de la recherche -->
                    <option value="ajoute" <?php if (isset($_GET['tri']) && $_GET['tri'] == "id") echo 'selected="selected"'; ?>>Ajouté</option>
                    <option value="nouveau" <?php if (isset($_GET['tri']) && $_GET['tri'] == "nouveau") echo 'selected="selected"'; ?>>Nouveau</option>
                    <option value="ancien" <?php if (isset($_GET['tri']) && $_GET['tri'] == "ancien") echo 'selected="selected"'; ?>>Ancien</option>
                    <?php if((isset($_GET['categorie']) && $_GET['categorie'] == "Jeux") || (!isset($_GET['categorie']))) { // On ajoute ces options si la categorie est jeu ?>
                    <option value="note" <?php if (isset($_GET['tri']) && $_GET['tri'] == "note") echo 'selected="selected"'; ?>>Mieux noté</option>
                    <option value="plus_avis" <?php if (isset($_GET['tri']) && $_GET['tri'] == "plus_avis") echo 'selected="selected"'; ?>>Plus noté</option>
                    <?php } ?>
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

            <script>
            $('#categorie').on('change', function() {
                if($(this).val() == 'Jeux') { // Si on change de catégorie et qu'on met jeux, la categorie jeu et le select des plateformes apparaissent
                    if(!$('#categorie_jeu_group').is(":visible")) { // On teste si les éléments sont visible avant
                        $('#categorie_jeu_group').show();
                    }
                    if(!$('#plateforme_group').is(":visible")) { // On teste si les éléments sont visible avant
                        $('#plateforme_group').show();
                    }
                    if(!$('#genre_group').is(":visible")) { // On teste si les éléments sont visible avant
                        $('#genre_group').show();
                    }
                    $('select[name=tri]').append('<option value="note" <?php if (isset($_GET['tri']) && $_GET['tri'] == "note") echo 'selected="selected"'; ?>>Mieux noté</option>'); // On ajoute les options
                    $('select[name=tri]').append('<option value="plus_avis" <?php if (isset($_GET['tri']) && $_GET['tri'] == "plus_avis") echo 'selected="selected"'; ?>>Plus noté</option>');
                }
                else {
                    $('#categorie_jeu_group').hide();
                    $('#plateforme_group').hide();
                    $('#genre_group').hide();
                   // $('#tri_group').hide();

                    $('select[name=tri] option[value=note]').remove(); // On enlève les options qui ne servent à rien pour les articles
                    $('select[name=tri] option[value=plus_avis]').remove();
                }
            })
            
            </script>

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