<?php
header('Content-type: application/json');

require_once('commun/BddJeuClass.php');

if(!empty($_POST['id_utilisateur'])) {
// Initialisation
$bddJeu = new BddJeuClass();

$idUtilisateur = $_POST['id_utilisateur'];
$idJeu = $_POST['id_jeu'];

$dejaFavori = $bddJeu->selectionJeuDejaFavori($idUtilisateur, $idJeu); // On regarde si le jeu est déjà favori et on prend la décision

if (!$dejaFavori) { // Si pas déjà favori 
    try {
        $insertionReussie = $bddJeu->ajouterFavoriJeu($idUtilisateur, $idJeu); // On ajoute un jeu en favori

        if ($insertionReussie) {
            $response = array('success' => true, 'message' => 'Le jeu a ete ajoute en favori avec succes' . $idUtilisateur, 'type' => 'ajout');
        } else {
            $response = array('success' => false, 'message' => 'Le jeu était deja en favori', 'type' => 'ajout');
        }
    } catch (Exception $e) {
        $response = array('success' => false, 'message' => 'Une erreur s\'est produite lors de l\'ajout en favori : ' . $e->getMessage(), 'type' => 'ajout');
    }
} else {
    try {
        $bddJeu->retirerFavoriJeu($idUtilisateur, $idJeu); // On retire le jeu

        $response = array('success' => true, 'message' => 'Le jeu a ete retire des favori', 'type' => 'retirer');
    } catch(Exception $e) {
        $response = array('success' => false, 'message' => 'Une erreur s\'est produite lors de la suppression en favori : ' . $e->getMessage(), 'type' => 'retirer');
    }
}
}

else { // On revoit une erreur si utilisateur non connecté
    $response = array('success' => false, 'message' => 'Utilisateur non connecte');
}
echo json_encode($response);
