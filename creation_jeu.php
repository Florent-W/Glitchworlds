<?php
$title = "Création de jeu";
include('Header.php');
?>

<body style="<?php if(!isset($_SESSION['nom_image_background'])) { echo "background-image: url('/background.jpg');"; } else { echo "background-image: url('/utilisateurs/" . $_SESSION['id'] . "/background_site/" . $_SESSION['nom_image_background'] . "');"; } ?> background-repeat: no-repeat; background-attachment: fixed; background-size: cover; background-position: center center; overflow-x: hidden;">
    <div class="container container-bordure animation fadeRight bg-white">
        <div class="row">
            <form class="form" id="creation_jeu" method="post" enctype="multipart/form-data" style="margin:50px">
                <h1>Création de jeu</h1>
                <hr> <!-- Trait -->
                <?php if (isset($_SESSION['pseudo'])) { // Si l'utilisateur est connecter, il peut modifier un jeu
                ?>
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" id="nom" value="<?php if (!empty($_POST['nom'])) echo $_POST['nom'] ?>" required onchange="controleTexteInput(this, 'titreIndication', 'titre')" class="form-control"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
                        <label id="titreIndication" class="text-danger"><?php if (isset($_POST['nom']) and empty($_POST['nom'])) echo "Veuillez choisir un nom" ?></label> <!-- Indication nom, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                    </div>
                    <div class="form-group">
                        <!-- Description -->
                        <label for="description">Description de 150 caractères max (non obligatoire)</label>
                        <input type="text" maxlenght="150" name="description" id="description" value="<?php if (!empty($_POST['description'])) echo $_POST['description']; ?>" class="form-control"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
                    </div>
                    <div class="form-group">
                        <label for="contenu">Contenu</label>
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col">
                                <script>
                                    var nom_contenu = 'contenu';
                                </script>
                                <?php
                                include('bouton_bb_code.php');
                                ?>

                            </div>
                        </div>
                        <textarea name="contenu" id="contenu" required oninput="previsualisationContenu()" onchange="controleTexteInput(this, 'contenuIndication', 'contenu')" class="form-control" rows="5"><?php if (!empty($_POST['contenu'])) {
                                                                                                                                                                                                                    echo $_POST['contenu'];
                                                                                                                                                                                                                } ?></textarea>
                        <label id="contenuIndication" class="text-danger"><?php if (isset($_POST['contenu']) and empty($_POST['contenu'])) echo "Veuillez choisir un contenu"; ?></label> <!-- Indication contenu, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                        <hr>
                        <div name="previsualisationContenu" id="previsualisationContenu" style="white-space: pre-wrap;"></div>
                    </div>
                    <div class="form-group">
                        <label for="nom">Date de sortie</label>
                        <input type="date" name="date_sortie" id="date_sortie" value="<?php if (!empty($_POST['date_sortie'])) echo $_POST['date_sortie']; ?>" required onchange="controleTexteInput(this, 'dateSortieIndication', 'date')" class="form-control"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
                        <label id="dateSortieIndication" class="text-danger"><?php if (isset($_POST['date_sortie']) and empty($_POST['date_sortie'])) echo "Veuillez choisir une date" ?></label> <!-- Indication date de sortie, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                    </div>
                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <select class="form-control" name="categorie" id="categorie" required onchange="controleTexteInput(this, 'categorieIndication', 'categorie')" class="form-control">
                            <!-- Selection catégorie du jeu -->
                            <?php $reponse = $bdd->prepare('SELECT categorie_jeu.nom FROM categorie_jeu ORDER BY categorie_jeu.id');
                            $reponse->execute();
                            while ($donnees = $reponse->fetch()) { ?>
                                <option value="<?php echo $donnees['nom']; ?>" <?php if (isset($_POST['categorie']) and $_POST['categorie'] == $donnees['nom']) echo 'selected="selected"'; ?>><?php echo $donnees['nom']; ?></option> <!-- Les différentes options du select -->
                            <?php }

                            $reponse->closeCursor(); ?>
                        </select>
                        <label id="categorieIndication" class="text-danger"><?php if (isset($_POST['categorie']) and empty($_POST['categorie'])) echo "Veuillez choisir une catégorie"; ?></label> <!-- Indication categorie, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                    </div>

                    <div class="form-group">
                        <!-- Type de présentation -->
                        <label for="presentation">Type de Présentation</label>
                        <select class="form-control" name="presentation" id="presentation" required onchange="controleTexteInput(this, 'presentationIndication', 'presentation')" class="form-control">
                            <!-- Selection presentation de l'article -->
                            <option value="conteneur" <?php if (isset($_POST['presentation']) && $_POST['presentation'] == "conteneur") {
                                                            echo 'selected="selected"';
                                                        } ?>>Conteneur</option> <!-- Les différentes options du select -->
                            <option value="section" <?php if (isset($_POST['presentation']) && $_POST['presentation'] == "section") {
                                                        echo 'selected="selected"';
                                                    } ?>>Section</option> <!-- Les différentes options du select -->
                            <option value="normal" <?php if (isset($_POST['presentation']) && $_POST['presentation'] == "normal") {
                                                        echo 'selected="selected"';
                                                    } ?>>Normal</option> <!-- Les différentes options du select -->
                        </select>
                        <label id="presentationIndication" class="text-danger"><?php if (isset($_POST['presentation']) and empty($_POST['presentation'])) echo "Veuillez choisir un type de présentation" ?></label> <!-- Indication presentation, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                    </div>

                    <script>
                        autoCompletion("plateformes", "Plateformes");

                        var listeTags = tagsCreationArticles('liste_plateformes', 'lierPlateformes'); // On récupère les tags des plateformes
                    </script>

                        <div class="form-group">
                        <!-- Console / Pc -->
                        <label for="plateformes">Plateformes (non obligatoire)</label>
                        <div name="lierPlateformes" id="lierPlateformes" style="position: relative; border : 1px solid;">
                            <input type="text" name="plateformes" id="plateformes" style="border: 0;" value="<?php if (!empty($_POST['plateformes'])) echo $_POST['plateformes'] ?>" class="form-control" style="display: inline-block;"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
                        </div>
                        <label id="plateformesIndication" class="text-danger"><?php if (isset($_POST['plateformes'])) echo "La plateforme du jeu n'a pas été trouvée"; ?></label> <!-- Indication plateforme du jeu, il sera indiqué si le formulaire a déjà été soumis mais qu'il y a une erreur -->
                    </div>

                    <script>
                        autoCompletion("genres", "Genres");

                        var listeTags = tagsCreationArticles('liste_genres', 'lierGenres'); // On récupère les tags des genres
                    </script>

                        <div class="form-group">
                        <!-- Type jeu -->
                        <label for="genres">Genres (non obligatoire)</label>
                        <div name="lierGenres" id="lierGenres" style="position: relative; border : 1px solid;">
                            <input type="text" name="genres" id="genres" style="border: 0;" value="<?php if (!empty($_POST['genres'])) echo $_POST['genres'] ?>" class="form-control" style="display: inline-block;"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
                        </div>
                        <label id="genresIndication" class="text-danger"><?php if (isset($_POST['genres'])) echo "Le genre du jeu n'a pas été trouvé"; ?></label> <!-- Indication genre du jeu, il sera indiqué si le formulaire a déjà été soumis mais qu'il y a une erreur -->
                    </div>

                    <div class="form-group">
                        <label for="video_background">Vidéo en Arrière plan / URL Youtube (non obligatoire)</label>
                        <input type="text" name="video_background" id="video_background" value="<?php if (!empty($_POST['video_background'])) echo $_POST['video_background'] ?>" class="form-control"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
                    </div>

                    <div class="form-group">
                        <label for="bandeaux">Bannière (non obligatoire)</label> <!-- La bannière est placé au début des articles -->
                        <div class="input-group">
                            <!-- Upload de bannière -->
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon02">Upload</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" accept=".jpg, .png, .bmp, .gif" name="bandeaux" id="inputGroupFile02" onchange="controleTexteInput(this, 'banniereIndication', 'miniature')" aria-describedby="inputGroupFileAddon02"> <!-- Si un fichier a été choisi, l'événement onchange permettra de montrer le nom du fichier sur le label d'information -->
                                <label id="banniereIndication" class="custom-file-label" for="inputGroupFile02">Choisir fichier</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="miniature">Miniature</label>
                        <div class="input-group">
                            <!-- Upload de miniature -->
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" accept=".jpg, .png, .bmp, .gif" name="miniature" id="inputGroupFile01" required onchange="controleTexteInput(this, 'miniatureIndication', 'miniature')" aria-describedby="inputGroupFileAddon01"> <!-- Si un fichier a été choisi, l'événement onchange permettra de montrer le nom du fichier sur le label d'information -->
                                <label id="miniatureIndication" class="custom-file-label" for="inputGroupFile01">Choisir fichier</label>
                            </div>
                        </div>
                    </div>
                    <!-- On passe l'id de l'auteur -->
                    <?php
                    $pseudo = null;

                    if (isset($_SESSION['pseudo'])) {
                        $pseudo = $_SESSION['pseudo'];
                    }

                    $reponse2 = $bdd->prepare('SELECT id, statut FROM utilisateurs WHERE utilisateurs.pseudo = :pseudo'); // Récupération de l'id et de son statut
                    $reponse2->execute(array('pseudo' => $pseudo));
                    $donnees2 = $reponse2->fetch();
                    ?>
                    <input type="hidden" name="auteur" id="auteur" value="<?php echo $donnees2['id']; ?>">

                    <button type="submit" id="btn_envoi" class="btn btn-success">Envoyer</button>
                <?php } else if (!isset($_SESSION['pseudo'])) {
                ?><div class="alert alert-warning" role="alert" style="margin-top: 10px;">Veuillez vous <a href="/connexion.php">connecter</a> pour écrire un jeu.</div> <?php
                                                                                                                                                                        }    ?>
            </form>
        </div>
    </div>

    <?php
    include('footer.php');
    ?>
    <?php
    include('upload_image.php');
    ?>
    <?php
    include('ajout_url.php');
    ?>
    <?php
    include('ajout_tableau.php');
    ?>
    <?php
    include('ajout_section.php');
    ?>
    <?php
    include('ajout_video.php');
    ?>
    <?php

