<?php
    if (isset($_GET['aime_avis_id']) and isset($_GET['url']) and isset($_SESSION['pseudo']) and isset($_GET['id'])) { // Traitement ajout aime avis
    ?>
        <?php
        $reponse = $bdd->prepare('INSERT INTO aime_avis(id_avis, pseudo_utilisateur_qui_aime) VALUES(:id_avis, :pseudo_utilisateur)'); // On ajoute l'utilisateur qui a aimé l'avis
        $reponse->execute(array('id_avis' => $_GET['aime_avis_id'], 'pseudo_utilisateur' => $_SESSION['pseudo']));
        $reponse->closeCursor();
        ?>
        <script>
            document.location.href = '/jeu/<?php echo $_GET['url']; ?>-<?php echo $_GET['id']; ?>/avis'; // Redirection nouvelle url
        </script>
    <?php
    }