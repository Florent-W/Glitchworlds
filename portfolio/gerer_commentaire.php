<?php
include('Header.php');

if(isset($_SESSION['pseudo'])) { // Si on est connecter, on peut modifier un commentaire
if (isset($_GET['action']) and isset($_GET['url']) and isset($_GET['id_news']) and isset($_GET['id_commentaire'])) { // Si tous les paramètres sont là pour modifier un commentaire
    $action = $_GET['action'];
    $url = $_GET['url'];
    $id_news = $_GET['id_news'];
    $id_commentaire = $_GET['id_commentaire'];
    $type_commentaire = $_GET['type_commentaire'];

    switch ($_GET['action']) { // Verification action
        case ("supprimer_commentaire"):
            $reponse = $bdd->prepare('DELETE FROM ' . $type_commentaire . ' WHERE id = :id_commentaire');
            $reponse->execute(array('id_commentaire' => $id_commentaire));
            $reponse->closeCursor();

        case ("modifier_commentaire"):
            if (isset($_POST['modifier_commentaire'])) { // Verification qu'on a bien modifié le commentaire
                $reponse = $bdd->prepare('UPDATE ' . $type_commentaire . ' SET contenu = :contenu WHERE id = :id_commentaire');
                $reponse->execute(array('contenu' => $_POST['modifier_commentaire'], 'id_commentaire' => $id_commentaire));
                $reponse->closeCursor();
            }
    }
?>
   <?php if ($type_commentaire == "commentaire") { ?>
    <script>
          document.location.href = '/news/<?php echo $url; ?>-<?php echo $id_news; ?>'; // Redirection vers la news
    </script>
    <?php }
    else if($type_commentaire == "commentaire_jeu") { ?>
        <script>
          document.location.href = '/jeu/<?php echo $url; ?>-<?php echo $id_news; ?>'; // Redirection vers la news
    </script>
    <?php
    }
    ?>
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