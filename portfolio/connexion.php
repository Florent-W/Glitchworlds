<?php            
session_start();
$erreurConnexion = false;
if (!empty($_POST['nom']) and !empty($_POST['mdp'])) { // Contrôle formulare
    $pseudo = $_POST['nom'];
    $mdp = $_POST['mdp'];
    include('connexion_base_donnee.php');
    $reponse = $bdd->query('SELECT * FROM utilisateurs WHERE pseudo = "' . $pseudo . '"'); // Selection de l'utilisateur si il a rempli son pseudo et son mot de passe
    $donnees = $reponse->fetch();
    if ($donnees) { // Si l'utilisateur indiqué est trouvé et les identifiants sont corrects, on continue
        if (password_verify($_POST['mdp'], $donnees['mdp'])) { // Vérification du mot de passe
            $_SESSION['pseudo'] = $donnees['pseudo']; // Variable de session
            $_SESSION['id'] = $donnees['id'];
            $_SESSION['statut'] = $donnees['statut'];
            if (isset($_POST['saveLogin'])) { // Si l'utilisateur veut sauvegarder ses logins
                // Génére le token
                $token = random_bytes(16);
                // Converti en caractère
                $token = bin2hex($token);
                // Insertion dans la base de données
                $reponse = $bdd->prepare('UPDATE utilisateurs SET token = :token WHERE pseudo = :pseudo'); // Insertion utilisateur
                $reponse->execute(array('token' => $token, 'pseudo' => $donnees['pseudo']));
                setcookie('utilisateur', $token . '-' . $donnees['pseudo'], time() + 365 * 24 * 3600, null, null, false, true); // cooki
            }
?>
            <script>
                document.location.href = '/portfolio/index.php'; // Redirection vers l'accueil si la connexion a réussi
            </script>
<?php
        }
        else {
            $erreurConnexion = true;
        }
    } else {
        $erreurConnexion = true;
    }

    $reponse->closeCursor();
}
    $title = "Connexion";
    include('Header.php');

?>

<body class="background">
    <div class="container container-bordure animated fadeInRight bg-white">
        <div class="row">
            <form class="form" method="post" style="margin:50px">
                <h1>Connexion</h1>
                <hr> <!-- Trait -->
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" required value="<?php if (!empty($_POST['nom'])) echo $_POST['nom'] ?>" onchange="controleTexteInput(this, 'pseudoIndication', 'pseudo')" class="form-control"> <!-- On conserve les valeurs au cas où il y a une erreur dans l'envoi -->
                    <label id="pseudoIndication" class="text-danger"><?php if (isset($_POST['nom']) and empty($_POST['nom'])) echo "Veuillez choisir un pseudo" ?></label> <!-- Indication pseudo, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                </div>
                <div class="form-group">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" name="mdp" id="mdp" required value="<?php if (!empty($_POST['mdp'])) echo $_POST['mdp'] ?>" onchange="controleTexteInput(this, 'mdpIndication', 'mdp')" class="form-control">
                    <label id="mdpIndication" class="text-danger"><?php if (isset($_POST['mdp']) and empty($_POST['mdp'])) echo "Veuillez choisir un mot de passe" ?></label> <!-- Indication mot de passe, il sera indiqué si le texte n'a pas de caractère ou le formulaire a déjà été soumis mais qu'il y a une erreur -->
                </div>
                <div class="form-group form-check">
                    <?php if (isset($_POST['saveLogin'])) {
                    ?> <input type="checkbox" name="saveLogin" id="saveLogin" checked value="saveLogin" class="form-check-input">
                    <?php } else {
                    ?> <input type="checkbox" name="saveLogin" id="saveLogin" value="saveLogin" class="form-check-input">
                    <?php } ?>
                    <label for="saveLogin" class="form-check-label">Se souvenir de moi</label>
                </div>
                <button type="submit" class="btn btn-success">Envoyer</button>

                <?php if($erreurConnexion == true) { // Si il y a une erreur dans le formulaire, on affiche un message d'erreur
                     echo '<div class="alert alert-warning" role="alert" style="margin-top: 10px; margin-bottom: 10px;">Les identifiants ne correspondent pas !</div>';
                } ?>
                <hr>
                <div class="form-group">
                    <a href="/portfolio/inscription.php">S'inscrire</a>
                </div>
            </form>

        </div>
    </div>

    <?php
    include('footer.php');
    ?>
</body>



</html>