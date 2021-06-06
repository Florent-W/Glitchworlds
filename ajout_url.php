<!-- Fenetre pour lien -->
<div class="modal fade" id="modalUrl" tabindex="-1" role="dialog" aria-labelledby="modalLabelUrl" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Création de lien</h5>
            </div>
            <div class="modal-body">
                <form class="form">
                    <div class="form-group">
                        <label for="lien">Lien</label>
                        <input type="text" name="lien" id="lien" required onchange="controleTexteInput(this, 'lienIndication', 'contenu')" class="form-control">
                        <label id="lienIndication" class="text-danger"></label> <!-- Indication nom, il sera indiqué si le texte n'a pas de caractère ou qu'il y a une erreur -->
                    </div>
                    <div class="form-group">
                        <label for="nom">Texte</label>
                        <input type="text" name="texte" id="texte" required onchange="controleTexteInput(this, 'texteIndication', 'contenu')" class="form-control">
                        <label id="texteIndication" class="text-danger"></label> <!-- Indication nom, il sera indiqué si le texte n'a pas de caractère ou qu'il y a une erreur -->
                    </div>
                    <button type="submit" name="formUrl" class="btn btn-success" data-dismiss="modal" onclick="ajoutClickBBcodeFormulaire('[lien]' + $('#lien').val() + '[/lien]' + '[texteLien]' + $('#texte').val() + '[/texteLien]', '', nom_contenu)">Envoyer</button> <!-- On ajoute le lien et le texte -->
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>