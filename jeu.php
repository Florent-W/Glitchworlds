<?php
include('Header.php');
?>

<?php
$id = $_GET['id'];

$reponse = $bdd->prepare('SELECT *, DATE_FORMAT(date_sortie, "%d %M %Y à %Hh%imin%ss") AS date_jeu FROM jeu WHERE id = :id'); // Récupération du jeu
$reponse->execute(array('id' => $id));
$donnees = $reponse->fetch();
?>

<!-- Affichage jeu -->
<div class="container">
    <h3 class="d-flex justify-content-center"><?php echo $donnees['nom']; ?></h3>
    <div>
        <p class="d-flex justify-content-center"><em>Publié le <?php echo $donnees['date_sortie']; ?></em></p>
    </div> <img src="/miniature/<?php echo $donnees['nom_miniature'] ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="d-block img-fluid" style="width:800vh; height:50vh; margin:1vh">
    <p class="d-flex justify-content-center text-break text-justify"><?php echo remplacementBBCode(nl2br($donnees['contenu'])); ?></p>
    <?php
    $reponse->closeCursor();
    ?>
    <ul class="list-group" style="top:100px">
        <?php $reponse = $bdd->prepare('SELECT article.id, article.titre, article.url, article.nom_miniature, article.contenu, DATE_FORMAT(date_sortie, "%d %M %Y à %Hh%imin%ss") AS date_glitch FROM article INNER JOIN jeu ON article.id_jeu = jeu.id WHERE article.nom_categorie = "Glitch" AND jeu.nom = :nom_jeu ORDER BY article.id DESC LIMIT 20'); // Sélection des glitchs et formatage de la date à partir de la page de jeu selectionnée
        $reponse->execute(array('nom_jeu' => $donnees['nom']));

        while ($donnees = $reponse->fetch()) {
        ?>

            <!-- Liste glitch -->
            <div class="list-group-item">
                <img src="/miniature/<?php echo $donnees['nom_miniature'] ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-thumbnail" style="float:left; height: 200px"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
                <div class="row">
                    <div class="col">
                        <a href="news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>" style="text-decoration-color: black">
                            <!-- L'url est composé à l'aide de l'url rewriting, de l'url marqué dans la base de données ainsi que de l'id -->
                            <h1 class="list-group-item-heading text-body"><?php echo $donnees['titre']; ?></h1> <!-- Nom du glitch -->
                        </a>
                    </div>
                    <div class="col">
                        <p class="list-group-item-text pull-right text-right lead"><?php echo $donnees['date_glitch']; ?></p> <!-- Date de l'écriture de l'article -->
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="list-group-item-text pull-right lead" style="word-wrap: break-word"><?php echo tronquerTexte($donnees['contenu'], 150, "news/" . $donnees['url'] . "-" . $donnees['id']) ?> </p> <!-- Contenu -->
                    </div>
                    <?php if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier l'article 
                    ?>
                        <div class="col">
                            <a href="modifier_news/<?php echo $donnees['url']; ?>-<?php echo $donnees['id']; ?>">
                                <p class="list-group-item-text pull-right text-right lead">Modifier</p> <!-- Modification de la page du glitch -->
                            </a>
                        </div>
                    <?php }
                    ?>
                </div>
            </div>
        <?php
        }
        $reponse->closeCursor();
        ?>
    </ul>
</div>
</body>

</html>