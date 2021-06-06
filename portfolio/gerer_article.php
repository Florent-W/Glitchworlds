<?php
include('Header.php');

if(isset($_SESSION['statut']) && $_SESSION['statut'] == "Administrateur") { // Si on est un administrateur, on peut supprimer
if (isset($_GET['action']) and isset($_GET['url']) and isset($_GET['id'])) { // Si tous les paramètres sont là pour modifier un article
    $action = $_GET['action'];
    $url = $_GET['url'];
    $id_article = $_GET['id'];

    switch ($_GET['action']) { // Verification action
        case ("supprimer_article"):
            $reponse = $bdd->prepare('DELETE FROM article WHERE id = :id_article');
            $reponse->execute(array('id_article' => $id_article));
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
}
?>
    <script>
      document.location.href = '/portfolio/index.php'; // Redirection vers l'index
    </script>