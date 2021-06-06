<?php
// Prend en paramètre une chaine de caractère et permet de lui enlever les accents et les caractères spéciaux pour que le titre soit dans l'url
function EncodageTitreEnUrl($string)
{
    return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
}

// Prend en paramètre un texte et un nombre de caractère maximum et renvoi le texte couper du premier caractère jusqu'à à la lettre correspondant au dernier caractère autorisé
function tronquerTexte($texte, $nombreCaractereMax, $lien)
{
    $nombreCaractereTexte = strlen($texte); // Nombre de caractère dans le texte

    if ($nombreCaractereTexte > $nombreCaractereMax) { // Si le nombre de caractère dans le texte est supérieur au nombre de caractère max, on affiche lire la suite
        $texte = substr($texte, 0, $nombreCaractereMax);
        $positionEspace = strrpos($texte, " "); // On cherche la position du dernier mot pour ne pas que l'article se coupe en plein mot
        $texte = substr($texte, 0, $positionEspace); // On enlève le dernier mot       
        if ($lien != "") { // Si le lien n'est pas vide, surtout pour le carousel
            return $texte . "...<p>[Lire la suite]</p>";
        } else {
            return $texte; // Texte sans lire
        }
        // return $texte . ' <a href="'. $lien . '">[Lire la suite]</a>';
    } else {
        return $texte;
    }
}

// Prend en paramètre une url d'une vidéo youtube et permet de récupérer l'id de la vidéo
function recupererIdVideo($url)
{

    $partieUrl = explode("?v=", $url); // Prendre à partir du v=

    $nouvelleUrl = "https://www.youtube.com/embed/" . $partieUrl[1]; // Nouvelle url avec le lien permettant d'intégrer le lecteur

    return $nouvelleUrl;
}

// Retire les accents d'une chaîne de caractère
function retirerAccent($str)
{
    $url = $str;
    $url = preg_replace('#Ç#', 'C', $url);
    $url = preg_replace('#ç#', 'c', $url);
    $url = preg_replace('#è|é|ê|ë#', 'e', $url);
    $url = preg_replace('#È|É|Ê|Ë#', 'E', $url);
    $url = preg_replace('#à|á|â|ã|ä|å#', 'a', $url);
    $url = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $url);
    $url = preg_replace('#ì|í|î|ï#', 'i', $url);
    $url = preg_replace('#Ì|Í|Î|Ï#', 'I', $url);
    $url = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $url);
    $url = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $url);
    $url = preg_replace('#ù|ú|û|ü#', 'u', $url);
    $url = preg_replace('#Ù|Ú|Û|Ü#', 'U', $url);
    $url = preg_replace('#ý|ÿ#', 'y', $url);
    $url = preg_replace('#Ý#', 'Y', $url);

    return ($url);
}

// Permet de redimensionner la hauteur et la largeur d'une image
function redimensionImage($largeur, $hauteur, $tailleLargeurAutoriser, $tailleHauteurAutoriser)
{
    while ($largeur > $tailleLargeurAutoriser) { // Tant que la largeur est au dessus de la taille autorisé, on redimensionne
        $largeur = $largeur / 1.5;
    }
    while ($hauteur > $tailleHauteurAutoriser) {
        $hauteur = $hauteur / 1.5;
    }
}

