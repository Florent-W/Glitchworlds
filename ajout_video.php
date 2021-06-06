<!-- Fenetre pour lien -->
<div class="modal fade" id="modalVideo" tabindex="-1" role="dialog" aria-labelledby="modalLabelVideo" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajout de vidéo</h5>
            </div>
            <div class="modal-body">
                <form class="form">
                    <div class="form-group">
                        <label for="lien">Lien</label>
                        <input type="text" name="lienvideo" id="lienvideo" required onchange="controleTexteInput(this, 'lienIndication', 'contenu')" class="form-control">
                        <label id="lienIndication" class="text-danger"></label> <!-- Indication nom, il sera indiqué si le texte n'a pas de caractère ou qu'il y a une erreur -->
                    </div>
                        <button type="button" name="btnInitialisationVideo" id="btnInitialisationVideo" class="btn btn-info">Prévisualisation</button> <!-- D'abord on initialise la vidéo -->
                    <button type="submit" style="display: none;" name="btnVideo" id="btnVideo" class="btn btn-success" data-dismiss="modal" onclick="ajoutClickBBcodeFormulaire('[video]' + $('#lienvideo').val() + '[/video]', '', nom_contenu)">Envoyer</button> <!-- On ajoute le lien et le texte -->
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>

    <script>
        $('#btnInitialisationVideo').click(function() { // Initialisation de l'interface de la video
            $('#divInitialisationVideo').remove(); // Si on recharge la vidéo, on supprime l'ancienne

            video = $('#lienvideo').val();
            video = video.replace(/https:\/\/www.youtube.com\/watch\?v=(.+)/g, '<div id="divInitialisationVideo"><iframe width=280 height=180 class="video-commentaire" name="video" id="video" frameborder=0 allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen src="https://www.youtube.com/embed/$1"></div>'); // On replace le lien par un iframe
            $('#btnInitialisationVideo').after(video); // On place l'iframe
            $('#btnVideo').show();
        });
    </script>