if (!empty($_POST['liste_plateformes'])) { // Servira si il y a une erreur, on split les plateformes et on les reprend
    $liste_plateformes = $_POST['liste_plateformes'];
    $liste_plateformes_splitter = explode(',', $liste_plateformes); // On split les differentes plateformes
}

if (!empty($_POST['liste_plateformes'])) { // On cherche pour voir si le jeu est lié à des plateformes, si oui on regarde si les plateformes entré correspond à une plateforme sinon on redemande de retaper la plateforme
    $plateforme_trouver = array();
    $id_plateforme_trouver = array();

    for ($i = 0; $i < count($liste_plateformes_splitter); $i++) { // On cherche pour chaque plateforme son id
        $reponse = $bdd->prepare('SELECT id FROM plateformes WHERE nom_plateforme = :nom');
        $reponse->execute(array('nom' => $liste_plateformes_splitter[$i]));
        $nombre_id_plateforme_trouver = $reponse->rowCount();
        array_push($id_plateforme_trouver, $nombre_id_plateforme_trouver);
        $donnees = $reponse->fetch();
        array_push($plateforme_trouver, $donnees['id']);
        $reponse->closeCursor();
    }
}

if (!empty($_POST['liste_genres'])) { // Servira si il y a une erreur, on split les genres et on les reprend
    $liste_genres = $_POST['liste_genres'];
    $liste_genres_splitter = explode(',', $liste_genres); // On split les differents genres
}

