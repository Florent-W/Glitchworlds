<?php
include('header.php');

if (isset($_GET['action']) and isset($_GET['url']) and isset($_GET['id_news']) and isset($_GET['id_commentaire'])) { // Si tous les paramètres sont là pour modifier un commentaire
    $action = $_GET['action'];
    $url = $_GET['url'];
    $id_news = $_GET['id_news'];
    $id_commentaire = $_GET['id_commentaire'];

    switch ($_GET['action']) { // Verification action
        case ("supprimer_commentaire"):
            $id_commentaire = $_GET['id_commentaire'];

            $reponse = $bdd->prepare('DELETE FROM commentaire WHERE id = :id_commentaire');
            $reponse->execute(array('id_commentaire' => $id_commentaire));
            $reponse->closeCursor();
    }
?>
    <script>
        document.location.href = '/news/<?php echo $url; ?>-<?php echo $id_news; ?>'; // Redirection vers la news
    </script>
<?php
} else { // Si des paramètres manquent, on redirige vers l'index
    ?>
    <script>
        document.location.href = '/index.php'; // Redirection vers l'index
    </script>
    <?php
}
