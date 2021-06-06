<!-- Fenetre pour confirmer supprimer article -->
<div class="modal fade" id="modalConfirmationSupprimerArticle" tabindex="-1" role="dialog" aria-labelledby="modalLabelConfirmationSupprimerArticle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer Article</h5>
            </div>
            <div class="modal-body">
                <form class="form" method="get" action="/portfolio/gerer_article.php">
                    <div class="form-group">
                        <label for="titre">Titre</label>
                        <input type="text" name="titre" id="titre" value="<?php echo $donnees['titre']; ?>" disabled class="form-control">
                    </div>
                    <div class="form-group">
                        <div>Voulez-vous vraiment supprimer l'article ?</div>
                        <input type="hidden" name="id" value="<?php echo $donnees['id']; ?>"> <!-- On passe l'id de l'article -->
                        <input type="hidden" name="url" value="<?php echo $donnees['url']; ?>"> <!-- On passe l'url -->
                        <input type="hidden" name="action" value="supprimer_article"> <!-- On passe l'action à réaliser -->
                        <button type="submit" class="btn btn-danger">Oui</button> <!-- On confirme la suppression de l'article -->
                        <button class="btn btn-success" data-dismiss="modal">Non</button> <!-- On ferme la fenetre -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>