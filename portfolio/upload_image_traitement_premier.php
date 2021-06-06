
    <?php
    include_once('connexion_base_donnee.php');
    
    $ancien_nom_image = $_POST['ancien_nom'];
    // $nom_image = $_FILES['images']['name'];
    $nom_image = $_POST['nom_image'];
    // $fichier = $_POST['file'];
    $extension_image = strtolower(pathinfo($nom_image, PATHINFO_EXTENSION));
    $date_actuel = $_POST['date_actuel'];
   // $dateActuel = date("Y-m-d H:i:s");
    // On récupère les variables
    $jour = $_POST['jour'];
    $mois = $_POST['mois'];
    $annee = $_POST['annee'];
    $heure = $_POST['heure'];
    $minute = $_POST['minute'];
    $seconde = $_POST['seconde'];
    $hash = $_POST['hash'];
    $extension_image = $_POST['extension_image'];

  //  move_uploaded_file($_FILES['images']['tmp_name'], $_FILES['images']['name'] . "." . strtolower(pathinfo($_FILES['images']['name'], PATHINFO_EXTENSION))); // Bouge l'image sans la redimensionner, il faudra faire en sorte qu'elle ne dépasse pas une taille
   // $jour = strftime("%d", strtotime($dateActuel));
  //  $mois = strftime("%B", strtotime($dateActuel));
   // $annee = strftime("%Y", strtotime($dateActuel));
   // $heure = strftime("%H", strtotime($dateActuel));
  //  $minute = strftime("%M", strtotime($dateActuel));
   // $seconde = strftime("%S", strtotime($dateActuel));

  /*  // Génére un hash qui servira pour le nom du fichier
    $hash_avant = random_bytes(8);
    // Converti en caractère
    $hash = bin2hex($hash_avant);
*/
    $tailleImage = getimagesize($_FILES['images']['tmp_name']); // Récupération taille de l'image uploadée
    $largeur = $tailleImage[0];
    $hauteur = $tailleImage[1];

    if ($_POST['tailleImage'] == "icone") { // Si l'utilisateur à choisi une taille d'image, on choisi parmi les tailles d'image disponible
        $largeur_miniature = 75;
        $hauteur_miniature = ($hauteur / $largeur * 300) / 4;
    } else if ($_POST['tailleImage'] == "petite") {
        $largeur_miniature = 300;
        $hauteur_miniature = $hauteur / $largeur * 300;
    } else if ($_POST['tailleImage'] == "moyenne") {
        $largeur_miniature = 600;
        $hauteur_miniature = ($hauteur / $largeur * 300) * 2;
    } else if ($_POST['tailleImage'] == "grande") {
        $largeur_miniature = 1200;
        $hauteur_miniature = ($hauteur / $largeur * 300) * 4;
    } else {
        $largeur_miniature = 300; // Largeur de la future miniature
        $hauteur_miniature = $hauteur / $largeur * 300;
    }

    $type_image = 'images'; // Recupère le nom de l'image (formulaire) pour indiquer quel type de fichier on va récupérer, miniature
    include('image_traitement.php');

    $reponse = $bdd->prepare('INSERT INTO image (ancien_nom_image, nom_image, mot_hash, extension_image, date_image) VALUES (:ancien_nom_image, :nom_image, :mot_hash, :extension_image, :date_image)'); // Insertion
    $reponse->execute(array('ancien_nom_image' => $ancien_nom_image, 'nom_image' => $nom_image, 'mot_hash' => $hash, 'extension_image' => $extension_image, 'date_image' => $date_actuel));
    $reponse->closeCursor();