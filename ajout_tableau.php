<!-- Fenetre pour tableau -->
<div class="modal fade" id="modalTableau" tabindex="-1" role="dialog" aria-labelledby="modalLabelTableau" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Création de tableau</h5>
            </div>
            <div class="modal-body">
                <form name="formTableau" id="formTableau" class="form">
                    <div class="form-group">
                        <label for="nombreLigne">Nombre de ligne</label>
                        <input type="number" name="nombreLigne" id="nombreLigne" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nombreColonne">Nombre de colonne</label>
                        <input type="number" name="nombreColonne" id="nombreColonne" required class="form-control">
                    </div>
                    <button type="button" name="btnInitaliasationTableau" id="btnInitaliasationTableau" class="btn btn-info">Prévisualisation</button> <!-- D'abord on initialise le tableau avec le nombre de ligne et de colonne -->
                    <button type="submit" style="display: none;" name="btnTableau" id="btnTableau" class="btn btn-success" data-dismiss="modal">Envoyer</button>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#btnInitaliasationTableau').click(function() { // Initialisation de l'interface des tableaux

        $('table').remove();

        $table = '<table name="tableau" id="tableau" class="table table-striped table-bordered table-responsive table-hover">';
        header = '<thead class="thead-light"><tr>'; // Header
        $table = $table + header;

        premierinput = '<th scope="col"><input name="inputEntree" id="inputEntree" type="text"></th>'; // Element du tableau
        $table = $table + premierinput;

        for (i = 0; i < $('#nombreColonne').val(); i++) { // On ajoute le nombre de colonne pour l'header du tableau qui va servir à décrire les colonnes
                input = '<th scope="col"><input name="inputEntree" id="inputEntree" type="text"></th>'; // Element du tableau
                $table = $table + input;
            }
        finheader = '</thead>';
        $table = $table + finheader;
        body = '<tbody>';
        $table = $table + body;

        // On ajoute les colonnes du tableau à l'interface
        for (j = 0; j < $('#nombreLigne').val(); j++) {
            ligne = '<tr><th scope="row"><input name="inputEntree" id="inputEntree" type="text"></th>';
            $table = $table + ligne;

            for (i = 0; i < $('#nombreColonne').val(); i++) {
                input = '<td><input name="inputEntree" id="inputEntree" type="text"></td>'; // Element du tableau
                $table = $table + input;
            }

            finligne = '</tr>';
            $table = $table + finligne;
        }
        fintable = $('</thead></tbody></table>');
        $table = $table + finligne;
        $('#btnInitaliasationTableau').after($table); // Ajout du tableau dans le formulaire
        $('#btnTableau').show();
    });

    $('#btnTableau').click(function() { // Envoi du tableau
                $('input[id="inputEntree"]').each(function() { // Met à jour les input
                        $(this).attr('value', $(this).val());
                    });
                    ajoutClickBBcodeFormulaire(remplacerBaliseParBBCode($('#tableau').get(0).outerHTML), '', nom_contenu);
                });
</script>