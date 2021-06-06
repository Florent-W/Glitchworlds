<!-- Fenetre pour confirmer supprimer jeu -->
<div class="modal fade" id="modalConfirmationSupprimerJeu" tabindex="-1" role="dialog" aria-labelledby="modalLabelConfirmationSupprimerJeu" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer Jeu</h5>
            </div>
            <div class="modal-body">
                <form class="form" method="get" action="/portfolio/gerer_jeu.php">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" id="nom" value="<?php echo $donnees['nom']; ?>" disabled class="form-control">
                    </div>
                    <div class="form-group">
                        <div>Voulez-vous vraiment supprimer le jeu ?</div>
                        <input type="hidden" name="id" value="<?php echo $donnees['id']; ?>"> <!-- On passe l'id du jeu -->
                        <input type="hidden" name="action" value="supprimer_jeu"> <!-- On passe l'action à réaliser -->
                        <button type="submit" class="btn btn-danger">Oui</button> <!-- On confirme la suppression du jeu -->
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