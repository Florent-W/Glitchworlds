<?php
include('Header.php');
?>

<body>
    <div class="view carousel-view d-none d-lg-block"> <!-- style="background-image: url('https://images.unsplash.com/photo-1563625236407-04ec2b4539d0?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjExMDk0fQ&auto=format&fit=crop&w=1916&q=80'); background-repeat: no-repeat; background-size: cover; background-position: center center;"> -->
        <!-- <div class="mask rgba-gradient align-items-center"> -->
        <!-- background -->
        <div id="carousel" class="carousel slide " data-ride="carousel" data-interval="5000" style="width:100%;"> <!-- style="width:100%; object-fit: contain; height: 51vh; top:20px;"> -->
            <!-- Le carousel est présent sur les écrans grands et moyens -->
            <!-- carousel -->
            <ol class="carousel-indicators">
                <?php $reponse = $bdd->prepare("SELECT * FROM carousel ORDER BY page");
                $reponse->execute();
                while ($donnees = $reponse->fetch()) {
                    if ($donnees['page'] == "1") { ?>
                        <li data-target="#carousel" data-slide-to="0" class="active"></li> <!-- Pointillé pour choisir la page en bas -->
                    <?php
                    } else {
                        $page = $donnees['page'] - 1;
                    ?>
                        <li data-target="#carousel" data-slide-to="<?php echo $page; ?>"></li>
                    <?php
                    }
                    ?>

                <?php }
                $reponse->closeCursor();
                ?>
            </ol>

            <div class="carousel-inner" style="height:510px;">
                <?php $reponse = $bdd->prepare('SELECT * FROM article INNER JOIN carousel ON article.id = carousel.id_news ORDER BY page'); // Sélection des news du carousel
                $reponse->execute();
                while ($donnees = $reponse->fetch()) {

                    if ($donnees['page'] == "1") { // Seule la première page est active au début
                     ?>  
                        <div class="carousel-item active"> 
                        <?php } else { ?>
                            <div class="carousel-item">
                            <?php
                        } ?>
                            <!-- slider -->
                            <a href="news/<?php echo htmlspecialchars($donnees['url']); ?>-<?php echo htmlspecialchars($donnees['id']); ?>" style="text-decoration-color: black; text-decoration: none;">
                            <img src="bandeaux/<?php echo htmlspecialchars($donnees['nom_banniere']); ?>" onerror="this.oneerror=null; this.src='banniere.jpg';" class="img-fluid" style="max-height: 510px; height:auto; width: 100%;"> <!-- style="max-width: 230vh; min-height: 20vh; max-height: 43vh;"> --> <!-- object-fit : contain -->
                            <div class="carousel-caption d-none d-md-block">
                                    <h5><?php echo htmlspecialchars($donnees['titre']); ?></h5>
                                    <p><?php echo tronquerTexte(remplacementBBCode(htmlspecialchars($donnees['contenu']), false, true), 350, ""); ?></p>
                               
                            </div>
                            </div> </a>
                        <?php
                    }
                    $reponse->closeCursor();
                        ?>

                        </div>

                        <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                            <!-- Bouton précédent pour revenir au slider précédent -->
                            <span class="fa fa-chevron-left" aria-hidden="true" style="color: black; font-size: 3vh;"></span>
                            <span class="sr-only">Précédent</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                            <!-- Bouton suivant pour revenir au slider suivant -->
                            <span class="fa fa-chevron-right" aria-hidden="true" style="color: black; font-size: 3vh;"></span>
                            <span class="sr-only">Suivant</span>
                        </a>
            </div>
        </div>
    </div>

    <?php
    include('liste_news.php');
    ?>

    <?php
    include('footer.php');
    ?>
</body>


</html>