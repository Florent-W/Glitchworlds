<?php
include('Header.php');

if(isset($_SESSION['statut']) && $_SESSION['statut'] == "Administrateur") { // Si on est un administrateur, on peut supprimer
if (isset($_GET['action']) and isset($_GET['id'])) { // Si tous les paramètres sont là pour modifier un jeu
    $action = $_GET['action'];
    $id_jeu = $_GET['id'];

    switch ($_GET['action']) { // Verification action
        case ("supprimer_jeu"):
            $reponse = $bdd->prepare('DELETE FROM jeu WHERE id = :id_jeu');
            $reponse->execute(array('id_jeu' => $id_jeu));
            $reponse->closeCursor();
    }
?>
    <script>
        document.location.href = '/portfolio/index.php'; // Redirection vers l'index
    </script>
<?php
} else { // Si des paramètres manquent, on redirige vers l'index
    ?>
    <script>
        document.location.href = '/portfolio/index.php'; // Redirection vers l'index
    </script>
    <?php
}
?>
<?php }
?>
<script>
 document.location.href = '/portfolio/index.php'; // Redirection vers l'index
</script>
