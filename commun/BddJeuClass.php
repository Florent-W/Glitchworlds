<?php
require_once('BddCommunClass.php');

class BddJeuClass extends BddCommunClass {
        
    /**
     * selectionStatistiquesNoteJeu
     *
     * @param  mixed $idJeu
     * @return array $donnees
     */
    public function selectionStatistiquesNotesJeu($idJeu) {
        $reponse = $this->pdo->prepare('SELECT AVG(avis.note) AS moyenne_note, COUNT(avis.note) as nombre_note, COUNT(CASE WHEN avis.contenu != "" THEN 1 END) AS nombre_avis FROM avis LEFT JOIN jeu ON avis.id_jeu = jeu.id WHERE jeu.id = :id'); 
        $reponse->bindValue('id', $idJeu, PDO::PARAM_INT);
        $reponse->execute();
        $donnees = $reponse->fetch();

        return $donnees;
    }

    /**
     * Sélection du nombre d'articles par catégorie
     * 
     * @param  mixed $idJeu
     * @return array $donnees
     */
    public function selectionNombreArticleCategorie($idJeu) {
        $reponse = $this->pdo->prepare('SELECT COUNT(CASE WHEN article.nom_categorie = "News" THEN 1 END) as nb_news, COUNT(CASE WHEN article.nom_categorie = "Glitchs" THEN 1 END) AS nb_glitch, COUNT(CASE WHEN article.nom_categorie = "Mods" THEN 1 END) AS nb_mods, COUNT(CASE WHEN article.nom_categorie = "Tutoriels" THEN 1 END) AS nb_tutoriel FROM article_lier_jeu INNER JOIN jeu ON article_lier_jeu.id_jeu = jeu.id INNER JOIN article ON article_lier_jeu.id_article = article.id WHERE jeu.id = :id AND article.approuver = "Approuver"'); 
        $reponse->bindValue('id', $idJeu, PDO::PARAM_INT);
        $reponse->execute();
        $donnees = $reponse->fetch();

        return $donnees;
    }

    /**
     * Sélection de la liste de jeux
     * 
     * @param  mixed $idJeu
     * @return array $donnees
     */
    public function selectionListeJeux($nombreJeuParPage, $offsetPageJeu, $recherche, $tri, $genre = '', $plateforme = '', $categorieJeu = '', $ordreTri = 'DESC', $jeuApprouver = '', $onlyFavoris = '', $langue = '') {
        $sql = 'SELECT AVG(avis.note) AS moyenne_note, COUNT(DISTINCT(avis.id)) AS nombre_note, jeu.*, DATE_FORMAT(date_sortie, "%d %M %Y") AS date_jeu, COUNT(DISTINCT(jeu.id)) AS nb_jeu FROM jeu LEFT JOIN categorie_jeu ON jeu.id_categorie = categorie_jeu.id LEFT JOIN jeu_lier_plateformes ON jeu.id = jeu_lier_plateformes.id_jeu LEFT JOIN plateformes ON jeu_lier_plateformes.id_plateforme = plateformes.id LEFT JOIN avis ON jeu.id = avis.id_jeu LEFT JOIN jeu_lier_genres ON jeu.id = jeu_lier_genres.id_jeu LEFT JOIN genres ON jeu_lier_genres.id_genre = genres.id LEFT JOIN jeu_lier_langues ON jeu.id = jeu_lier_langues.id_jeu LEFT JOIN langues ON jeu_lier_langues.id_langue = langues.id LEFT JOIN favoris_jeu ON jeu.id = favoris_jeu.id_jeu WHERE jeu.nom LIKE :article';
        
        if(!empty($plateforme)) {
            $sql .= ' AND nom_plateforme = :nom_plateforme';
        }
        if(!empty($genre)) {
            $sql .= ' AND genre = :genre';
        }
        if(!empty($langue)) {
            $sql .= ' AND langue = :langue';
        }
        if(!empty($categorieJeu)) {
            $sql .= ' AND categorie_jeu.nom = :nom_categorie_jeu';
        }
        if(!empty($jeuApprouver)) {
            $sql .= ' AND jeu.approuver = :jeu_approuver';
        }
        if(!empty($onlyFavoris)) {
            $sql .= ' AND favoris_jeu.id_utilisateur = :id_utilisateur';
        }

        $sql .= ' GROUP BY jeu.id ORDER BY :tri ';
        
         // Préparation de la requête pour compter le nombre total de résultats
        $countSql = 'SELECT COUNT(*) FROM (' . $sql . ') AS subquery';
        $countReponse = $this->pdo->prepare($countSql);
        $countReponse->bindValue('article', '%' . $recherche, PDO::PARAM_STR);
        $countReponse->bindValue('tri', $tri, PDO::PARAM_INT);

        if(!empty($genre)) {
            $countReponse->bindValue('genre', $genre, PDO::PARAM_STR);   
        }
        if(!empty($plateforme)) {
            $countReponse->bindValue('nom_plateforme', $plateforme, PDO::PARAM_STR);
        }
        if(!empty($langue)) {
            $countReponse->bindValue('langue', $langue, PDO::PARAM_STR);
        }
        if(!empty($categorieJeu)) {
            $countReponse->bindValue('nom_categorie_jeu', $categorieJeu, PDO::PARAM_STR);
        }
        if(!empty($jeuApprouver)) {
            $countReponse->bindValue('jeu_approuver', $jeuApprouver, PDO::PARAM_STR);
        }
        if(!empty($onlyFavoris)) {
            $countReponse->bindValue('id_utilisateur', 57, PDO::PARAM_INT);
        }

        $countReponse->execute();

        // Exécution de la requête pour compter le nombre total de résultats
        $nombreResultats = (int) $countReponse->fetchColumn();

        $sql .= $ordreTri . ' LIMIT :nombreJeuParPage OFFSET :offsetPageJeu';

        $reponse = $this->pdo->prepare($sql); // Sélection des jeux et formatage de la date à partir de la page de jeu selectionnée
        $reponse->bindValue('nombreJeuParPage', $nombreJeuParPage, PDO::PARAM_INT);
        $reponse->bindValue('offsetPageJeu', $offsetPageJeu, PDO::PARAM_INT);
        $reponse->bindValue('article', '%' . $recherche, PDO::PARAM_STR);
        $reponse->bindValue('tri', $tri, PDO::PARAM_INT);

        if(!empty($genre)) {
            $reponse->bindValue('genre', $genre, PDO::PARAM_STR);   
        }
        if(!empty($plateforme)) {
            $reponse->bindValue('nom_plateforme', $plateforme, PDO::PARAM_STR);
        }
        if(!empty($langue)) {
            $reponse->bindValue('langue', $langue, PDO::PARAM_STR);
        }
        if(!empty($categorieJeu)) {
            $reponse->bindValue('nom_categorie_jeu', $categorieJeu, PDO::PARAM_STR);
        }
        if(!empty($jeuApprouver)) {
            $reponse->bindValue('jeu_approuver', $jeuApprouver, PDO::PARAM_STR);
        }
        if(!empty($onlyFavoris)) {
            $reponse->bindValue('id_utilisateur', '57', PDO::PARAM_INT);
        }
        $reponse->execute();
        $donnees = $reponse->fetchAll();

        return array('donnees' => $donnees, 'nb_jeu' => $nombreResultats);
    }

