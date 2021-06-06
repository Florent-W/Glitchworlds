<?php
include_once('connexion_base_donnee.php');
include_once('fonctions_php.php');
include_once('fonctions_javascript.php');

$reponse = $bdd->prepare('SELECT jeu.contenu FROM jeu WHERE jeu.id = :id'); // Récupération du jeu
$reponse->execute(array('id' => $_POST['id']));
$donnees = $reponse->fetch();
/*
<script type="application/ld+json">
{
  "@context" : "http://schema.org",
  "@type" : "SoftwareApplication",
  "name" : "Sonic Robo Blast 2",
  "image" : "https://glitchworlds.com/Jeux/Sonic%20Robo%20Blast%202/miniature/cover_sonicroboblast2.PNG",
  "author" : {
    "@type" : "Person",
    "name" : "Sonic Team Junior"
  },
  "datePublished" : "2019-12-07",
  "downloadUrl" : "https://www.srb2.org/download/",
  "screenshot" : [ "https://glitchworlds.com/jeu/sonic-robo-blast-2-119", "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==", "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==", "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==", "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==", "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==", "https://glitchworlds.com/jeu/sonic-robo-blast-2-119", "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==", "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" ]
}
</script>
*/
?>
<script>
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
  $("#lightGallery").lightGallery({
    addClass: 'fixed-size',
    counter: true,
    startClass: '',
  });
</script>
<?php
if ($_POST['presentation'] == "section") { ?><script>
     activer_son_video_background = '<?php echo $_POST['activer_son_video_background']; ?>'; // On regarde si on a activé le son pour les vidéos
    // Chargement du script des sections si le plugin n'est pas déjà initialisé
    if (!$('html').hasClass('fp-enabled')) {
      fullPageJeu(activer_son_video_background);
    }
  </script>
<?php
}
?>
</script>
<?php if ($_POST['presentation'] == "section") {
?> <div class="container container-bordure bg-white">
    <!-- On met un fond si c'est une section -->
  <?php } ?>
  <div>
    <p class="d-flex justify-content-center"><em>Sortie le <?php echo htmlspecialchars($_POST['date_jeu']); ?></em></p>
  </div>
  <img src="/Jeux/<?php echo $_POST['url']; ?>/miniature/<?php echo htmlspecialchars($_POST['nom_miniature']); ?>" onerror="this.oneerror=null; this.src='/banniere.jpg';" id="imageJeu" class="d-block img-fluid mx-auto" style="margin:1vh">
  <!-- <img src="/miniature/<?php echo $donnees['nom_miniature'] ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="d-block img-fluid" style="width:800vh; height:50vh; margin:1vh"> -->

  <?php if ($_POST['presentation'] == "section") {
  ?>
  </div>
<?php } ?>

<?php if ($_POST['presentation'] == "section") { // Si c'est une section, on charge les sections 
?>
  <?php echo remplacementBBCode(nl2br(htmlspecialchars($donnees['contenu'])), true, false); ?>
  <script>
    var countSection = 0;
    $('.section').each(function() { // On renomme chaque player pour pas qu'ils aient le même id
      countSection++;
      $(this).children('.player').prop('id', 'bgndVideo' + countSection);

      var countSlide = 0;

      $(this).children('.slide').each(function() { // On parcours chaque slide de section
        countSlide++;
        $(this).children('.player').prop('id', 'bgndVideo' + countSection + '_' + countSlide);
      });
    })
  </script>
<?php
} else { ?>
  <p class="justify-content-center text-break text-justify">
  <div class="contenu-jeu"><?php echo remplacementBBCode(nl2br(htmlspecialchars($donnees['contenu'])), true, false); ?></div>
  </p>
<?php  } ?>
<?php $reponse->closeCursor();
?>


