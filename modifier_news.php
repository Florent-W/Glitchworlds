<?php
session_start();
$id_news = $_GET['id']; // Recupération de l'id de la news à modifier

include('connexion_base_donnee.php');
$reponse = $bdd->prepare('SELECT article.*, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i/") AS date_article_dossier FROM article WHERE id = :id'); // Sélection de la news à modifier
$reponse->execute(array('id' => $id_news));
$donnees = $reponse->fetch();
if ($donnees['url'] != $_GET['url']) { // Si l'url qu'on vient d'entrer n'est pas égal à l'url de l'id de la news de la base de données, on redirige vers la bonne page
    header("location:/modifier_news/" . $donnees['url'] . "-" . $donnees['id']);
}
$reponse->closeCursor();
?>

<?php
$title = "Modifier news";
include('Header.php');

$dateArticle = $donnees['date_article_dossier']; // Récupération de la date pour savoir dans quel dossier mettre les images
?>

<body style="<?php if(!isset($_SESSION['nom_image_background'])) { echo "background-image: url('/background.jpg');"; } else { echo "background-image: url('/utilisateurs/" . $_SESSION['id'] . "/background_site/" . $_SESSION['nom_image_background'] . "');"; } ?> background-repeat: no-repeat; background-attachment: fixed; background-size: cover; background-position: center center; overflow-x: hidden;">
    <div class="container container-bordure animation fadeRight bg-white">
        <div class="row">
            <form class="form" id="modifier_news" method="post" enctype="multipart/form-data" style="margin:50px">
                <h1>Modifier news</h1>
                <hr> <!-- Trait -->
                <?php if (isset($_SESSION['pseudo'])) { // Si l'utilisateur est connecter, il peut écrire un article
                ?>
                    <div class="form-group">
                        <label for="titre">Titre</label>
                        <input type="text" name="titre" id="titre" required value="<?php if (!isset($_POST['titre'])) echo $donnees['titre'];
                                                                                    else echo $_POST['titre']; ?>" onchange="controleTexteInput(this, 'titreIndication', 'titre')" class="form-control"> <!-- Titre déjà pré-rempli avec les informations de la news et si on tente de modifier le titre, le titre est modifié -->
                        <label id="titreIndication" class="text-danger"><?php if (isset($_POST['titre']) and empty($_POST['titre'])) echo "Veuillez choisir un titre" ?></label> <!-- Indication titre, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                    </div>
                    <div class="form-group"> <!-- Description -->
                        <label for="description">Description de 150 caractères max (non obligatoire)</label> 
                        <input type="text" maxlenght="150" name="description" id="description" value="<?php if (!empty($_POST['description'])) echo $_POST['description']; else if(isset($donnees['description'])) echo $donnees['description']; ?>" class="form-control"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
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
                        <textarea name="contenu" id="contenu" required oninput="previsualisationContenu()" onchange="controleTexteInput(this, 'contenuIndication', 'contenu')" class="form-control" rows="5"><?php if (!isset($_POST['contenu'])) echo $donnees['contenu'];
                                                                                                                                                                                                                else if (!empty($_POST['contenu'])) {
                                                                                                                                                                                                                    echo $_POST['contenu'];
                                                                                                                                                                                                                } ?></textarea>
                        <label id="contenuIndication" class="text-danger"><?php if (isset($_POST['contenu']) and empty($_POST['contenu'])) echo "Veuillez choisir un contenu" ?></label> <!-- Indication contenu, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                        <hr>
                        <div name="previsualisationContenu" id="previsualisationContenu" style="white-space: pre-wrap;"></div>
                    </div>

                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <select class="form-control" name="categorie" id="categorie" required onchange="controleTexteInput(this, 'categorieIndication', 'categorie')" class="form-control">
                            <!-- Selection catégorie de l'article -->
                            <?php
                            $reponse = $bdd->prepare('SELECT nom FROM categorie ORDER BY id');
                            $reponse->execute();
                            while ($donnees2 = $reponse->fetch()) { ?>
                                <option value="<?php echo $donnees2['nom']; ?>" <?php if (isset($donnees['nom_categorie']) and $donnees2['nom'] == $donnees['nom_categorie']) echo 'selected="selected"'; ?>><?php echo $donnees2['nom']; ?></option> <!-- Les différentes options du select -->
                            <?php }

                            $reponse->closeCursor(); ?>
                        </select>
                        <label id="categorieIndication" class="text-danger"><?php if (isset($_POST['categorie']) and empty($_POST['categorie'])) echo "Veuillez choisir une catégorie" ?></label> <!-- Indication categorie, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                    </div>

                    <div class="form-group"> <!-- Type de présentation -->
                        <label for="presentation">Type de Présentation</label>
                        <select class="form-control" name="presentation" id="presentation" required onchange="controleTexteInput(this, 'presentationIndication', 'presentation')" class="form-control">
                            <!-- Selection presentation de l'article -->
                                <option value="conteneur" <?php if (isset($_POST['presentation']) && $_POST['presentation'] == "conteneur") { echo 'selected="selected"'; } else if ($donnees['presentation'] == "conteneur") { echo 'selected="selected"'; } ?>>Conteneur</option> <!-- Les différentes options du select -->
                                <option value="section" <?php if (isset($_POST['presentation']) && $_POST['presentation'] == "section") { echo 'selected="selected"'; } else if ($donnees['presentation'] == "section") { echo 'selected="selected"'; } ?>>Section</option> <!-- Les différentes options du select -->
                                <option value="normal" <?php if (isset($_POST['presentation']) && $_POST['presentation'] == "normal") { echo 'selected="selected"'; } else if ($donnees['presentation'] == "normal") { echo 'selected="selected"'; } ?>>Normal</option> <!-- Les différentes options du select -->
                        </select>
                        <label id="presentationIndication" class="text-danger"><?php if (isset($_POST['presentation']) and empty($_POST['presentation'])) echo "Veuillez choisir un type de présentation" ?></label> <!-- Indication presentation, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                    </div>

                    <?php

                    /*
                    if (!empty($donnees['id_jeu'])) {
                        $reponse = $bdd->prepare('SELECT jeu.nom FROM jeu INNER JOIN article ON jeu.id = article.id_jeu WHERE jeu.id = :id_jeu'); // On cherche le nom du jeu à partir de l'id du jeu entré pour l'article
                        $reponse->execute(array('id_jeu' => $donnees['id_jeu']));
                        $donnees2 = $reponse->fetch();
                        $reponse->closeCursor();
                    } */
                    ?>

                    <script>
                        autoCompletion("jeu", "Jeux");

                        var listeTags = tagsCreationArticles('tags', 'lierJeux'); // On récupère les tags de l'article
                    </script>

                    <div class="form-group">
                        <label for="jeu">Lié à un jeu (non obligatoire)</label>
                        <div name="lierJeux" id="lierJeux" style="position: relative; border : 1px solid;">
                            <?php
                            if (!empty($_POST['tags'])) { // Servira si il y a une erreur, on split les jeux et on les reprend
                                $liste_tags = $_POST['tags'];
                                $liste_tags_splitter = explode(',', $liste_tags); // On split les differents tags
                            }

                            ?>
                            <?php
                            $reponse2 = $bdd->prepare('SELECT jeu.nom AS jeu_lier FROM jeu INNER JOIN article_lier_jeu ON jeu.id = article_lier_jeu.id_jeu WHERE article_lier_jeu.id_article = :id_article'); // On cherche le nom des jeux lié à l'article
                            $reponse2->execute(array('id_article' => $donnees['id']));
                            $i = 0; // Servira si il y a une erreur pour savoir quel numéro de tags cherché

                            while ($donnees2 = $reponse2->fetch()) {
                            ?> <span class='badge badge-info tag' style='margin-left: 5px;'><?php if (!empty($_POST['tags'])) echo $liste_tags_splitter[$i];
                                                                                            else if (!empty($donnees2['jeu_lier'])) echo $donnees2['jeu_lier']; ?> <i class="far fa-window-close" onclick="$(this).parent().remove()" ;></i></span> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->

                            <?php $i++;
                            }
                            $reponse2->closeCursor();
                            ?>
                            <input type="text" name="jeu" id="jeu" style="border: 0;" class="form-control" style="display: inline-block;">
                        </div>
                        <label id="jeuIndication" class="text-danger"><?php if ((!empty($_POST['jeu']) and ($_POST['jeu'] != $donnees2['nom']))) echo "Le titre du jeu n'a pas été trouvé"; ?></label> <!-- Indication titre du jeu, il sera indiqué si le formulaire a déjà été soumis mais qu'il y a une erreur -->
                    </div>

                    <div class="form-group">
                        <label for="bandeaux">Bannière (non obligatoire)</label> <!-- La bannière est placé au début des articles -->
                        <div class="input-group">
                            <!-- Upload de bannière -->
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon02">Upload</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="bandeaux" id="inputGroupFile02" onchange="controleTexteInput(this, 'banniereIndication', 'miniature')" aria-describedby="inputGroupFileAddon02"> <!-- Si un fichier a été choisi, l'événement onchange permettra de montrer le nom du fichier sur le label d'information -->
                                <label id="banniereIndication" class="custom-file-label" for="inputGroupFile02">Choisir fichier</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="miniature">Miniature (non obligatoire)</label>
                        <div class="input-group">
                            <!-- Upload de miniature -->
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" accept=".jpg, .png, .bmp, .gif" name="miniature" id="inputGroupFile01" onchange="controleTexteInput(this, 'miniatureIndication', 'miniature')" aria-describedby="inputGroupFileAddon01"> <!-- Si un fichier a été choisi, l'événement onchange permettra de montrer le nom du fichier sur le label d'information -->
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
                    <div class="form-group">
                        <label for="article_approuver">Etat de l'article</label>
                        <select class="form-control" name="article_approuver" id="article_approuver" required class="form-control">
                        <?php if ($donnees2['statut'] == "Administrateur") { // On affiche l'option pour approuver un article directement que si on est administrateur ?>
                                <option value="Approuver" <?php if ((isset($_POST['article_approuver']) and $_POST['article_approuver'] == "Approuver") || $donnees['approuver'] == "Approuver") echo 'selected="selected"'; ?>>Approuver</option> <!-- Les différentes options du select -->
                                <?php } ?>
                                <option value="Préapprouver" <?php if ((isset($_POST['article_approuver']) and $_POST['article_approuver'] == "Préapprouver") || $donnees['approuver'] == "Préapprouver") echo 'selected="selected"'; ?>>En attente</option> <!-- Les différentes options du select -->
                                <option value="Brouillon" <?php if ((isset($_POST['article_approuver']) and $_POST['article_approuver'] == "Brouillon") || $donnees['approuver'] == "Brouillon") echo 'selected="selected"'; ?>>Brouillon</option> <!-- Les différentes options du select -->
                        </select>
                    </div>
                    <?php
                    $reponse2->closeCursor();
                    ?>
                    <input name="id_news" value="<?php echo $id_news; ?>" type="hidden"> <!-- Champ caché permettant de transmettre l'id de la news pour sélectionner la news -->
                    <button type="submit" id="btn_envoi" class="btn btn-success">Envoyer</button>
                    <hr>
                    <div class="form-group">
                        <button type="button" id="supprimerArticle" data-placement="top" class="btn btn-warning" title="Supprimer Article" data-toggle="modal" data-target="#modalConfirmationSupprimerArticle">
                            <div class="list-group-item-text pull-right text-right text-white">Supprimer</div> <!-- Bouton qui va ouvrir une page pour confirmer la suppr de l'article -->
                        </button>
                    </div>
                <?php } else if (!isset($_SESSION['pseudo'])) {
                ?><div class="alert alert-warning" role="alert" style="margin-top: 10px;">Veuillez vous <a href="/connexion.php">connecter</a> pour modifier un article.</div> <?php
                                                                                                                                                                            }    ?>
                <?php /*
                <textarea id="previsualisationContenu" name="previsualisationContenu" class="form-control" rows="3" disabled></textarea>
                <script>
                    $('#previsualationContenu').ready(function() { // Chargement de la prévisualisation du contenu
                        $('#previsualisationContenu').val($('#contenu').val());
                    });
                    $('#contenu').change(function() { // Si le contenu change, on met à jour
                        $('#previsualisationContenu').val($('#contenu').val());
                    });
                </script>
                */
                ?>
            </form>
        </div>
    </div>
