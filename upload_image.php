<!-- Fenetre upload -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
                <form class="form" id="formImage" name="formImage" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="dispositionImage">Disposition de l'image</label> <!-- On demande à l'utilisateur si l'image doit être placé à côté d'un texte -->
                        <div class="row text-center">
                            <div class="form-check col">
                                <input type="radio" id="choixAucun" name="dispositionImage" value="none" checked class="form-check-input">
                                <label class="form-check-label" for="choixAucun">Aucun</label>
                            </div>
                            <div class="form-check col">
                                <input type="radio" id="choixGauche" name="dispositionImage" value="left" class="form-check-input">
                                <label class="form-check-label" for="choixGauche">Gauche</label>
                            </div>
                            <div class="form-check col">
                                <input type="radio" id="choixDroite" name="dispositionImage" value="right" class="form-check-input">
                                <label class="form-check-label" for="choixDroite">Droite</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tailleImage">Taille de l'image</label> <!-- On demande à l'utilisateur quelle taille d'image il veut -->
                        <div class="row text-center">
                            <div class="form-check col">
                                <input type="radio" id="choixIcone" name="tailleImage" value="200" checked class="form-check-input">
                                <label class="form-check-label" for="choixIcone">Icone</label>
                            </div>
                            <div class="form-check col">
                                <input type="radio" id="choixPetite" name="tailleImage" value="300" class="form-check-input">
                                <label class="form-check-label" for="choixPetite">Petite</label>
                            </div>
                            <div class="form-check col">
                                <input type="radio" id="choixMoyenne" name="tailleImage" value="500" class="form-check-input">
                                <label class="form-check-label" for="choixMoyenne">Moyenne</label>
                            </div>
                            <div class="form-check col">
                                <input type="radio" id="choixGrande" name="tailleImage" value="900" class="form-check-input">
                                <label class="form-check-label" for="choixGrande">Grande</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="images">Image</label>
                        <div class="input-group">
                            <!-- Upload de miniature -->
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="images[]" id="images" accept=".jpg, .png, .bmp, .gif" required multiple onchange="controleTexteInput(this, 'imageIndication', 'miniature')" aria-describedby="images"> <!-- Si un fichier a été choisi, l'événement onchange permettra de montrer le nom du fichier sur le label d'information -->
                                <label id="imageIndication" class="custom-file-label" for="images">Choisir fichier</label>
                            </div>
                            <div>
                                <input type="hidden" id="commentaire" name="commentaire">
                                <button type="submit" style="margin-left: 9px;" class="btn btn-success">Envoyer</button> <!-- On ajoute l'image -->
                            </div>
                        </div>
                    </div>
                    <div class="progress">
                        <!-- Barre de progression -->
                        <div class="bar progress-bar" id="bar" role="progressbar" aria-valuemin="0" aria-valuenow="0" aria-valuemax="100"></div>
                        <div class="percent" id="percent">0%</div>
                    </div>
                    <div id="status">Ajoutez une image</div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        // Fonction pour l'upload et la barre de progression
        var bar = $('#bar');
        var percent = $('#percent');
        var status = $('#status');

        $('#formImage').ajaxForm({
            beforeSend: function() {
                // Avant l'envoi, on met la barre à zéro
                // status.empty();
                status.text("Ajoutez une image");
                var percentVal = '0%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            uploadProgress: function(event, position, total, percentComplete) {
                // Progression de la barre
                var percentVal = percentComplete + '%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            complete: function(xhr) {
                // Fin de l'upload
                status.text("Fichier upload");
                // nom_dossier = annee + '/' + mois + '/' + jour + "/" + heure + 'h' + minute + '/' + 'images' + '/' + nom_fichier;

                // fichier = $('#inputGroupFile01').prop('files')[0];
                var images = $("#images")[0].files; // On récupére la liste des fichiers
                var imageResponses = [];

                for (var i = 0; i < images.length; i++) {
                    var image = images[i];

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

                    imageNom = image.name;
                    hash = Math.random().toString(36).substring(2); // Le hash servira à générer un nom de fichier qui ne soit pas le même
                    extension_image = imageNom.substr(imageNom.lastIndexOf('.') + 1, imageNom.length);

                    tailleImage = $("[name='tailleImage']:checked").val();

                    var nom_fichier = jour + "_" + mois + "_" + annee + "_" + heure + "h" + minute + "m" + seconde + "_"

                    if (i != 0) {
                        nom_fichier += i + 1 + "_";
                    }
                    nom_fichier += hash + "." + extension_image; // Le nom du fichier
                    var data = new FormData();
                    data.append('images', image);
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
                    data.append('nom_image', nom_fichier);
                    data.append('num_image', i + 1);
                    data.append('dispositionImage', $("[name='dispositionImage']:checked").val());
                    data.append('tailleImage', tailleImage);
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
                        url: "/upload_image_traitement_premier.php",
                        error: function() {
                            alert('Erreur');
                        },
                        success: function(data) {
                            var reponse = JSON.parse(data);
                            var imageElement = '<div class="col-3"><img src="/images/' + reponse.nom_image + '" onerror="this.onerror=null; this.src=\'/1.jpg\';" name="imagePrevisualisation' + reponse.num_image + '" id="imagePrevisualisation' + reponse.num_image + '" class="img-fluid img-thumbnail form-control" style="height: 7.1em; width: 10em;"></div>';

                            // Puisque les images ne sont pas traités dans l'ordre, on retrie l'ordre des images
                            imageResponses.push({
                                order: reponse.num_image,
                                imageElement: imageElement
                            });

                            if (imageResponses.length === images.length) {
                                imageResponses.sort(function(a, b) {
                                    return a.order - b.order;
                                });

                                var divPrevisualisationImage = $('<div class="form-group row" id="divPrevisualisationImage"></div>');
                                var label = '<label id="labelPrevisualisationImage" for="imagePrevisualisation"><h5>Prévisualisation</h5></label>';

                                // On remet dans l'ordre
                                for (var j = 0; j < imageResponses.length; j++) {
                                    divPrevisualisationImage.append(imageResponses[j].imageElement);
                                }

                                $('#status').after(divPrevisualisationImage);
                                $('#status').after(label); // On met le label avant la div de prévisualisation des images

                                popupChangementDePage();
                            }
                        }
                    });
                    ajoutClickBBcodeFormulaire('[image2=' + $('input[name="dispositionImage"]:checked').val() + ',' + tailleImage + ']' + nom_fichier + '[/image2]', '', nom_contenu); // Ajoute les balises et l'alignement

                    $('#divPrevisualisationImage').remove(); // Si on recharge l'image, on supprime l'ancienne
                    $('#labelPrevisualisationImage').remove();
                }

                // nom_dossier = "/images/" + nom_dossier;
                // ('#btn').trigger('hide'); // Ferme le modal
            },
        });
    });
</script>