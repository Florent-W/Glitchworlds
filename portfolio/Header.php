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
            $reponse = $bdd->prepare('SELECT pseudo, id, statut FROM utilisateurs WHERE pseudo = :pseudo AND token = :token'); // Selection de l'utilisateur pour vérifier si la partie du pseudo et le token sont corrects
            $reponse->execute(array('token' => $premierePartie, 'pseudo' => $deuxiemePartie));

            $donneesConnexion = $reponse->fetch();

            $membreTrouver = $reponse->rowCount();

            if ($membreTrouver > 0) { // Si les valeurs sont exactes, on connecte l'utilisateur

                $_SESSION['pseudo'] = $donneesConnexion['pseudo'];
                $_SESSION['id'] = $donneesConnexion['id'];
                $_SESSION['statut'] = $donneesConnexion['statut'];
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
    <title><?php if (isset($title)) {
                echo $title . " - Glitchworld";
            } else { ?>Glitchworld<?php } ?></title>
    <meta name="description" content="Bienvenue sur Glitchworld !" />
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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.11/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.11/jquery.lazy.plugins.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.1/howler.min.js" integrity="sha512-L6Z/YtIPQ7eU3BProP34WGU5yIRk7tNHk7vaC2dB1Vy1atz6wl9mCkTPPZ2Rn1qPr+vY2mZ9odZLdGYuaBk7dQ==" crossorigin="anonymous"></script>
    <!-- <script src="/jquery.typeText.min.js"></script> -->
    <script src="/ghost-typer.min.js"></script>
    <link rel="stylesheet" href="/dist/css/lightgallery.min.css" />
    <script src="/dist/js/lightgallery-all.min.js"></script>
    <script src="/dist/js/jquery.easy-ticker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.9.7/vendors/jquery.easings.min.js" integrity="sha512-rXZZDfRSa6rsBuT78nRTbh1ccwpXhTCspKUDqox3hUQNYdjB6KB6mSj6mXcB9/5F5wODAJnkztXPxzkTalp11w==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.9.7/vendors/scrolloverflow.min.js" integrity="sha512-FzESM/E7XJBqcJyrXa08gRcpp5rDHO661C0L3vH4NsZfUWUsjN4+t6Lg8h+e8TMR2aYijIrcT+CPGq7tSugRzA==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.9.7/jquery.fullpage.css" integrity="sha512-/AilQf/shuEGfh8c3DoIqcIqHZCKpiImSyt+fxIKJphHiNa6QMPb6AbDly6rkjmGr/5OZd35JtvVkbEKnCZO+A==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.9.7/jquery.fullpage.min.js" integrity="sha512-bxzECOBohzcTcWocMAlNDE2kYs0QgwGs4eD8TlAN2vfovq13kfDfp95sJSZrNpt0VMkpP93ZxLC/+WN/7Trw2g==" crossorigin="anonymous"></script>
    <script>
        $('.selectpicker').selectpicker({
            iconBase: 'fa',
            tickIcon: 'fa-check'
        });
        $(document).ready(function() {
            $("#lightGallery").lightGallery({
                // width: '1400px',
                // height: '900px',
                addClass: 'fixed-size',
                counter: true,
                startClass: '',
            });
        });
        $(function() {
            $('.lazy').Lazy({
                // your configuration goes here
                scrollDirection: 'vertical',
                effect: "fadeIn",
                effectTime: 2000,
                threshold: 0,
                visibleOnly: true,
                combined: true,
                delay: 5000,
                onError: function(element) {
                    console.log('error loading ' + element.data('src'));
                },
            });
        });
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
            $("#creation_news").submit(function()
                // Permet de récuperer chaque tag avec le formulaire
                {
                    var tags;
                    $('#lierJeux span').each(function() {
                        alert('b');
                        tags = tags + $(this).text();
                    });
                    alert(tags);
                });
        });
        $(function() {
            $('#fullpage').fullpage({

                // Whether anchors in the URL will have any effect at all in the library
                lockAnchors: false,

                // Enables navigation
                navigation: false,

                // Or 'left'
                navigationPosition: 'right',

                // Enables active tooltip
                showActiveTooltip: false,

                // Or 'top'
                slidesNavPosition: 'bottom',

                // Whether to use JavaScript or CSS3 transforms
                css3: true,

                // <a href="https://www.jqueryscript.net/tags.php?/Scroll/">Scroll</a>ing speed in ms
                scrollingSpeed: 700,

                // Enables auto scrolling
                autoScrolling: true,

                // Auto fits sections to the viewport
                fitToSection: true,

                // In ms
                fitToSectionDelay: 1000,

                // Shows browser scrollbar
                scrollBar: false,

                // Easing effect
                easing: 'easeInOutCubic',
                easingcss3: 'ease',

                // Auto scrolls to the top/bottom
                loopBottom: false,
                loopTop: false,

                // Enables infinite looping on horizontal sliders
                loopHorizontal: true,

                // Defines whether scrolling down in the last section or should scroll down to the first one and if scrolling up in the first section should scroll up to the last one. 
                continuousVertical: false,

                // Defines whether sliding right in the last slide should slide right to the first one or not, and if scrolling left in the first slide should slide left to the last one or not.
                continuousHorizontal: false,

                // Slides horizontally within sliders by using the mouse wheel or trackpad
                scrollHorizontally: false,

                // Moving one horizontal slider will force the sliding of sliders in other section in the same direction
                interlockedSlides: false,

                // Enables drag and move
                // true: enables the feature.
                // false: disables the feature.
                // vertical: enables the feature only vertically.
                // horizontal: enables the feature only horizontally.
                // fingersonly: enables the feature for touch devices only.
                // mouseonly: enables the feature for desktop devices only (mouse and trackpad).
                dragAndMove: false,

                // Use non full screen sections based on percentage
                offsetSections: false,

                // Uses fade effect instead
                fadingEffect: false,

                // If you want to avoid the auto scroll when scrolling over some elements, this is the option you need to use. (useful for <a href="https://www.jqueryscript.net/tags.php?/map/">map</a>s, scrolling divs etc.) 
                // It requires a string with the Javascript selectors for those elements. (For example: normalScrollElements: '#element1, .element2'). 
                normalScrollElements: '#element1, .element2',

                // Creates a scroll for the section/slide in case its content is bigger than the height of it
                // Requires: /vendor/scrolloverflow.js
                scrollOverflow: true,

                // Scrolls up the content of the section/slide with scroll bar when leaving to another vertical section
                scrollOverflowReset: false,

                // Requires iScroll.js library
                scrollOverflowOptions: null,

                // Defines a percentage of the browsers window width/height, and how far a swipe must measure for navigating to the next section / slide.
                touchSensitivity: 5,

                // Defines how to scroll to a section which size is bigger than the viewport.
                // top, bottom, null
                bigSectionsDestination: null,

                // Accessibility
                keyboardScrolling: true,

                // Enables smooth scroll on anchor links
                animateAnchor: true,

                // Records URL history
                recordHistory: true,

                // Shows navigation arrows
                controlArrows: true,

                // Vertically centered?
                verticalCentered: true,

                // Which elements will be taken off the scrolling structure of the plugin which is necessary when using the css3 option to keep them fixed
                fixedElements: '#header, .footer',

                // A normal scroll (autoScrolling:false) will be used under the defined width in pixels
                responsiveWidth: 0,

                // A normal scroll (autoScrolling:false) will be used under the defined height in pixels
                responsiveHeight: 0,

                // When set to true slides will be turned into vertical sections when responsive mode is fired
                responsiveSlides: false,

                // Enables <a href="https://www.jqueryscript.net/tags.php?/parallax/">parallax</a> backgrounds effects
                parallax: false,

                // Parallax options
                parallaxOptions: {
                    type: 'reveal',
                    percentage: 62,
                    property: 'translate'
                },

                // Enables card effects
                cards: false,

                // Card options
                cardsOptions: {
                    perspective: 100,
                    fadeContent: true,
                    fadeBackground: true
                },

                // Custom selectors
                sectionSelector: '.section',
                slideSelector: '.slide',

                // Lazy load media elements
                lazyLoading: true
            });
        });
    </script>
    <audio id="audio_bouton" muted="muted">
        <source src="/bouton_hover.wav">
    </audio>
    <?php  // if (session_id() == "") session_start(); // Lance une session si elle n'est pas ouverte
    ?>
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
        <!-- Barre de navigation -->
        <a class="navbar-brand" href="/portfolio">Portfolio <div style="font-size : 14px;">De Florent Weltmann</div>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <!-- Bouton pour agrandir si l'appareil est petit -->
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <!-- Le menu avec liens -->
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/portfolio"><i class="fas fa-home"></i> Accueil</a>
                </li>
                <div class="dropdown nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fas fa-desktop"></i> Applications</a> <!-- Bouton pour activer la liste déroulante -->
                    <div class="dropdown-menu">
                        <!-- Liste déroulante -->
                        <a class="nav-link" href="/portfolio/news/application-rpg-sous-windows-forms-en-c-sharp-117"><i class="fas fa-desktop"></i> RPG : Interface 2D (Windows Forms)</a>
                        <a class="nav-link" href="/portfolio/news/application-rpg-sous-unity-118"><i class="fas fa-desktop"></i> RPG : Open World 3D (Unity)</a>
                        <!-- <a class="dropdown-item" href="/portfolio/creation_jeu.php">Créer jeu</a> -->
                    </div>
                </div>
                <div class="dropdown nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fab fa-chrome"></i> Site Web</a> <!-- Bouton pour activer la liste déroulante -->
                    <div class="dropdown-menu">
                        <!-- Liste déroulante -->
                        <a class="nav-link" href="/portfolio/news/creation-du-site-internet-glitchworld-119"><i class="fab fa-chrome"></i> Glitchworld</a>
                        <!-- <a class="dropdown-item" href="/portfolio/creation_jeu.php">Créer jeu</a> -->
                    </div>
                </div>
                <div class="dropdown nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fas fa-laptop-house"></i> PPE</a> <!-- Bouton pour activer la liste déroulante -->
                    <div class="dropdown-menu">
                        <!-- Liste déroulante -->
                        <a class="nav-link" href="/portfolio/news/ppe-1-creation-d-un-site-internet-pour-le-laboratoire-galaxy-swiss-bourdin-105"><i class="fas fa-laptop-house"></i> PPE 1 : Site Web GSB</a>
                        <a class="nav-link" href="/portfolio/news/ppe-2-creation-d-un-service-windows-116"><i class="fas fa-laptop-house"></i> PPE 2 : Service Windows</a>
                        <!-- <a class="dropdown-item" href="/portfolio/creation_jeu.php">Créer jeu</a> -->
                    </div>
                </div>

                <!-- <div class="dropdown nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fas fa-gamepad"></i> Jeux</a>
                    <div class="dropdown-menu">
                        <!-- Liste déroulante -->
                <!--
                        <a class="dropdown-item" href="/portfolio/liste/Jeux">Liste des jeux</a>
                        <a class="dropdown-item" href="/portfolio/liste/Jeux/Officiel">Jeux officiels</a>
                        <a class="dropdown-item" href="/portfolio/liste/Jeux/Rom+hack">Rom hacks</a>
                        <a class="dropdown-item" href="/portfolio/liste/Jeux/Fan+game">Fan games</a>
                    </div>
                </div> -->

                <div class="dropdown nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="far fa-building"></i> Stage 1</a> <!-- Bouton pour activer la liste déroulante -->
                    <div class="dropdown-menu">
                        <!-- Liste déroulante -->
                        <a class="nav-link" href="/portfolio/news/stage-de-premiere-annee-121"><i class="far fa-building"></i> Résumé</a>
                        <a class="nav-link" href="/portfolio/news/stage-de-premiere-annee-situation-livraison-de-packages-122"><i class="far fa-building"></i> Situation Livraison Packages</a>
                        <a class="nav-link" href="/portfolio/news/stage-de-premiere-annee-situation-ecriture-de-requetes-sql-pour-assister-l-utilisateur-123"><i class="far fa-building"></i> Situation Ecriture SQL</a>
                        <a class="nav-link" href="/portfolio/news/stage-de-premiere-annee-situation-corrections-et-modifications-de-requetes-sql-124"><i class="far fa-building"></i> Situation Modification SQL</a>
                        <a class="nav-link" href="/portfolio/news/stage-de-premiere-annee-situation-corrections-de-boutons-sur-l-ecran-de-registre-des-amortissements-dans-l-intranet-de-l-erp-peoplesoft-125"><i class="far fa-building"></i> Situation Corrections Ecran Amortissement dans PeopleSoft</a>
                        <!-- <a class="dropdown-item" href="/portfolio/creation_jeu.php">Créer jeu</a> -->
                    </div>
                </div>

                <div class="dropdown nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="far fa-building"></i> Stage 2</a> <!-- Bouton pour activer la liste déroulante -->
                    <div class="dropdown-menu">
                        <!-- Liste déroulante -->
                        <a class="nav-link" href="/portfolio/news/stage-de-deuxieme-annee-126"><i class="far fa-building"></i> Résumé</a>
                        <a class="nav-link" href="/portfolio/news/stage-de-deuxieme-annee-situation-livraison-de-packages-127"><i class="far fa-building"></i> Situation Livraison Packages</a>
                        <a class="nav-link" href="/portfolio/news/stage-de-deuxieme-annee-situation-resolution-d-un-probleme-de-coherence-sur-les-comptes-de-repartitions-des-factures-dans-l-erp-peoplesoft-128"><i class="far fa-building"></i> Situation Résolution Problème de cohérence des factures dans l'ERP PeopleSoft</a>
                        <a class="nav-link" href="/portfolio/news/stage-de-deuxieme-annee-situation-verificafition-de-l-exigibilite-de-la-tva-saisie-sur-les-factures-billing-129"><i class="far fa-building"></i> Situation Vérificafition Exigibilité TVA saisie sur les factures billing</a>
                        <a class="nav-link" href="/portfolio/news/stage-de-deuxieme-annee-prise-en-compte-des-tickets-d-incidents-analyse-et-corrections-130"><i class="far fa-building"></i> Autres Situations</a>
                        <!-- <a class="dropdown-item" href="/portfolio/creation_jeu.php">Créer jeu</a> -->
                    </div>
                </div>

                <li class="nav-item">
                    <a class="nav-link" href="/portfolio/news/cv-de-florent-weltmann-120"><i class="fas fa-book-open"></i> CV</a>
                </li>
                <div class="dropdown nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fas fa-edit"></i> Créer</a> <!-- Bouton pour activer la liste déroulante -->
                    <div class="dropdown-menu">
                        <!-- Liste déroulante -->
                        <a class="dropdown-item" href="/portfolio/creation_news.php">Créer article</a>
                        <!-- <a class="dropdown-item" href="/portfolio/creation_jeu.php">Créer jeu</a> -->
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
                            <a class="dropdown-item" href="/portfolio/modifier_profil.php">Modifier profil</a>
                            <a class="dropdown-item" href="/portfolio/deconnexion.php">Se déconnecter</a>
                        </div>
                    <?php } else { // Si le membre n'est pas connecté, la page de connexion est proposée
                    ?>
                        <a class="nav-link" href="/portfolio/connexion.php">Se connecter</a>
                    <?php }
                    ?>
                </div>
            </ul>
            <form class="form-inline my-2 my-lg-0" method="get" action="recherche.php">
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
include('fonctions_php.php');
?>

<?php
include('fonctions_javascript.php');
?>
<script>
    autoCompletion("recherche", "Tous");
</script>