// Remplace les caractères du BBCode par des balises html avec ou sans lien pour ne pas buguer les articles, ou juste supprimer les balises
function remplacementBBCode($contenu, bool $avecLien, bool $supprimerBalise)
{
    // italique
    if ($supprimerBalise == false) {
        $contenu = str_replace("[i]", "<em>", $contenu);
        $contenu = str_replace("[/i]", "</em>", $contenu);

        // gras
        $contenu = str_replace("[b]", "<strong>", $contenu);
        $contenu = str_replace("[/b]", "</strong>", $contenu);

        // souligne
        $contenu = str_replace("[u]", "<u>", $contenu);
        $contenu = str_replace("[/u]", "</u>", $contenu);

        // citation
        $contenu = str_replace("[citation]", "<blockquote class=\"blockquote\">", $contenu);
        $contenu = str_replace("[/citation]", "</blockquote>", $contenu);

        // texte centré
        $contenu = str_replace("[center]", "<div style=\"text-align: center;\">", $contenu);
        $contenu = str_replace("[/center]", "</div>", $contenu);

        // texte à gauche
        $contenu = str_replace("[gauche]", "<div style=\"text-align: left;\">", $contenu);
        $contenu = str_replace("[/gauche]", "</div>", $contenu);

        // texte à droite
        $contenu = str_replace("[droite]", "<div style=\"text-align: right;\">", $contenu);
        $contenu = str_replace("[/droite]", "</div>", $contenu);

        // alignement flottant à gauche
        $contenu = preg_replace('#\[gaucheFlottant](.*?)\[/gaucheFlottant]#', "<div style='text-align: left;'>$1</div>", $contenu); // Recherche pour enlever la balise

        // alignement flottant à droite
        $contenu = preg_replace('#\[droiteFlottant](.*?)\[/droiteFlottant]#', "<div style='text-align: right;'>$1</div>", $contenu); // Recherche pour enlever la balise

        // liste
        $contenu = str_replace("[liste]", "<ul style=\"list-style-position: inside;\">", $contenu);
        $contenu = str_replace("[/liste]", "</ul>", $contenu);

        // élément de liste
        $contenu = str_replace("[elementliste]", "<li>", $contenu);
        $contenu = str_replace("[/elementliste]", "</li>", $contenu);

        // Galerie
        $contenu = str_replace("[gallery]", "<div id=\"lightGallery\" class=\"gallery\">", $contenu);
        $contenu = str_replace("[/gallery]", "</div>", $contenu);

        // Image
        $contenu = str_replace("[image]", "<img class=img-fluid src=", $contenu);
        $contenu = str_replace("[/image]", "></img>", $contenu);

        // Image serveur
        /*
        $contenu = preg_replace('#\[image2=(.+?),icone](.+?)\[\/image2]#', '#\[image2=$1,32]$2\[\/image2]#', $contenu); // Recherche pour remplacer la taille des images en valeur
        $contenu = preg_replace('#\[image2=(.+?),petite](.+?)\[\/image2]#', '#\[image2=$1,200]$2\[\/image2]#', $contenu); // Recherche pour remplacer la taille des images en valeur
        $contenu = preg_replace('#\[image2=(.+?),moyenne](.+?)\[\/image2]#', '#\[image2=$1,500]$2\[\/image2]#', $contenu); // Recherche pour remplacer la taille des images en valeur
        $contenu = preg_replace('#\[image2=(.+?),grande](.+?)\[\/image2]#', '#\[image2=$1,900]$2\[\/image2]#', $contenu); // Recherche pour remplacer la taille des images en valeur
        */

        $contenu = preg_replace('#\[image2=(.+?),([1-9]|[1-9][0-9]|[1-9][0-9][0-9])\](.+?)\[\/image2]#', "<div data-title='Title 1' data-desc='Description 1' data-responsive-src='/images/$3' data-src='/images/$3' style='display:inline;'><a href='#$3' onclick='return false;'><img class='img-fluid mw-100 lazy' id='$3' style='float: $1; margin-top: 10px; margin-bottom: 10px; width: auto; height: auto; max-height: $2px; max-width: $2px' data-src='/images/$3'></a></div>", $contenu); // Recherche pour enlever la balise ainsi que recuperer l'alignement
        $contenu = preg_replace('#\[image2=(.+?)](.+?)\[\/image2]#', "<div data-title='Title 1' data-desc='Description 1' data-responsive-src='/images/$2' data-src='/images/$2' style='display:inline;'><a href='#$2' onclick='return false;'><img class='img-fluid mw-100 lazy' id='$2' style='float: $1; margin-top: 10px; margin-bottom: 10px; width: auto; height: auto; max-height: 900px;' data-src='/images/$2'></a></div>", $contenu); // Recherche pour enlever la balise ainsi que recuperer l'alignement
        // $contenu = str_replace("[/image2]", "'></a></div>", $contenu);

        // $contenu = str_replace("[image2]", "<div data-title='Title 1' data-desc='Description 1' data-responsive-src='/1.jpg' data-src='/1.jpg'> <a href='#'><img class='img-fluid' width: auto; height: auto; max-width: 210px; max-height: 210px;' src='/images/", $contenu);

        // Icone serveur
        $contenu = preg_replace('#\[icone=(.+?)]\[/icone]#', "<img class='img-fluid' style='margin:10px; width: auto; height: auto; max-width: 900px; max-height: 900px;' src='/icones/$1'>", $contenu); // Recherche pour enlever la balise ainsi que recuperer le nom de l'image
        //  $contenu = str_replace("[/image2]", "'></img>", $contenu);

        // Lien
        if ($avecLien == true) { // Pour ne pas faire buguer les articles qui possède déjà un lien
            $contenu = str_replace("[lien]", "<a href='", $contenu);
            $contenu = str_replace("[/lien]", "'>", $contenu);

            // Texte du lien
            $contenu = str_replace("[texteLien]", "", $contenu);
            $contenu = str_replace("[/texteLien]", "</a>", $contenu);
        } else { // Sinon on met juste le texte du lien
            $contenu = preg_replace('#\[lien](.+)\[/lien]#', '', $contenu); // Recherche pour enlever la balise et le lien

            $contenu = str_replace("[texteLien]", "", $contenu);
            $contenu = str_replace("[/texteLien]", "", $contenu);
        }

        // Tableau
        $contenu = preg_replace('#\[Tableau]#', '<table class="table table-striped table-bordered table-hover" id="tableau" class="table">', $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[\/Tableau]#', '</table>', $contenu);

        $contenu = str_replace("[TableauDebut]", '<thead class="thead-light">', $contenu);
        $contenu = str_replace("[/TableauDebut]", '</thead>', $contenu);

        $contenu = str_replace("[TableauEntréeColonne]", '<th scope="col">', $contenu);
        $contenu = str_replace("[/TableauEntréeColonne]", '</th>', $contenu);

        $contenu = str_replace("[TableauEntréeLigne]", '<th scope="row">', $contenu);
        $contenu = str_replace("[/TableauEntréeLigne]", '</th>', $contenu);

        $contenu = str_replace("[TableauCorps]", "<tbody>", $contenu);
        $contenu = str_replace("[/TableauCorps]", "</tbody>", $contenu);

        $contenu = str_replace("[TableauLigne]", "<tr>", $contenu);
        $contenu = str_replace("[/TableauLigne]", "</tr>", $contenu);

        $contenu = str_replace("[TableauColonne]", "<td>", $contenu);
        $contenu = str_replace("[/TableauColonne]", "</td>", $contenu);

        $contenu = str_replace("[TableauEntrée][/TableauEntrée]", "<div></div>", $contenu);
        $contenu = preg_replace('#\[TableauEntrée](.+?)\[/TableauEntrée]#', '<div>$1</div>', $contenu); // Recherche pour enlever la balise

        // Video
        // <iframe width="560" height="315" src="https://www.youtube.com/embed/FCAgvzCHnBA" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        $contenu = str_replace("[video]", "<iframe width=640 height=360 class=video-commentaire frameborder=0 allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen src=", $contenu);
        $contenu = str_replace("[/video]", " ></iframe>", $contenu);

        // Remplacer url youtube par une url d'integration
        $contenu = str_replace("https://www.youtube.com/watch?v=", "https://www.youtube.com/embed/", $contenu);

        // Taille
        $contenu = preg_replace('#\[taille=(.+?)]#', '<div style="font-size: $1;">', $contenu); // Recherche pour enlever la balise ainsi que recuperer la taille
        $contenu = preg_replace('#\[/taille]#', '</div>', $contenu);

        // Animation
        $contenu = preg_replace('#\[animation=(.+?)](.+?)\[/animation]#s', "<div data-aos='$1'>$2</div>", $contenu); // Recherche pour enlever la balise ainsi que recuperer le nom de l'animation

        // Section
        $contenu = preg_replace('#\[section couleur=(.+?) titre=(.+?)](.*?)\[/section]#s', "<div class='section' id='$2' style='background-color:$1;'>$3</div>", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[section gradient=(.+?) titre=(.+?)](.*?)\[/section]#s', "<div class='section' id='$2' style='background: linear-gradient($1);'>$3</div>", $contenu); // Recherche pour enlever la balise
        // $contenu = preg_replace('#\[section video=(.+?) titre=(.+?)](.*?)\[/section]#s', "<div class='section' id='$2' style='background-color:$1;'><iframe  style='position: absolute; right: 0; bottom: 0; top:0; width: 100%; height: 100%; background-size: 100% 100%; background-color: black; background-position: center center; background-size: contain; object-fit: cover; z-index:3;' data-autoplay width=640 height=360 class=video frameborder=0 allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen src=https://www.youtube.com/embed/qTbwnLg2sEE?modestbranding=1></iframe></div>", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[section video=(.+?) titre=(.+?)](.*?)\[/section]#s', "<div class='section' id='$2'><div id='bgndVideo' class='player' style='width: 100%; height: 100%; background-size: 100% 100%; background-position: center center; background-color: black; background-size: contain; object-fit: cover;' data-property='{videoURL:\"$1\",containment:\"self\",autoPlay:false, useOnMobile: true, mute:true, opacity:1, startAt: 1, showControls: false, stopMovieOnBlur: false, remember_last_time: true, addRaster: true, optimizeDisplay: true, showYTLogo: false, ratio: \"auto\"}'><div style=\"color: white; margin: 20%;\">$3</div></div></div>", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[section couleur=(.+?)](.*?)\[/section]#s', "<div class='section' style='background-color:$1;'>$2</div>", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[section fond=(.+?) titre=(.+?)](.*?)\[/section]#s', "<div class='section' id='$2' style='background-image:url(/images/$1);'>$3</div>", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[section fond=(.+?)](.*?)\[/section]#s', "<div class='section' style='background-image:url(/images/$1);'>$2</div>", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[section titre=(.+?)](.+?)\[/section]#', "<div class='section' id='$1'>$2</div>", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[section](.*?)\[/section]#', "<div class='section'>$1</div>", $contenu); // Recherche pour enlever la balise

        // Slide
        $contenu = preg_replace('#\[slide couleur=(.+?) titre=(.+?)](.*?)\[/slide]#s', "<div class='slide' id='$2' style='background-color:$1;'>$3</div>", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[slide gradient=(.+?) titre=(.+?)](.*?)\[/slide]#s', "<div class='slide' id='$2' style='background: linear-gradient($1);'>$3</div>", $contenu); // Recherche pour enlever la balise
        // $contenu = preg_replace('#\[section video=(.+?) titre=(.+?)](.*?)\[/section]#s', "<div class='section' id='$2' style='background-color:$1;'><iframe  style='position: absolute; right: 0; bottom: 0; top:0; width: 100%; height: 100%; background-size: 100% 100%; background-color: black; background-position: center center; background-size: contain; object-fit: cover; z-index:3;' data-autoplay width=640 height=360 class=video frameborder=0 allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen src=https://www.youtube.com/embed/qTbwnLg2sEE?modestbranding=1></iframe></div>", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[slide video=(.+?) titre=(.+?)](.*?)\[/slide]#s', "<div class='slide' id='$2'><div id='bgndVideo' class='player' style='width: 100%; height: 100%; background-size: 100% 100%; background-position: center center; background-color: black; background-size: contain; object-fit: cover;' data-property='{videoURL:\"$1\",containment:\"self\",autoPlay:false, useOnMobile: true, mute:true, opacity:1, startAt: 1, showControls: false, stopMovieOnBlur: false, remember_last_time: true, addRaster: true, optimizeDisplay: true, showYTLogo: false, ratio: \"auto\"}'><div style=\"color: white; margin: 20%;\">$3</div></div></div>", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[slide couleur=(.+?)](.*?)\[/slide]#s', "<div class='slide' style='background-color:$1;'>$2</div>", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[slide fond=(.+?) titre=(.+?)](.*?)\[/slide]#s', "<div class='slide' id='$2' style='background-image:url(/images/$1);'>$3</div>", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[slide fond=(.+?)](.*?)\[/slide]#s', "<div class='slide' style='background-image:url(/images/$1);'>$2</div>", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[slide](.+?)\[/slide]#', "<div class='slide'>$1</div>", $contenu); // Recherche pour enlever la balise

        // Titre
        $contenu = preg_replace('#\[titre=(.+?)]#', '<div class="$1">', $contenu); // Recherche pour enlever la balise ainsi que recuperer le titre
        $contenu = preg_replace('#\[/titre]#', '</div>', $contenu);

        // Couleur
        $contenu = preg_replace('#\[couleur=(.+?)]#', '<div style="color: $1;">', $contenu); // Recherche pour enlever la balise ainsi que recuperer la couleur
        $contenu = preg_replace('#\[/couleur]#', '</div>', $contenu);

        // Couleur de fond
        $contenu = preg_replace('#\[couleurfond=(.+?)]#', '<div style="background-color: $1;">', $contenu); // Recherche pour enlever la balise ainsi que recuperer la couleur de fond
        $contenu = preg_replace('#\[/couleurfond]#', '</div>', $contenu);
    } else { // Si on veut juste supprimer les balises du bbcode
        $contenu = str_replace("[i]", "", $contenu);
        $contenu = str_replace("[/i]", "", $contenu);

        // gras
        $contenu = str_replace("[b]", "", $contenu);
        $contenu = str_replace("[/b]", "", $contenu);

        // souligne
        $contenu = str_replace("[u]", "", $contenu);
        $contenu = str_replace("[/u]", "", $contenu);

        // citation
        $contenu = str_replace("[citation]", "", $contenu);
        $contenu = str_replace("[/citation]", "", $contenu);

        // texte centré
        $contenu = str_replace("[center]", "", $contenu);
        $contenu = str_replace("[/center]", "", $contenu);

        // texte à gauche
        $contenu = str_replace("[gauche]", "", $contenu);
        $contenu = str_replace("[/gauche]", "", $contenu);

        // texte à droite
        $contenu = str_replace("[droite]", "", $contenu);
        $contenu = str_replace("[/droite]", "", $contenu);

        // alignement flottant à gauche
        $contenu = preg_replace('#\[gaucheFlottant](.*?)\[/gaucheFlottant]#', "", $contenu); // Recherche pour enlever la balise

        // alignement flottant à droite
        $contenu = preg_replace('#\[droiteFlottant](.*?)\[/droiteFlottant]#', "", $contenu); // Recherche pour enlever la balise

        // liste
        $contenu = str_replace("[liste]", "", $contenu);
        $contenu = str_replace("[/liste]", "", $contenu);

        // élément de liste
        $contenu = str_replace("[elementliste]", "", $contenu);
        $contenu = str_replace("[/elementliste]", "", $contenu);

        // Galerie
        $contenu = str_replace("[gallery]", "", $contenu);
        $contenu = str_replace("[/gallery]", "", $contenu);

        // Image
        $contenu = str_replace("[image]", "", $contenu);
        $contenu = str_replace("[/image]", "", $contenu);

        // Image serveur
        $contenu = preg_replace('#\[image2=(.+?)](.+?)\[/image2]#', "", $contenu); // Recherche pour enlever la balise ainsi que recuperer l'alignement
        $contenu = str_replace("[/image2]", "", $contenu);

        $contenu = str_replace("[image2]", "", $contenu);

        // Icone serveur
        $contenu = preg_replace('#\[icone=(.+?)]\[/icone]#', "", $contenu); // Recherche pour enlever la balise ainsi que recuperer le nom de l'image

        // Lien
        if ($avecLien == true) { // Pour ne pas faire buguer les articles qui possède déjà un lien
            $contenu = str_replace("[lien]", "", $contenu);
            $contenu = str_replace("[/lien]", "", $contenu);

            // Texte du lien
            $contenu = str_replace("[texteLien]", "", $contenu);
            $contenu = str_replace("[/texteLien]", "", $contenu);
        } else { // Sinon on met juste le texte du lien
            $contenu = preg_replace('#\[lien](.+)\[/lien]#', '', $contenu); // Recherche pour enlever la balise et le lien

            $contenu = str_replace("[texteLien]", "", $contenu);
            $contenu = str_replace("[/texteLien]", "", $contenu);
        }

        // Tableau
        $contenu = preg_replace('#\[Tableau]#', '', $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[\/Tableau]#', '', $contenu);

        $contenu = str_replace("[TableauDebut]", '', $contenu);
        $contenu = str_replace("[/TableauDebut]", '', $contenu);

        $contenu = str_replace("[TableauEntréeColonne]", '', $contenu);
        $contenu = str_replace("[/TableauEntréeColonne]", '', $contenu);

        $contenu = str_replace("[TableauEntréeLigne]", '', $contenu);
        $contenu = str_replace("[/TableauEntréeLigne]", '', $contenu);

        $contenu = str_replace("[TableauCorps]", "", $contenu);
        $contenu = str_replace("[/TableauCorps]", "", $contenu);

        $contenu = str_replace("[TableauLigne]", "", $contenu);
        $contenu = str_replace("[/TableauLigne]", "", $contenu);

        $contenu = str_replace("[TableauColonne]", "", $contenu);
        $contenu = str_replace("[/TableauColonne]", "", $contenu);

        $contenu = str_replace("[TableauEntrée][/TableauEntrée]", "", $contenu);
        $contenu = preg_replace('#\[TableauEntrée](.+?)\[/TableauEntrée]#', '', $contenu); // Recherche pour enlever la balise

        // Video
        // <iframe width="560" height="315" src="https://www.youtube.com/embed/FCAgvzCHnBA" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        $contenu = str_replace("[video]", "", $contenu);
        $contenu = str_replace("[/video]", "", $contenu);

        // Remplacer url youtube par une url d'integration
        $contenu = str_replace("https://www.youtube.com/watch?v=", "", $contenu);

        // Taille
        $contenu = preg_replace('#\[taille=(.+?)]#', '<div style="font-size: $1;">', $contenu); // Recherche pour enlever la balise ainsi que recuperer la taille
        $contenu = preg_replace('#\[/taille]#', '</div>', $contenu);

        // Animation
        $contenu = preg_replace('#\[animation=(.+?)](.+?)\[/animation]#s', "", $contenu); // Recherche pour enlever la balise ainsi que recuperer le nom de l'animatio,

        // Section
        $contenu = preg_replace('#\[section couleur=(.+?) titre=(.+?)](.*?)\[/section]#s', "", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[section gradient=(.+?) titre=(.+?)](.*?)\[/section]#s', "", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[section video=(.+?) titre=(.+?)](.*?)\[/section]#s', "", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[section couleur=(.+?)](.*?)\[/section]#s', "", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[section fond=(.+?) titre=(.+?)](.*?)\[/section]#s', "", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[section fond=(.+?)](.*?)\[/section]#s', "", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[section titre=(.+?)](.+?)\[/section]#', "", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[section](.*?)\[/section]#s', "", $contenu); // Recherche pour enlever la balise

        // Slide
        $contenu = preg_replace('#\[slide couleur=(.+?) titre=(.+?)](.*?)\[/slide]#s', "", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[slide gradient=(.+?) titre=(.+?)](.*?)\[/slide]#s', "", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[slide video=(.+?) titre=(.+?)](.*?)\[/slide]#s', "", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[slide couleur=(.+?)](.*?)\[/slide]#s', "", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[slide fond=(.+?) titre=(.+?)](.*?)\[/slide]#s', "", $contenu); // Recherche pour enlever la balise
        $contenu = preg_replace('#\[slide fond=(.+?)](.*?)\[/slide]#s', "", $contenu); // Recherche pour enlever la balise


        // Titre
        $contenu = preg_replace('#\[titre=(.+?)]#', '', $contenu); // Recherche pour enlever la balise ainsi que recuperer le titre
        $contenu = preg_replace('#\[/titre]#', '', $contenu);

        // Couleur
        $contenu = preg_replace('#\[couleur=(.+?)]#', '', $contenu); // Recherche pour enlever la balise ainsi que recuperer la couleur
        $contenu = preg_replace('#\[/couleur]#', '', $contenu);

        // Couleur de fond
        $contenu = preg_replace('#\[couleurfond=(.+?)]#', '', $contenu); // Recherche pour enlever la balise ainsi que recuperer la couleur de fond
        $contenu = preg_replace('#\[/couleurfond]#', '', $contenu);
    }

    return $contenu;
}
