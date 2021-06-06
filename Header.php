<?php
if (session_id() == "") {
    session_start(); // Lance variable de session
}

if (!isset($_SESSION['pseudo']) and !isset($_SESSION['id']) and !isset($_SESSION['statut'])) { // Si pas de session, on regarde si il y a des cookies pour connexion
    if (isset($_COOKIE['utilisateur'])) { // Vérification cookie
        $premierePartie = substr($_COOKIE['utilisateur'], 0, 32);
        $deuxiemePartie = substr($_COOKIE['utilisateur'], 33, strlen($_COOKIE['utilisateur']));

        include_once('connexion_base_donnee.php');

        try {
            $reponse = $bdd->prepare('SELECT pseudo, id, statut, nom_image_background FROM utilisateurs WHERE pseudo = :pseudo AND token = :token'); // Selection de l'utilisateur pour vérifier si la partie du pseudo et le token sont corrects
            $reponse->execute(array('token' => $premierePartie, 'pseudo' => $deuxiemePartie));

            $donneesConnexion = $reponse->fetch();

            $membreTrouver = $reponse->rowCount();

            if ($membreTrouver > 0) { // Si les valeurs sont exactes, on connecte l'utilisateur

                $_SESSION['pseudo'] = $donneesConnexion['pseudo'];
                $_SESSION['id'] = $donneesConnexion['id'];
                $_SESSION['statut'] = $donneesConnexion['statut'];

                if(isset($donneesConnexion['nom_image_background'])) { // Si l'utilisateur à une image de background définie, on met son nom en variable de session
                    $_SESSION['nom_image_background'] = $donneesConnexion['nom_image_background'];
                }
            }

            $reponse->closeCursor();
        } catch (PDOException $e) {
            echo "Erreur de connexion";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1 minimum-scale=1">
    <meta name="google-site-verification" content="mnHR8i8D51UnSwXEeDMG0bEWx_Q61zmr3pk3vXd72Sc" />
    <link rel="stylesheet" href="/style.css">
    <link rel="icon" type="image/png" href="/icone.png">
    <link rel="stylesheet" href="/fullpage.css">
    <title><?php if (isset($title)) {
                echo $title . " - Glitchworlds";
            } else { ?>Glitchworlds | Le site des Glitchs et des Fan Games<?php } ?></title>

    <?php if (!isset($meta_description)) { ?>
        <meta name="description" content="Bienvenue sur Glitchworld ! Le site qui vous permettra de vous renseigner sur les différents Bugs, Glitchs, Fan games, Mods et Rom Hacks !" />
    <?php } else { ?>
        <meta name="description" content="<?php echo $meta_description; ?>" />
    <?php } ?>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha512-YUkaLm+KJ5lQXDBdqBqk7EVhJAdxRnVdT2vtCzwPHSweCzyMgYV/tgGF4/dCyqtCC2eCphz0lRQgatGVdfR0ww==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" integrity="sha512-yDlE7vpGDP7o2eftkCiPZ+yuUyEcaBwoJoIhdXv71KZWugFqEphIS3PU60lEkFaz8RxaVsMpSvQxMBaKVwA5xg==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" integrity="sha512-ARJR74swou2y0Q2V9k0GbzQ/5vJ2RBSoCWokg4zkfM29Fb3vZEQyv0iWBMW/yvKgyHSR/7D64pFMmU8nYmbRkg==" crossorigin="anonymous" />
    <script src="https://kit.fontawesome.com/008be5dab2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js" integrity="sha512-UXumZrZNiOwnTcZSHLOfcTs0aos2MzBWHXOHOuB0J/R44QB0dwY5JgfbvljXcklVf65Gc4El6RjZ+lnwd2az2g==" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.11/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.11/jquery.lazy.plugins.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.1/howler.min.js" integrity="sha512-L6Z/YtIPQ7eU3BProP34WGU5yIRk7tNHk7vaC2dB1Vy1atz6wl9mCkTPPZ2Rn1qPr+vY2mZ9odZLdGYuaBk7dQ==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mb.YTPlayer/3.3.9/jquery.mb.YTPlayer.min.js" integrity="sha512-rVFx7vXgVV8cmgG7RsZNQ68CNBZ7GL3xTYl6GAVgl3iQiSwtuDjTeE1GESgPSCwkEn/ijFJyslZ1uzbN3smwYg==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.1.0/fullpage.css" integrity="sha512-hGBKkjAVJUXoImyDezOKpzuY4LS1eTvJ4HTC/pbxn47x5zNzGA1vi3vFQhhOehWLTNHdn+2Yqh/IRNPw/8JF/A==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.1.0/fullpage.min.js" integrity="sha512-HqbDsHIJoZ36Csd7NMupWFxC7e7aX2qm213sX+hirN+yEx/eUNlZrTWPs1dUQDEW4fMVkerv1PfMohR1WdFFJQ==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.1.0/fullpage.extensions.min.js" integrity="sha512-2whoeiZFzA/g6YGH/nI7iXRQlMPYLECQYLFYKXxqigVzCQYAMvQ1v+oy8TYN5ccbyeyG55mjdMjI7CQSN25B0g==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.1.0/vendors/scrolloverflow.min.js" integrity="sha512-pYyQWhzi2lV+RM4GmaUA56VPL48oLVvsHmP9tuQ8MaZMDHomVEDjXXnfSVKXayy+wLclKPte0KbsuVoFImtE7w==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.mb.YTPlayer/3.3.9/css/jquery.mb.YTPlayer.min.css" integrity="sha512-+HWFHCZZfMe4XQRKS0bOzQ1r4+G2eknhMqP+FhFIkcmWPJlB4uFaIagSIRCKDOZI3IHc0t7z4+N/g2hIaO/JIw==" crossorigin="anonymous" />
    <script data-ad-client="ca-pub-1179428382006278" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- <script src="/jquery.typeText.min.js"></script> -->
    <script src="/ghost-typer.min.js"></script>
    <link rel="stylesheet" href="/dist/css/jquery.rating.css" />
    <script src="/dist/js/jquery.rating.js"></script>
    <link rel="stylesheet" href="/dist/css/lightgallery.min.css" />
    <script src="/dist/js/lightgallery-all.min.js"></script>
    <script src="/dist/js/jquery.easy-ticker.min.js"></script>
    <script>
        $('.selectpicker').selectpicker({
            iconBase: 'fa',
            tickIcon: 'fa-check'
        });
        $(document).ready(function() {
            page_actuel = '<?php echo $_SERVER['PHP_SELF']; ?>';
            if (page_actuel != "/jeu_presentation.php") {
                $("#lightGallery").lightGallery({
                    // width: '1400px',
                    // height: '900px',
                    addClass: 'fixed-size',
                    counter: true,
                    startClass: '',
                });
            }
        });
        $(function() {
            if (page_actuel != "/jeu_presentation.php") {
                $('.lazy').Lazy({
                    // your configuration goes here
                    scrollDirection: 'vertical',
                    effect: "fadeIn",
                    effectTime: 500,
                    // threshold: 0,
                    // visibleOnly: true,
                    // combined: true,
                    // delay: 5000,
                    onError: function(element) {
                        console.log('error loading ' + element.data('src'));
                    },
                });
            }
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip(); // Initialiser les tooltips
        })
        $(function() {
            $('.demo').easyTicker({
                direction: 'up',
                easing: 'swing',
                speed: 'slow',
                interval: 2000,

                // Hauteur
                height: '500px',

                // Le nombre d'élément visible
                // visible: 10,
                // Pause si la souris est dessus
                mousePause: 0,
                controls: {
                    up: '.btnDown', // On inverse car ça représente mieux
                    down: '.btnUp',
                    toggle: '',
                    playText: 'Play',
                    stopText: 'Stop',
                },

            });
        });
        $(function() {
            // if (page_actuel != "/news.php") {
            $(".player").YTPlayer(); // Si ce n'est pas par section, on charge directement le player
            // }
        });
        $(function() {
            $('.star-demo').rating({
                cancel: "Retirer la note",
                half: true, // Met les demi etoiles
                callback: function(value) {
                    if (value) {
                        $('#note_label').text(value);
                    } else {
                        $('#note_label').empty();
                    }
                }
            });
            $('.star-affichage').rating({ // Pour l'affichage des notes
                half: true, // Met les demi etoiles
                readOnly: true
            });
        });
        $(function() {
            AOS.init();
        });
    </script>
    <audio id="audio_bouton" muted="muted">
        <source src="/bouton_hover.wav">
    </audio>
    <?php  // if (session_id() == "") session_start(); // Lance une session si elle n'est pas ouverte
    ?>
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
        <!-- Barre de navigation -->
        <a class="navbar-brand" href="/">Glitchworld</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <!-- Bouton pour agrandir si l'appareil est petit -->
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <!-- Le menu avec liens -->
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/"><i class="fas fa-home" style="color: #D7DBDD;"></i> Accueil</a>
                </li>

                <div class="dropdown nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fas fa-newspaper" style="color: lightblue;"></i> Articles</a>
                    <div class="dropdown-menu">
                        <!-- Liste déroulante -->
                        <a class="dropdown-item" href="/">Liste des Articles</a>
                        <a class="dropdown-item" href="/articles/news">News</a>
                        <a class="dropdown-item" href="/articles/glitchs">Glitchs</a>
                        <a class="dropdown-item" href="/articles/tutoriels">Tutoriels</a>
                        <!-- <a class="dropdown-item" href="?nom_categorie=mods">Mods</a> -->
                    </div>
                </div>

                <div class="dropdown nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fas fa-gamepad" style="color: #58D68D;"></i> Jeux</a>
                    <div class="dropdown-menu">
                        <!-- Liste déroulante -->
                        <a class="dropdown-item" href="/liste/Jeux">Liste des jeux</a>
                        <a class="dropdown-item" href="/liste/Jeux/Officiels">Jeux officiels</a>
                        <a class="dropdown-item" href="/liste/Jeux/Rom+hacks">Rom hacks</a>
                        <a class="dropdown-item" href="/liste/Jeux/Fan+games">Fan games</a>
                    </div>
                </div>

                <div class="dropdown nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fas fa-edit" style="color: #F7DC6F"></i> Créer</a> <!-- Bouton pour activer la liste déroulante -->
                    <div class="dropdown-menu">
                        <!-- Liste déroulante -->
                        <a class="dropdown-item" href="/creation_news.php">Créer article</a>
                        <a class="dropdown-item" href="/creation_jeu.php">Créer jeu</a>
                    </div>
                </div>
            </ul>

            <ul class="navbar-nav">
                <div class="dropdown nav-item">
                    <?php if (isset($_SESSION['pseudo'])) { // Si le membre est connecté, le pseudo est affiché 
                    ?>
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['pseudo']; ?></a> <!-- Pseudo à coté de la barre de recherche -->
                        <div class="dropdown-menu">
                            <!-- Liste déroulante -->
                            <a class="dropdown-item" href="/modifier_profil.php">Modifier profil</a>
                            <a class="dropdown-item" href="/deconnexion.php">Se déconnecter</a>
                        </div>
                    <?php } else { // Si le membre n'est pas connecté, la page de connexion est proposée
                    ?>
                        <a class="nav-link" href="/connexion.php"><i class="fas fa-user"></i> Se connecter</a>
                    <?php }
                    ?>
                </div>
                <?php if (!isset($_SESSION['pseudo'])) {
                    ?>
                <li class="nav-item">
                    <a class="nav-link" href="/inscription.php"><i class="fas fa-user-friends"></i> S'inscrire</a> <!-- Page pour s'inscrire si l'utilisateur n'est pas connecté -->
                </li>   
                <?php }
                    ?>
            </ul>
            
            <form class="form-inline my-2 my-lg-0" method="get" action="/recherche.php">
                <input class="form-control mr-sm-2" type="search" name="recherche" id="recherche" placeholder="Rechercher">
                <button class="btn btn-outline-success btn-header my-2 my-sm-0" type="submit">Rechercher</button>
            </form>
        </div>
    </nav>

    <!-- <input type="button" id="playButton" value="Jouer Musique"> -->

    <script>
        $(function() {
            $(".colonne-liste").on("hover", function() {
                ion.sound.play("/song.mp3");
            });
        });
        /*
        var sound = new Howl({
            src: ['/song.mp3']
        });
        $(document).ready(function() {
            $('#playButton').click(function() {
                sound.stop();
                sound.play();
            });
        })
        */
    </script>


    <?php
    include_once('connexion_base_donnee.php');
    ?>

</head>

<?php
include_once('fonctions_php.php');
?>

<?php
include('fonctions_javascript.php');
?>
<script>
    autoCompletion("recherche", "Tous");
</script>