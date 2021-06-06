<?php
session_start(); // Lance variable de session
$url = htmlspecialchars($_GET['url']);
$id = htmlspecialchars($_GET['id']);

if (isset($_GET['onglet_jeu'])) {
    $onglet_jeu = htmlspecialchars($_GET['onglet_jeu']);
} else {
    $onglet_jeu = "";
}

include_once('connexion_base_donnee.php');
$reponse = $bdd->prepare('SELECT jeu.*, categorie_jeu.nom AS categorie_jeu_nom, DATE_FORMAT(date_sortie, "%d %M %Y") AS date_jeu, utilisateurs.id AS utilisateurs_id, utilisateurs.pseudo, utilisateurs.nom_photo_profil FROM jeu LEFT JOIN utilisateurs ON jeu.id_auteur_presentation = utilisateurs.id LEFT JOIN categorie_jeu ON jeu.id_categorie = categorie_jeu.id WHERE jeu.id = :id'); // Récupération du jeu
$reponse->execute(array('id' => $id));
$donnees = $reponse->fetch();

$reponse2 = $bdd->prepare('SELECT utilisateurs.activer_video_background, utilisateurs.activer_son_video_background FROM utilisateurs WHERE utilisateurs.pseudo = :pseudo AND utilisateurs.id = :id'); // Récupération de l'option de musique d'utilisateur
$reponse2->execute(array('pseudo' => $_SESSION['pseudo'], 'id' => $_SESSION['id']));
$donnees2 = $reponse2->fetch();
$activer_video_background = $donnees2['activer_video_background'];
$activer_son_video_background = $donnees2['activer_son_video_background'];
$reponse2->closeCursor();

if ($onglet_jeu == "") {
    $title = $donnees['nom'];
} else {
    $title = ucfirst($onglet_jeu) . " de " . $donnees['nom']; // Premier caractère en majuscule
}

include_once('fonctions_php.php');

if (($donnees['description']) != "") {
    $meta_description = $donnees['description'];
} else {
    $meta_description = tronquerTexte(remplacementBBCode($donnees['contenu'], false, true), 150, "");
}

include('Header.php');

$nom_jeu = $donnees['nom'];
?>
</script>

