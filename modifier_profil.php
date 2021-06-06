<?php
$title = "Modifier profil";
include('Header.php');

$pseudo_membre = $_SESSION['pseudo'];

$reponse = $bdd->prepare('SELECT * FROM utilisateurs WHERE pseudo = :pseudo'); // Sélection du membre à modifier
$reponse->execute(array('pseudo' => $pseudo_membre));
$donnees = $reponse->fetch();
$reponse->closeCursor();
?>

<script>
    $(function() {
        $('#btn_form_profil').on('click', function() { // Quand on click sur le formulaire, on regarde si les switch sont changé et si oui, on met la nouvelle valeur dans des input cachés
            if ($('#switch_video_background').is(':checked')) {
                $('#video_background').val("true");
            } else {
                $('#video_background').val("false");
            }

            if ($('#switch_video_son_background').is(':checked')) {
                $('#video_son_background').val("true");
            } else {
                $('#video_son_background').val("false");
            }
        });
    });
</script>

<body style="<?php if (!isset($_SESSION['nom_image_background'])) {
                    echo "background-image: url('/background.jpg');";
                } else {
                    echo "background-image: url('/utilisateurs/" . $_SESSION['id'] . "/background_site/" . $_SESSION['nom_image_background'] . "');";
                } ?> background-repeat: no-repeat; background-attachment: fixed; background-size: cover; background-position: center center; overflow-x: hidden;">
    <div class="container container-bordure animation fadeRight bg-white">
        <div class="row">
            <form class="form" method="post" name="form_profil" id="form_profil" enctype="multipart/form-data" style="margin:50px">
                <h1>Modifier profil</h1>
                <hr> <!-- Trait -->
                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" name="pseudo" id="pseudo" value="<?php if (!empty($donnees['pseudo'])) echo $donnees['pseudo'] ?>" disabled class="form-control"> <!-- Valeur du pseudo -->
                </div>
                <div class="form-group">
                    <label for="photo_profil_actuel">Photo de profil actuelle</label>
                    <img src="/utilisateurs/<?php echo htmlspecialchars($donnees['id']); ?>/photo_profil/<?php echo $donnees['nom_photo_profil'] ?>" onerror="this.oneerror=null; this.src='/1.jpg';" name="photo_profil_actuel" id="photo_profil_actuel" class="img-fluid img-thumbnail form-control" style="height: 10vh; width: 10vh;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut -->
                </div>
                <div class="form-group">
                    <label for="photo_profil">Modifier la photo de profil (non obligatoire)</label>
                    <div class="input-group">
                        <!-- Upload de photo de profil -->
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" accept=".jpg, .png, .bmp, .gif" class="custom-file-input" name="photo_profil" id="inputGroupFile01" onchange="controleTexteInput(this, 'miniatureIndication', 'miniature')" aria-describedby="inputGroupFileAddon01"> <!-- Si un fichier a été choisi, l'événement onchange permettra de montrer le nom du fichier sur le label d'information -->
                            <label id="miniatureIndication" class="custom-file-label" for="inputGroupFile01">Choisir fichier</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="background_site">Changer le background du site (non obligatoire)</label>
                    <div class="input-group">
                        <!-- Upload de background -->
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupFileAddon02">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" accept=".jpg, .png, .bmp, .gif" class="custom-file-input" name="background_site" id="inputGroupFile02" onchange="controleTexteInput(this, 'imageBackgroundIndication', 'miniature')" aria-describedby="inputGroupFileAddon02"> <!-- Si un fichier a été choisi, l'événement onchange permettra de montrer le nom du fichier sur le label d'information -->
                            <label id="imageBackgroundIndication" class="custom-file-label" for="inputGroupFile02">Choisir fichier</label>
                        </div>
                    </div>
                </div>
                <div class="form-check" id="defaultBackgroundGroup" style="display: none;"> <!-- On supprime le background inscrit par l'utilisateur; ce menu sera affiché si il y a déjà une variable de session background -->
                    <input class="form-check-input" type="checkbox" id="flexCheckDefaultBackground" name="flexCheckDefaultBackground">
                    <label class="form-check-label" for="flexCheckDefaultBackground">
                       Supprimer le background du site et le remettre par défaut
                    </label>
                </div>
                <div class="custom-control custom-switch form-group">
                    <?php
                    if (isset($_POST['video_background']) && $_POST['video_background'] == "true" || !isset($_POST['video_background']) && $donnees['activer_video_background'] == "true") { ?>
                        <input class="custom-control-input" checked type="checkbox" id="switch_video_background">
                    <?php } else if (!isset($_POST['video_background']) && $donnees['activer_video_background'] == "false" || isset($_POST['video_background']) && $_POST['video_background'] == "false") { ?>
                        <input class="custom-control-input" type="checkbox" id="switch_video_background">
                    <?php } else { ?>
                        <input class="custom-control-input" type="checkbox" id="switch_video_background">
                    <?php } ?>
                    <label class="custom-control-label" for="switch_video_background">Activer les vidéos en fond</label>
                    <input type="hidden" name="video_background" id="video_background">
                </div>
                <div class="custom-control custom-switch form-group">
                    <?php
                    if (isset($_POST['video_son_background']) && $_POST['video_son_background'] == "true" || !isset($_POST['video_son_background']) && $donnees['activer_son_video_background'] == "true") { ?>
                        <input class="custom-control-input" checked type="checkbox" id="switch_video_son_background">
                    <?php } else if (!isset($_POST['video_son_background']) && $donnees['activer_son_video_background'] == "false" || isset($_POST['video_son_background']) && $_POST['video_son_background'] == "false") { ?>
                        <input class="custom-control-input" type="checkbox" id="switch_video_son_background">
                    <?php } else { ?>
                        <input class="custom-control-input" type="checkbox" id="switch_video_son_background">
                    <?php } ?>
                    <label class="custom-control-label" for="switch_video_son_background">Activer le son pour les vidéos en fond</label>
                    <input type="hidden" name="video_son_background" id="video_son_background">
                </div>

                <button type="submit" value="envoyer" class="btn btn-success" name="btn_form_profil" id="btn_form_profil">Envoyer</button>

                <hr>
            </form>
        </div>
    </div>
