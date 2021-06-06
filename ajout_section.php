<!-- Fenetre pour section -->
<div class="modal fade" id="modalSection" tabindex="-1" role="dialog" aria-labelledby="modalLabelSection" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajout de section et de slides</h5>
            </div>
            <div class="modal-body">
                <form id="formSection" class="form" method="post" enctype="multipart/form-data">
                    <div class="form-group" id="ajoutSectionSlide">
                        <!-- On demande à l'utilisateur si il veut ajouter une section vide pour les slides -->
                        <button type="button" id='ajoutSectionVide' onclick="if(!$('#titreSectionGroup').is(':visible')) { $('#titreSectionGroup').show(); }" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Un slider est la partie horizontale d'une section. Il a besoin d'une section vide pour être affiché. 
                        Exemple : [section][slider][/slider][slider][/slider][slider][/slider][section]. 
                        Il y aura une section avec trois sliders allant de gauche vers la droite.">Ajouter une Section pour les Slides</button> 
                    </div>
                    <div class="form-group" id="titreSectionGroup" style='display: none;'> <!-- Ajout du titre avant -->
                        <label for="titreSection">Titre de la section</label>
                        <input type="text" name="titreSection" id="titreSection" class="form-control">
                        <button name="btnSectionSection" id="btnSectionSection" class="btn btn-success" style="margin-top: 9px;" onclick="ajoutClickBBcodeFormulaire('[section titre=' + $('#titreSection').val() + ']', '[/section]', nom_contenu); $('#titreSectionGroup').hide();" data-dismiss="modal">Envoyer</button>
                    </div>
                    <div class="form-group">
                        <label for="titreSlide">Titre du slide</label>
                        <input type="text" name="titreSlide" id="titreSlide" required onchange="controleTexteInput(this, 'contenuSlideIndication', 'contenu')" class="form-control">
                        <label id="contenuSlideIndication" class="text-danger"></label> <!-- Indication nom, il sera indiqué si le texte n'a pas de caractère ou qu'il y a une erreur -->
                    </div>
                    <div class="form-group">
                        <label for="typeFondSection">Type de fond</label> <!-- On demande à l'utilisateur quel type de fond il veut -->
                        <div class="row text-center">
                            <div class="form-check col">
                                <input type="radio" id="choixFondCouleur" name="typeFondSection" value="couleur" class="form-check-input">
                                <label class="form-check-label" for="choixFondCouleur">Couleur de fond</label>
                            </div>
                            <div class="form-check col">
                                <input type="radio" id="choixFondGradient" name="typeFondSection" value="gradient" class="form-check-input">
                                <label class="form-check-label" for="choixFondGradient">Dégradé de fond</label>
                            </div>
                            <div class="form-check col">
                                <input type="radio" id="choixFondVideo" name="typeFondSection" value="video" class="form-check-input">
                                <label class="form-check-label" for="choixFondVideo">Video Youtube</label>
                            </div>
                            <div class="form-check col">
                                <input type="radio" id="choixFondImage" name="typeFondSection" value="image" class="form-check-input">
                                <label class="form-check-label" for="choixFondImage">Image de fond</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" name="couleurfondSectionGroup" id="couleurfondSectionGroup" style="display: none;">
                        <select class="selecticone fa selectpicker" name="couleurfondSection" id="couleurfondSection" data-placement="top" data-live-search="true" data-width="fit" title="Couleur de fond">
                            <!-- Selection couleur de fond -->
                            <option hidden>&#xf53f; Couleur de fond</option> <!-- Selection avec icone -->
                            <option value="blue" style="background-color: blue;">Bleu</option>
                            <option value="lightskyblue" style="background-color: lightskyblue;">Bleu clair</option>
                            <option value="yellow" style="background-color: #ebca0e;">Jaune</option>
                            <option value="green" style="background-color: green;">Vert</option>
                            <option value="orange" style="background-color: orange;">Orange</option>
                            <option value="red" style="background-color: red;">Rouge</option>
                            <option value="pink" style="background-color: pink;">Rose</option>
                            <option value="violet" style="background-color: violet;">Violet</option>
                            <option value="brown" style="background-color: brown;">Marron</option>
                            <option value="silver" style="background-color: silver;">Argenté</option>
                        </select>
                    </div>
                    <div class="form-group" name="gradientfondSectionGroup" id="gradientfondSectionGroup" style="display: none;">
                        <select class="selecticone fa selectpicker" name="gradientfondSection" id="gradientfondSection" data-placement="top" data-live-search="true" data-width="fit" title="Dégradé de fond">
                            <!-- Selection couleur graduente de fond -->
                            <option hidden>&#xf53f; Dégradé de fond</option> <!-- Selection avec icone -->
                            <option value="#e66465, #9198e5" style="background: linear-gradient(#e66465, #9198e5);">Bleu Orangé</option>
                            <option value="#00416a, #e4e5e6" style="background: linear-gradient(#00416a, #e4e5e6);">Bupe</option>
                            <option value="#ffe259, #ffa751" style="background: linear-gradient(#ffe259, #ffa751);">Mango</option>
                            <option value="#5433ff, #20bdff, #a5fecb" style="background: linear-gradient(#5433ff, #20bdff, #a5fecb);">Lunada</option>
                            <option value="#0052d4, #4364f7, #6fb1fc" style="background: linear-gradient(#0052d4, #4364f7, #6fb1fc);">Bluelagoo</option>
                            <option value="#c6ffdd, #fbd786, #f7797d" style="background: linear-gradient(#c6ffdd, #fbd786, #f7797d);">Megatron</option>
                            <option value="#12c2e9, #c471ed, #f64f59" style="background: linear-gradient(#12c2e9, #c471ed, #f64f59);">Jshine</option>
                            <option value="b92b27, #1565c0" style="background: linear-gradient(#b92b27, #1565c0);">Evening Sunshine</option>
                            <option value="#2980b9, #6dd5fa, #ffffff" style="background: linear-gradient(#2980b9, #6dd5fa, #ffffff);">Cool Sky</option>
                            <option value="#7f7fd5, #86a8e7, #91eae4" style="background: linear-gradient(#7f7fd5, #86a8e7, #91eae4);">Azur Lane</option>
                            <option value="#8a2387, #e94057, #f27121" style="background: linear-gradient(#8a2387, #e94057, #f27121);">Wiretap</option>
                            <option value="#00b4db, #0083b0" style="background: linear-gradient(#00b4db, #0083b0);">Blue Raspberry</option>
                            <option value="#a8c0ff, #3f2b96" style="background: linear-gradient(#a8c0ff, #3f2b96);">Slight Ocean</option>
                            <option value="#fc5c7d, #6a82fb" style="background: linear-gradient(#fc5c7d, #6a82fb);">Sublime Light</option>
                            <option value="#ffecd2, #fcb69f" style="background: linear-gradient(#ffecd2, #fcb69f);">Peach</option>
                            <option value="#cfd9df, #e2ebf0" style="background: linear-gradient(#cfd9df, #e2ebf0);">Rain</option>
                            <option value="#d4fc79, #96e6a1" style="background: linear-gradient(#d4fc79, #96e6a1);">Grass</option>
                            <option value="#a6c0fe, #f68084" style="background: linear-gradient(#a6c0fe, #f68084);">Amy Crisp</option>
                            <option value="#4facfe, #00f2fe" style="background: linear-gradient(#4facfe, #00f2fe);">Beach</option>
                            <option value="#43e97b, #38f9d7" style="background: linear-gradient(#43e97b, #38f9d7);">Life</option>
                            <option value="#2af598, #009efd" style="background: linear-gradient(#2af598, #009efd);">Itmeo</option>
                            <option value="#0250c5, #d43f8d" style="background: linear-gradient(#0250c5, #d43f8d);">Night Party</option>
                            <option value="#e8198b, #c7eafd" style="background: linear-gradient(#e8198b, #c7eafd);">Frozen Berry</option>
                            <option value="#209cff, #68e0cf" style="background: linear-gradient(#209cff, #68e0cf);">Seashore</option>
                            <option value="#7DE2FC, #B9B6E5" style="background: linear-gradient(#7DE2FC, #B9B6E5);">Fresh Oasis</option>
                        </select>
                    </div>
                    <div name="videofondSectionGroup" id="videofondSectionGroup" style="display: none;">
                        <div class="form-group">
                            <label for="lien">Lien</label>
                            <input type="text" name="lienfond" id="lienfond" onchange="controleTexteInput(this, 'lienIndication', 'contenu')" class="form-control">
                            <label id="lienIndication" class="text-danger"></label> <!-- Indication nom, il sera indiqué si le texte n'a pas de caractère ou qu'il y a une erreur -->
                        </div>
                        <button type="button" name="btnInitialisationFondVideo" id="btnInitialisationFondVideo" class="btn btn-info" style="margin-bottom: 5px;">Prévisualisation</button> <!-- D'abord on initialise la vidéo -->
                    </div>

                    <div name="imagesBackgroundSectionGroup" id="imagesBackgroundSectionGroup" style="display: none">
                        <div class="form-group">
                            <label for="images">Image de fond</label>
                            <div class="input-group">
                                <!-- Upload de miniature -->
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupFileAddon01Section">Upload</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="imagesBackgroundSection" id="imagesBackgroundSection" accept=".jpg, .png, .bmp, .gif" onchange="controleTexteInput(this, 'imageIndicationSection', 'miniature')" aria-describedby="images"> <!-- Si un fichier a été choisi, l'événement onchange permettra de montrer le nom du fichier sur le label d'information -->
                                    <label id="imageIndicationSection" class="custom-file-label" for="imagesBackgroundSection">Choisir fichier</label>
                                </div>
                                <div>
                                    <input type="hidden" id="commentaire" name="commentaire">
                                    <button type="submit" style="margin-left: 9px;" class="btn btn-success">Envoyer</button> <!-- On ajoute l'image -->
                                </div>
                            </div>
                        </div>
                        <div class="progress">
                            <!-- Barre de progression -->
                            <div class="bar progress-bar" id="barSection" role="progressbar" aria-valuemin="0" aria-valuenow="0" aria-valuemax="100"></div>
                            <div class="percent" id="percentSection">0%</div>
                        </div>
                        <div id="statusSection">Ajoutez une image</div>
                    </div>

                    <button type="submit" name="btnSection" id="btnSection" class="btn btn-success" data-dismiss="modal">Envoyer</button> <!-- On ajoute le lien et le texte -->
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#btnInitialisationFondVideo').click(function() { // Initialisation de l'interface de la video
        $('#divInitialisationFondVideo').remove(); // Si on recharge la vidéo, on supprime l'ancienne

        video = $('#lienfond').val();
        video = video.replace(/https:\/\/www.youtube.com\/watch\?v=(.+)/g, '<div id="divInitialisationFondVideo"><iframe width=280 height=180 class="video-commentaire" name="video" id="video" frameborder=0 allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen src="https://www.youtube.com/embed/$1"></div>'); // On replace le lien par un iframe
        $('#btnSection').after(video); // On place l'iframe
        // $('#btnSection').show();
    });

    $('#formSection input').on('change', function() {
        if ($('input[name=typeFondSection]:checked', '#formSection').val() == 'couleur') { // Si l'utilisateur à fait un choix, on montre les menus
            if ($('#imagesBackgroundSectionGroup').is(':visible')) {
                $('#imagesBackgroundSectionGroup').hide(); // On enlève l'autre menu si il est déjà présent
            }
            if ($('#gradientfondSectionGroup').is(':visible')) {
                $('#gradientfondSectionGroup').hide(); // On enlève l'autre menu si il est déjà présent
            }
            if ($('#videofondSectionGroup').is(':visible')) {
                $('#videofondSectionGroup').hide(); // On enlève l'autre menu si il est déjà présent
            }
            $('#couleurfondSectionGroup').show();
        } else if ($('input[name=typeFondSection]:checked', '#formSection').val() == 'image') {
            if ($('#couleurfondSectionGroup').is(':visible')) {
                $('#couleurfondSectionGroup').hide(); // On enlève l'autre menu si il est déjà présent
            }
            if ($('#gradientfondSectionGroup').is(':visible')) {
                $('#gradientfondSectionGroup').hide(); // On enlève l'autre menu si il est déjà présent
            }
            if ($('#videofondSectionGroup').is(':visible')) {
                $('#videofondSectionGroup').hide(); // On enlève l'autre menu si il est déjà présent
            }

            $('#imagesBackgroundSectionGroup').show();
        } else if ($('input[name=typeFondSection]:checked', '#formSection').val() == 'gradient') {
            if ($('#couleurfondSectionGroup').is(':visible')) {
                $('#couleurfondSectionGroup').hide(); // On enlève l'autre menu si il est déjà présent
            }
            if ($('#imagesBackgroundSectionGroup').is(':visible')) {
                $('#imagesBackgroundSectionGroup').hide(); // On enlève l'autre menu si il est déjà présent
            }
            if ($('#videofondSectionGroup').is(':visible')) {
                $('#videofondSectionGroup').hide(); // On enlève l'autre menu si il est déjà présent
            }
            $('#gradientfondSectionGroup').show();
        } else if ($('input[name=typeFondSection]:checked', '#formSection').val() == 'video') {
            if ($('#couleurfondSectionGroup').is(':visible')) {
                $('#couleurfondSectionGroup').hide(); // On enlève l'autre menu si il est déjà présent
            }
            if ($('#imagesBackgroundSectionGroup').is(':visible')) {
                $('#imagesBackgroundSectionGroup').hide(); // On enlève l'autre menu si il est déjà présent
            }
            if ($('#gradientfondSectionGroup').is(':visible')) {
                $('#gradientfondSectionGroup').hide(); // On enlève l'autre menu si il est déjà présent
            }
            $('#videofondSectionGroup').show();
        }
    });

    $('#btnSection').on('click', function() { // Si on envoi, on regarde si la couleur de fond à été selectionné et on envoi le bbcode
        var typeSection = 'slide'; // On regarde si c'est une section ou un slide

        if ($('#titreSlide').val() != "" && $('input[name=typeFondSection]:checked', '#formSection').val() == 'couleur' && $('#couleurfondSection option:selected')) {
            ajoutClickBBcodeFormulaire('[' + typeSection + ' couleur=' + $('#couleurfondSection option:selected').val() + ' titre=' + $('#titreSlide').val() + ']', '[/' + typeSection + ']', nom_contenu);
        } else if ($('#titreSlide').val() != "" && $('input[name=typeFondSection]:checked', '#formSection').val() == 'gradient' && $('#gradientfondSection option:selected')) {
            ajoutClickBBcodeFormulaire('[' + typeSection + ' gradient=' + $('#gradientfondSection option:selected').val() + ' titre=' + $('#titreSlide').val() + ']', '[/' + typeSection + ']', nom_contenu);
        } else if ($('#titreSlide').val() != "" && $('input[name=typeFondSection]:checked', '#formSection').val() == 'video' && $('#videofondSection option:selected')) {
            ajoutClickBBcodeFormulaire('[' + typeSection + ' video=' + $('#lienfond').val() + ' titre=' + $('#titreSlide').val() + ']', '[/' + typeSection + ']', nom_contenu);
        }
    });
