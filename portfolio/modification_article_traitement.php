
<?php
include('Header.php');

if (!empty($_POST['jeu'])) { // On cherche pour voir si l'utilisateur veut lier l'article à un jeu, si oui, on regarde si le titre entré correspond à un titre d'un jeu sinon on redemande de retaper le jeu
    $reponse = $bdd->prepare('SELECT id FROM jeu WHERE nom = :nom');
    $reponse->execute(array('nom' => $_POST['jeu']));
    $id_jeu_trouver = $reponse->rowCount();
    $jeu = $reponse->fetch();
    $id_jeu = $jeu['id'];
    $reponse->closeCursor();
} else {
    $id_jeu = null;
}

if (!empty($_POST['titre']) and !empty($_POST['id']) and !empty($_POST['contenu']) and !empty($_POST['categorie']) and !empty($_FILES['miniature']['tmp_name']) and (empty($_POST['jeu']) or (!empty($_POST['jeu'] and $id_jeu_trouver == 1)))) { // Traitement
    $titre = $_POST['titre'];
    $id = $_POST['id'];
    $url = EncodageTitreEnUrl($titre);
    $contenu = $_POST['contenu'];
    $categorie = $_POST['categorie'];
    $nom_miniature = $_FILES['miniature']['name'];

    $tailleImage = getimagesize($_FILES['miniature']['tmp_name']); // Récupération taille de l'image uploadée
    $largeur = $tailleImage[0];
    $hauteur = $tailleImage[1];
    $largeur_miniature = 300; // Largeur de la future miniature
    $hauteur_miniature = $hauteur / $largeur * 300;
    $im = imagecreatefromjpeg($_FILES['miniature']['tmp_name']); // Stockage de la photo qui vient d'être uploadée
    $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
    imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
    imagejpeg($im_miniature, 'miniature/' . $_FILES['miniature']['name'], 100); // Création de l'image jpg dans le dossier miniature

    $reponse = $bdd->prepare('UPDATE article SET titre = :titre, contenu = :contenu, nom_categorie = :categorie, nom_miniature = :nom_miniature, url = :url, id_jeu = :id_jeu WHERE id = :id'); // Modification news
    $reponse->execute(array('titre' => $titre, 'contenu' => $contenu, 'categorie' => $categorie, 'nom_miniature' => $nom_miniature, 'url' => $url, 'id' => $id, 'id_jeu' => $id_jeu));

?>
    <script>
        <?php /* document.location.href = '/modifier_news/<?php echo $url; ?>-<?php echo $id; ?>'; // Redirection nouvelle url */ ?>
       document.location.href = '/portfolio/index.php';
    </script>
<?php
    // header('Location: index.php'); // Redirection vers la page d'accueil

} else {
}
?>