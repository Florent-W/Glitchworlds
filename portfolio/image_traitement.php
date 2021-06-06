<?php
////// Categorie de l'image envoyée : Article, jeu ou autre /////
if (isset($_POST['nom'])) {
    $categorie_article = "Jeux/"; // A ranger dans le dossier jeux
    $titre_article = $_POST['nom'] . '/';
    $nom_image = $_FILES[$type_image]['name'];
} else if (isset($_POST['titre'])) {
    $categorie_article = "Articles/"; // A ranger dans le dossier articles
    $titre_article = $_POST['titre'] . '/';
    $nom_image = $_FILES[$type_image]['name'];
} else if (isset($_POST['image'])) {
    $categorie_article = ""; // A ranger dans le dossier images mais ça sera fait avec le type de l'image
    $titre_article = "";
    // $nom_image = $ancien_nom_image;
    /*
    $reponse = $bdd->prepare('SELECT date_image FROM image ORDER BY id DESC LIMIT 1'); // On récupère l'id pour le nom du fichier
    $reponse->execute();
    $donnees = $reponse->fetch();
    */
}

////// Date à créer selon la catégorie de l'image envoyée et si c'est une création ou une modification /////
if (!isset($parametre_upload_image)) {
    // Va créer les dossiers nécessaire pour stocker les images si ce n'est pas une modification, on pourra en créer
    if (isset($_POST['nom'])) { // Création d'autre répertoire
        $date = ""; // Date article qui va servir à créer les dossiers
        if (!file_exists("/portfolio/Jeux/" . $date . $_POST['nom'] . '/' . $type_image)) { // Pas besoin de mettre la date
            mkdir("/portfolio/Jeux/" . $date . $_POST['nom'] . '/' . $type_image, 0777, true);
        }
    } else if (isset($_POST['titre'])) {

        $dateActuel = date("Y-m-d H:i:s");
        setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra');

        $jour = strftime("%d", strtotime($dateActuel));
        $mois = strftime("%B", strtotime($dateActuel));
        $annee = strftime("%Y", strtotime($dateActuel));
        $heure = strftime("%H", strtotime($dateActuel));
        $minute = strftime("%M", strtotime($dateActuel));
        $seconde = strftime("%S", strtotime($dateActuel));

        $date = $annee . '/' . $mois . '/' . $jour . '/' . $heure . 'h' . $minute . '/'; // Date article qui va servir à créer les dossiers
        
        if (!file_exists("Articles" . '/' . $date . $_POST['titre']) . '/' . $type_image) {
            mkdir("Articles" . '/' . $date . $_POST['titre'] . '/' . $type_image, 0777, true);
        }
    } else if (isset($_POST['image'])) {
        // $date = $annee . '/' . $mois . '/' . $jour . '/' . $heure . 'h' . $minute; // Date article qui va servir à créer les dossiers

        $date = "";
        /* if (!file_exists("images/" . $date . $titre_article . "/" . $type_image)) {
            mkdir("images/" . $date . $titre_article . "/" . $type_image, 0777, true);
        } */

        $nom_image = $jour . "_" . $mois . "_" . $annee . "_" . $heure . "h" . $minute . "m" . $seconde . "_" . $hash . "." . $extension_image; // Nom de l'image final
        $reponse->closeCursor();
    }
} else if ($parametre_upload_image == "modification") { // On récupère la date depuis l'article, pas besoin de créer d'autre dossier
    $date = $dateArticle;
}

if (strtolower(pathinfo($_FILES[$type_image]['name'], PATHINFO_EXTENSION)) == "jpg") { // On regarde l'extension de l'image pour convertir (elle est d'abord récupérée et convertit en minuscule pour pouvoir comparer quand les extensions sont en en majuscules),
    $im = imagecreatefromjpeg($_FILES[$type_image]['tmp_name']); // Stockage de la photo qui vient d'être uploadée
    $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
    imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
    imagejpeg($im_miniature, $categorie_article . $date . $titre_article . $type_image . '/' . $nom_image, 100); // Création de l'image jpg dans le dossier miniature
} else if (strtolower(pathinfo($_FILES[$type_image]['name'], PATHINFO_EXTENSION)) == "png") { // On regarde l'extension de l'image pour convertir
    $im = imagecreatefrompng($_FILES[$type_image]['tmp_name']); // Stockage de la photo qui vient d'être uploadée
    $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
    $background = imagecolorallocatealpha($im_miniature, 255, 255, 255, 128);
    imagecolortransparent($im_miniature, $background);
    imagealphablending($im_miniature, false);
    imagesavealpha($im_miniature, true);
    imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
    imagepng($im_miniature, $categorie_article . $date . $titre_article . $type_image . '/' . $nom_image); // Création de l'image png dans le dossier miniature
    imagedestroy($im);
} else if (strtolower(pathinfo($_FILES[$type_image]['name'], PATHINFO_EXTENSION)) == "bmp") { // On regarde l'extension de l'image pour convertir (elle est d'abord récupérée et convertit en minuscule pour pouvoir comparer quand les extensions sont en en majuscules),
    $im = imagecreatefrombmp($_FILES[$type_image]['tmp_name']); // Stockage de la photo qui vient d'être uploadée
    $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
    imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
    imagebmp($im_miniature, $categorie_article . $date . $titre_article . $type_image . '/' . $nom_image, 100); // Création de l'image bmp dans le dossier miniature
} else if (strtolower(pathinfo($_FILES[$type_image]['name'], PATHINFO_EXTENSION)) == "gif") { // On regarde l'extension de l'image pour convertir
    move_uploaded_file($_FILES[$type_image]['tmp_name'], $categorie_article . $date . $titre_article . $type_image . '/' . $nom_image); // Bouge l'image sans la redimensionner, il faudra faire en sorte qu'elle ne dépasse pas une taille
}
?>