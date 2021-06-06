<button type="button" name="italique" id="italique" style="font-style: italic;" data-toggle="tooltip" data-placement="top" title="Italique" onclick="ajoutClickBBcodeFormulaire('[i]', '[/i]', nom_contenu)">I</button> <!-- Bouton pour ajouter italique -->
<button type="button" name="gras" id="gras" style="font-weight: bold;" data-toggle="tooltip" data-placement="top" title="Gras" onclick="ajoutClickBBcodeFormulaire('[b]', '[/b]', nom_contenu)">G</button>
<button type="button" name="souligne" id="souligne" style="text-decoration: underline;" data-toggle="tooltip" data-placement="top" title="Souligner" onclick="ajoutClickBBcodeFormulaire('[u]', '[/u]', nom_contenu)">U</button>
<button type="button" name="citation" id="citation" data-toggle="tooltip" data-placement="top" title="Citation" onclick="ajoutClickBBcodeFormulaire('[citation]', '[/citation]', nom_contenu)">“</button> <!-- Bouton pour citer -->
<button type="button" name="gauche" id="gauche" value="G" data-toggle="tooltip" data-placement="top" title="Placer à gauche" onclick="ajoutClickBBcodeFormulaire('[gauche]', '[/gauche]', nom_contenu)"><i class="fas fa-align-left"></i></button>
<button type="button" name="centre" id="center" data-toggle="tooltip" data-placement="top" title="Placer au centre" onclick="ajoutClickBBcodeFormulaire('[center]', '[/center]', nom_contenu)"><i class="fas fa-align-center"></i></button>
<button type="button" name="droite" id="droite" value="R" data-toggle="tooltip" data-placement="top" title="Placer à droite" onclick="ajoutClickBBcodeFormulaire('[droite]', '[/droite]', nom_contenu)"><i class="fas fa-align-right"></i></button>
<button type="button" name="gallery" id="gallery" value="Gallerie" data-toggle="tooltip" data-placement="top" title="Galerie d'image" onclick="ajoutClickBBcodeFormulaire('[gallery]', '[/gallery]', nom_contenu)"><i class="fas fa-photo-video"></i></button>
<button type="button" name="image" id="image" value="I" data-toggle="tooltip" data-placement="top" title="Image par lien" onclick="ajoutClickBBcodeFormulaire('[image]', '[/image]', nom_contenu)"><i class="far fa-images"></i></button>
<button type="button" data-placement="top" title="Image" onclick="copieText('ajout_commentaire', 'commentaire')" data-toggle="modal" data-target="#modal"><i class="far fa-image"></i> </button> <!-- Bouton qui va activer la fenetre d'upload -->
<button type="button" name="video" id="video" title="Vidéo" data-toggle="modal" data-target="#modalVideo"><i class="fab fa-youtube"></i></button>
<button type="button" data-placement="top" title="Lien" onclick="copieText('ajout_commentaire', 'commentaireUrl')" data-toggle="modal" data-target="#modalUrl"><i class="fas fa-link"></i> </button> <!-- Bouton qui va activer la fenetre d'url -->
<button type="button" name="section" id="section" value="S" data-toggle="tooltip" data-placement="top" title="Section" onclick="ajoutClickBBcodeFormulaire('[section]', '[/section]', nom_contenu)"><i class="fas fa-clone"></i></i></i></button> <!-- Bouton pour mettre une nouvelle section scrolling -->
<button type="button" data-placement="top" title="Tableau" data-toggle="modal" data-target="#modalTableau"><i class="fas fa-table"></i> </button> <!-- Bouton qui va activer la fenetre des tableaux -->
<button type="button" name="liste" id="liste" value="L" data-toggle="tooltip" data-placement="top" title="Liste" onclick="ajoutClickBBcodeFormulaire('[liste]', '[/liste]', nom_contenu)"><i class="fas fa-list-ul"></i></button>
<button type="button" name="elementliste" id="elementliste" value="EL" data-toggle="tooltip" data-placement="top" title="Element de Liste" onclick="ajoutClickBBcodeFormulaire('[elementliste]', '[/elementliste]', nom_contenu)"><i class="far fa-list-alt"></i></button>
<select class="selecticone fa selectpicker" name="taille" id="taille" data-placement="top" data-live-search="true" data-width="fit" title="Taille" onchange="ajoutClickBBcodeFormulaire('[taille=' + this.value +']', '[/taille]', nom_contenu)">
    <!-- Selection taille -->
    <option hidden>Taille</option> <!-- Grandeur texte  -->
    <option value="smaller" style="font-size: x-small;">Très Petit</option>
    <option value="small" style="font-size: small;">Petit</option>
    <option value="medium" style="font-size: medium;">Moyen</option>
    <option value="large" style="font-size: large;">Grand</option>
    <option value="largest" style="font-size: x-large;">Très grand</option>
</select>
<select class="selecticone fa selectpicker" name="titreBBCode" id="titreBBCode" data-placement="top" data-live-search="true" data-width="fit" title="Titre" onchange="ajoutClickBBcodeFormulaire('[titre=' + this.value +']', '[/titre]', nom_contenu)">
    <!-- Selection titre -->
    <option hidden>Titre</option> <!-- Grandeur titre  -->
    <option value="h1" class="h1">H1</option>
    <option value="h2" class="h2">H2</option>
    <option value="h3" class="h3">H3</option>
    <option value="h4" class="h4">H4</option>
    <option value="h5" class="h5">H5</option>
