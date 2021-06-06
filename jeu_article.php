<?php if (session_id() == "") {
    session_start(); // Lance variable de session
}
?>
<script>
    autoCompletion("rechercheArticle", "Articles");
</script>
<form class="form-inline my-2 my-lg-0 justify-content-center" id="form_recherche" method="POST">
    <!-- Recherche -->
    <div class="form-group" style="margin: 5px;">
        <input class="form-control" type="search" onclick="chargerJeuOngletArticleRecherche()" value="<?php if (isset($_POST['recherche'])) echo htmlspecialchars($_POST['recherche']); ?>" placeholder="Rechercher" name="recherche" id="rechercheArticle" aria-label="Rechercher"> <!-- Recherche -->
    </div>
    <?php
    ?>
    <button class="btn btn-outline-success" style="margin: 5px;" type="submit">Rechercher</button>
</form>
<script>
// $('#form_recherche').attr('action', "/jeu/<?php echo htmlspecialchars($_GET['url']); ?>-<?php echo htmlspecialchars($_GET['id']); ?>/" + onglet_jeu); // On ajoute l'onglet avec le jquery
</script>

<?php
if (!isset($_POST['recherche'])) { // Si on arrive sur un jeu, on prend tous les articles
    $rechercheArticle = "";
} else {
    $rechercheArticle = $_POST['recherche'];
}

if (!isset($_GET['page'])) { // Si on arrive sur la liste des glitch, la page selectionnée par défaut est la une
    $pageSelectionner = 1;
} else {
    $pageSelectionner = $_GET['page'];
}

$nombreNewsParPage = 5;
$nombreGlitchParPage = 5;
$nombreModsParPage = 2;
$nombreTutorielsParPage = 5;
?>

<!-- articles -->
<?php if ($_GET['onglet_jeu'] == "news" || $_GET['onglet_jeu'] == "glitchs" || $_GET['onglet_jeu'] == "mods" || $_GET['onglet_jeu'] == "tutoriels") { // Si une categorie est selectionné, on affiche les articles
    $nom_jeu = $_POST['nom_jeu'];
    include_once('connexion_base_donnee.php');
    include_once('fonctions_php.php');
    include('liste_news_colonne.php');
}
?>