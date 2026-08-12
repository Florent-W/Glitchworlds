<?php
require_once('commun/BddCommunClass.php');
require_once('commun/TexteCommunClass.php');

// Initialisation
$bddCommun = new BddCommunClass();
$texteCommun = new TexteCommunClass();

$selectionCarouselPage = $bddCommun->selectionCarousel();
$selectionArticleCarousel = $bddCommun->selectionNewsCarousel();
   
include('Header.php');
include('index_view.php');
// include('liste_news.php');
// include('footer.php');