<?php
include('Header.php');
?>

<body>
    <div class="container">
        <div class="row">
            <form class="form" method="post" enctype="multipart/form-data" style="margin:50px">
                <h1>Modifier galerie des news</h1>
                <hr> <!-- Trait -->
                <h3><?php if (isset($_POST['choix_page'])) echo "Page " . $_POST['choix_page']; ?></h3>

                <!-- Choix de la page à modifier -->
                <div class="form-group">
                    <label for="choix_page">Choix de la page :</label>
                    <select class="form-control" name="choix_page" required onchange="controleTexteInput(this, 'choix_pageIndication', 'choix_page')" class="form-control">

                        <?php
                        $reponse = $bdd->prepare("SELECT * FROM carousel ORDER BY page"); // Cherche toutes les pages
                        $reponse->execute();

                        while ($donnees = $reponse->fetch()) { ?>
                            <!-- On met les page trouvés en choix -->

                            <?php if ($_POST['choix_page'] == $donnees['page']) { // On sélectionne la page qui a été selectionné par l'utilisateur 
                            ?>
                                <option selected><?php echo $donnees['page'] ?></option>
                            <?php } else {
                            ?> <option><?php echo $donnees['page']; ?></option> <?php
                                                                            }
                                                                        }
                                                                        $nombre_resultats = $reponse->rowCount(); // Compte le nombre de résultats
                                                                        $reponse->closeCursor();
                                                                                ?>
                    </select>
                    <label id="choix_pageIndication" class="text-danger"><?php if (isset($_POST['choix_page']) and empty($_POST['choix_page'])) echo "Veuillez choisir une page" ?></label> <!-- Indication choix page, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                    <div>
                        <button type="submit" class="btn btn-info">Valider</button>
                    </div>
                </div>

                <?php if (isset($_POST['choix_page'])) { // Si une page a été choisie, on demande à l'utilisateur de chercher une news à mettre sur la page
                ?>
                    <hr> <!-- Trait -->
                    <div class="form-group">
                        <label for="titre">Recherche du titre :</label>

                        <input type="text" name="titre" id="titre" value="<?php if (isset($_POST['titre'])) echo $_POST['titre']; ?>" onchange="controleTexteInput(this, 'titreIndication', 'titre')" class="form-control"> <!-- Titre déjà pré-rempli avec les informations de la news et si on tente de modifier le titre, le titre est modifié -->
                        <label id="titreIndication" class="text-danger"><?php if (isset($_POST['titre']) and empty($_POST['titre'])) echo "Veuillez choisir un titre" ?></label> <!-- Indication titre, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                        <div>
                            <button type="submit" class="btn btn-primary">Rechercher</button>
                        </div>
                    </div>
                <?php
                }
                ?>

                <!-- Choix de la news -->
                <?php if (!empty($_POST['titre'])) { // Si l'utilisateur à déjà cherché un titre, un select est rempli avec un choix de news à présenter sur le carousel
                ?>
                    <hr> <!-- Trait -->
                    <div class="form-group">
                        <label for="choix_news">Choix :</label>
                        <select class="form-control" name="choix_news" required onchange="controleTexteInput(this, 'choix_newsIndication', 'choix_news')" class="form-control">
                            <!-- Titre déjà pré-rempli avec les informations de la news et si on tente de modifier le titre, le titre est modifié -->>
                            <?php
                            $titre = $_POST['titre'];

                            $reponse = $bdd->prepare("SELECT * FROM news WHERE titre Like :titre"); // Cherche toutes les news ressemblants au titre indiqué
                            $reponse->execute(array('titre' => '%' . $_POST['titre'] . '%'));

                            while ($donnees = $reponse->fetch()) { ?>
                                <!-- On met les news trouvés en choix -->

                                <?php if ($_POST['choix_news'] == $donnees['id']) { // On sélectionne la news qui a été selectionné par l'utilisateur 
                                ?>
                                    <option value="<?php echo $donnees['id']; ?>" selected><?php echo $donnees['titre'] ?></option>
                                <?php } else {
                                ?> <option value="<?php echo $donnees['id']; ?>"><?php echo $donnees['titre'] ?></option> <?php
                                                                                                                        }
                                                                                                                    }
                                                                                                                    $nombre_resultats = $reponse->rowCount(); // Compte le nombre de résultats
                                                                                                                    $reponse->closeCursor();
                                                                                                                            ?>
                        </select>
                        <label id="choix_newsIndication" class="text-danger"><?php if (isset($_POST['choix_news']) and empty($_POST['choix_news'])) echo "Veuillez choisir une news" ?></label> <!-- Indication choix news, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                        <div>
                            <button type="submit" name="information" class="btn btn-secondary">Plus d'informations</button> <!-- Pour voir les informations de la news recherché -->
                        </div>
                        <!-- <label for="resultat"><?php echo $nombre_resultats; ?> Résultats</label> Nombre de résultats -->
                    </div>

                    <?php
                    if (isset($_POST['information'])) { // Si l'utilisateur demande de voir les informations d'une news
                        $id = $_POST['choix_news'];

                        $reponse = $bdd->prepare("SELECT * FROM news WHERE id = :id"); // Cherche la new demandé
                        $reponse->execute(array('id' => $id));

                        $donnees = $reponse->fetch() ?>

                        <!-- On met les informations de la news -->
                        <hr> <!-- Trait -->
                        <div class="form-group">
                            <label for="titre_news">Titre :</label>
                            <input type="text" readonly name="titre_news" value="<?php echo $donnees['titre']; ?>" class="form-control">

                            <label for="contenu_news">Contenu :</label>
                            <textarea readonly name="contenu_news" class="form-control"><?php echo $donnees['contenu']; ?></textarea>

                            <label for="date_creation">Date creation :</label>
                            <input type="text" readonly name="date_creation" value="<?php echo $donnees['date_creation']; ?>" class="form-control">
                        </div>
                <?php
                        $reponse->closeCursor();
                    }
                }
                ?>

                <div class="grid">
                    <?php if (isset($_POST['titre'])) { ?>
                        <button type="submit" name="valider_modification" class="btn btn-success">Valider</button> <!-- Si une des news à été chercher, cela veut dire que l'utilisateur peut valider la news à mettre dans le carousel -->
                    <?php }
                    ?>

                    <?php
                    // Traitement et modification du carousel
                    if (isset($_POST['valider_modification']) && isset($_POST['choix_page']) && isset($_POST['choix_news'])) {

                        $reponse = $bdd->prepare("UPDATE carousel SET id_news = :choix_news WHERE page = :choix_page");
                        $reponse->execute(array('choix_page' => $_POST['choix_page'], 'choix_news' => $_POST['choix_news']));
                    ?>
                        <div class="alert alert-success" style="margin-top:10px" role="alert">La page à bien été mise à jour !</div>

                    <?php
                        $reponse->closeCursor();
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>


</body>