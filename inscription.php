<?php
$title = "Inscription";
include('Header.php');

$errors = array(); // Tableau pour stocker les messages d'erreur

if (!empty($_POST['nom']) and !empty($_POST['mdp']) and !empty($_POST['mail']) and !empty($_FILES['photo_profil']['tmp_name'])) {
    $pseudo = $_POST['nom'];
    $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT); // Hash du mot de passe
    $mail = $_POST['mail'];
    $statut = "Membre";
    $nom_photo_profil_avant_conversion = $_FILES['photo_profil']['name']; // Pour obtenir le nom final du fichier image, on utilise l'id du membre ainsi que l'extension de l'image

    $reponse = $bdd->prepare('SELECT COUNT(pseudo) as NbPseudo FROM utilisateurs WHERE pseudo = :pseudo'); // On cherche le nombre de pseudo sous le meme nom pour voir si il est déjà pris
    $reponse->execute(array('pseudo' => $pseudo));
    $donnees = $reponse->fetch();
    $reponse->closeCursor();

    if ($donnees['NbPseudo'] == 0) { // Si le pseudo est disponible
        // Gestion de l'image
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'bmp', 'gif');

        // Vérifiez si le fichier est une image valide
        $image_info = getimagesize($_FILES['photo_profil']['tmp_name']);
        if ($image_info === false) {
            $errors[] = "Le fichier n'est pas une image valide.";
        } else {
            $extension = strtolower(pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, $allowed_extensions)) {
                $errors[] = "L'extension de fichier n'est pas autorisée. Utilisez une image JPG, JPEG, PNG, BMP ou GIF.";
            } else {
                // Limitez la taille de l'image téléchargée (ex : 2 Mo)
                $max_file_size = 2 * 1024 * 1024; // 2 Mo
                if ($_FILES['photo_profil']['size'] > $max_file_size) {
                    $errors[] = "La taille du fichier est trop grande. Veuillez choisir une image plus petite.";
                } else {
                    // Tout est valide, continuez avec l'upload de l'image
                    $reponse = $bdd->prepare('INSERT INTO utilisateurs (pseudo, mdp, mail, statut, activer_video_background, activer_son_video_background) VALUES (:pseudo, :mdp, :mail, :statut, "true", "false")'); // Insertion utilisateur
                    $reponse->execute(array('pseudo' =>  $pseudo, 'mdp' => $mdp, 'mail' => $mail, 'statut' => $statut));

                    $idUtilisateur = $bdd->lastInsertId();

                    $reponse = $bdd->prepare('UPDATE utilisateurs SET nom_photo_profil = :nom_photo_profil WHERE id = :idUtilisateur'); // Mise à jour de l'utilisateur avec nom de l'image de profil
                    $reponse->execute(array('nom_photo_profil' => $idUtilisateur . '.' . $extension, 'idUtilisateur' => $idUtilisateur));

                    if (!file_exists("utilisateurs/" . $idUtilisateur)) {
                        mkdir("utilisateurs/" . $idUtilisateur, 0777, true); // On créé les dossiers pour l'utiilisateur
                        mkdir("utilisateurs/" . $idUtilisateur . "/photo_profil", 0777, true);
                        mkdir("utilisateurs/" . $idUtilisateur . "/background_site", 0777, true);
                    }

                    $upload_folder = 'utilisateurs/' . $idUtilisateur . '/photo_profil/';
                    // Déplacer le fichier image vers le dossier de destination
                    $upload_path = $upload_folder . $idUtilisateur . '.' . $extension;
                    if (move_uploaded_file($_FILES['photo_profil']['tmp_name'], $upload_path)) {
                        $_SESSION['pseudo'] = $pseudo; // Variable de session, connexion
                        $_SESSION['id'] = $idUtilisateur;
                        $_SESSION['statut'] = $statut;
                        header("Location: /index.php"); // Redirection vers une nouvelle URL
                        exit;
                    } else {
                        $errors[] = "Erreur lors de l'upload de l'image.";
                    }
                }
            }
        }
    } else {
        $errors[] = "Le pseudo à déjà été pris. Veuillez en choisir un autre.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Mettez ici vos balises meta, title, CSS, etc. -->
</head>

<body class="background">
    <div class="container container-bordure animation fadeRight bg-white">
        <div class="row">
            <form class="form" method="post" enctype="multipart/form-data" style="margin:50px">
                <h1>Inscription</h1>
                <hr> <!-- Trait -->
                <div class="form-group">
                    <label for="nom">Pseudo</label>
                    <input type="text" name="nom" id="nom" required value="<?php if (!empty($_POST['nom'])) echo $_POST['nom'] ?>" onchange="controleTexteInput(this, 'pseudoIndication', 'pseudo')" class="form-control"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
                    <label id="pseudoIndication" class="text-danger"><?php if (isset($_POST['nom']) and empty($_POST['nom'])) echo "Veuillez choisir un pseudo" ?></label> <!-- Indication pseudo, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                </div>
                <div class="form-group">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" name="mdp" id="mdp" required value="<?php if (!empty($_POST['mdp'])) echo $_POST['mdp'] ?>" onchange="controleTexteInput(this, 'mdpIndication', 'mdp')" class="form-control">
                    <label id="mdpIndication" class="text-danger"><?php if (isset($_POST['mdp']) and empty($_POST['mdp'])) echo "Veuillez choisir un mot de passe" ?></label> <!-- Indication mot de passe, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                </div>
                <div class="form-group">
                    <label for="mail">Adresse e-mail</label>
                    <input type="text" name="mail" id="mail" required value="<?php if (!empty($_POST['mail'])) echo $_POST['mail'] ?>" onchange="controleTexteInput(this, 'mailIndication', 'mail')" class="form-control">
                    <label id="mailIndication" class="text-danger"><?php if (isset($_POST['mailIndication']) and empty($_POST['mailIndication'])) echo "Veuillez choisir un e-mail" ?></label> <!-- Indication e-mail, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                </div>
                <div class="form-group">
                    <label for="photo_profil">Photo de profil</label>
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
                <button type="submit" id="btn_envoi" class="btn btn-success">Envoyer</button>
                <hr>
                <?php
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo '<div class="alert alert-danger" role="alert" style="margin-bottom: 10px; margin-top: 10px;">' . $error . '</div>';
                }
            }
            ?>
                <div class="form-group">
                    <a href="/connexion.php">Se connecter</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