</select>
<select class="selecticone fa selectpicker" name="couleur" id="couleur" data-placement="top" data-live-search="true" data-width="fit" title="Couleur" onchange="ajoutClickBBcodeFormulaire('[couleur=' + this.value +']', '[/couleur]', nom_contenu)">
    <!-- Selection couleur -->
    <option hidden>&#xf53f; Couleur</option> <!-- Selection avec icone -->
    <option value="blue" style="color: blue;">Bleu</option>
    <option value="lightskyblue" style="color: lightskyblue;">Bleu clair</option>
    <option value="yellow" style="color: #ebca0e;">Jaune</option>
    <option value="green" style="color: green;">Vert</option>
    <option value="orange" style="color: orange;">Orange</option>
    <option value="red" style="color: red;">Rouge</option>
    <option value="pink" style="color: pink;">Rose</option>
    <option value="violet" style="color: violet;">Violet</option>
    <option value="brown" style="color: brown;">Marron</option>
    <option value="silver" style="color: silver;">Argenté</option>
</select> <!-- Select qui va changer la couleur -->
<select class="selecticone fa selectpicker" name="couleurfond" id="couleurfond" data-placement="top" data-live-search="true" data-width="fit" title="Couleur de fond" onchange="ajoutClickBBcodeFormulaire('[couleurfond=' + this.value +']', '[/couleurfond]', nom_contenu)">
    <!-- Selection couleur de fond -->
    <option hidden>&#xf53f; Couleur de fond</option> <!-- Selection avec icone -->
    <option value="blue" style="background-color: blue;">Bleu</option>
    <option value="lightskyblue" style="background-color: lightskyblue;">Bleu clair</option>
    <option value="yellow" style="background-color: #ebca0e;">Jaune</option>
    <option value="green" style="background-color: green;">Vert</option>
    <option value="orange" style="background-color: orange;">Orange</option>
    <option value="red" style="background-color: red;">Rouge</option>
    <option value="pink" style="background-color: pink;">Rose</option>
    <option value="violet" style="background-color: violet;">Violet</option>
    <option value="brown" style="background-color: brown;">Marron</option>
    <option value="silver" style="background-color: silver;">Argenté</option>
</select> <!-- Select qui va changer la couleur de fond -->
<select class="selecticone fa selectpicker" name="selectionIcone" id="selectionIcone" data-placement="top" data-live-search="true" data-width="fit" title="Icone" onchange="ajoutClickBBcodeFormulaire('[icone=' + this.value +']', '[/icone]', nom_contenu)"> <!-- On demande à l'utilisateur si il veut placé une icône défini par exemple dans un article -->
    <!-- Selection icone -->
    <option hidden>Icone</option>
    <option data-content="Histoire <img class='img-fluid' style='width: auto; height: auto; max-width: 41px; max-height: 31px;' src='/icones/histoir.png'>" value="histoir.png"></option>
    <option data-content="Important <img class='img-fluid' style='width: auto; height: auto; max-width: 41px; max-height: 31px;' src='/icones/Important.png'>" value="Important.png"></option>
    <option data-content="Pokédex <img class='img-fluid' style='width: auto; height: auto; max-width: 41px; max-height: 31px;' src='/icones/pokedex-kanto.png'>" value="pokedex-kanto.png"></option>
    <option data-content="Map <img class='img-fluid' style='width: auto; height: auto; max-width: 41px; max-height: 31px;' src='/icones/iconemap.png'>" value="iconemap.png"></option>
    <option data-content="Trailer <img class='img-fluid' style='width: auto; height: auto; max-width: 41px; max-height: 31px;' src='/icones/iconetrailer.png'>" value="iconetrailer.png"></option>
    <option data-content="Téléchargement <img class='img-fluid' style='width: auto; height: auto; max-width: 41px; max-height: 31px;' src='/icones/iconetelechargement.png'>" value="iconetelechargement.png"></option>
    <option data-content="Crédits <img class='img-fluid' style='width: auto; height: auto; max-width: 41px; max-height: 31px;' src='/icones/iconecoupe.png'>" value="iconecoupe.png"></option>
    <option data-content="Capture d'écran <img class='img-fluid' style='width: auto; height: auto; max-width: 41px; max-height: 31px;' src='/icones/iconecapture.png'>" value="iconecapture.png"></option>
    <option data-content="Anglais <img class='img-fluid' style='width: auto; height: auto; max-width: 41px; max-height: 31px;' src='/icones/anglais.png'>" value="anglais.png"></option>
    <option data-content="Français <img class='img-fluid' style='width: auto; height: auto; max-width: 41px; max-height: 31px;' src='/icones/français.png'>" value="français.png"></option>
    <option data-content="Windows <img class='img-fluid' style='width: auto; height: auto; max-width: 41px; max-height: 31px;' src='/icones/iconewindows.png'>" value="iconewindows.png"></option>
    <option data-content="Mac <img class='img-fluid' style='width: auto; height: auto; max-width: 41px; max-height: 31px;' src='/icones/mac.png'>" value="mac.png"></option>
    <option data-content="Linux <img class='img-fluid' style='width: auto; height: auto; max-width: 41px; max-height: 31px;' src='/icones/linux.png'>" value="linux.png"></option>
    <option data-content="Android <img class='img-fluid' style='width: auto; height: auto; max-width: 41px; max-height: 31px;' src='/icones/android.png'>" value="android.png"></option>
</select>