</body>
<?php
$reponse->closeCursor();
?>

<script>
$(function() {
    if('<?php echo $_SESSION['nom_image_background']; ?>') {
        $('#defaultBackgroundGroup').show(); // Si on a déjà un background, on peut le supprimer
    }
});
</script>

<?php
include('upload_image.php');
?>
<?php
include('ajout_url.php');

include('footer.php');
?>

<?php
if (!empty($_POST['btn_form_profil'])) {
    $idUtilisateur = $donnees['id'];
    if (!empty($_FILES['photo_profil']['tmp_name'])) { // Traitement
        $nom_photo_profil = $idUtilisateur . '.' . strtolower(pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION));

        $tailleImage = getimagesize($_FILES['photo_profil']['tmp_name']); // Récupération taille de l'image uploadée
        $largeur = $tailleImage[0];
        $hauteur = $tailleImage[1];
        $largeur_miniature = 300; // Largeur de la future miniature
        $hauteur_miniature = $hauteur / $largeur * 300;
        if (strtolower(pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION)) == "jpg") { // On regarde l'extension de l'image pour convertir
            $im = imagecreatefromjpeg($_FILES['photo_profil']['tmp_name']); // Stockage de la photo qui vient d'être uploadée
            $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
            imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
            imagejpeg($im_miniature, 'utilisateurs/' . $idUtilisateur . '/photo_profil/' . $idUtilisateur . '.jpg', 100); // Création de l'image jpg dans le dossier photo_profil
        } else if (strtolower(pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION)) == "png") { // On regarde l'extension de l'image pour convertir
            $im = imagecreatefromstring(file_get_contents($_FILES['photo_profil']['tmp_name'])); // Stockage de la photo qui vient d'être uploadée
            $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
            $background = imagecolorallocatealpha($im_miniature, 255, 255, 255, 128); // Gestion de la transparence
            imagecolortransparent($im_miniature, $background);
            imagealphablending($im_miniature, false);
            imagesavealpha($im_miniature, true);
            imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
            imagepng($im_miniature, 'utilisateurs/' . $idUtilisateur . '/photo_profil/' . $idUtilisateur . '.png'); // Création de l'image png dans le dossier photo_profil
            imagedestroy($im);
        } else if (strtolower(pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION)) == "bmp") { // On regarde l'extension de l'image pour convertir (elle est d'abord récupérée et convertit en minuscule pour pouvoir comparer quand les extensions sont en en majuscules),
            $im = imagecreatefrombmp($_FILES['photo_profil']['tmp_name']); // Stockage de la photo qui vient d'être uploadée
            $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
            imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
            imagebmp($im_miniature, 'utilisateurs/' . $idUtilisateur . '/photo_profil/' . $idUtilisateur . '.bmp' , 100); // Création de l'image bmp dans le dossier photo_profil
        } else if (strtolower(pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION)) == "gif") { // On regarde l'extension de l'image pour convertir
            move_uploaded_file($_FILES['photo_profil']['tmp_name'], 'utilisateurs/' . $idUtilisateur . 'photo_profil'); // Bouge l'image sans la redimensionner, il faudra faire en sorte qu'elle ne dépasse pas une taille
        }

        $reponse = $bdd->prepare('UPDATE utilisateurs SET nom_photo_profil = :nom_photo_profil WHERE pseudo = :pseudo'); // Modification utilisateur
        $reponse->execute(array('nom_photo_profil' =>  $nom_photo_profil, 'pseudo' => $donnees['pseudo']));
    }
    if(isset($_POST['flexCheckDefaultBackground'])) { // On regarde si l'utilisateur veut supprimer le background inscrit
        if(isset($_SESSION['nom_image_background'])) { // On regarde si l'utilisateur à déjà un background avant de le supprimer
            $reponse = $bdd->prepare('UPDATE utilisateurs SET nom_image_background = "" WHERE pseudo = :pseudo'); // Modification utilisateur
            $reponse->execute(array('pseudo' => $donnees['pseudo']));
            unset($_SESSION['nom_image_background']); // On enlève la variable de session
        }

    }
    else if (!empty($_FILES['background_site']['tmp_name'])) { // Traitement
        $nom_background = 'background.' . strtolower(pathinfo($_FILES['background_site']['name'], PATHINFO_EXTENSION));

        $tailleImage = getimagesize($_FILES['background_site']['tmp_name']); // Récupération taille de l'image uploadée
        $largeur = $tailleImage[0];
        $hauteur = $tailleImage[1];

        if ($largeur > 3840 || $hauteur > 2160) { // Si l'image est trop grande, on redimensionne
            $largeur_miniature = $largeur / 2;
            $hauteur_miniature = $hauteur / 2;
        } else {
            $largeur_miniature = $largeur;
            $hauteur_miniature  = $hauteur;
        }

        /*
    if ($_POST['tailleImage'] == "icone") { // Si l'utilisateur à choisi une taille d'image, on choisi parmi les tailles d'image disponible
        $largeur_miniature = 75;
        $hauteur_miniature = ($hauteur / $largeur * 300) / 4;
    } else if ($_POST['tailleImage'] == "petite") {
        $largeur_miniature = 300;
        $hauteur_miniature = $hauteur / $largeur * 300;
    } else if ($_POST['tailleImage'] == "moyenne") {
        $largeur_miniature = 600;
        $hauteur_miniature = ($hauteur / $largeur * 300) * 2;
    } else if ($_POST['tailleImage'] == "grande") {
        $largeur_miniature = 1200;
        $hauteur_miniature = ($hauteur / $largeur * 300) * 4;
    } else {
        $largeur_miniature = 300; // Largeur de la future miniature
        $hauteur_miniature = $hauteur / $largeur * 300;
    }
    */
        $reponse = $bdd->prepare('UPDATE utilisateurs SET nom_image_background = :nom_image_background WHERE pseudo = :pseudo'); // Modification utilisateur
        $reponse->execute(array('nom_image_background' => $nom_background, 'pseudo' => $donnees['pseudo']));
        $_SESSION['nom_image_background'] = $nom_background; // On met le nom du background en variable de session

        $type_image = 'background_site'; // Recupère le nom de l'image (formulaire) pour indiquer quel type de fichier on va récupérer, image de background
        $parametre_upload_image = "modification"; // Dit si c'est une modification
        include('image_traitement.php');

        /*
        $tailleImage = getimagesize($_FILES['image_background']['tmp_name']); // Récupération taille de l'image uploadée
        $largeur = $tailleImage[0];
        $hauteur = $tailleImage[1];
        $largeur_miniature = 1920; // Largeur de la future miniature
        $hauteur_miniature = $hauteur / $largeur * 1080;
        if (strtolower(pathinfo($_FILES['image_background']['name'], PATHINFO_EXTENSION)) == "jpg") { // On regarde l'extension de l'image pour convertir
            $im = imagecreatefromjpeg($_FILES['image_background']['tmp_name']); // Stockage de la photo qui vient d'être uploadée
            $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
            imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
            imagejpeg($im_miniature, '/photo_profil/57/background_site/Pokemon-Fire-red-and-leaf-green-pokemon-games-39743639-470-235.jpg', 100); // Création de l'image jpg dans le dossier photo_profil
        } else if (strtolower(pathinfo($_FILES['image_background']['name'], PATHINFO_EXTENSION)) == "png") { // On regarde l'extension de l'image pour convertir
            $im = imagecreatefromstring(file_get_contents($_FILES['image_background']['tmp_name'])); // Stockage de la photo qui vient d'être uploadée
            $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
            $background = imagecolorallocatealpha($im_miniature, 255, 255, 255, 128); // Gestion de la transparence
            imagecolortransparent($im_miniature, $background);
            imagealphablending($im_miniature, false);
            imagesavealpha($im_miniature, true);
            imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
            imagepng($im_miniature, '/photo_profil/' . $idUtilisateur . '/background_site/' . $_FILES['image_background']['name'] . '.png'); // Création de l'image png dans le dossier photo_profil
            imagedestroy($im);
        } else if (strtolower(pathinfo($_FILES['image_background']['name'], PATHINFO_EXTENSION)) == "bmp") { // On regarde l'extension de l'image pour convertir (elle est d'abord récupérée et convertit en minuscule pour pouvoir comparer quand les extensions sont en en majuscules),
            $im = imagecreatefrombmp($_FILES['image_background']['tmp_name']); // Stockage de la photo qui vient d'être uploadée
            $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
            imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
            imagebmp($im_miniature, '/photo_profil/' . $idUtilisateur . '/background_site/' . $_FILES['image_background']['name'] . '.jpg', 100); // Création de l'image bmp dans le dossier photo_profil
        } else if (strtolower(pathinfo($_FILES['image_background']['name'], PATHINFO_EXTENSION)) == "gif") { // On regarde l'extension de l'image pour convertir
            move_uploaded_file($_FILES['image_background']['tmp_name'], '/photo_profil/' . $idUtilisateur . '/background_site/' . $_FILES['image_background']['name'] . '.gif'); // Bouge l'image sans la redimensionner, il faudra faire en sorte qu'elle ne dépasse pas une taille
        }
        $reponse = $bdd->prepare('UPDATE utilisateurs SET nom_image_background = :image_background WHERE pseudo = :pseudo'); // Modification utilisateur
        $reponse->execute(array('image_background' =>  $nom_background, 'pseudo' => $donnees['pseudo'])); */
    }

    $reponse = $bdd->prepare('UPDATE utilisateurs SET activer_video_background = :activer_video_background, activer_son_video_background = :activer_son_video_background WHERE pseudo = :pseudo'); // Modification utilisateur
    $reponse->execute(array('activer_video_background' => $_POST['video_background'], 'activer_son_video_background' => $_POST['video_son_background'], 'pseudo' => $donnees['pseudo']));
?>
    <script>
        <?php /* document.location.href = '/modifier_news/<?php echo $url; ?>-<?php echo $id; ?>'; // Redirection nouvelle url */ ?>
        document.location.href = '/';
    </script>
<?php
}
?>
<?php
// header('Location: index.php'); // Redirection vers la page d'accueil
?>