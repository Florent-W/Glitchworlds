<?php
include('Header.php');

if (!empty($_POST['titre']) and !empty($_POST['contenu']) and !empty($_FILES['miniature']['tmp_name'])) {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $nom_miniature = $_FILES['miniature']['name'];

    $tailleImage = getimagesize($_FILES['miniature']['tmp_name']); // Récupération taille de l'image uploadée
    $largeur = $tailleImage[0];
    $hauteur = $tailleImage[1];
    $largeur_miniature = 300; // Largeur de la future miniature
    $hauteur_miniature = $hauteur / $largeur * 300;
    if(pathinfo($_FILES['miniature']['tmp_name'], PATHINFO_EXTENSION) == ".jpg") { // On regarde l'extension de l'image pour convertir
    $im = imagecreatefromjpeg($_FILES['miniature']['tmp_name']); // Stockage de la photo qui vient d'être uploadée
    $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
    imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
    imagejpeg($im_miniature, 'miniature/' . $_FILES['miniature']['name'], 100); // Création de l'image jpg dans le dossier miniature
    }
    else if(pathinfo($_FILES['miniature']['tmp_name'], PATHINFO_EXTENSION) == ".png") { // On regarde l'extension de l'image pour convertir
        $im = imagecreatefrompng($_FILES['miniature']['tmp_name']); // Stockage de la photo qui vient d'être uploadée
        $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
        imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
        imagepng($im_miniature, 'miniature/' . $_FILES['miniature']['name'], 100); // Création de l'image png dans le dossier miniature
    }

    $reponse = $bdd->prepare('INSERT INTO article (titre, contenu, nom_miniature) VALUES (:titre, :contenu, :nom_miniature)'); // Insertion news
    $reponse->execute(array('titre' => $titre, 'contenu' => $contenu, 'nom_miniature' => $nom_miniature));

    header('Location: index.php'); // Redirection vers la page d'accueil

} 
else {
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
}
?>
</body>