</script>
<script>
    $(function() {
        // Fonction pour l'upload et la barre de progression
        var barSection = $('#barSection');
        var percentSection = $('#percentSection');
        var status = $('#statusSection');

        $('#formSection').ajaxForm({
            beforeSend: function() {
                // Avant l'envoi, on met la barre à zéro
                // status.empty();
                status.text("Ajoutez une image");
                var percentVal = '0%';
                barSection.width(percentVal);
                percentSection.html(percentVal);
            },
            uploadProgress: function(event, position, total, percentComplete) {
                // Progression de la barre
                var percentVal = percentComplete + '%';
                barSection.width(percentVal);
                percentSection.html(percentVal);
            },
            complete: function(xhr) {
                // Fin de l'upload
                status.text("Fichier upload");

                date = new Date(); // On prend la date

                jour = String(date.getDate()).padStart(2, '0');
                mois = String(date.toLocaleString('fr-FR', {
                    month: 'long'
                }));
                mois_chiffre = String(date.getMonth());
                annee = String(date.getFullYear());
                heure = String(date.getHours()).padStart(2, '0');
                minute = String(date.getMinutes()).padStart(2, '0');
                seconde = String(date.getSeconds()).padStart(2, '0');

                date_actuel = String(annee + '-' + mois_chiffre + '-' + jour + " " + heure + ':' + minute + ":" + seconde);

                imageNom = $('#imageIndicationSection').text();
                hash = Math.random().toString(36).substring(2); // Le hash servira à générer un nom de fichier qui ne soit pas le même
                extension_image = imageNom.substr(imageNom.lastIndexOf('.') + 1, imageNom.length);

                type_section = 'slide';

                var nom_fichier = jour + "_" + mois + "_" + annee + "_" + heure + "h" + minute + "m" + seconde + "_" + hash + "." + extension_image; // Le nom du fichier
                // nom_dossier = annee + '/' + mois + '/' + jour + "/" + heure + 'h' + minute + '/' + 'images' + '/' + nom_fichier;

                // fichier = $('#inputGroupFile01').prop('files')[0];
                var data = new FormData();
                data.append('images', $("#imagesBackgroundSection")[0].files[0]);
                data.append('date_actuel', date_actuel);
                data.append('jour', jour);
                data.append('mois', mois);
                data.append('annee', annee);
                data.append('heure', heure);
                data.append('minute', minute);
                data.append('seconde', seconde);
                data.append('jour', jour);
                data.append('hash', hash);
                data.append('extension_image', extension_image);
                data.append('ancien_nom', imageNom);
                data.append('nom_fichier', nom_fichier);
                data.append('image', true);

                // data.append('file', fichier);

                $.ajax({
                    data:
                        /*{ // Les données à exporter vers le traitement
                                               date_actuel: date_actuel,
                                               data,
                                               jour: jour,
                                               mois: mois,
                                               annee: annee,
                                               heure: heure,
                                               minute: minute,
                                               seconde: seconde,
                                               hash: hash,
                                               extension_image: extension_image,
                                               ancien_nom: imageNom,
                                               nom_fichier: nom_fichier,
                                               image: "true"
                                           }*/
                        data,
                    type: "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: "/upload_image_section_traitement_premier.php",
                    error: function() {
                        alert('Erreur');
                    },
                    success: function(data) {
                        image = '<div class="form-group" id="divPrevisualisationImage"><label for="imagePrevisualisation">Prévisualisation</label><img src="/images/' + nom_fichier + '"onerror="this.oneerror=null; this.src="/1.jpg";" name="imagePrevisualisation" id="imagePrevisualisation" class="img-fluid img-thumbnail form-control" style="height: 10vh; width: 10vh;"></div>';
                        $('#statusSection').after(image); // On place l'iframe
                        // alert("Data Save: " + data);
                        // console.log($("#inputGroupFile01")[0].files[0]);
                    }
                });

                ajoutClickBBcodeFormulaire('[' + type_section + ' fond=' + nom_fichier + ' titre=' + $('#titreSlide').val() + ']', '[/' + type_section + ']', nom_contenu); // Ajoute les balises et l'alignement

                $('#divPrevisualisationImage').remove(); // Si on recharge l'image, on supprime l'ancienne

                // nom_dossier = "/images/" + nom_dossier;
                // ('#btn').trigger('hide'); // Ferme le modal
            },
        });
    });
</script>