</body>
<?php
$reponse->closeCursor();
?>

<?php include('confirmation_suppression_article.php'); ?>
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
include('ajout_section.php');
?>
<?php
include('ajout_video.php');
?>

<?php
if (!empty($_POST['tags'])) { // On cherche pour voir si l'utilisateur veut lier l'article à un jeu, si oui, on regarde si le titre entré correspond à un titre d'un jeu sinon on redemande de retaper le jeu

    $jeu_trouver = array();
    $id_jeu_trouver = array();

    for ($i = 0; $i < count($liste_tags_splitter); $i++) { // On cherche pour chaque tags son id
        $reponse = $bdd->prepare('SELECT id FROM jeu WHERE nom = :nom');
        $reponse->execute(array('nom' => $liste_tags_splitter[$i]));
        $nombre_id_jeu_trouver = $reponse->rowCount();
        array_push($id_jeu_trouver, $nombre_id_jeu_trouver);
        $donnees = $reponse->fetch();
        array_push($jeu_trouver, $donnees['id']);
        $reponse->closeCursor();
?>
<?php
    }
}
?>

<?php

if (!empty($_POST['titre']) and !empty($_POST['contenu']) and !empty($_POST['categorie']) and (empty($_POST['jeu']) or (!empty($_POST['jeu'] and count($id_jeu_trouver) > 0 and isset($_SESSION['pseudo']) and !empty($_POST['presentation']))))) { // Traitement
    $titre = $_POST['titre'];
    $url = EncodageTitreEnUrl($titre);
    $description = $_POST['description'];
    $contenu = $_POST['contenu'];
    $categorie = $_POST['categorie'];
    $article_approuver = $_POST['article_approuver'];
    $presentation = $_POST['presentation'];

    $reponse = $bdd->prepare('SELECT titre, url, DATE_FORMAT(date_creation, "%Y/%M/%d/%Hh%i") AS date_article_dossier FROM article WHERE id = :id'); // Selection de l'ancien titre de l'article, servira à renommer le dossier de l'article si il est changé
    $reponse->execute(array('id' => $id_news));
    $donnees = $reponse->fetch();
    $reponse->closeCursor();

    if ($donnees['titre'] != $_POST['titre']) { // Si le nom à changé, on renomme 
        rename("Articles/" . $donnees['date_article_dossier'] . "/" . $donnees['url'], "Articles/" . $donnees['date_article_dossier'] . "/" . $url);
    }

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
        $parametre_upload_image = "modification"; // Dit si c'est une modification pour savoir si il faut créer un dossier pour l'image
        include('image_traitement.php');

        $reponse = $bdd->prepare('UPDATE article SET nom_banniere = :nom_banniere WHERE id = :id'); // Modification de l'article directement pour mettre la banniere car on ne sait pas si la banniere est là dans la requete suivante et ça créer moins de requete
        $reponse->execute(array('nom_banniere' => $nom_banniere, 'id' => $id_news));
    }

    if (!empty($_FILES['miniature']['tmp_name'])) { // On regarde si il y a une nouvelle miniature
        $nom_miniature = $_FILES['miniature']['name'];

        $tailleImage = getimagesize($_FILES['miniature']['tmp_name']); // Récupération taille de l'image uploadée
        $largeur = $tailleImage[0];
        $hauteur = $tailleImage[1];
        $largeur_miniature = 300; // Largeur de la future miniature
        $hauteur_miniature = $hauteur / $largeur * 300;

        $type_image = 'miniature'; // Recupère le nom de l'image (formulaire) pour indiquer quel type de fichier on va récupérer, miniature
        $parametre_upload_image = "modification"; // Dit si c'est une modification pour savoir si il faut créer un dossier pour l'image
        include('image_traitement.php');

        $reponse = $bdd->prepare('UPDATE article SET titre = :titre, contenu = :contenu, nom_categorie = :categorie, presentation = :presentation, nom_miniature = :nom_miniature, url = :url, approuver = :article_approuver, description = :description WHERE id = :id'); // Modification news
        $reponse->execute(array('titre' => $titre, 'contenu' => $contenu, 'categorie' => $categorie, 'presentation' => $presentation, 'nom_miniature' => $nom_miniature, 'url' => $url, 'article_approuver' => $article_approuver, 'description' => $description, 'id' => $id_news));
    } else { // Si il n'y a pas de nouvelle miniature
        $reponse = $bdd->prepare('UPDATE article SET titre = :titre, contenu = :contenu, nom_categorie = :categorie, presentation = :presentation, url = :url, approuver = :article_approuver, description = :description WHERE id = :id'); // Modification news
        $reponse->execute(array('titre' => $titre, 'contenu' => $contenu, 'categorie' => $categorie, 'presentation' => $presentation, 'url' => $url, 'article_approuver' => $article_approuver, 'description' => $description, 'id' => $id_news));
    }

    $reponse = $bdd->prepare('DELETE FROM article_lier_jeu WHERE id_article = :id_article'); // On supprime d'abord les jeux liés puisqu'ils seront liés après
    $reponse->execute(array('id_article' => $id_news));

    for ($i = 0; $i < count($jeu_trouver); $i++) { // Parcours des différents id des tags
        if ($id_jeu_trouver[$i] == 1) {
            $reponse = $bdd->prepare('INSERT INTO article_lier_jeu (id_article, id_jeu) VALUES (:id_article, :id_jeu) '); // Insertion de la liste des jeux lié à l'article
            $reponse->execute(array('id_article' => $id_news, 'id_jeu' => $jeu_trouver[$i]));
        }
    }
?>
    <script>
        <?php /* document.location.href = '/modifier_news/<?php echo $url; ?>-<?php echo $id; ?>'; // Redirection nouvelle url */ ?>
        document.location.href = '/news/<?php echo $url; ?>-<?php echo $id_news; ?>';
    </script>
<?php
    // header('Location: index.php'); // Redirection vers la page d'accueil

} else {
}