    /**
     * Ajouter un jeu en favori pour un utilisateur
     * 
     * @param  mixed $idUtilisateur
     * @param  mixed $idJeu
     * @return bool $donnees     
     */
    public function ajouterFavoriJeu($idUtilisateur, $idJeu) {
        $reponse = $this->pdo->prepare('INSERT IGNORE INTO favoris_jeu (id_utilisateur, id_jeu) VALUES (:id_utilisateur, :id_jeu)');
        $reponse->bindValue('id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $reponse->bindValue('id_jeu', $idJeu, PDO::PARAM_INT);
        $reponse->execute();

        if ($reponse->rowCount() > 0) {
            return true; // Une insertion a eu lieu
        } else {
            return false; // Aucune insertion n'a eu lieu (déjà en favori)
        }
    }

    /**
     * Retire un jeu des favoris de l'utilisateur
     * 
     * @param  mixed $idUtilisateur
     * @param  mixed $idJeu
     */
    public function retirerFavoriJeu($idUtilisateur, $idJeu) {
        $reponse = $this->pdo->prepare('DELETE FROM favoris_jeu WHERE id_utilisateur = :id_utilisateur AND id_jeu = :id_jeu');
        $reponse->bindValue('id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $reponse->bindValue('id_jeu', $idJeu, PDO::PARAM_INT);
        $reponse->execute();
    }

    /**
     * Voir si le jeu est en favori pour l'utilisateur
     * 
     * @param  mixed $idJeu
     * @return array $donnees
     */
    public function selectionJeuDejaFavori($idUtilisateur, $idJeu) {
        $reponse = $this->pdo->prepare('SELECT DISTINCT(favoris_jeu.id) FROM jeu INNER JOIN favoris_jeu WHERE favoris_jeu.id_utilisateur = :id_utilisateur AND favoris_jeu.id_jeu = :id_jeu'); 
        $reponse->bindValue('id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $reponse->bindValue('id_jeu', $idJeu, PDO::PARAM_INT);
        $reponse->execute();
        $donnees = $reponse->fetch();

        if($reponse->rowCount() > 0) { // On regarde si il y a bien un favori
            return true;
        } else {
            return false;
        }
    }
}