if (!empty($_POST['liste_genres'])) { // On cherche pour voir si le jeu est lié à des genres, si oui on regarde si les genres entré correspond à un genre sinon on redemande de retaper le genre
    $genre_trouver = array();
    $id_genre_trouver = array();

    for ($i = 0; $i < count($liste_genres_splitter); $i++) { // On cherche pour chaque genre son id
        $reponse = $bdd->prepare('SELECT id FROM genres WHERE genre = :nom');
        $reponse->execute(array('nom' => $liste_genres_splitter[$i]));
        $nombre_id_genre_trouver = $reponse->rowCount();
        array_push($id_genre_trouver, $nombre_id_genre_trouver);
        $donnees = $reponse->fetch();
        array_push($genre_trouver, $donnees['id']);
        $reponse->closeCursor();
    }
}

    if (!empty($_POST['nom']) and !empty($_POST['contenu']) and !empty($_POST['date_sortie']) and !empty($_POST['categorie']) and (empty($_POST['plateformes'])) or (!empty($_POST['plateformes']) and count($id_plateforme_trouver) > 0) and (empty($_POST['genres'])) or (!empty($_POST['genres']) and count($id_genre_trouver) > 0) and !empty($_FILES['miniature']['tmp_name']) and !empty($_POST['presentation'])) { // Traitement
        $nom = $_POST['nom'];
        $url = EncodageTitreEnUrl($nom);
        $description = $_POST['description'];
        $contenu = $_POST['contenu'];
        $date_sortie = $_POST['date_sortie'];
        $nom_categorie = $_POST['categorie'];
        $presentation = $_POST['presentation'];
        $video_background = $_POST['video_background'];
        $approuver = 'approuver';
        $nom_miniature = $_FILES['miniature']['name'];

        $tailleImage = getimagesize($_FILES['miniature']['tmp_name']); // Récupération taille de l'image uploadée
        $largeur = $tailleImage[0];
        $hauteur = $tailleImage[1];
        $largeur_miniature = 300; // Largeur de la future miniature
        $hauteur_miniature = $hauteur / $largeur * 300;

        $type_image = 'miniature'; // Recupère le nom de l'image pour indiquer quel type de fichier on va récupérer, miniature
        include('image_traitement.php');

        if (!empty($_FILES['bandeaux']['tmp_name'])) { // On regarde si une bannière à été ajoutée
            $nom_banniere = $_FILES['bandeaux']['name']; // Si il y a un nom, cela sera bien mis dans la base de donnees

            $tailleImage = getimagesize($_FILES['bandeaux']['tmp_name']); // Récupération taille de l'image uploadée
            $largeur = $tailleImage[0];
            $hauteur = $tailleImage[1];
            if ($largeur > 2500) { // On redimensionne
                redimensionImage($largeur, $hauteur, 2500, 2500);
            } else {
                $largeur_miniature = $largeur;
                $hauteur_miniature = $hauteur;
            }

            $type_image = 'bandeaux'; // Recupère le nom de l'image (formulaire) pour indiquer quel type de fichier on va récupérer, miniature, bien penser à mettre le nom du dossier pour le nom de l'input
            include('image_traitement.php');
        } else { // Si une image n'a pas été mise
            $nom_banniere = null;
        }

        $reponse = $bdd->prepare('SELECT id FROM categorie_jeu WHERE nom = :nom_categorie'); // Selection id catégorie du jeu à l'aide du nom pour l'insérer ensuite
        $reponse->execute(array('nom_categorie' => $nom_categorie));
        $donnees = $reponse->fetch();
        $id_categorie_jeu = $donnees['id'];
        $reponse->closeCursor();

        $reponse = $bdd->prepare('INSERT INTO jeu (nom, contenu, nom_miniature, date_sortie, url, id_categorie, presentation, nom_banniere, approuver, video_background, description, id_auteur_presentation) VALUES (:nom, :contenu, :nom_miniature, :date_sortie, :url, :id_categorie_jeu, :presentation, :nom_banniere, :approuver, :video_background, :description, :id_auteur_presentation)'); // Insertion jeu
        $reponse->execute(array('nom' => $nom, 'contenu' => $contenu, 'nom_miniature' => $nom_miniature, 'date_sortie' => $date_sortie, 'url' => $url, 'id_categorie_jeu' => $id_categorie_jeu, 'presentation' => $presentation, 'nom_banniere' => $nom_banniere, 'approuver' => $approuver, 'video_background' => $video_background, 'description' => $description, 'id_auteur_presentation' => $_POST['auteur']));
        $reponse->closeCursor();

        $id_jeu = $bdd->lastInsertId(); // On récupère l'id de jeu pour la liste des tag lié au jeu
        if(!empty($_POST['liste_plateformes'])) {
    // Traitement plateformes des jeux
    for ($i = 0; $i < count($plateforme_trouver); $i++) { // Parcours des différents id des plateformes
        if ($id_plateforme_trouver[$i] == 1) {
            $reponse = $bdd->prepare('INSERT INTO jeu_lier_plateformes (id_jeu, id_plateforme) VALUES (:id_jeu, :id_plateforme) '); // Insertion de la liste des plateformes lié au jeu
            $reponse->execute(array('id_jeu' => $id_jeu, 'id_plateforme' => $plateforme_trouver[$i]));
        }
    }
}
    if(!empty($_POST['liste_genres'])) {
    // Traitement genres des jeux
    for ($i = 0; $i < count($genre_trouver); $i++) { // Parcours des différents id des genres
        if ($id_genre_trouver[$i] == 1) {
            $reponse = $bdd->prepare('INSERT INTO jeu_lier_genres (id_jeu, id_genre) VALUES (:id_jeu, :id_genre) '); // Insertion de la liste des genre lié au jeu
            $reponse->execute(array('id_jeu' => $id_jeu, 'id_genre' => $genre_trouver[$i]));
        }
    }
}

    ?>
        <script>
            document.location.href = '/'; // Redirection nouvelle url
        </script>

    <?php
    } else {
    }

    /*
    if (empty($_POST['titre'])) { // Si le titre n'a pas été rempli
?>
        <div class="form-group">Le titre n'a pas été rempli</div> <?php
    }
    if (empty($_POST['contenu'])) {
        ?>
        <div class="form-group">Le contenu n'a pas été rempli</div> <?php
    }
    if (empty($_FILES['miniature']['tmp_name'])) {
        ?>
        <div class="form-group">La miniature n'a pas été choisie</div> <?php
    }
    ?>
    <a href="creation_news.php">Cliquez pour revenir à la création d'une news</a> 
    </span>
    <?php
    */
    ?>
</body>