<?php if (isset($_POST['nom_photo_profil'])) {
?>
  <hr> <!-- Trait -->

  <!-- Auteur de la présentation -->
  <div class="col-md-7 cadre" style="display: flex; align-items: center;">
    <div class="col-md-6">
      <img src="/utilisateurs/<?php echo htmlspecialchars($_POST['utilisateurs_id']); ?>/photo_profil/<?php echo htmlspecialchars($_POST['nom_photo_profil']); ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="float-left img-fluid img-thumbnail" style="height: 20vh; width: 15vh;"> <!-- Image à gauche et si image non trouvée, elle est remplacée par une image par défaut, titre à droite -->
    </div>
    <div class="text-center col-md-4">
      Ecrit par <em id="auteurPresentation"><?php echo htmlspecialchars($_POST['pseudo']); ?></em></div>
  </div>
<?php
}
?>
<hr>

<div class="row">
<?php // Page précédente et suivante
$reponse = $bdd->prepare('SELECT jeu.id, jeu.url, jeu.nom_miniature, jeu.nom FROM jeu WHERE id < :id AND jeu.approuver = "approuver" ORDER BY id DESC LIMIT 1'); // Récupération de la news précédente
$reponse->execute(array('id' => $_POST['id']));
$nbPagePrecedente = $reponse->rowCount();
$donnees = $reponse->fetch();

$pagePrecedente = "/jeu" . "/" . $donnees['url'] . '-' . $donnees['id'];

if ($nbPagePrecedente > 0) { // On affiche l'article précédent si il y en a un
?>
<div class='col float-left text-left' style="margin-left: 1.5%;">
<div class="row justify-content-start">
<?php echo "<a href=" . $pagePrecedente . ">"; ?><img src="/Jeux/<?php echo $donnees['url']; ?>/miniature/<?php echo $donnees['nom_miniature']; ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-fluid img-news img-thumbnail" style="float:left; height: 200px; background-color:transparent;"></a>
</div>
<div class="row justify-content-start">< Jeu Précédent</div>
<div class="row justify-content-start">
<?php
echo "<a href=" . $pagePrecedente . ">" . $donnees['nom'] . "</a>";
?>
</div>
</div>
<?php  } 
$reponse->closeCursor();

$reponse = $bdd->prepare('SELECT jeu.id, jeu.url, jeu.nom_miniature, jeu.nom FROM jeu WHERE id > :id AND jeu.approuver = "approuver" ORDER BY id ASC LIMIT 1'); // Récupération de la news suivante
$reponse->execute(array('id' => $_POST['id']));
$nbPageSuivante = $reponse->rowCount();
$donnees = $reponse->fetch();

$pageSuivante = "/jeu" . "/" . $donnees['url'] . '-' . $donnees['id'];

if ($nbPageSuivante > 0) {
    ?>
    <div class='col float-right text-right' style="margin-right: 1.5%;">
    <div class="row justify-content-end">
   <?php echo "<a href=" . $pageSuivante . " >"; ?><img src="/Jeux/<?php echo $donnees['url']; ?>/miniature/<?php echo $donnees['nom_miniature']; ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="img-fluid img-news img-thumbnail" style="float:left; height: 200px; background-color:transparent;"></a>
    </div>
    <div class="row justify-content-end">Jeu Suivant ></div>
    <div class="row justify-content-end">
    <?php
        echo "<a href=" . $pageSuivante . " >" . $donnees['nom'] . "</a>";
?>             
    </div>       
</div>
<?php }
$reponse->closeCursor();
?>
</div>

<?php
// Variable pour le json
$categorie_jeu = $_POST['categorie_jeu_nom'];
$plateformes = $_POST['plateformes'];
?>
<!-- Balisage JSON-LD généré par l'outil d'aide au balisage de données structurées de Google -->
<script>
  var json_balisage = document.createElement('script');
  json_balisage.type = 'application/ld+json';
  json_balisage.text = JSON.stringify({
    "@context": "http://schema.org",
    "@type": "SoftwareApplication",
    "name": $('#titreJeu').text(),
    "image": $('#imageJeu').attr("src"),
    "applicationCategory": <?php echo json_encode($categorie_jeu); ?>,
    "operatingSystem": <?php echo json_encode($plateformes); ?>
  });
  document.querySelector('body').appendChild(json_balisage);
</script>

<script>
  // alert($('#imageJeu').attr("src"));
</script>