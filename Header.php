<?php session_start(); // Lance variable de session
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>Glitchworld</title>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/008be5dab2.js" crossorigin="anonymous"></script>

    <style type="text/css">
        .dropdown:hover .dropdown-menu {
            display: block;
            /* Permet d'ouvrir la liste déroulante juste en pointant un bouton */
            margin-top: 0;
            /* Corrige le problème d'espacement entre la liste et le bouton qui ferme la liste */
        }

        a.dropdown-item:hover {
            background: transparent;
            /* Rend le background transparent dans la liste déroulante quand un élément est pointé et met de la couleur sur le texte */
            color: #0F59E9;
        }

        a.nav-link {
            color: white !important;
            /* Change la couleur du texte des nav link */
        }

        .dropdown-menu {
            background-color: #343a40;
            /* Remplace la couleur de fond des listes déroulantes dans l'entête */
        }

        .form-inline input { /* Pour que le bouton recherche dans la barre de navigation soit à côté du input recherche */
            display: inline-block;
            width: 100px;
        }

        .dropdown-menu>.dropdown-item {
            background: transparent;
            /* Laisse le background par défaut des nav link */
            color: white;
            /* Change la couleur du texte des éléments de la liste déroulante */
        }

        .carousel .carousel-indicators li {
            background-color: #fff;
            background-color: rgba(70, 70, 70, 0.25);
            /* Changer la couleur des indicateurs des carousels */
        }

        .carousel .carousel-indicators .active {
            background-color: #444;
            /* Change la couleur des indicateurs des carousels actifs */
        }
    </style>

    <?php  // if (session_id() == "") session_start(); // Lance une session si elle n'est pas ouverte
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <!-- Barre de navigation -->
        <a class="navbar-brand" href="#">Glitchworld</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <!- Bouton pour agrandir si l'appareil est petit -->
                <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <!- Le menu avec liens -->
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Accueil</a>
                    </li>

                    <div class="dropdown nav-item">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown">Créer</a> <!-- Bouton pour activer la liste déroulante -->
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
                                <a class="dropdown-item" href="/deconnexion.php">Se déconnecter</a>
                            </div>
                        <?php } else { // Si le membre n'est pas connecté, la page de connexion est proposée
                        ?>
                            <a class="nav-link" href="/connexion.php">Se connecter</a>
                        <?php }
                        ?>
                    </div>
                </ul>

                <form class="form-inline my-2 my-lg-0" method="get" action="recherche.php">
                    <input class="form-control mr-sm-2" type="search" name="recherche" placeholder="Rechercher";>
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Rechercher</button>
                </form>
        </div>
    </nav>

    <?php
    try {
        // $bdd = new PDO('mysql:host=db5000315646.hosting-data.io;dbname=dbs308204;charset:utf8', 'dbu566316', '96pvBbDE8DUyw@N'); // Connexion à la base de données
        $bdd = new PDO('mysql:host=localhost:3308;dbname=glitchworld;charset:utf8', 'root', ''); // Connexion à la base de données
        $reponse = $bdd->prepare("SET lc_time_names = 'fr_FR'"); // Conversion date en français
        $reponse->execute();
        $reponse->fetch();
        $reponse->closeCursor();
    } catch (Exception $erreur) {
        die('erreur : ' . $erreur->getMessage());
    }
    ?>

</head>

<?php
include('fonctions_php.php');
?>

<script>
    // Fonction qui prend en paramètre un input et le nom de l'indication du message de l'input et va dire si il est rempli ou non via le javascript avec du texte et de la couleur
    function controleTexteInput(input, nomInputInfo, typeInput) {
        var inputInfo = document.getElementById(nomInputInfo);

        if (input.value.length == 0) { // Si le texte n'a pas été complété, on dit qu'il n'a pas été rempli
            // input.className = "form-control" + " bg-primary";

            if (typeInput == "titre") { // Selon le type de l'input, la phrase sera changée
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir un titre";
            } else if (typeInput == "contenu") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir un contenu";
            } else if (typeInput == "date") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir une date";
            } else if (typeInput == "categorie") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir une catégorie";
            } else if (typeInput == "miniature") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir un image";
            } else if (typeInput == "commentaire") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez écrire un commentaire";
            } else if (typeInput == "pseudo") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir un pseudo";
            } else if (typeInput == "mdp") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir un mot de passe";
            } else if (typeInput == "mail") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir un e-mail";
            } else if (typeInput == "choix_news") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir une news";
            } else if (typeInput == "choix_page") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir une page";
            }

        } else { // Sinon on affiche de la couleur pour indiquer que le texte à été rempli ainsi que du texte
            // input.className = "form-control" + " bg-success";

            if (typeInput == "titre") { // Selon le type de l'input, la phrase sera changée
                inputInfo.className = "text-success";
                inputInfo.innerText = "Le titre a été rempli";
            } else if (typeInput == "contenu") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "Le contenu a été rempli";
            } else if (typeInput == "date") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "Le date a été remplie";
            } else if (typeInput == "categorie") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "Le catégorie a été remplie";
            } else if (typeInput == "miniature") {
                inputInfo.innerText = input.files[0].name;
            } else if (typeInput == "commentaire") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "Le commentaire a été écrit";
            } else if (typeInput == "pseudo") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "Le pseudo a été rempli";
            } else if (typeInput == "mdp") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "Le mot de passe a été rempli";
            } else if (typeInput == "mail") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "L'adresse e-mail a été remplie";
            } else if (typeInput == "choix_news") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "La news a été choisie";
            } else if (typeInput == "choix_page") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "La page a été choisie";
            }
        }
    }

    // Fonction qui prend en paramètre les differentes balises et les rajoutes dans un formulaire (pour le bbcode)
    function ajoutClickBBcodeFormulaire(debutBalise, finBalise, idTextArea) {

        var textareaFormulaire = document.getElementById(idTextArea);

        // Position texte selectionner
        var startSelection = textareaFormulaire.value.substring(0, textareaFormulaire.selectionStart);
        var currentSelection = textareaFormulaire.value.substring(textareaFormulaire.selectionStart, textareaFormulaire.selectionEnd);
        var endSelection = textareaFormulaire.value.substring(textareaFormulaire.selectionEnd);

        textareaFormulaire.value = startSelection + debutBalise + currentSelection + finBalise + endSelection;
    }
</script>