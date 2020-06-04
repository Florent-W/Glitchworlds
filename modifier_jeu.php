<?php
$id_jeu = $_GET['id']; // Recupération de l'id du jeu à modifier

try {
    $bdd = new PDO('mysql:host=localhost:3308;dbname=glitchworld;charset:utf8', 'root', ''); // Connexion à la base de données
    // $bdd = new PDO('mysql:host=db5000315646.hosting-data.io;dbname=dbs308204;charset:utf8', 'dbu566316', '96pvBbDE8DUyw@N'); // Connexion à la base de données
} catch (Exception $erreur) {
    die('erreur : ' . $erreur->getMessage());
}
$reponse = $bdd->prepare('SELECT * FROM jeu WHERE id = :id'); // Sélection du jeu à modifier
$reponse->execute(array('id' => $id_jeu));
$donnees = $reponse->fetch(); /*
if ($donnees['url'] != $_GET['url']) { // Si l'url qu'on vient d'entrer n'est pas égal à l'url de l'id du jeu de la base de données, on redirige vers la bonne page
    header("location:/modifier_jeu/" . $donnees['url'] . "-" . $donnees['id']);
}
*/
$reponse->closeCursor();
?>

<?php
include('Header.php');
?>

<body>
    <div class="container">
        <div class="row">
            <form class="form" method="post" enctype="multipart/form-data" style="margin:50px">
                <h1>Modifier jeu</h1>
                <hr> <!-- Trait -->
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" required value="<?php if (!isset($_POST['nom'])) echo $donnees['nom'];
                                                                            else echo $_POST['nom']; ?>" onchange="controleTexteInput(this, 'titreIndication', 'titre')" class="form-control"> <!-- Titre déjà pré-rempli avec les informations de la news et si on tente de modifier le titre, le titre est modifié -->
                    <label id="titreIndication" class="text-danger"><?php if (isset($_POST['nom']) and empty($_POST['nom'])) echo "Veuillez choisir un nom" ?></label> <!-- Indication nom, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                </div>
                <div class="form-group">
                    <label for="contenu">Contenu</label>
                    <div class="row" style="margin-bottom:10px;">
                        <div class="col">
                            <input type="button" name="italique" id="italique" value="I" style="font-style: italic;" onclick="ajoutClickBBcodeFormulaire('[i]', '[/i]', 'contenu')"> <!-- Bouton pour ajouter italique -->
                            <input type="button" name="gras" id="gras" value="G" style="font-weight: bold;" onclick="ajoutClickBBcodeFormulaire('[g]', '[/g]', 'contenu')">
                            <input type="button" name="souligne" id="souligne" value="U" style="text-decoration: underline;" onclick="ajoutClickBBcodeFormulaire('[u]', '[/u]', 'contenu')">
                            <input type="button" name="citation" id="citation" value="“" onclick="ajoutClickBBcodeFormulaire('[citation]', '[/citation]', 'contenu')">
                            <input type="button" name="centre" id="center" value="C" style="text-align: center;" onclick="ajoutClickBBcodeFormulaire('[center]', '[/center]', 'contenu')">
                        </div>
                    </div>
                    <textarea name="contenu" id="contenu" required onchange="controleTexteInput(this, 'contenuIndication', 'contenu')" class="form-control" rows="3"><?php if (!isset($_POST['contenu'])) echo $donnees['contenu'];
                                                                                                                                                                        else echo $_POST['contenu']; ?></textarea>
                    <label id="contenuIndication" class="text-danger"><?php if (isset($_POST['contenu']) and empty($_POST['contenu'])) echo "Veuillez choisir un contenu" ?></label> <!-- Indication contenu, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                </div>
                <div class="form-group">
                    <label for="nom">Date de sortie</label>
                    <input type="date" name="date_sortie" id="date_sortie" value="<?php if (!isset($_POST['date_sortie'])) echo $donnees['date_sortie'];
                                                                                    else echo $_POST['date_sortie']; ?>" required onchange="controleTexteInput(this, 'dateSortieIndication', 'date')" class="form-control"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
                    <label id="dateSortieIndication" class="text-danger"><?php if (isset($_POST['date_sortie']) and empty($_POST['date_sortie'])) echo "Veuillez choisir une date" ?></label> <!-- Indication date de sortie, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                </div>

                <div class="form-group">
                    <label for="categorie">Catégorie</label>
                    <select class="form-control" name="categorie" id="categorie" required onchange="controleTexteInput(this, 'categorieIndication', 'categorie')" class="form-control">
                        <!-- Selection catégorie de l'article -->
                        <?php
                        $reponse = $bdd->prepare('SELECT categorie_jeu.nom FROM categorie_jeu ORDER BY categorie_jeu.id');
                        $reponse->execute();
                        while ($donnees2 = $reponse->fetch()) { ?>
                            <option value="<?php echo $donnees2['nom']; ?>" <?php if (isset($donnees['nom_categorie']) and $donnees2['nom'] == $donnees['nom_categorie']) echo 'selected="selected"'; ?>><?php echo $donnees2['nom']; ?></option> <!-- Les différentes options du select -->
                        <?php }

                        $reponse->closeCursor(); ?>
                    </select>
                    <label id="categorieIndication" class="text-danger"><?php if (isset($_POST['categorie']) and empty($_POST['categorie'])) echo "Veuillez choisir une catégorie" ?></label> <!-- Indication categorie, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                </div>

                <div class="form-group">
                    <label for="miniature">Miniature</label>
                    <div class="input-group">
                        <!-- Upload de miniature -->
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="miniature" id="inputGroupFile01" onchange="controleTexteInput(this, 'miniatureIndication', 'miniature')" aria-describedby="inputGroupFileAddon01"> <!-- Si un fichier a été choisi, l'événement onchange permettra de montrer le nom du fichier sur le label d'information -->
                            <label id="miniatureIndication" class="custom-file-label" for="inputGroupFile01">Choisir fichier</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Envoyer</button>
            </form>
        </div>
    </div>
