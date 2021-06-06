<?php
$title = "Création de news";
include('Header.php');
?>

<body class="background">
    <div class="container container-bordure animated fadeInRight bg-white">
        <div class="row">
            <form class="form" id="creation_news" method="post" enctype="multipart/form-data" style="margin:50px">
                <h1>Création de news</h1>
                <hr> <!-- Trait -->
                <?php if (isset($_SESSION['pseudo'])) { // Si l'utilisateur est connecter, il peut modifier un article
                ?>
                    <div class="form-group">
                        <label for="titre">Titre</label>
                        <input type="text" name="titre" id="titre" value="<?php if (!empty($_POST['titre'])) echo $_POST['titre'] ?>" required onchange="controleTexteInput(this, 'titreIndication', 'titre')" class="form-control"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
                        <label id="titreIndication" class="text-danger"><?php if (isset($_POST['titre']) and empty($_POST['titre'])) echo "Veuillez choisir un titre" ?></label> <!-- Indication titre, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
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
                        <label for="categorie">Catégorie</label>
                        <select class="form-control" name="categorie" id="categorie" required onchange="controleTexteInput(this, 'categorieIndication', 'categorie')" class="form-control">
                            <!-- Selection catégorie de l'article -->
                            <?php $reponse = $bdd->prepare('SELECT nom FROM categorie ORDER BY id');
                            $reponse->execute();
                            while ($donnees = $reponse->fetch()) { ?>
                                <option value="<?php echo $donnees['nom']; ?>" <?php if (isset($_POST['categorie']) and $_POST['categorie'] == $donnees['nom']) echo 'selected="selected"'; ?>><?php echo $donnees['nom']; ?></option> <!-- Les différentes options du select -->
                            <?php }

                            $reponse->closeCursor(); ?>
                        </select>
                        <label id="categorieIndication" class="text-danger"><?php if (isset($_POST['categorie']) and empty($_POST['categorie'])) echo "Veuillez choisir une catégorie"; ?></label> <!-- Indication categorie, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                    </div>

                    <script>
                        autoCompletion("jeu", "Jeux");
                    </script>

                    <div class="form-group">
                        <label for="jeu">Lié à un jeu (non obligatoire)</label>
                        <div name="lierJeux" id="lierJeux" style="position: relative; border : 1px solid;">
                        <input type="text" name="jeu" id="jeu" style="border: 0;" value="<?php if (!empty($_POST['jeu'])) echo $_POST['jeu'] ?>" class="form-control" style="display: inline-block;"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
                        </div>
                        <label id="jeuIndication" class="text-danger"><?php if (isset($_POST['jeu'])) echo "Le titre du jeu n'a pas été trouvé"; ?></label> <!-- Indication titre du jeu, il sera indiqué si le formulaire a déjà été soumis mais qu'il y a une erreur -->
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

                    <!-- On récupère le statut de l'utilisateur et si le statut de l'utilisateur est suffisant, on approuve la news, sinon la news est rédigé mais il faudra l'approuver après -->
                    <input type="hidden" name="article_approuver" id="article_approuver" value="<?php if ($donnees2['statut'] == "Administrateur") {
                                                                                                    echo 1;
                                                                                                } else {
                                                                                                    echo 0;
                                                                                                }; ?>">
                    <?php
                    $reponse2->closeCursor();
                    ?>

                    <button type="submit" id="btn_envoi" class="btn btn-success">Envoyer</button>
                <?php } else if (!isset($_SESSION['pseudo'])) {
                ?><div class="alert alert-warning" role="alert" style="margin-top: 10px;">Veuillez vous <a href="/connexion.php">connecter</a> pour écrire un article.</div> <?php
                                                                                                                                                                            }    ?>
            </form>
        </div>
    </div>
    <script>
        //  $('#contenu').on('change', function() { // On met le contenu dans la prévisualisation
        //    $('#previsualisationContenu').empty();
        //   contenuTexteHtml = remplacerBaliseParBBCodePrevisualisation($('#contenu').val());
        //   $('#previsualisationContenu').append(contenuTexteHtml); // On replace le contenu dans la prévisualisation
        // });
    </script>

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
    if (!empty($_POST['jeu'])) { // On cherche pour voir si l'utilisateur veut lier l'article à un jeu, si oui, on regarde si le titre entré correspond à un titre d'un jeu sinon on redemande de retaper le jeu
        ?>
        <script>
            alert($_POST['lierJeux']);
            console.log(($('#lierJeux').children('.tag').val()));
    </script>
    <?php
        $reponse = $bdd->prepare('SELECT id FROM jeu WHERE nom = :nom');
        $reponse->execute(array('nom' => $_POST['jeu']));
        $id_jeu_trouver = $reponse->rowCount();
        $donnees = $reponse->fetch();
        $reponse->closeCursor();
    }

    if (!empty($_POST['titre']) and !empty($_POST['contenu']) and !empty($_POST['auteur']) and !empty($_POST['categorie']) and isset($_POST['article_approuver']) and !empty($_FILES['miniature']['tmp_name']) and (empty($_POST['jeu']) or (!empty($_POST['jeu'] and $id_jeu_trouver == 1 and isset($_SESSION['pseudo']))))) { // Traitement
        $titre = $_POST['titre'];
        $url = EncodageTitreEnUrl($titre);
        $contenu = $_POST['contenu'];
        $categorie = $_POST['categorie'];
        $article_approuver = $_POST['article_approuver'];
        $nom_miniature = $_FILES['miniature']['name'];

        $tailleImage = getimagesize($_FILES['miniature']['tmp_name']); // Récupération taille de l'image uploadée
        $largeur = $tailleImage[0];
        $hauteur = $tailleImage[1];
        $largeur_miniature = 300; // Largeur de la future miniature
        $hauteur_miniature = $hauteur / $largeur * 300;

        $type_image = 'miniature'; // Recupère le nom de l'image (formulaire) pour indiquer quel type de fichier on va récupérer, miniature
        include('image_traitement.php');
        /*
        if (pathinfo($_FILES['miniature']['tmp_name'], PATHINFO_EXTENSION) == "jpg") { // On regarde l'extension de l'image pour convertir
            $im = imagecreatefromjpeg($_FILES['miniature']['name']); // Stockage de la photo qui vient d'être uploadée
            $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
            imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
            imagejpeg($im_miniature, 'miniature/' . $_FILES['miniature']['name'], 100); // Création de l'image jpg dans le dossier miniature
        } else if (pathinfo($_FILES['miniature']['name'], PATHINFO_EXTENSION) == "png") { // On regarde l'extension de l'image pour convertir
            $im = imagecreatefrompng($_FILES['miniature']['tmp_name']); // Stockage de la photo qui vient d'être uploadée
            $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
            $background = imagecolorallocatealpha($im_miniature, 255, 255, 255, 128);
            imagecolortransparent($im_miniature, $background);
            imagealphablending($im_miniature, false);
            imagesavealpha($im_miniature, true);
            imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
            imagepng($im_miniature, 'miniature/' . $_FILES['miniature']['name']); // Création de l'image png dans le dossier miniature
            imagedestroy($im);
        }
        */

        if (!empty($_FILES['bandeaux']['tmp_name'])) { // On regarde si une bannière à été ajoutée
            $nom_banniere = $_FILES['bandeaux']['name']; // Si il y a un nom, cela sera bien mis dans la base de donnees

            $tailleImage = getimagesize($_FILES['bandeaux']['tmp_name']); // Récupération taille de l'image uploadée
            $largeur = $tailleImage[0];
            $hauteur = $tailleImage[1];
            if ($largeur > 2500) { // On redimensionne
                redimensionImage($largeur, $hauteur, 2500, 2500);
                // $largeur_miniature = 1200; // Largeur de la future miniature
                // $hauteur_miniature = $hauteur / $largeur * 675;
            } else {
                $largeur_miniature = $largeur;
                $hauteur_miniature = $hauteur;
            }

            $type_image = 'bandeaux'; // Recupère le nom de l'image (formulaire) pour indiquer quel type de fichier on va récupérer, miniature, bien penser à mettre le nom du dossier pour le nom de l'input
            include('image_traitement.php');
        } else { // Si une image n'a pas été mise
            $nom_banniere = null;
        }

        $reponse = $bdd->prepare('INSERT INTO article (titre, contenu, nom_categorie, nom_miniature, date_creation, url, id_jeu, id_auteur, approuver, nom_banniere) VALUES (:titre, :contenu, :categorie, :nom_miniature, :date_creation, :url, :id_jeu, :id_auteur, :article_approuver, :nom_banniere)'); // Insertion news
        $reponse->execute(array('titre' => $titre, 'contenu' => $contenu, 'categorie' => $categorie, 'nom_miniature' => $nom_miniature, 'date_creation' => date("Y-m-d H:i:s"), 'url' => $url, 'id_jeu' => $donnees['id'], 'id_auteur' => $_POST['auteur'], 'article_approuver' => $article_approuver, 'nom_banniere' => $nom_banniere));
    ?>
        <script>
            document.location.href = '/portfolio/index.php'; // Redirection nouvelle url
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