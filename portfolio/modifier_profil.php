<?php
$title = "Modifier profil";
include('Header.php');

$pseudo_membre = $_SESSION['pseudo'];

$reponse = $bdd->prepare('SELECT * FROM utilisateurs WHERE pseudo = :pseudo'); // Sélection du membre à modifier
$reponse->execute(array('pseudo' => $pseudo_membre));
$donnees = $reponse->fetch();
$reponse->closeCursor();
?>

<body class="background">
    <div class="container container-bordure animated fadeInRight bg-white">
        <div class="row">
            <form class="form" method="post" enctype="multipart/form-data" style="margin:50px">
                <h1>Modifier profil</h1>
                <hr> <!-- Trait -->
                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" name="pseudo" id="pseudo" value="<?php if (!empty($donnees['pseudo'])) echo $donnees['pseudo'] ?>" disabled class="form-control"> <!-- Valeur du pseudo -->
                </div>
                <div class="form-group">
                    <label for="photo_profil_actuel">Photo de profil actuelle</label>
                    <img src="/photo_profil/<?php echo $donnees['nom_photo_profil'] ?>" onerror="this.oneerror=null; this.src='/1.jpg';" name="photo_profil_actuel" id="photo_profil_actuel" class="img-fluid img-thumbnail form-control" style="height: 10vh; width: 10vh;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut -->
                </div>
                <div class="form-group">
                    <label for="photo_profil">Modifier la photo de profil (non obligatoire)</label>
                    <div class="input-group">
                        <!-- Upload de photo de profil -->
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" accept=".jpg, .png, .bmp, .gif" required class="custom-file-input" name="photo_profil" id="inputGroupFile01" onchange="controleTexteInput(this, 'miniatureIndication', 'miniature')" aria-describedby="inputGroupFileAddon01"> <!-- Si un fichier a été choisi, l'événement onchange permettra de montrer le nom du fichier sur le label d'information -->
                            <label id="miniatureIndication" class="custom-file-label" for="inputGroupFile01">Choisir fichier</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Envoyer</button>

                <hr>
            </form>
        </div>
    </div>
</body>
<?php
$reponse->closeCursor();
?>

<?php
include('upload_image.php');
?>
<?php
include('ajout_url.php');

include('footer.php');
?>

<?php
if (!empty($_FILES['photo_profil']['tmp_name'])) { // Traitement
    $nom_miniature = $_FILES['photo_profil']['name'];

    $tailleImage = getimagesize($_FILES['photo_profil']['tmp_name']); // Récupération taille de l'image uploadée
    $largeur = $tailleImage[0];
    $hauteur = $tailleImage[1];
    $largeur_miniature = 300; // Largeur de la future miniature
    $hauteur_miniature = $hauteur / $largeur * 300;
    if (strtolower(pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION)) == "jpg") { // On regarde l'extension de l'image pour convertir
        $im = imagecreatefromjpeg($_FILES['photo_profil']['tmp_name']); // Stockage de la photo qui vient d'être uploadée
        $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
        imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
        imagejpeg($im_miniature, 'photo_profil/' . $idUtilisateur . '.jpg', 100); // Création de l'image jpg dans le dossier photo_profil
    } else if (strtolower(pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION)) == "png") { // On regarde l'extension de l'image pour convertir
        $im = imagecreatefromstring(file_get_contents($_FILES['photo_profil']['tmp_name'])); // Stockage de la photo qui vient d'être uploadée
        $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
        $background = imagecolorallocatealpha($im_miniature, 255, 255, 255, 128); // Gestion de la transparence
        imagecolortransparent($im_miniature, $background);
        imagealphablending($im_miniature, false);
        imagesavealpha($im_miniature, true);
        imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
        imagepng($im_miniature, 'photo_profil/' . $idUtilisateur . '.png'); // Création de l'image png dans le dossier photo_profil
        imagedestroy($im);
    } else if (strtolower(pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION)) == "bmp") { // On regarde l'extension de l'image pour convertir (elle est d'abord récupérée et convertit en minuscule pour pouvoir comparer quand les extensions sont en en majuscules),
        $im = imagecreatefrombmp($_FILES['photo_profil']['tmp_name']); // Stockage de la photo qui vient d'être uploadée
        $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
        imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
        imagebmp($im_miniature, 'photo_profil' . '/' . $_FILES['photo_profil']['name'], 100); // Création de l'image bmp dans le dossier photo_profil
    } else if (strtolower(pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION)) == "gif") { // On regarde l'extension de l'image pour convertir
        move_uploaded_file($_FILES['photo_profil']['tmp_name'], $type_image . '/' . $_FILES['photo_profil']['name']); // Bouge l'image sans la redimensionner, il faudra faire en sorte qu'elle ne dépasse pas une taille
    }

    $reponse = $bdd->prepare('UPDATE utilisateurs SET nom_photo_profil = :nom_photo_profil WHERE pseudo = :pseudo'); // Modification utilisateur
    $reponse->execute(array('nom_photo_profil' =>  $nom_photo_profil, 'pseudo' => $donnees['pseudo']));   
    ?>
    <script>
        <?php /* document.location.href = '/modifier_news/<?php echo $url; ?>-<?php echo $id; ?>'; // Redirection nouvelle url */ ?>
        document.location.href = '/index.php';
    </script>
    <?php }
?>
<?php
    // header('Location: index.php'); // Redirection vers la page d'accueil
    ?>