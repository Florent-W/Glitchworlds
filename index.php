<?php
require_once('commun/BddCommunClass.php');
require_once('commun/TexteCommunClass.php');
require_once('vendor/mustache/mustache/src/Mustache/Autoloader.php');
Mustache_Autoloader::register();

// Initialisation
$bddCommun = new BddCommunClass();
$texteCommun = new TexteCommunClass();

$selectionCarouselPage = $bddCommun->selectionCarousel();
$selectionArticleCarousel = $bddCommun->selectionNewsCarousel();

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views'),
));
echo $m->render('index', $texteCommun);

// A refactorisé
include('Header.php');
include('index_view.php');
include('liste_news.php');
include('footer.php');

?>
</body>


</html>