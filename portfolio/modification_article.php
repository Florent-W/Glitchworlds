<!-- Fenetre pour modifier article -->
<div class="modal fade" id="modalModifierArticle" tabindex="-1" role="dialog" aria-labelledby="modalLabelModifierArticle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Modification article</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form class="form" method="post" enctype="multipart/form-data" style="margin:50px" action="/portfolio/modification_article_traitement.php">
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
                        <input name="id" id="id" type="hidden"> <!-- Champ caché permettant de transmettre l'id de la news pour sélectionner la news -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Envoyer</button>
                        </div>

                        <hr>
                        <div class="form-group">
                            <a id="supprimerArticle" class="btn btn-warning">
                                <div class="list-group-item-text pull-right text-right text-white">Supprimer</div> <!-- Suppression de l'article -->
                            </a>
                        </div>
                    </form>

                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>