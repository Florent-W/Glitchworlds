<?php
include('Header.php');
?>

<body>
    <div class="container">
        <div class="row">
            <form class="form" method="post" enctype="multipart/form-data" style="margin:50px">
                <h1>Création de news</h1>
                <hr> <!-- Trait -->
                <div class="form-group">
                    <label for="titre">Titre</label>
                    <input type="text" name="titre" id="titre" value="<?php if (!empty($_POST['titre'])) echo $_POST['titre'] ?>" required onchange="controleTexteInput(this, 'titreIndication', 'titre')" class="form-control"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
                    <label id="titreIndication" class="text-danger"><?php if (isset($_POST['titre']) and empty($_POST['titre'])) echo "Veuillez choisir un titre" ?></label> <!-- Indication titre, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
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
                    <textarea name="contenu" id="contenu" required onchange="controleTexteInput(this, 'contenuIndication', 'contenu')" class="form-control" rows="3"><?php if (!empty($_POST['contenu'])) echo $_POST['contenu'] ?></textarea>
                    <label id="contenuIndication" class="text-danger"><?php if (isset($_POST['contenu']) and empty($_POST['contenu'])) echo "Veuillez choisir un contenu"; ?></label> <!-- Indication contenu, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
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

                <div class="form-group">
                    <label for="jeu">Article sur le jeu (non obligatoire)</label>
                    <input type="text" name="jeu" id="jeu" value="<?php if (!empty($_POST['jeu'])) echo $_POST['jeu'] ?>" class="form-control"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
                    <label id="jeuIndication" class="text-danger"><?php if (isset($_POST['jeu'])) echo "Le titre du jeu n'a pas été trouvé"; ?></label> <!-- Indication titre du jeu, il sera indiqué si le formulaire a déjà été soumis mais qu'il y a une erreur -->
                </div>

                <div class="form-group">
                    <label for="miniature">Miniature</label>
                    <div class="input-group">
                        <!-- Upload de miniature -->
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="miniature" id="inputGroupFile01" required onchange="controleTexteInput(this, 'miniatureIndication', 'miniature')" aria-describedby="inputGroupFileAddon01"> <!-- Si un fichier a été choisi, l'événement onchange permettra de montrer le nom du fichier sur le label d'information -->
                            <label id="miniatureIndication" class="custom-file-label" for="inputGroupFile01">Choisir fichier</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Envoyer</button>
            </form>
        </div>
    </div>

    <?php

    if (!empty($_POST['jeu'])) { // On cherche pour voir si l'utilisateur veut lier l'article à un jeu, si oui, on regarde si le titre entré correspond à un titre d'un jeu sinon on redemande de retaper le jeu
        $reponse = $bdd->prepare('SELECT id FROM jeu WHERE nom = :nom');
        $reponse->execute(array('nom' => $_POST['jeu']));
        $id_jeu_trouver = $reponse->rowCount();
        $donnees = $reponse->fetch();
        $reponse->closeCursor();
    }

    if (!empty($_POST['titre']) and !empty($_POST['contenu']) and !empty($_POST['categorie']) and !empty($_FILES['miniature']['tmp_name']) and (empty($_POST['jeu']) or (!empty($_POST['jeu'] and $id_jeu_trouver == 1)))) { // Traitement
        $titre = $_POST['titre'];
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

        $reponse = $bdd->prepare('INSERT INTO article (titre, contenu, nom_categorie, nom_miniature, date_creation, url, id_jeu) VALUES (:titre, :contenu, :categorie, :nom_miniature, :date_creation, :url, :id_jeu)'); // Insertion news
        $reponse->execute(array('titre' => $titre, 'contenu' => $contenu, 'categorie' => $categorie, 'nom_miniature' => $nom_miniature, 'date_creation' => date("Y-m-d H:i:s"), 'url' => $url, 'id_jeu' => $donnees['id']));
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