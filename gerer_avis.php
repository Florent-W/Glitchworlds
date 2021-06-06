<?php
include('Header.php');

if (isset($_SESSION['pseudo'])) { // Si on est connecter, on peut modifier un avis
    if (isset($_GET['action']) and isset($_GET['url']) and isset($_GET['id_jeu']) and isset($_GET['id_avis'])) { // Si tous les paramètres sont là pour modifier un avis
        $action = $_GET['action'];
        $url = $_GET['url'];
        $id_jeu = $_GET['id_jeu'];
        $id_avis = $_GET['id_avis'];

        switch ($_GET['action']) { // Verification action
            case ("supprimer_avis"):
                $reponse = $bdd->prepare('DELETE FROM avis WHERE id = :id_avis');
                $reponse->execute(array('id_avis' => $id_avis));
                $reponse->closeCursor();

            case ("modifier_avis"):
                if (isset($_POST['modifier_avis']) && isset($_POST['note_modifier'])) { // Verification qu'on a bien modifié l'avis
                    $note = $_POST['note_modifier'];
                    $reponse = $bdd->prepare('UPDATE avis SET contenu = :contenu, note = :note WHERE id = :id_avis');
                    $reponse->execute(array('contenu' => $_POST['modifier_avis'], 'note' => $note, 'id_avis' => $id_avis));
                    $reponse->closeCursor();
                }
        }
?>
        <script>
            document.location.href = '/jeu/<?php echo $url; ?>-<?php echo $id_jeu; ?>/avis'; // Redirection vers le jeu
        </script>
    <?php
    } else { // Si des paramètres manquent, on redirige vers l'index
    ?>
        <script>
            document.location.href = '/index.php'; // Redirection vers l'index
        </script>
<?php
    }
} ?>
<script>
    document.location.href = '/index.php'; // Redirection vers l'index
</script>