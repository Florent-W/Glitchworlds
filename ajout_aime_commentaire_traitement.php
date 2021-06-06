
    <?php
    if($type_commentaire == "commentaire") {
    if (isset($_GET['aime_commentaire_id']) and isset($_GET['url']) and isset($_SESSION['pseudo']) and isset($_GET['id'])) { // Traitement ajout aime commentaire
    ?>
        <?php
        $reponse = $bdd->prepare('INSERT INTO aime_commentaire(id_commentaire, pseudo_utilisateur_qui_aime) VALUES(:id_commentaire, :pseudo_utilisateur)'); // On ajoute l'utilisateur qui a aimé le commentaire
        $reponse->execute(array('id_commentaire' => $_GET['aime_commentaire_id'], 'pseudo_utilisateur' => $_SESSION['pseudo']));
        $reponse->closeCursor();
        ?>
        <script>
            document.location.href = '/news/<?php echo $_GET['url']; ?>-<?php echo $_GET['id']; ?>'; // Redirection nouvelle url
        </script>
    <?php
    }
}

else if($type_commentaire == "commentaire_jeu") {
if (isset($_GET['aime_commentaire_id']) and isset($_GET['url']) and isset($_SESSION['pseudo']) and isset($_GET['id'])) { // Traitement ajout aime commentaire
    ?>
        <?php
        $reponse = $bdd->prepare('INSERT INTO aime_commentaire_jeu(id_commentaire, pseudo_utilisateur_qui_aime) VALUES(:id_commentaire, :pseudo_utilisateur)'); // On ajoute l'utilisateur qui a aimé le commentaire
        $reponse->execute(array('id_commentaire' => $_GET['aime_commentaire_id'], 'pseudo_utilisateur' => $_SESSION['pseudo']));
        $reponse->closeCursor();
        ?>
        <script>
            document.location.href = '/jeu/<?php echo $_GET['url']; ?>-<?php echo $_GET['id']; ?>'; // Redirection nouvelle url
        </script>
    <?php
    }
}
    ?>