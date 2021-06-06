<?php if (session_id() == "") {
    session_start(); // Lance variable de session
}
$id = $_GET['id'];
?>

<?php include_once('connexion_base_donnee.php');
include_once('fonctions_php.php');
?>

<script>
    $(function() {
        $('.star-demo').rating({
            cancel: "Retirer la note",
            half: true, // Met les demi etoiles
            callback: function(value) {
                if (value) {
                    $('#note_label').text(value);
                } else {
                    $('#note_label').empty();
                }
            }
        });
        $('.star-affichage').rating({ // Pour l'affichage des notes
            half: true, // Met les demi etoiles
            readOnly: true
        });
    });
</script>
<!-- Affichage des avis -->
<h2 style="margin-bottom: 20px;" class="d-flex justify-content-center">Liste des Avis de <?php echo $_POST['nom_jeu']; ?> :</h2>
<ul class="list-group" style="top:100px">
    <!-- Choix du type d'affichage des avis, par ancienneneté, par nombre de j'aime -->
    <form class="form-inline my-2 my-lg-0 justify-content-center" id="formulaire_avis_tri" method="POST">
        <?php
        ?>
        <div class="form-group">
            <label class="label-form d-md-none" for="tri_avis">Affichage :</label> <!-- Affichage sur des écrans petits -->
            <label class="label-form d-none d-md-block" for="tri_avis">Affichage des avis :</label> <!-- Affichage sur des écrans plus grands -->
        </div>
        <div class="form-group">
            <select class="form-control form-select" name="tri_avis" id="tri_avis" style="margin: 5px;">
                <option value="Recents" <?php if (!isset($_POST['tri_avis']) or $_POST['tri_avis'] == "Recents") echo 'selected="selected"'; ?>>Récents</option>
                <option value="Tops" <?php if (isset($_POST['tri_avis']) and $_POST['tri_avis'] == "Tops") echo 'selected="selected"'; ?>>Tops</option>
            </select>
        </div>
        <div class="form-group">
            <button class="btn btn-outline-success" style="margin: 5px;" type="submit">Valider</button>
        </div>
    </form>
    <?php
    // Si l'utilisateur n'a pas fait de choix concernant le tri des avis ou qu'il a sélectionné le tri par avis les plus récents
    if (!isset($_POST['tri_avis']) or (isset($_POST['tri_avis']) and $_POST['tri_avis'] == "Recents")) {
        $reponse = $bdd->prepare('SELECT avis.id, utilisateurs.pseudo, utilisateurs.id AS utilisateurs_id, utilisateurs.nom_photo_profil, DATE_FORMAT(avis.date_avis, "%d %M %Y à %Hh%imin%ss") AS date_message, avis.note, avis.contenu FROM avis LEFT JOIN utilisateurs ON avis.id_utilisateur = utilisateurs.id LEFT JOIN jeu ON avis.id_jeu = jeu.id WHERE avis.id_jeu = :id ORDER BY date_avis'); // Récupération des avis
        $reponse->execute(array('id' => $id));
        $nombreAvis = $reponse->rowCount();
    }

    // Si l'utilisateur a sélectionné le tri par avis les plus aimé
    else if (isset($_POST['tri_avis']) and $_POST['tri_avis'] == "Tops") {
        $reponse = $bdd->prepare('SELECT avis.id, utilisateurs.pseudo, utilisateurs.id AS utilisateurs_id, utilisateurs.nom_photo_profil, aime_avis.id_avis, COUNT(aime_avis.id_avis) AS nombre_aime, DATE_FORMAT(avis.date_avis, "%d %M %Y à %Hh%imin%ss") AS date_message, avis.note, avis.contenu FROM avis LEFT JOIN utilisateurs ON avis.id_utilisateur = utilisateurs.id LEFT JOIN aime_avis ON avis.id = aime_avis.id_avis LEFT JOIN jeu ON avis.id_jeu = jeu.id WHERE jeu.id = :id GROUP BY aime_avis.id_avis, avis.id ORDER BY nombre_aime DESC'); // Récupération des avis
        $reponse->execute(array('id' => $id));
        $nombreAvis = $reponse->rowCount();
    }

    // Affichage des abvis
    if ($nombreAvis >= 1) {
        $reponse2 = $bdd->prepare('SELECT AVG(avis.note) AS moyenne_note FROM avis LEFT JOIN jeu ON avis.id_jeu = jeu.id WHERE jeu.id = :id'); // Récupération de la moyenne des avis
        $reponse2->execute(array('id' => $id));
        $donnees2 = $reponse2->fetch();
        $moyenne_note = $donnees2['moyenne_note'];
        $reponse2->closeCursor();
    ?>
         <div class="list-group-item-text pull-right text-right lead"><span style="border-radius: 50%; border: solid; background-color: LightGreen; padding: 8px; width: 51px; height: 51px; display: inline-block; text-align: center;"><?php echo round($moyenne_note, 1); ?></span></div> <!-- Moyenne des notes arrondis si il y en a pour un jeu -->
        <h3 style="margin-bottom: 20px;"><?php echo htmlspecialchars($nombreAvis); ?> Avis :</h3>
    <?php
    } else { ?>
        <h3 style="margin-bottom: 20px;"><?php echo htmlspecialchars($nombreAvis); ?> Avis :</h3>
    <?php }
    ?>

    <?php
    $positionAvis = 0; // On va voir la place de l'avis et une fois sur deux, il sera en couleur 


    while ($donnees = $reponse->fetch()) {
        if($donnees['contenu'] != '') { // On regarde si l'avis contient un message et on l'affiche si il en a un
        if ($positionAvis % 2 == 0) {  // Un avis sur deux sera en couleur
    ?>
            <div class="list-group-item list-group-item-secondary liste-item-commentaire">
            <?php } else { ?>
                <div class="list-group-item liste-item-commentaire">
                <?php }
                ?>
                <div class="media">
                    <img src="/utilisateurs/<?php echo htmlspecialchars($donnees['utilisateurs_id']); ?>/photo_profil/<?php echo htmlspecialchars($donnees['nom_photo_profil']); ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-fluid img-profil img-thumbnail" style="float:left; object-fit: cover;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                    <div class="media-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="d-flex text-break texte-pseudo text-justify" style="margin-left: 14px;"><?php echo htmlspecialchars($donnees['pseudo']); ?></h3>
                            </div>
                            <div class="col d-none d-lg-block">
                                <div class="list-group-item-text pull-right texte-date text-right lead"><?php echo htmlspecialchars($donnees['date_message']); ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="text-break text-justify" id="contenu<?php echo htmlspecialchars($donnees['id']); ?>" style="margin-left: 14px;"><?php echo remplacementBBCode(nl2br(htmlspecialchars($donnees['contenu'])), true, false); ?></div> <!-- Les id ne sont pas les memes pour pouvoir les récupérer pour la modif -->
                            </div>
                        </div>
                        <div class="row" style="margin-left: 1%;">
                            <div class="col">
                                <input name="note<?php echo $donnees['id']; ?>" type="radio" value="0.5" <?php if ($donnees['note'] == 0.5) {
                                                                                                                    echo "checked";
                                                                                                                } ?> class="star-affichage"> <!-- Note -->
                                <input name="note<?php echo $donnees['id']; ?>" type="radio" value="1" <?php if ($donnees['note'] == 1) {
                                                                                                                echo "checked";
                                                                                                            } ?> class="star-affichage">
                                <input name="note<?php echo $donnees['id']; ?>" type="radio" value="1.5" <?php if ($donnees['note'] == 1.5) {
                                                                                                                    echo "checked";
                                                                                                                } ?>class="star-affichage">
                                <input name="note<?php echo $donnees['id']; ?>" type="radio" value="2" <?php if ($donnees['note'] == 2) {
                                                                                                                echo "checked";
                                                                                                            } ?> class="star-affichage">
                                <input name="note<?php echo $donnees['id']; ?>" type="radio" value="2.5" <?php if ($donnees['note'] == 2.5) {
                                                                                                                    echo "checked";
                                                                                                                } ?> class="star-affichage">
                                <input name="note<?php echo $donnees['id']; ?>" type="radio" value="3" <?php if ($donnees['note'] == 3) {
                                                                                                                echo "checked";
                                                                                                            } ?> class="star-affichage">
                                <input name="note<?php echo $donnees['id']; ?>" type="radio" value="3.5" <?php if ($donnees['note'] == 3.5) {
                                                                                                                    echo "checked";
                                                                                                                } ?> class="star-affichage">
                                <input name="note<?php echo $donnees['id']; ?>" type="radio" value="4" <?php if ($donnees['note'] == 4) {
                                                                                                                echo "checked";
                                                                                                            } ?> class="star-affichage">
                                <input name="note<?php echo $donnees['id']; ?>" type="radio" value="4.5" <?php if ($donnees['note'] == 4.5) {
                                                                                                                    echo "checked";
                                                                                                                } ?> class="star-affichage">
                                <input name="note<?php echo $donnees['id']; ?>" type="radio" value="5" <?php if ($donnees['note'] == 5) {
                                                                                                                echo "checked";
                                                                                                            } ?> class="star-affichage">
                            </div>
                        </div>
                        <div style="margin-left: 3%;" id="note_afficher" class="row"><em><?php if (isset($donnees['note'])) {
                                                                            echo floatval($donnees['note']) . ' / 5';
                                                                        } ?></em></div> <!-- Affiche la note selectionné et convertit si il n'y a pas de chiffre après la virgule -->

                        <?php
                        // Voir si l'utilisateur à déjà aimer l'avis

                        if (isset($_SESSION['pseudo'])) {
                            $pseudoActuel = $_SESSION['pseudo'];
                        } else {
                            $pseudoActuel = null;
                        }

                        $reponse2 = $bdd->prepare('SELECT COUNT(DISTINCT(aime_avis.pseudo_utilisateur_qui_aime)) AS utilisateur_a_deja_aime FROM aime_avis INNER JOIN avis ON aime_avis.id_avis = avis.id INNER JOIN utilisateurs ON aime_avis.pseudo_utilisateur_qui_aime = utilisateurs.pseudo WHERE id_avis = :id AND utilisateurs.pseudo = :pseudo');
                        $reponse2->execute(array('id' => $donnees['id'], 'pseudo' => $pseudoActuel));
                        $donnees2 = $reponse2->fetch();
                        $reponse2->closeCursor();
                        ?>

                        <?php
                        // Voir le nombre de j'aime pour un avis
                        $reponse3 = $bdd->prepare('SELECT COUNT(DISTINCT(aime_avis.pseudo_utilisateur_qui_aime)) AS nombre_aime FROM aime_avis INNER JOIN avis ON aime_avis.id_avis = avis.id INNER JOIN utilisateurs ON aime_avis.pseudo_utilisateur_qui_aime = utilisateurs.pseudo WHERE id_avis = :id');
                        $reponse3->execute(array('id' => $donnees['id']));
                        $donnees3 = $reponse3->fetch();
                        $reponse3->closeCursor();
                        ?>

                        <?php if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier un avis  
                        ?>
                            <div class="row">
                                <div class="col">
                                    <p class="list-group-item-text pull-right text-right lead"><button type="button" name="modifier" class="btn btn-primary" id="modifier" data-toggle="tooltip" data-placement="top" title="Modifier" onclick="ajoutModificationCommentaire('contenu<?php echo htmlspecialchars($donnees['id']); ?>', 'modifier_avis', 'modifier_avis_formulaire', <?php echo htmlspecialchars($donnees['id']); ?>, 'ajout_avis_formulaire', 'id_avis')">Modifier</button> / <a href="/gerer_avis.php?url=<?php echo htmlspecialchars($_GET['url']); ?>&id_jeu=<?php echo htmlspecialchars($id); ?>&id_avis=<?php echo htmlspecialchars($donnees['id']); ?>&action=supprimer_avis" class="btn btn-warning">Supprimer</a></p><!-- Suppression de l'avis -->
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col">
                                <?php if (isset($_SESSION['id']) and ($donnees2['utilisateur_a_deja_aime']) < 1) { // Si l'utilisateur est connecté et qu'il n'a pas encore aimer l'avis, il pourra aimer un avis
                                ?>
                                    <a href="/jeu.php?id=<?php echo htmlspecialchars($_GET['id']); ?>&url=<?php echo htmlspecialchars($_GET['url']); ?>&onglet_jeu=avis&aime_avis_id=<?php echo $donnees['id']; ?>" class="float-right"><?php echo htmlspecialchars($donnees3['nombre_aime']); ?> <i class="fas fa-thumbs-up"></i> <!-- Pour aimer un avis -->
                                    </a>
                                <?php
                                } else {
                                ?>
                                    <div class="float-right"><?php echo $donnees3['nombre_aime']; ?> <i class="fas fa-thumbs-up"></i>
                                    </div>
                                <?php }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            <?php
            $positionAvis++; // On augmente la position des avis vu qu'on change
           }
        }
        $reponse->closeCursor();
            ?>
            <!-- Pour ajouter un avis -->
            <?php if (isset($_SESSION['id'])) { // Si l'utilisateur est connecté, il peut envoyer un avis
                $reponse = $bdd->prepare('SELECT avis.id FROM avis WHERE avis.id_jeu = :id_jeu AND avis.id_utilisateur = :id_utilisateur'); // On regarde si l'utilisateur à déjà posté un avis, si oui, on désactive le formulaire
                $reponse->execute(array('id_jeu' => $id, 'id_utilisateur' => $_SESSION['id']));
                $nombreAvisUtilisateur = $reponse->rowCount();
                $reponse->closeCursor();
            ?>
                <div style="margin-top: 10px; margin-left: 3%;">
                    <!-- Pour ajouter un avis -->
                    <?php if ($nombreAvisUtilisateur == 0) { // Si l'utilisateur est connecté et qu'il n'a pas envoyé d'avis, il peut envoyer un avis
                    ?>
                        <form class="form" id="ajout_avis_formulaire" action="/ajout_avis_traitement.php" method="post">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="ajout_avis">Ajouter un avis :</label>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom:10px;">
                                    <div class="col">
                                        <!--
                    <input type="button" name="italique" id="italique" value="I" style="font-style: italic;" onclick="ajoutClickBBcodeFormulaire('[i]', '[/i]', 'ajout_commentaire')">
                    <input type="button" name="gras" id="gras" value="G" style="font-weight: bold;" onclick="ajoutClickBBcodeFormulaire('[g]', '[/g]', 'ajout_commentaire')">
                    <input type="button" name="souligne" id="souligne" value="U" style="text-decoration: underline;" onclick="ajoutClickBBcodeFormulaire('[u]', '[/u]', 'ajout_commentaire')">
                    <input type="button" name="citation" id="citation" value="“" onclick="ajoutClickBBcodeFormulaire('[citation]', '[/citation]', 'ajout_commentaire')">
                      
                    <input type="button" name="centre" id="center" value="C" onclick="ajoutClickBBcodeFormulaire('[center]', '[/center]', 'ajout_commentaire')">
                    <input type="button" name="gauche" id="gauche" value="G" onclick="ajoutClickBBcodeFormulaire('[gauche]', '[/gauche]', 'ajout_commentaire')">
                    <input type="button" name="droite" id="droite" value="R"  onclick="ajoutClickBBcodeFormulaire('[droite]', '[/droite]', 'ajout_commentaire')">
                    <input type="button" name="image" id="image" value="I" onclick="ajoutClickBBcodeFormulaire('[image]', '[/image]', 'ajout_commentaire')">                            
                    <input type="button" name="video" id="video" value="V" onclick="ajoutClickBBcodeFormulaire('[video]', '[/video]', 'ajout_commentaire')">                                                
                    -->

                                        <script>
                                            var nom_contenu_avis = 'ajout_avis';
                                        </script>
                                        <?php include('bouton_bb_code_avis.php');
                                        ?>

                                    </div>
                                </div>
                                <div class="row" style="margin-bottom : 5px;">
                                    <input name="note" type="radio" value="0.5" required class="star-demo"> <!-- Note -->
                                    <input name="note" type="radio" value="1" class="star-demo">
                                    <input name="note" type="radio" value="1.5" class="star-demo">
                                    <input name="note" type="radio" value="2" class="star-demo">
                                    <input name="note" type="radio" value="2.5" class="star-demo">
                                    <input name="note" type="radio" value="3" class="star-demo">
                                    <input name="note" type="radio" value="3.5" class="star-demo">
                                    <input name="note" type="radio" value="4" class="star-demo">
                                    <input name="note" type="radio" value="4.5" class="star-demo">
                                    <input name="note" type="radio" value="5" class="star-demo">
                                    <div id="note_label" style="margin-left: 4px;"></div> <!-- Affiche la note selectionné -->
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <textarea name="ajout_avis" id="ajout_avis" placeholder="Poster un avis." onchange="controleTexteInput(this, 'commentaireIndication', 'commentaire')" class="form-control" rows="3"><?php if (isset($_POST['avis'])) {
                                                                                                                                                                                                                                            echo $_POST['avis'] ?> [image2]<?php echo $_FILES['image']['name']; ?>[/image2]<?php
                                                                                                                                                                                                                                                                                                                    } else if (isset($_POST['formUrl']) and isset($_POST['lien']) and isset($_POST['texte'])) {
                                                                                                                                                                                                                                                                                                                        echo $_POST['commentaireUrl']; ?>[lien]<?php echo $_POST['lien']; ?>[/lien] [texteLien] <?php echo $_POST['texte']; ?>[/texteLien]<?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                        } ?></textarea>
                                        <!-- <label id="commentaireIndication" class="text-danger"><?php // if (isset($_POST['contenu']) and empty($_POST['contenu'])) echo "Veuillez écrire un avis" ?></label> <!-- Indication avis, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                                    </div>
                                </div>
                                <div class="row" style="margin-left: 0.1%; margin-top: 9px;">
                                    <button type="submit" id="btn_ajout_avis_formulaire" class="btn btn-success" style="margin-bottom : 3px;">Envoyer</button>
                                </div>
                            </div>
                            <label id="noteIndication" class="text-danger" style="display: none;">Veuillez choisir une note.</label> <!-- Indication note, il sera indiqué si il n'y a pas eu de note -->
                            <input type="hidden" value="<?php echo htmlspecialchars($_GET['id']); ?>" name="id">
                            <input type="hidden" value="<?php echo htmlspecialchars($_GET['url']); ?>" name="url">
                        </form>
            <?php } ?>
                        <script>
                            $('#btn_ajout_avis_formulaire').on('click', function() { // Lors de l'envoi du formulaire, si une note n'est donnée, on affiche un message d'erreur 
                                if (!$("input[name='note']:checked").val()) {
                                    $('#noteIndication').show();
                                } else {
                                    $('#noteIndication').hide();
                                }
                            });
                        </script>
              
                        <form class="form" method="post" id="modifier_avis_formulaire" style="display: none;" action="/gerer_avis.php?url=<?php echo htmlspecialchars($_GET['url']); ?>&id_jeu=<?php echo $_GET['id']; ?>&action=modifier_avis">
                            <!-- Modifie un avis -->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="modifier_avis">Modifier un avis :</label>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom:10px;">
                                    <div class="col">
                                        <script>
                                            var nom_contenu_modifier_avis = 'modifier_avis';
                                        </script>
                                        <?php
                                        include('bouton_bb_code_modification_avis.php');
                                        ?>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom : 5px;">
                                    <input name="note_modifier" type="radio" value="0.5" required class="star-demo"> <!-- Note -->
                                    <input name="note_modifier" type="radio" value="1" class="star-demo">
                                    <input name="note_modifier" type="radio" value="1.5" class="star-demo">
                                    <input name="note_modifier" type="radio" value="2" class="star-demo">
                                    <input name="note_modifier" type="radio" value="2.5" class="star-demo">
                                    <input name="note_modifier" type="radio" value="3" class="star-demo">
                                    <input name="note_modifier" type="radio" value="3.5" class="star-demo">
                                    <input name="note_modifier" type="radio" value="4" class="star-demo">
                                    <input name="note_modifier" type="radio" value="4.5" class="star-demo">
                                    <input name="note_modifier" type="radio" value="5" class="star-demo">
                                    <div id="note_label" style="margin-left: 4px;"></div> <!-- Affiche la note selectionné -->
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <textarea name="modifier_avis" id="modifier_avis" placeholder="Poster un avis." onchange="controleTexteInput(this, 'avisIndication', 'avis')" required class="form-control" rows="3"><?php if (isset($_POST['commentaire'])) {
                                                                                                                                                                                                                                    echo $_POST['commentaire'] ?> [image2]<?php echo $_FILES['image']['name']; ?>[/image2]<?php
                                                                                                                                                                                                                                                                                                                    } else if (isset($_POST['formUrl']) and isset($_POST['lien']) and isset($_POST['texte'])) {
                                                                                                                                                                                                                                                                                                                        echo $_POST['commentaireUrl']; ?>[lien]<?php echo $_POST['lien']; ?>[/lien] [texteLien] <?php echo $_POST['texte']; ?>[/texteLien]<?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                        } ?></textarea>
                                        <!-- <label id="commentaireIndication" class="text-danger"><?php if (isset($_POST['contenu']) and empty($_POST['contenu'])) echo "Veuillez écrire un commentaire" ?></label> <!-- Indication commentaire, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                                    </div>
                                </div>

                            </div>
                            <button type="submit" id="btn_ajout_avis_formulaire" class="btn btn-success" style="margin-bottom : 10px;">Envoyer</button>
                            <div class="row">
                                <div class="col">
                                    <p class="list-group-item-text lead"><button type="button" name="boutonRevenirFormulaireAjout" class="btn btn-info" id="boutonRevenirFormulaireAjout" data-toggle="tooltip" data-placement="top" title="Apparaitre Formulaire Ajout" onclick="changementMenu('modifier_avis_formulaire', 'ajout_avis_formulaire')">Revenir vers l'ajout d'avis</button></p><!-- Bouton pour faire revenir le formulaire d'ajout -->
                                    </a>
                                </div>
                            </div>
                        </form>

                        <?php
                        include_once('ajout_aime_avis_traitement.php');
                        ?>

                        <?php
                        include_once('upload_image.php');
                        ?>

                        <?php
                        include_once('ajout_url.php');
                        ?>
                        <?php
                        include_once('ajout_tableau.php');
                        ?>
                        <?php
                        include_once('ajout_video.php');
                        ?>
                </div>
            <?php
                } else { // Sinon, il y aura un message pour dire de se connecter
            ?>
            <div class="alert alert-primary" style="margin-top: 9px;">Veuillez vous <a href="/connexion.php">connecter</a> pour écrire un avis.</div>
        <?php
                }
        ?>
</ul>