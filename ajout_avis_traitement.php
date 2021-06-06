<?php
if (session_id() == "") {
    session_start(); // Lance variable de session
}
include_once('connexion_base_donnee.php');
include_once('fonctions_php.php');
if(isset($_POST['ajout_avis'])) { // Si c'est un avis
    if (isset($_POST['ajout_avis']) and isset($_SESSION['pseudo']) and isset($_POST['id']) and isset($_POST['note'])) { // Traitement ajout avis
        $contenu= $_POST['ajout_avis'];

        $reponse = $bdd->prepare('SELECT avis.id FROM avis WHERE avis.id_jeu = :id_jeu AND avis.id_utilisateur = :id_utilisateur'); // On regarde si l'utilisateur à déjà posté un avis, si oui, on désactive le formulaire
        $reponse->execute(array('id_jeu' => $_POST['id'], 'id_utilisateur' => $_SESSION['id']));
        $nombreAvisUtilisateur = $reponse->rowCount();
        $reponse->closeCursor();

        if($nombreAvisUtilisateur == 0) {
        $reponse = $bdd->prepare('INSERT INTO avis (id_utilisateur, contenu, id_jeu, date_avis, note) VALUES (:id_utilisateur, :contenu, :id_jeu, :date_avis, :note)');
        $reponse->execute(array('id_utilisateur' => $_SESSION['id'], 'contenu' => $contenu, 'id_jeu' => $_POST['id'], 'date_avis' => date(("Y-m-d H:i:s")), 'note' => $_POST['note']));

        $reponse->closeCursor();
        }
    ?>
        <script>
            document.location.href = '/jeu/<?php echo $_POST['url']; ?>-<?php echo $_POST['id']; ?>/avis'; // Redirection nouvelle url
        </script>
    <?php
    }
}
    ?>