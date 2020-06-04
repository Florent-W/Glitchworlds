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
        return $texte . ' <a href="'. $lien . '">[Lire la suite]</a>';
    } else {
        return $texte;
    }
}

// Remplace les caractères du BBCode par des balises html
function remplacementBBCode($contenu){
    // italique
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

    return $contenu;
}
?>