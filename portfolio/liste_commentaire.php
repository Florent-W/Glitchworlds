 <!-- Affichage des commentaires -->
 <ul class="list-group" style="top:100px">



     <!-- Choix du type d'affichage des commentaires, par ancienneneté, par nombre de j'aime -->
     <form class="form-inline my-2 my-lg-0 justify-content-center" method="POST">
         <?php
            ?>
         <div class="form-group">
             <label class="label-form d-md-none" for="tri_commentaire">Affichage :</label> <!-- Affichage sur des écrans petits -->
             <label class="label-form d-none d-md-block" for="tri_commentaire">Affichage des commentaires :</label> <!-- Affichage sur des écrans plus grands -->
         </div>
         <div class="form-group">
             <select class="form-control form-select" name="tri_commentaire" style="margin: 5px;">
                 <option value="Recents" <?php if (!isset($_POST['tri_commentaire']) or $_POST['tri_commentaire'] == "Recents") echo 'selected="selected"'; ?>>Récents</option>
                 <option value="Tops" <?php if (isset($_POST['tri_commentaire']) and $_POST['tri_commentaire'] == "Tops") echo 'selected="selected"'; ?>>Tops</option>
             </select>
         </div>
         <div class="form-group">
             <button class="btn btn-outline-success" style="margin: 5px;" type="submit">Valider</button>
         </div>
     </form>
     <?php
        if ($type_commentaire == "commentaire") {
            $type_id = "id_news";
            $type_aime_commentaire = "aime_commentaire";
        } else if ($type_commentaire == "commentaire_jeu") {
            $type_id = "id_jeu";
            $type_aime_commentaire = "aime_commentaire_jeu";
        }

        // Si l'utilisateur n'a pas fait de choix concernant le tri des commentaires ou qu'il a sélectionné le tri par commentaires les plus récents
        if (!isset($_POST['tri_commentaire']) or (isset($_POST['tri_commentaire']) and $_POST['tri_commentaire'] == "Recents")) {
            $reponse = $bdd->prepare('SELECT ' . $type_commentaire . '.id, utilisateurs.pseudo, utilisateurs.nom_photo_profil, DATE_FORMAT(' . $type_commentaire . '.date_commentaire, "%d %M %Y à %Hh%imin%ss") AS date_message, ' . $type_commentaire . '.contenu FROM ' . $type_commentaire . ' INNER JOIN utilisateurs ON ' . $type_commentaire . '.id_utilisateur = utilisateurs.id WHERE ' . $type_id . ' = :id ORDER BY date_commentaire'); // Récupération des commentaires
            $reponse->execute(array('id' => $id));
            $nombreCommentaire = $reponse->rowCount();
        }

        // Si l'utilisateur a sélectionné le tri par commentaires les plus aimé
        else if (isset($_POST['tri_commentaire']) and $_POST['tri_commentaire'] == "Tops") {
            $reponse = $bdd->prepare('SELECT ' . $type_commentaire . '.id, utilisateurs.pseudo, utilisateurs.nom_photo_profil, ' . $type_aime_commentaire . '.id_commentaire, COUNT(aime_commentaire.id_commentaire) AS nombre_aime, DATE_FORMAT(' . $type_commentaire . '.date_commentaire, "%d %M %Y à %Hh%imin%ss") AS date_message, ' . $type_commentaire . '.contenu FROM ' . $type_commentaire . ' LEFT JOIN utilisateurs ON ' . $type_commentaire . '.id_utilisateur = utilisateurs.id LEFT JOIN ' . $type_aime_commentaire . ' ON ' . $type_commentaire . '.id = ' . $type_aime_commentaire . '.id_commentaire WHERE ' . $type_id . ' = :id GROUP BY ' . $type_aime_commentaire . '.id_commentaire, ' . $type_commentaire . '.id ORDER BY nombre_aime DESC'); // Récupération des commentaires
            $reponse->execute(array('id' => $id));
            $nombreCommentaire = $reponse->rowCount();
        }

        // Affichage des commentaires
        if ($nombreCommentaire > 1) {
        ?>
         <h3 style="margin-bottom: 20px;"><?php echo htmlspecialchars($nombreCommentaire); ?> Commentaires :</h3>
     <?php
        } else { ?>
         <h3 style="margin-bottom: 20px;"><?php echo htmlspecialchars($nombreCommentaire); ?> Commentaire :</h3>
     <?php }
        ?>

     <?php
        $positionCommentaire = 0; // On va voir la place du commentaire et une fois sur deux, il sera en couleur 


        while ($donnees = $reponse->fetch()) {
            if ($positionCommentaire % 2 == 0) { // Un commentaire sur deux sera en couleur
        ?>
             <div class="list-group-item list-group-item-secondary liste-item-commentaire">
             <?php } else { ?>
                 <div class="list-group-item liste-item-commentaire">
                 <?php }
                    ?>
                 <div class="media">

                     <img src="/portfolio/photo_profil/<?php echo htmlspecialchars($donnees['nom_photo_profil']); ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-fluid img-profil img-thumbnail" style="float:left; object-fit: cover;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
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

                         <?php
                            // Voir si l'utilisateur à déjà aimer le commentaire

                            if (isset($_SESSION['pseudo'])) {
                                $pseudoActuel = $_SESSION['pseudo'];
                            } else {
                                $pseudoActuel = null;
                            }

                            $reponse2 = $bdd->prepare('SELECT COUNT(DISTINCT(' . $type_aime_commentaire . '.pseudo_utilisateur_qui_aime)) AS utilisateur_a_deja_aime FROM ' . $type_aime_commentaire . ' INNER JOIN ' . $type_commentaire . ' ON ' . $type_aime_commentaire . '.id_commentaire = ' . $type_commentaire . '.id INNER JOIN utilisateurs ON ' . $type_aime_commentaire . '.pseudo_utilisateur_qui_aime = utilisateurs.pseudo WHERE id_commentaire = :id AND utilisateurs.pseudo = :pseudo');
                            $reponse2->execute(array('id' => $donnees['id'], 'pseudo' => $pseudoActuel));
                            $donnees2 = $reponse2->fetch();
                            $reponse2->closeCursor();
                            ?>

                         <?php
                            // Voir le nombre de j'aime pour un commentaire
                            $reponse3 = $bdd->prepare('SELECT COUNT(DISTINCT(' . $type_aime_commentaire . '.pseudo_utilisateur_qui_aime)) AS nombre_aime FROM ' . $type_aime_commentaire . ' INNER JOIN ' . $type_commentaire . ' ON ' . $type_aime_commentaire . '.id_commentaire = ' . $type_commentaire . '.id INNER JOIN utilisateurs ON ' . $type_aime_commentaire . '.pseudo_utilisateur_qui_aime = utilisateurs.pseudo WHERE id_commentaire = :id');
                            $reponse3->execute(array('id' => $donnees['id']));
                            $donnees3 = $reponse3->fetch();
                            $reponse3->closeCursor();
                            ?>

                         <?php if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier un commentaire  
                            ?>
                             <div class="row">
                                 <div class="col">
                                     <p class="list-group-item-text pull-right text-right lead"><button type="button" name="modifier" class="btn btn-primary" id="modifier" data-toggle="tooltip" data-placement="top" title="Modifier" onclick="ajoutModificationCommentaire('contenu<?php echo htmlspecialchars($donnees['id']); ?>', 'modifier_commentaire', 'modifier_commentaire_formulaire', <?php echo htmlspecialchars($donnees['id']); ?>, 'ajout_commentaire_formulaire')">Modifier</button> / <a href="/gerer_commentaire.php?url=<?php echo htmlspecialchars($_GET['url']); ?>&id_news=<?php echo htmlspecialchars($id); ?>&id_commentaire=<?php echo htmlspecialchars($donnees['id']); ?>&type_commentaire=<?php echo htmlspecialchars($type_commentaire); ?>&action=supprimer_commentaire" class="btn btn-warning">Supprimer</a></p><!-- Suppression du commentaire -->
                                     </a>
                                 </div>
                             </div>
                         <?php } ?>
                         <div class="row">
                             <div class="col">
                                 <?php if (isset($_SESSION['id']) and ($donnees2['utilisateur_a_deja_aime']) < 1) { // Si l'utilisateur est connecté et qu'il n'a pas encore aimer le commentaire, il pourra aimer un commentaire
                                        if (!isset($type_commentaire)) {
                                    ?>
                                         <a href="/news.php?id=<?php echo htmlspecialchars($_GET['id']); ?>&url=a&aime_commentaire_id=<?php echo $donnees['id']; ?>" class="float-right"><?php echo htmlspecialchars($donnees3['nombre_aime']); ?> <i class="fas fa-thumbs-up"></i> <!-- Pour aimer un commentaire -->
                                         </a>
                                     <?php
                                        }
                                        else if($type_commentaire == "commentaire_jeu") {
                                            ?>
                                            <a href="/jeu.php?id=<?php echo htmlspecialchars($_GET['id']); ?>&url=a&aime_commentaire_id=<?php echo $donnees['id']; ?>" class="float-right"><?php echo htmlspecialchars($donnees3['nombre_aime']); ?> <i class="fas fa-thumbs-up"></i> <!-- Pour aimer un commentaire -->
                                         </a>
                                         <?php
                                        }
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
                $positionCommentaire++; // On augmente la position des commentaire vu qu'on change

            }
            $reponse->closeCursor();

                ?>
             <!-- Pour ajouter un commentaire -->
             <?php if (isset($_SESSION['id'])) { // Si l'utilisateur est connecté, il peut envoyer un commentaire
                ?>
                 <div style="margin-top: 10px;">
                     <form class="form" id="ajout_commentaire_formulaire" method="post">
                         <div class="form-group">
                             <div class="row">
                                 <div class="col">
                                     <label for="ajout_commentaire">Ajouter un commentaire :</label>
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
                                         var nom_contenu = 'ajout_commentaire';
                                     </script>
                                     <?php include('bouton_bb_code.php');
                                        ?>

                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col">
                                     <textarea name="ajout_commentaire" id="ajout_commentaire" placeholder="Poster un commentaire." onchange="controleTexteInput(this, 'commentaireIndication', 'commentaire')" required class="form-control" rows="3"><?php if (isset($_POST['commentaire'])) {
                                                                                                                                                                                                                                                            echo $_POST['commentaire'] ?> [image2]<?php echo $_FILES['image']['name']; ?>[/image2]<?php
                                                                                                                                                                                                                                                                                                                                            } else if (isset($_POST['formUrl']) and isset($_POST['lien']) and isset($_POST['texte'])) {
                                                                                                                                                                                                                                                                                                                                                echo $_POST['commentaireUrl']; ?>[lien]<?php echo $_POST['lien']; ?>[/lien] [texteLien] <?php echo $_POST['texte']; ?>[/texteLien]<?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    } ?></textarea>
                                     <!-- <label id="commentaireIndication" class="text-danger"><?php if (isset($_POST['contenu']) and empty($_POST['contenu'])) echo "Veuillez écrire un commentaire" ?></label> <!-- Indication commentaire, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                                 </div>
                             </div>
                         </div>
                         <button type="submit" class="btn btn-success" style="margin-bottom : 10px;">Envoyer</button>
                         <input type="hidden" value="<?php echo htmlspecialchars($_GET['id']); ?>" name="id_news">
                         <input type="hidden" value="<?php echo htmlspecialchars($_GET['url']); ?>" name="url">
                     </form>

                     <form class="form" method="post" id="modifier_commentaire_formulaire" style="display: none;" action="/gerer_commentaire.php?url=<?php echo htmlspecialchars($_GET['url']); ?>&id_news=<?php echo $_GET['id']; ?>&type_commentaire=<?php echo htmlspecialchars($type_commentaire); ?>&action=modifier_commentaire">
                         <!-- Modifie un commentaire -->
                         <div class="form-group">
                             <div class="row">
                                 <div class="col">
                                     <label for="modifier_commentaire">Modifier un commentaire :</label>
                                 </div>
                             </div>
                             <div class="row" style="margin-bottom:10px;">
                                 <div class="col">
                                     <script>
                                         var nom_contenu_modifier = 'modifier_commentaire';
                                     </script>
                                     <?php
                                        include('bouton_bb_code_modification.php');
                                        ?>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col">
                                     <textarea name="modifier_commentaire" id="modifier_commentaire" placeholder="Poster un commentaire." onchange="controleTexteInput(this, 'commentaireIndication', 'commentaire')" required class="form-control" rows="3"><?php if (isset($_POST['commentaire'])) {
                                                                                                                                                                                                                                                                    echo $_POST['commentaire'] ?> [image2]<?php echo $_FILES['image']['name']; ?>[/image2]<?php
                                                                                                                                                                                                                                                                                                                                                } else if (isset($_POST['formUrl']) and isset($_POST['lien']) and isset($_POST['texte'])) {
                                                                                                                                                                                                                                                                                                                                                    echo $_POST['commentaireUrl']; ?>[lien]<?php echo $_POST['lien']; ?>[/lien] [texteLien] <?php echo $_POST['texte']; ?>[/texteLien]<?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        } ?></textarea>
                                     <!-- <label id="commentaireIndication" class="text-danger"><?php if (isset($_POST['contenu']) and empty($_POST['contenu'])) echo "Veuillez écrire un commentaire" ?></label> <!-- Indication commentaire, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                                 </div>
                             </div>

                         </div>
                         <button type="submit" class="btn btn-success" style="margin-bottom : 10px;">Envoyer</button>
                         <div class="row">
                             <div class="col">
                                 <p class="list-group-item-text lead"><button type="button" name="boutonRevenirFormulaireAjout" class="btn btn-info" id="boutonRevenirFormulaireAjout" data-toggle="tooltip" data-placement="top" title="Apparaitre Formulaire Ajout" onclick="changementMenu('modifier_commentaire_formulaire', 'ajout_commentaire_formulaire')">Revenir vers l'ajout de commentaire</button></p><!-- Bouton pour faire revenir le formulaire d'ajout -->
                                 </a>
                             </div>
                         </div>
                     </form>

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
                 </div>
             <?php } else { // Sinon, il y aura un message pour dire de se connecter
                ?>
                 <div style="margin-top: 20px;">Veuillez vous <a href="/connexion.php">connecter</a> pour écrire un commentaire.</div>
             <?php
                }
                ?>
 </ul>