<?php
$title = "Création de jeu";
include('Header.php');
?>

<body class="background">
    <div class="container container-bordure animated fadeInRight bg-white">
        <div class="row">
            <form class="form" method="post" enctype="multipart/form-data" style="margin:50px">
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
    include('ajout_video.php');
    ?>
    <?php

    if (!empty($_POST['nom']) and !empty($_POST['contenu']) and !empty($_POST['date_sortie']) and !empty($_POST['categorie']) and !empty($_FILES['miniature']['tmp_name'])) { // Traitement
        $nom = $_POST['nom'];
        $url = EncodageTitreEnUrl($nom);
        $contenu = $_POST['contenu'];
        $date_sortie = $_POST['date_sortie'];
        $nom_categorie = $_POST['categorie'];
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
            }
            else {
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

        $reponse = $bdd->prepare('INSERT INTO jeu (nom, contenu, nom_miniature, date_sortie, url, id_categorie, nom_banniere) VALUES (:nom, :contenu, :nom_miniature, :date_sortie, :url, :id_categorie_jeu, :nom_banniere)'); // Insertion jeu
        $reponse->execute(array('nom' => $nom, 'contenu' => $contenu, 'nom_miniature' => $nom_miniature, 'date_sortie' => $date_sortie, 'url' => $url, 'id_categorie_jeu' => $id_categorie_jeu, 'nom_banniere' => $nom_banniere));
        $reponse->closeCursor();
    ?>
        <script>
            document.location.href = '/index.php'; // Redirection nouvelle url
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