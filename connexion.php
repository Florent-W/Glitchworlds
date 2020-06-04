<?php
include('Header.php');
?>

<body>
    <div class="container">
        <div class="row">
            <form class="form" method="post" style="margin:50px">
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
                <button type="submit" class="btn btn-success">Envoyer</button>
            </form>

            <?php

            if (!empty($_POST['nom']) and !empty($_POST['mdp'])) {
                $pseudo = $_POST['nom'];
                $mdp = $_POST['mdp'];

                $reponse = $bdd->query('SELECT * FROM utilisateurs WHERE pseudo = "' . $pseudo . '"'); // Selection de l'utilisateur si il a rempli son pseudo et son mot de passe

                $donnees = $reponse->fetch();

                if ($donnees) { // Si l'utilisateur indiqué est trouvé et les identifiants sont corrects, on continue
                    if (password_verify($_POST['mdp'], $donnees['mdp'])) { // Vérification du mot de passe
                        $_SESSION['pseudo'] = $donnees['pseudo']; // Variable de session
                        $_SESSION['id'] = $donnees['id'];
                        $_SESSION['statut'] = $donnees['statut'];
            ?>
                        <script>
                            document.location.href = '/index.php'; // Redirection vers l'accueil si la connexion a réussi
                        </script>
            <?php
                    } else {
                        echo "Les identifiants ne correspondent pas !";
                    }
                } else {
                    echo "Les identifiants ne correspondent pas !";
                }

                $reponse->closeCursor();
            }
            ?>
        </div>


</body>

</html>