<?php
if ($donnees['presentation'] == "section") { ?><script>
        // Chargement du script des sections
        // fullPageJeu(true);
        // fullPage(activer_son_video_background);
    </script>
<?php
}
?>
<?php
if ($donnees['presentation'] != "section") { // On charge le bon background si ce n'est pas des sections 
?>

<body style="<?php if(!isset($_SESSION['nom_image_background'])) { echo "background-image: url('/background.jpg');"; } else { echo "background-image: url('/utilisateurs/" . $_SESSION['id'] . "/background_site/" . $_SESSION['nom_image_background'] . "');"; } ?> background-repeat: no-repeat; background-attachment: fixed; background-size: cover; background-position: center center; overflow-x: hidden;">
<?php
                            } else {
                                ?>

        <body class="bg-white"><?php
                            }
                                ?>
        <?php $video_background = $donnees['video_background'];
        if ($video_background != "") { ?>
            <div id="bgndVideo" class="player" data-property="{videoURL:'<?php echo $donnees['video_background']; ?>',containment:'body',autoPlay:<?php if ($activer_video_background == "false") {
                                                                                                                                                        echo 0;
                                                                                                                                                    } else {
                                                                                                                                                        echo 1;
                                                                                                                                                    } ?>, useOnMobile: false, mute:<?php if ($activer_son_video_background == "true") {
                                                                                                                                                                                    echo 0;
                                                                                                                                                                                } else {
                                                                                                                                                                                    echo 1;
                                                                                                                                                                                } ?>, opacity:0.8, startAt: 30, showControls: false, stopMovieOnBlur: false, remember_last_time: true, addRaster: true, optimizeDisplay: true, showYTLogo: false, ratio: 'auto'}"></div>
        <?php } ?>
        <!-- Affichage jeu -->
        <?php
        if ($donnees['presentation'] == "conteneur") { // Si on a choisit comme type de présentation un conteneur
        ?>
            <div class="container container-bordure bg-white">
            <?php                                             } else if ($donnees['presentation'] == "section") {  ?><div style="text-align: center;" id="menuPlacement">
                </div>
                <div id="fullpage">
                    <div class="section container fp-auto-height container-bordure bg-white" name='sectionPresentationDebut' id='<?php echo $donnees['nom']; ?>'>
                    <?php
                } else {
                    ?> <div class="bg-white"><?php
                                                                                } ?>
                        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> <!-- Publicité -->
                        <ins class="adsbygoogle" style="display:block; text-align:center; margin-top: 10px;" data-ad-layout="in-article" data-ad-format="horizontal" data-ad-client="ca-pub-1179428382006278" data-full-width-responsive="false" data-ad-slot="4494542924"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                        <div class="d-flex justify-content-center">
                            <img src="/Jeux/<?php echo $donnees['url']; ?>/bandeaux/<?php echo htmlspecialchars($donnees['nom_banniere']); ?>" onerror="this.oneerror=null; this.src='/banniere.jpg';" class="d-block img-fluid" style="width: 100%; height:auto; max-height: 500px; margin-bottom:1vh; margin-top:1vh; border: 3px solid;">
                        </div>
                        <h1 class="d-flex justify-content-center" id="titreJeu" style="font-size: 1.8em;"><?php echo htmlspecialchars($donnees['nom']); ?></h1>
                        <hr>
                        <div class="row d-flex justify-content-center">
                            <script>
                                onglet_jeu = "<?php echo $_GET['onglet_jeu']; ?>"; // Initialisation de la variable onglet jeu
                                type_presentation = '<?php echo $donnees['presentation']; ?>';
                            </script>

                            <button class="btn btn-outline-primary col-xs-4 btn_jeu" id="btn_presentation" onclick="chargerJeuOngletPresentation(type_presentation);" style="margin-right: 5px;">Présentation</button> <!-- Lien vers présentation du jeu -->
                            <button href="/jeu/<?php echo $url; ?>-<?php echo $id; ?>/avis" class="btn btn-outline-warning col-xs-3 btn_jeu" id="btn_avis" onclick="chargerJeuOngletNews('avis', type_presentation);" style="margin-right: 5px;">Avis</button> <!-- Lien vers avis -->
                            <button href="/jeu/<?php echo $url; ?>-<?php echo $id; ?>/news" class="btn btn-outline-info col-xs-3 btn_jeu" id="btn_news" onclick="chargerJeuOngletNews('news', type_presentation);" style="margin-right: 5px;">News</button> <!-- Lien vers articles du jeu -->
                            <button href="/jeu/<?php echo $url; ?>-<?php echo $id; ?>/glitchs" class="btn btn-outline-secondary col-xs-3 btn_jeu" id="btn_glitchs" onclick="chargerJeuOngletNews('glitchs', type_presentation);">Glitchs</button> <!-- Lien vers glitch -->
                            <button href="/jeu/<?php echo $url; ?>-<?php echo $id; ?>/mods" class="btn btn-outline-dark col-xs-3 btn_jeu" id="btn_mods" onclick="chargerJeuOngletNews('mods', type_presentation);" style="margin-left: 5px;">Mods</button> <!-- Lien vers mods -->
                            <button href="/jeu/<?php echo $url; ?>-<?php echo $id; ?>/tutoriels" class="btn btn-outline-success col-xs-3 btn_jeu" id="btn_tutoriels" onclick="chargerJeuOngletNews('tutoriels', type_presentation);" style="margin-left: 5px;">Tutoriels</button> <!-- Lien vers tutoriels -->
                        </div>
                        <script>
                            if (onglet_jeu == "") { // On met actif le bouton d'onglet
                                $('#btn_presentation').addClass('active');
                            } else {
                                $('#btn_' + onglet_jeu).addClass('active');
                            }
                        </script>
                        <hr>
                        <?php if ($donnees['presentation'] == "section") {
                        ?>
                        </div><!-- On ferme la première section --><?php
                                                                }  ?>

                    <div id="jeu_onglet"></div>
                    <script>
                        // Fonction qui va charger l'onglet présentation d'un jeu
                        function chargerJeuOngletPresentation(type_presentation) {
                            if (onglet_jeu != "") { // Si l'onglet précédent n'est pas celui de présentation, on peut activer l'onglet selectionné et chargé la présentation
                                $('#btn_' + onglet_jeu).removeClass('active');

                                if(type_presentation == 'section') {                                                       
    
                                    $('div[name=sectionPresentationDebut]').addClass('section'); // On ajoute bien la classe de section pour les articles car on est dans une section
                                    $('div[name=sectionPresentationFin]').addClass('section'); // On ajoute bien la classe de section pour les articles car on est dans une section
                                }
                                $('#btn_presentation').addClass('active');
                                $.ajax('/jeu_presentation.php', {
                                    data: { // On passe des valeurs
                                        date_jeu: '<?php echo $donnees['date_jeu']; ?>',
                                        url: '<?php echo $donnees['url']; ?>',
                                        nom_miniature: '<?php echo $donnees['nom_miniature']; ?>',
                                        id: '<?php echo $id; ?>',
                                        categorie_jeu_nom: '<?php echo $donnees['categorie_jeu_nom']; ?>',
                                        plateformes: '<?php echo $donnees['plateformes']; ?>',
                                        pseudo: '<?php echo $donnees['pseudo']; ?>',
                                        utilisateurs_id: '<?php echo $donnees['utilisateurs_id']; ?>',
                                        nom_photo_profil: '<?php echo $donnees['nom_photo_profil']; ?>',
                                       // activer_son_video_background: "<?php echo $activer_son_video_background; ?>",
                                        presentation: '<?php echo $donnees['presentation']; ?>'
                                    },
                                    type: 'POST',
                                    dataType: 'html',
                                }).done(function(response) { // Une fois que la page à été chargé, on la met
                                    $('#jeu_onglet').html(response);
                                    /*
                                    $('.lazy').Lazy({
                                        scrollDirection: 'vertical',
                                        effect: "fadeIn",
                                        effectTime: 500,
                                        threshold: 0,
                                        visibleOnly: true,
                                        combined: true,
                                        delay: 5000,
                                        onError: function(element) {
                                            console.log('error loading ' + element.data('src'));
                                        },
                                    }); */
                                });
                                history.pushState('', document.title, '/jeu/<?php echo $url; ?>-<?php echo $id; ?>'); // Change l'url
                                onglet_jeu = '';
                            } /*
                            if (!($('html').hasClass('fp-enabled'))) {
                                    fullPageJeu(true); // On regarde si le plugin n'est pas activé avant de le refaire
                                } */
                        }

                        // Fonction qui va charger l'onglet selectionné d'un jeu, paramètre l'onglet
                        function chargerJeuOngletNews(onglet_suivant, type_presentation) {
                            if (onglet_suivant != onglet_jeu) { // Si l'onglet précédent n'est pas celui cliqué, on peut activer l'onglet selectionné et chargé l'onglet
                                if (onglet_jeu == "") {
                                    $('#btn_presentation').removeClass('active');

                                    if(type_presentation == 'section') {
                                    $('div[name=sectionPresentationDebut]').removeClass('section'); // On enlève bien la classe de section pour les articles car on est plus dans un type de présentation
                                    $('#fullpage').prepend($('div[name=sectionPresentationDebut]')); // On déplace la section du début pour qu'elle soit bien placé au début et pas dans l'onglet jeu, essayé de penser à une façon d'optimiser la position de cette section sans la déplacer
                                    $('div[name=sectionPresentationFin]').removeClass('section'); // On enlève bien la classe de section pour les articles car on est plus dans un type de présentation
                                    $('#jeu_onglet').after($('div[name=sectionPresentationFin]')); // On déplace la section de fin pour qu'elle soit bien placé à la fin et pas dans l'onglet jeu, essayé de penser à une façon d'optimiser la position de cette section sans la déplacer
                                }
                                } else {
                                    $('#btn_' + onglet_jeu).removeClass('active');
                                }
                                $('#btn_' + onglet_suivant).addClass('active');
                                history.pushState(null, document.title, "/jeu/<?php echo $url; ?>-<?php echo $id; ?>/" + onglet_suivant);
                                // onglet_jeu = location.hash.substring(1);
                                onglet_jeu = onglet_suivant;
                                if ((onglet_suivant == 'news' || onglet_suivant == 'mods' || onglet_suivant == 'glitchs' || onglet_suivant == 'tutoriels')) {
                                    $('#jeu_onglet').load('/jeu_article.php?url=<?php echo $url; ?>&id=<?php echo $id . "&onglet_jeu="; ?>' + onglet_suivant, {
                                        nom_jeu: '<?php echo $nom_jeu; ?>',
                                        recherche: '<?php echo $_POST['recherche']; ?>'
                                    });
                                } else if (onglet_suivant == 'avis') {
                                    $('#jeu_onglet').load('/jeu_avis.php?url=<?php echo $url; ?>&id=<?php echo $id . "&onglet_jeu="; ?>' + onglet_suivant, {
                                        nom_jeu: '<?php echo $nom_jeu; ?>',
                                        tri_avis: '<?php if (!isset($_POST['tri_avis'])) { echo 'Recents'; } else { echo $_POST['tri_avis']; } ?>'
                                    });
                                }
                                    // Destruction des sections si le plugin est initialisé
                                if ($('html').hasClass('fp-enabled')) {
                                    $('#menu').remove();
                                    fullpage_api.destroy('all');
                                }
                            }
                        }
                        window.onpopstate = function(event) { // Sert pour après le push state, si on revient en arrière dans les pages pour recharger les pages
                            location.reload();
                        }
                    </script>
                    <?php
                    switch ($onglet_jeu) { // On inclut l'onglet sur lequel on est    
                        case "news":
                        case "glitchs":
                        case "mods":
                        case "tutoriels":

                    ?>
                            <script>
                                $('#jeu_onglet').load('/jeu_article.php?url=<?php echo $url; ?>&id=<?php echo $id . "&onglet_jeu=" . $onglet_jeu; if (isset($_GET['page'])) { echo "&page=" . $_GET['page']; } ?>', {
                                    nom_jeu: '<?php echo $nom_jeu; ?>',
                                    recherche: '<?php echo $_POST['recherche']; ?>'
                                });
                            </script>
                        <?php
                            // include('jeu_article.php');
                            break;
                        case "avis":
                        ?>
                            <script>
                                $('#jeu_onglet').load('/jeu_avis.php?url=<?php echo $url; ?>&id=<?php echo $id . "&onglet_jeu=" . $onglet_jeu; if (isset($_GET['aime_avis_id'])) { echo "&aime_avis_id=" . $_GET['aime_avis_id']; } ?>', {
                                    nom_jeu: '<?php echo $nom_jeu; ?>',
                                    tri_avis: '<?php if (!isset($_POST['tri_avis'])) { echo 'Recents';} else { echo $_POST['tri_avis']; }  ?>'
                                });
                            </script>
                        <?php
                            break;
                        default:
                        ?>
                            <script>
                                $('#jeu_onglet').load('/jeu_presentation.php', {
                                    date_jeu: '<?php echo $donnees['date_jeu']; ?>',
                                    url: '<?php echo $donnees['url']; ?>',
                                    nom_miniature: '<?php echo $donnees['nom_miniature']; ?>',
                                    id: '<?php echo $id; ?>',
                                    categorie_jeu_nom: '<?php echo $donnees['categorie_jeu_nom']; ?>',
                                    plateformes: '<?php echo $donnees['plateformes']; ?>',
                                    pseudo: '<?php echo $donnees['pseudo']; ?>',
                                    utilisateurs_id: '<?php echo $donnees['utilisateurs_id']; ?>',
                                    nom_photo_profil: '<?php echo $donnees['nom_photo_profil']; ?>',
                                    // activer_son_video_background: "<?php echo $activer_son_video_background; ?>",
                                    presentation: '<?php echo $donnees['presentation']; ?>'
                                });
                            </script>
                            <?php
                            if ($donnees['presentation'] == "section") { ?><script>
                                    // activer_son_video_background = "<?php echo $activer_son_video_background; ?>";
                                    // Chargement du script des sections
                                    // fullPageJeu(true);
                                    // fullPage(activer_son_video_background);
                                </script>
                            <?php
                            }
                            ?>
                    <?php
                            // include('jeu_presentation.php');
                            break;
                    } ?>
                    <?php
                    if ($donnees['presentation'] == "section") {
                    ?><div><?php // On rajoute la section des commentaires
                        if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier une news 
                        ?>
                                <div class="row text-right" style="position: fixed; left:92%; top:91%;">
                                    <div class="col">
                                        <form class="form" method="post" action="/modifier_jeu/<?php echo $url; ?>-<?php echo $id ?>">
                                            <button type="submit" id="modifier_jeu" class="btn btn-info" title="Modifier jeu">Modifier Jeu</button> <!-- Bouton modif -->
                                        </form>
                                    </div>
                                </div>
                            <?php
                        }
                            ?>
                        </div> <?php }
                                ?>

                    <?php
                    if (isset($_SESSION['pseudo']) && $_SESSION['statut'] == "Administrateur") { // Si le statut de l'utilisateur est administrateur, on lui autorise à modifier un jeu 
                    ?>
                        <hr>
                        <div class="row text-right" style="margin-bottom: 15px;">
                            <div class="col">
                                <form class="form" method="post" action="/modifier_jeu/<?php echo $url; ?>-<?php echo $id ?>">
                                    <button type="submit" id="modifier_jeu" class="btn btn-info" title="Modifier jeu">Modifier Jeu</button> <!-- Bouton modif -->
                                </form>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                    <?php
                    if ($donnees['presentation'] == "section") {
                    ?><div name="sectionPresentationFin" id="Commentaires" class="section container"><?php
                                    }
                                    $type_commentaire = "commentaire_jeu";
                                    include('liste_commentaire.php');

                                    if ($donnees['presentation'] == "section") {
                                        ?></div><?php
                                    } ?>
                    </div>

                    <?php if ($video_background != "") { // Video de background 
                    ?>
                        <button class="btn btn-primary fixed-bottom d-none d-md-block" id="btnUnmute" style="border-radius: 35px; width: 51px; height: 51px; left: 5%; bottom: 5%;" onclick="$('#bgndVideo').YTPToggleVolume()"><?php if ($activer_son_video_background == "true") { ?><i class="fas fa-volume-up"></i><?php } else { ?><i class="fas fa-volume-mute"></i><?php } ?></button>
                        <button class="btn btn-primary fixed-bottom d-none d-md-block" id="btnPlay" style="border-radius: 35px; width: 51px; height: 51px; left: 8%; bottom: 5%;" disabled onclick="if('<?php echo $activer_video_background; ?>' == 'true') { $('#bgndVideo').YTPPause() } else { $('#bgndVideo').YTPPlay(); }"><i class="fa fa-spinner fa-lg fa-spin"></i></button> <!-- On met la bonne fonction selon si l'utilisateur à choisi d'activer les videos de background -->
                        <button class="btn btn-primary fixed-bottom d-none d-md-block" id="btnPleinEcran" style="border-radius: 35px; width: 51px; height: 51px; left: 11%; bottom: 5%;" onclick="$('#bgndVideo').YTPFullscreen()"><i class="fas fa-expand"></i></button>

                </div>
                <script>
                    $('#btnPlay').click(function() { // Si on clique sur le bouton pour démarrer la vidéo, on regarde si la vidéo est prete et qu'on veut jouer la video ou faire la pause
                        if (video_ready == true) {
                            if (video_background_play == 0) {
                                $('#bgndVideo').YTPPlay();
                            } else {
                                $('#bgndVideo').YTPPause();
                            }
                        }
                    });

                    $('#bgndVideo').on("YTPUnmuted", function() { // Bouton volume, changement d'icone
                        $("#btnUnmute").html('<i class="fas fa-volume-up"></i>');
                    });
                    $('#bgndVideo').on("YTPMuted", function() {
                        $("#btnUnmute").html('<i class="fas fa-volume-mute"></i>');
                    });

                    $('#bgndVideo').on("YTPPlay", function() {
                        video_background_play = 1;
                        $("#btnPlay").html('<i class="fas fa-pause"></i>');

                    });
                    $('#bgndVideo').on("YTPPause", function() {
                        video_background_play = 0;
                        $("#btnPlay").html('<i class="fas fa-play"></i>');
                    });
                    $('#bgndVideo').on("YTPReady", function() { // Video prete
                        activer_video_background = '<?php echo $activer_video_background; ?>';
                        if (activer_video_background == "false") { // Quand la vidéo est prete, on regarde si l'utilisateur à bien choisi d'activer les vidéos et si non, on met le bon bouton
                            setTimeout(function() {
                                video_background_play = 0;
                                video_ready = true;
                                $("#btnPlay").html('<i class="fas fa-play"></i>');
                                $("#btnPlay").prop("disabled", false)
                            }, 300);
                        } else {
                            setTimeout(function() {
                                video_background_play = 1;
                                video_ready = true;
                                $("#btnPlay").html('<i class="fas fa-pause"></i>');
                                $("#btnPlay").prop("disabled", false)
                            }, 300);
                        }
                    });
                </script>
            <?php } ?>

            <button class="btn btn-primary fixed-bottom btn-haut-page" id="btnHautPage"><i class="fas fa-arrow-up"></i></button>
            <script>
                $("#btnHautPage").click(function() {
                    $("html, body").animate({
                        scrollTop: 0
                    }, 500);
                });
            </script>
            <?php
            $reponse = $bdd->prepare('SELECT jeu.id, jeu.url FROM jeu WHERE id < :id AND jeu.approuver = "approuver" ORDER BY id DESC LIMIT 1'); // Récupération de la news précédente
            $reponse->execute(array('id' => $id));
            $nbPagePrecedente = $reponse->rowCount();
            $donnees = $reponse->fetch();

            $pagePrecedente = "/jeu" . "/" . $donnees['url'] . '-' . $donnees['id'];
            $reponse->closeCursor();

            $reponse = $bdd->prepare('SELECT jeu.id, jeu.url FROM jeu WHERE id > :id AND jeu.approuver = "approuver" ORDER BY id ASC LIMIT 1'); // Récupération de la news suivante
            $reponse->execute(array('id' => $id));
            $nbPageSuivante = $reponse->rowCount();
            $donnees = $reponse->fetch();

            $pageSuivante = "/jeu" . "/" . $donnees['url'] . '-' . $donnees['id'];
            $reponse->closeCursor();
            ?>

            <script>
                pagePrecedente = '<?php echo $pagePrecedente; ?>';
                pageSuivante = '<?php echo $pageSuivante; ?>';
                nbPagePrecedente = '<?php echo $nbPagePrecedente; ?>';
                nbPageSuivante = '<?php echo $nbPageSuivante; ?>';

                changerPage(pagePrecedente, pageSuivante, nbPagePrecedente, nbPageSuivante);
            </script>

            <?php
            include('ajout_commentaire_traitement.php');
            ?>
            <?php
            include('ajout_aime_commentaire_traitement.php');
            ?>
            <?php
            include('footer.php');
            ?>

        </body>

        </html>