</body>
<?php
$reponse->closeCursor();
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
    $im = imagecreatefromjpeg($_FILES['miniature']['tmp_name']); // Stockage de la photo qui vient d'être uploadée
    $im_miniature = imagecreatetruecolor($largeur_miniature, $hauteur_miniature); // Création de la miniature avec une couleur de 24 bits avec une hauteur proportionnelle à celle d'origine
    imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur); // Copie de l'image d'origine dans la miniature et redimensionnement
    imagejpeg($im_miniature, 'miniature/' . $_FILES['miniature']['name'], 100); // Création de l'image jpg dans le dossier miniature

    $reponse = $bdd->prepare('SELECT id FROM categorie_jeu WHERE nom = :nom_categorie'); // Selection id catégorie du jeu à l'aide du nom pour l'insérer ensuite
    $reponse->execute(array('nom_categorie' => $nom_categorie));
    $donnees = $reponse->fetch();
    $id_categorie_jeu = $donnees['id'];
    $reponse->closeCursor();

    $reponse = $bdd->prepare('UPDATE jeu SET nom = :nom, contenu = :contenu, id_categorie = :id_categorie, nom_miniature = :nom_miniature, date_sortie = :date_sortie, url = :url WHERE id = :id'); // Modification jeu
    $reponse->execute(array('nom' => $nom, 'contenu' => $contenu, 'id_categorie' => $id_categorie_jeu, 'nom_miniature' => $nom_miniature, 'date_sortie' => $date_sortie, 'url' => $url, 'id' => $id_jeu));

?>
    <script>
        <?php /* document.location.href = '/modifier_news/<?php echo $url; ?>-<?php echo $id; ?>'; // Redirection nouvelle url */ ?>
        //  document.location.href = '/index.php';
    </script>
<?php
    // header('Location: index.php'); // Redirection vers la page d'accueil

} else {
}
?>

</html>