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

<body class="background">
    <div class="container container-bordure animated fadeInRight bg-white">
        <div class="row">
            <form class="form" method="post" enctype="multipart/form-data" style="margin:50px">
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


                    <?php
                    if (!empty($donnees['id_jeu'])) {
                        $reponse = $bdd->prepare('SELECT jeu.nom FROM jeu INNER JOIN article ON jeu.id = article.id_jeu WHERE jeu.id = :id_jeu'); // On cherche le nom du jeu à partir de l'id du jeu entré pour l'article
                        $reponse->execute(array('id_jeu' => $donnees['id_jeu']));
                        $donnees2 = $reponse->fetch();
                        $reponse->closeCursor();
                    }
                    ?>
                    <div class="form-group">
                        <label for="jeu">Article sur le jeu (non obligatoire)</label>
                        <input type="text" name="jeu" id="jeu" value="<?php if (isset($_POST['jeu'])) echo $_POST['jeu'];
                                                                        else if (!empty($donnees['id_jeu'])) echo $donnees2['nom']; ?>" class="form-control"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
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
include('ajout_video.php');
?>

<?php
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

if (!empty($_POST['titre']) and !empty($_POST['contenu']) and !empty($_POST['categorie']) and (empty($_POST['jeu']) or (!empty($_POST['jeu'] and $id_jeu_trouver == 1)))) { // Traitement
    $titre = $_POST['titre'];
    $url = EncodageTitreEnUrl($titre);
    $contenu = $_POST['contenu'];
    $categorie = $_POST['categorie'];

    $reponse = $bdd->prepare('SELECT titre, DATE_FORMAT(date_creation, "%Y/%M/%d/%kh%i") AS date_article_dossier FROM article WHERE id = :id'); // Selection de l'ancien titre de l'article, servira à renommer le dossier de l'article si il est changé
    $reponse->execute(array('id' => $id_news));
    $donnees = $reponse->fetch();
    $reponse->closeCursor();

    if ($donnees['titre'] != $_POST['titre']) { // Si le nom à changé, on renomme 
        rename("/portfolio/Articles/" . $donnees['date_article_dossier'] . "/" . $donnees['titre'], "/portfolio/Articles/" . $donnees['date_article_dossier'] . "/" . $_POST['titre']);
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
        }
        else {
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
        ?>
<?php
        $type_image = 'miniature'; // Recupère le nom de l'image (formulaire) pour indiquer quel type de fichier on va récupérer, miniature
        $parametre_upload_image = "modification"; // Dit si c'est une modification pour savoir si il faut créer un dossier pour l'image
        include('image_traitement.php');

        $reponse = $bdd->prepare('UPDATE article SET titre = :titre, contenu = :contenu, nom_categorie = :categorie, nom_miniature = :nom_miniature, url = :url, id_jeu = :id_jeu WHERE id = :id'); // Modification news
        $reponse->execute(array('titre' => $titre, 'contenu' => $contenu, 'categorie' => $categorie, 'nom_miniature' => $nom_miniature, 'url' => $url, 'id' => $id_news, 'id_jeu' => $id_jeu));
    } else { // Si il n'y a pas de nouvelle miniature
        $reponse = $bdd->prepare('UPDATE article SET titre = :titre, contenu = :contenu, nom_categorie = :categorie, url = :url, id_jeu = :id_jeu WHERE id = :id'); // Modification news
        $reponse->execute(array('titre' => $titre, 'contenu' => $contenu, 'categorie' => $categorie, 'url' => $url, 'id' => $id_news, 'id_jeu' => $id_jeu));
    }
?>
    <script>
        <?php /* document.location.href = '/modifier_news/<?php echo $url; ?>-<?php echo $id; ?>'; // Redirection nouvelle url */ ?>
        document.location.href = '/portfolio/news/<?php echo $url; ?>-<?php echo $id_news; ?>';
    </script>
<?php
    // header('Location: index.php'); // Redirection vers la page d'accueil

} else {
}
