<?php
include('Header.php');
?>

<?php
$id = $_GET['id'];

$reponse = $bdd->prepare('SELECT *, DATE_FORMAT(date_creation, "%d %M %Y à %Hh%imin%ss") AS date_article FROM article WHERE id = :id'); // Récupération de la news
$reponse->execute(array('id' => $id));
$donnees = $reponse->fetch();
?>
<div class="container">

    <h3 class="d-flex justify-content-center"><?php echo $donnees['titre']; ?></h3>
    <div>
        <p class="d-flex justify-content-center"><em>Publié le <?php echo $donnees['date_article']; ?></em></p>
    </div> <img src="/banniere.jpg" onerror="this.oneerror=null; this.src='/banniere.jpg';" class="d-block img-fluid" style="width:800vh; height:50vh; margin:1vh">
    <!-- <img src="/miniature/<?php echo $donnees['nom_miniature'] ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="d-block img-fluid" style="width:800vh; height:50vh; margin:1vh"> -->
    <p class="d-flex justify-content-center text-break text-justify"><?php echo remplacementBBCode(nl2br($donnees['contenu'])); ?></p>
    <?php
    $reponse->closeCursor();
    ?>


    <!-- Affichage des articles précédents si c'est une news -->
    <h3>Autres articles :</h3>
    <div class="list-group" style="top:100px">
        <div class="list-group-item">
            <div class="row">
                <?php
                $reponse = $bdd->prepare('SELECT article.id, article.titre, article.nom_miniature, article.url FROM article WHERE id < :id ORDER BY id DESC LIMIT 3'); // Récupération des news précédentes
                $reponse->execute(array('id' => $id));

                while ($donnees = $reponse->fetch()) {
                ?>
                    <div class="col d-flex justify-content-center">
                        <a href="/news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>" style="text-decoration-color: black">
                            <div class="row">
                                <img src="/miniature/<?php echo $donnees['nom_miniature'] ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-thumbnail" style="height: 200px"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                            </div>
                            <h3 class="list-group-item-heading text-center text-body"><?php echo $donnees['titre'] ?></h3>
                        </a>
                    </div>
                <?php }
                $reponse->closeCursor();
                ?>
            </div>
        </div>
    </div>

    <!-- Affichage des commentaires -->
    <ul class="list-group" style="top:100px">
        <?php
        $reponse = $bdd->prepare('SELECT commentaire.id, utilisateurs.pseudo, utilisateurs.nom_photo_profil, DATE_FORMAT(commentaire.date_commentaire, "%d %M %Y à %Hh%imin%ss") AS date_message, commentaire.contenu FROM commentaire INNER JOIN utilisateurs ON commentaire.id_utilisateur = utilisateurs.id WHERE id_news = :id'); // Récupération des commentaires
        $reponse->execute(array('id' => $id));
        $nombreCommentaire = $reponse->rowCount();

        // Affichage des commentaires
        if ($nombreCommentaire > 1) {
        ?>
            <h3><?php echo $nombreCommentaire; ?> Commentaires</h3>
        <?php
        } else { ?>
            <h3><?php echo $nombreCommentaire; ?> Commentaire</h3>
        <?php }
        ?>

        <?php
        while ($donnees = $reponse->fetch()) {
        ?>
            <div class="list-group-item">
                <div class="media">
                    <img src="/photo_profil/<?php echo $donnees['nom_photo_profil'] ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-thumbnail" style="float:left; height: 200px"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                    <div class="media-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="d-flex text-break text-justify" style="margin-left: 14px;"><?php echo $donnees['pseudo'] ?></h3>
                            </div>
                            <div class="col">
                                <div class="list-group-item-text pull-right text-right lead"><?php echo $donnees['date_message']; ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="text-break text-justify" style="margin-left: 14px"><?php echo remplacementBBCode(nl2br($donnees['contenu'])); ?></div>
                            </div>
                            <?php if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier un commentaire  
                            ?>
                                <div class="col">
                                    <a href="/gerer_commentaire.php?url=<?php echo $_GET['url'] ?>&id_news=<?php echo $id; ?>&id_commentaire=<?php echo $donnees['id']; ?>&action=supprimer_commentaire">
                                        <p class="list-group-item-text pull-right text-right lead">Supprimer</p> <!-- Suppression du commentaire -->
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }

        $reponse->closeCursor();

        ?>
        <!-- Pour ajouter un commentaire -->
        <div>
            <form class="form" method="post">
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label for="ajout_commentaire">Ajouter un commentaire :</label>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:10px;">
                        <div class="col">
                            <input type="button" name="italique" id="italique" value="I" style="font-style: italic;" onclick="ajoutClickBBcodeFormulaire('[i]', '[/i]', 'ajout_commentaire')"> <!-- Bouton pour ajouter italique -->
                            <input type="button" name="gras" id="gras" value="G" style="font-weight: bold;" onclick="ajoutClickBBcodeFormulaire('[g]', '[/g]', 'ajout_commentaire')">
                            <input type="button" name="souligne" id="souligne" value="U" style="text-decoration: underline;" onclick="ajoutClickBBcodeFormulaire('[u]', '[/u]', 'ajout_commentaire')">
                            <input type="button" name="citation" id="citation" value="“" onclick="ajoutClickBBcodeFormulaire('[citation]', '[/citation]', 'ajout_commentaire')">
                            <input type="button" name="centre" id="center" value="C" style="text-align: center;" onclick="ajoutClickBBcodeFormulaire('[center]', '[/center]', 'ajout_commentaire')">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <textarea name="ajout_commentaire" id="ajout_commentaire" required placeholder="Poster un commentaire." onchange="controleTexteInput(this, 'commentaireIndication', 'commentaire')" class="form-control" rows="3"></textarea>
                            <!-- <label id="commentaireIndication" class="text-danger"><?php if (isset($_POST['contenu']) and empty($_POST['contenu'])) echo "Veuillez écrire un commentaire" ?></label> <!-- Indication commentaire, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success" style="margin-bottom : 10px;">Envoyer</button>
                <input type="hidden" value="<?php echo $_GET['id']; ?>" name="id_news">
                <input type="hidden" value="<?php echo $_GET['url']; ?>" name="url">
            </form>
        </div>
    </ul>

</div>

<?php
include('ajout_commentaire_traitement.php');
?>
</body>

</html>