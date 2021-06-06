<script>
    // Fonction qui prend en paramètre un input et le nom de l'indication du message de l'input et va dire si il est rempli ou non via le javascript avec du texte et de la couleur
    function controleTexteInput(input, nomInputInfo, typeInput) {
        var inputInfo = document.getElementById(nomInputInfo);

        if (input.value.length == 0) { // Si le texte n'a pas été complété, on dit qu'il n'a pas été rempli
            // input.className = "form-control" + " bg-primary";

            if (typeInput == "titre") { // Selon le type de l'input, la phrase sera changée
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir un titre";
            } else if (typeInput == "contenu") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir un contenu";
            } else if (typeInput == "date") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir une date";
            } else if (typeInput == "categorie") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir une catégorie";
            } else if (typeInput == "miniature") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir un image";
            } else if (typeInput == "commentaire") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez écrire un commentaire";
            } else if (typeInput == "pseudo") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir un pseudo";
            } else if (typeInput == "mdp") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir un mot de passe";
            } else if (typeInput == "mail") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir un e-mail";
            } else if (typeInput == "choix_news") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir une news";
            } else if (typeInput == "choix_page") {
                inputInfo.className = "text-danger";
                inputInfo.innerText = "Veuillez choisir une page";
            }

        } else { // Sinon on affiche de la couleur pour indiquer que le texte à été rempli ainsi que du texte
            // input.className = "form-control" + " bg-success";

            if (typeInput == "titre") { // Selon le type de l'input, la phrase sera changée
                inputInfo.className = "text-success";
                inputInfo.innerText = "Le titre a été rempli";
            } else if (typeInput == "contenu") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "Le contenu a été rempli";
            } else if (typeInput == "date") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "Le date a été remplie";
            } else if (typeInput == "categorie") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "Le catégorie a été remplie";
            } else if (typeInput == "miniature") {
                inputInfo.innerText = input.files[0].name;
            } else if (typeInput == "commentaire") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "Le commentaire a été écrit";
            } else if (typeInput == "pseudo") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "Le pseudo a été rempli";
            } else if (typeInput == "mdp") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "Le mot de passe a été rempli";
            } else if (typeInput == "mail") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "L'adresse e-mail a été remplie";
            } else if (typeInput == "choix_news") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "La news a été choisie";
            } else if (typeInput == "choix_page") {
                inputInfo.className = "text-success";
                inputInfo.innerText = "La page a été choisie";
            }
        }
    }

    // Fonction qui prend en paramètre un textarea, un champ et prend la valeur du textarea et le copie dans le champ
    function copieText(idTextArea, idChamp) {
        var textArea = document.getElementById(idTextArea);
        var champ = document.getElementById(idChamp);

        champ.value = textArea.value;
    }

    // Fonction qui prend en paramètre une valeur, un champ et prend la valeur et la copie dans le champ
    function remplirChamp(value, idChamp) {
        var champ = document.getElementById(idChamp);

        champ.value = value;
    }

    // Fonction qui va remplir les champs pour les modifications de news
    function remplirChampModificationArticle(valueId, valueTitre, valueContenu, valueCategorie, valueJeu, valueUrlSupprimerArticle) {

        var champId = document.getElementById('id');
        var champTitre = document.getElementById('titre');
        var champContenu = document.getElementById('contenu');
        var champCategorie = document.getElementById('categorie');
        var champJeu = document.getElementById('jeu');
        var champSupprimerLien = document.getElementById('supprimerArticle');

        champId.value = valueId;
        champTitre.value = valueTitre;
        champContenu.innerText = valueContenu;
        champCategorie.value = valueCategorie;
        champJeu.value = valueJeu;
        champSupprimerLien.href = valueUrlSupprimerArticle;
    }


    // Fonction qui prend en paramètre les differentes balises et les rajoutes dans un formulaire (pour le bbcode)
    function ajoutClickBBcodeFormulaire(debutBalise, finBalise, idTextArea) {

        var textareaFormulaire = document.getElementById(idTextArea);

        // Position texte selectionner
        var startSelection = textareaFormulaire.value.substring(0, textareaFormulaire.selectionStart);
        var currentSelection = textareaFormulaire.value.substring(textareaFormulaire.selectionStart, textareaFormulaire.selectionEnd);
        var endSelection = textareaFormulaire.value.substring(textareaFormulaire.selectionEnd);

        textareaFormulaire.value = startSelection + debutBalise + currentSelection + finBalise + endSelection;
    }

    function previsualisationContenu() {
        $('#contenu').on('change', function() { // On met le contenu dans la prévisualisation
            $('#previsualisationContenu').empty();
            contenuTexteHtml = remplacerBaliseParBBCodePrevisualisation($('#contenu').val());
            $('#previsualisationContenu').append(contenuTexteHtml); // On replace le contenu dans la prévisualisation
        });
    }

    // Pour réveler un texte
    function revelerTexte() {
        $(document).ready(function() {
            $(".revelerTexte").fadeIn(900);
        });
    }

    // Fonction pour remplacer certaines balises pour le tableau
    function remplacerBaliseParBBCode(contenu) {
        contenu = contenu.replace(/<table(.+?)>/, '[Tableau]');
        contenu = contenu.replace(/<\/table>/, '[/Tableau]');

        contenu = contenu.replace(/<thead(.+?)>/, '[TableauDebut]');
        contenu = contenu.replace(/<\/thead>/, '[/TableauDebut]');

        contenu = contenu.replace(/<th scope="col">/g, '[TableauEntréeColonne]');
        contenu = contenu.replace(/<\/th>/g, '[/TableauEntréeColonne]');

        contenu = contenu.replace(/<th scope="row">/g, '[TableauEntréeLigne]');
        contenu = contenu.replace(/<\/th>/g, '[/TableauEntréeLigne]');

        contenu = contenu.replace(/<tbody>/, '[TableauCorps]');
        contenu = contenu.replace(/<\/tbody>/, '[/TableauCorps]');

        contenu = contenu.replace(/<tr>/g, '[TableauLigne]');
        contenu = contenu.replace(/<\/tr>/g, '[/TableauLigne]');

        contenu = contenu.replace(/<td>/g, '[TableauColonne]');
        contenu = contenu.replace(/<\/td>/g, '[/TableauColonne]');

        contenu = contenu.replace(/<input name="inputEntree" id="inputEntree" type="text" value="">/g, '[TableauEntrée][/TableauEntrée]');
        contenu = contenu.replace(/<input name="inputEntree" id="inputEntree" type="text" value="(.+?)">/g, '[TableauEntrée]$1[/TableauEntrée]');

        // contenu = contenu.replace(/<input(.+?)>/g, '[TableauEntrée]');

        return contenu;
    }

    // Fonction pour remplacer certaines balises
    function remplacerBaliseParBBCodePrevisualisation(contenu) {
        contenu = contenu.replace(/<table(.+?)>/, '[Tableau]');
        contenu = contenu.replace(/<\/table>/, '[/Tableau]');

        contenu = contenu.replace(/<thead(.+?)>/, '[TableauDebut]');
        contenu = contenu.replace(/<\/thead>/, '[/TableauDebut]');

        contenu = contenu.replace(/<th scope="col">/g, '[TableauEntréeColonne]');
        contenu = contenu.replace(/<\/th>/g, '[/TableauEntréeColonne]');

        contenu = contenu.replace(/<th scope="row">/g, '[TableauEntréeLigne]');
        contenu = contenu.replace(/<\/th>/g, '[/TableauEntréeLigne]');

        contenu = contenu.replace(/<tbody>/, '[TableauCorps]');
        contenu = contenu.replace(/<\/tbody>/, '[/TableauCorps]');

        contenu = contenu.replace(/<tr>/g, '[TableauLigne]');
        contenu = contenu.replace(/<\/tr>/g, '[/TableauLigne]');

        contenu = contenu.replace(/<td>/g, '[TableauColonne]');
        contenu = contenu.replace(/<\/td>/g, '[/TableauColonne]');

        contenu = contenu.replace(/<input name="inputEntree" id="inputEntree" type="text" value="">/g, '[TableauEntrée][/TableauEntrée]');
        contenu = contenu.replace(/<input name="inputEntree" id="inputEntree" type="text" value="(.+?)">/g, '[TableauEntrée]$1[/TableauEntrée]');

        contenu = contenu.replace(/\[i\]/g, "<em>");
        contenu = contenu.replace(/\[\/i\]/g, "</em>");

        // gras
        contenu = contenu.replace(/\[b\]/g, "<strong>");
        contenu = contenu.replace(/\[\/b\]/g, "</strong>");

        // souligne
        contenu = contenu.replace(/\[u](.+?)\[\/u]/g, "<u>$1</u>");

        // citation
        contenu = contenu.replace("[citation]", "<blockquote class=\"blockquote\">");
        contenu = contenu.replace("[/citation]", "</blockquote>");

        // texte centré
        contenu = contenu.replace(/\[center](.+?)\[\/center]/sg, "<div style=\"text-align: center;\">$1</div>");
        contenu = contenu.replace("[center]", "<div style=\"text-align: center;\">");
        contenu = contenu.replace("[/center]", "</div>");

        // texte à gauche
        contenu = contenu.replace("[gauche]", "<div style=\"text-align: left;\">");
        contenu = contenu.replace("[/gauche]", "</div>");

        // texte à droite
        contenu = contenu.replace("[droite]", "<div style=\"text-align: right;\">");
        contenu = contenu.replace("[/droite]", "</div>");

        // alignement flottant à gauche
        contenu = contenu.replace(/\[gaucheFlottant](.*?)\[\/gaucheFlottant\]/g, "<div style=\"float: left;\">$1</div>");

        // alignement flottant à droite
        contenu = contenu.replace(/\[droiteFlottant](.*?)\[\/droiteFlottant\]/g, "<div style=\"float: right;\">$1</div>");

        // liste
        contenu = contenu.replace(/\[liste\]/g, "<ul>");
        contenu = contenu.replace(/\[\/liste\]/g, "</ul>");

        // élément liste
        contenu = contenu.replace(/\[elementliste\]/g, "<li style=\"list-style-position: inside;\">");
        contenu = contenu.replace(/\[\/elementliste\]/g, "</li>");

        // Galerie
        contenu = contenu.replace(/\[gallery\]/g, "<div id='lightGallery' class='gallery'>");
        contenu = contenu.replace(/\[\/gallery\]/g, "</div>");

        // Image
        contenu = contenu.replace("[image]", "<img class=img-fluid src=");
        contenu = contenu.replace("[/image]", "></img>");

        // Image serveur        
        /*
        contenu = contenu.replace(/\[image2=(.+?),icone\](.+?)\[\/image2]/g, "\[image2=$1,32\]$2\[\/image2]"); // Recherche pour remplacer la taille des images en valeur
        contenu = contenu.replace(/\[image2=(.+?),petite\](.+?)\[\/image2]/g, "\[image2=$1,200\]$2\[\/image2]"); // Recherche pour remplacer la taille des images en valeur
        contenu = contenu.replace(/\[image2=(.+?),moyenne\](.+?)\[\/image2]/g, "\[image2=$1,500\]$2\[\/image2]"); // Recherche pour remplacer la taille des images en valeur
        contenu = contenu.replace(/\[image2=(.+?),grande\](.+?)\[\/image2]/g, "\[image2=$1,900\]$2\[\/image2]"); // Recherche pour remplacer la taille des images en valeur
        */

        contenu = contenu.replace(/\[image2=(.+?),([1-9]|[1-9][0-9]|[1-9][0-9][0-9])\](.+?)\[\/image2]/g, "<div data-title='Title 1' data-desc='Description 1' data-responsive-src='/images/$3' data-src='/images/$3' style='display:inline;'> <a href='#'><img class='img-fluid lazy' style='float: $1; margin-top: 10px; margin-bottom: 10px; width: auto; height: auto; max-width: $2px; max-height: $2px;' src='/images/$3'></a></div>"); // Recherche pour enlever la balise ainsi que recuperer l'alignement
        contenu = contenu.replace(/\[image2=(.+?)\](.+?)\[\/image2]/g, "<div data-title='Title 1' data-desc='Description 1' data-responsive-src='/images/$2' data-src='/images/$2' style='display:inline;'> <a href='#'><img class='img-fluid lazy' style='float: $1; margin-top: 10px; margin-bottom: 10px; width: auto; height: auto; max-width: 900px; max-height: 900px;' src='/images/$2'></a></div>"); // Recherche pour enlever la balise ainsi que recuperer l'alignement
        // contenu = contenu.replace("[/image2]", "'></img>");

        // contenu = contenu.replace("[image2]", "<img class='img-fluid' width: auto; height: auto; max-width: 210px; max-height: 210px;' src='/images/");

        // Icone serveur
        contenu = contenu.replace(/\[icone=(.+?)]\[\/icone]/g, "<img class='img-fluid' style='margin:10px; width: auto; height: auto; max-width: 900px; max-height: 900px;' src='/icones/$1'>"); // Recherche pour enlever la balise ainsi que recuperer le nom de l'image

        // lien
        contenu = contenu.replace("[lien]", "<a href='");
        contenu = contenu.replace("[/lien]", "'>");

        // Texte du lien
        contenu = contenu.replace("[texteLien]", "");
        contenu = contenu.replace("[/texteLien]", "</a>");

        // Video
        contenu = contenu.replace("[video]", "<iframe width=640 height=360 class=video-commentaire frameborder=0 allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen src=");
        contenu = contenu.replace("[/video]", " ></iframe>");

        // Remplacer url youtube par une url d'integration
        contenu = contenu.replace("https://www.youtube.com/watch?v=", "https://www.youtube.com/embed/");

        // Taille
        contenu = contenu.replace(/\[taille=(.+?)]/g, '<div style="font-size: $1;">'); // Recherche pour enlever la balise ainsi que recuperer la taille
        contenu = contenu.replace(/\[\/taille]/g, '</div>');

        // Animation
        contenu = contenu.replace(/\[animation=(.+?)](.+?)\[\/animation]/gs, "<div data-aos='$1'>$2</div>"); // Recherche pour enlever la balise ainsi que mettre l'animation

        // Section
        contenu = contenu.replace(/\[section couleur=(.+?) titre=(.+?)](.*?)\[\/section]/gs, "<div class='section' id='$2' style='background-color:$1;'>$3</div>"); // Recherche pour enlever la balise
        contenu = contenu.replace(/\[section gradient=(.+?) titre=(.+?)](.*?)\[\/section]/gs, "<div class='section' id='$2' style='background: linear-gradient($1);'>$3</div>"); // Recherche pour enlever la balise
        contenu = contenu.replace(/\[section video=(.+?) titre=(.+?)](.*?)\[\/section]/gs, "<div class='section' id='$2'><div id='bgndVideo' class='player' data-property='{videoURL:\"$1\",containment:\"self\",autoPlay:0, useOnMobile: true, mute:0, opacity:1, startAt: 1, showControls: false, stopMovieOnBlur: false, remember_last_time: true, addRaster: true, optimizeDisplay: true, showYTLogo: false, ratio: \"auto\"}'>$3</div></div>"); // Recherche pour enlever la balise
        contenu = contenu.replace(/\[section couleur=(.+?)](.*?)\[\/section]/gs, "<div class='section' style='background-color:$1;'>$2</div>"); // Recherche pour enlever la balise
        contenu = contenu.replace(/\[section fond=(.+?) titre=(.+?)](.*?)\[\/section]/gs, "<div class='section' id='$2' style='background-image:url(/images/$1);'>$3</div>"); // Recherche pour enlever la balise
        contenu = contenu.replace(/\[section fond=(.+?)](.*?)\[\/section]/gs, "<div class='section' style='background-image:url(/images/$1);'>$2</div>"); // Recherche pour enlever la balise
        contenu = contenu.replace(/\[section titre=(.+?)](.*?)\[\/section]/gs, "<div class='section' id='$1'>$2</div>"); // Recherche pour enlever la balise
        contenu = contenu.replace(/\[section](.*?)\[\/section]/gs, "<div class='section'>$1</div>"); // Recherche pour enlever la balise

        // Slide
        contenu = contenu.replace(/\[slide couleur=(.+?) titre=(.+?)](.*?)\[\/slide]/gs, "<div class='slide' id='$2' style='background-color:$1;'>$3</div>"); // Recherche pour enlever la balise
        contenu = contenu.replace(/\[slide gradient=(.+?) titre=(.+?)](.*?)\[\/slide]/gs, "<div class='slide' id='$2' style='background: linear-gradient($1);'>$3</div>"); // Recherche pour enlever la balise
        contenu = contenu.replace(/\[slide video=(.+?) titre=(.+?)](.*?)\[\/slide]/gs, "<div class='slide' id='$2'><div id='bgndVideo' class='player' data-property='{videoURL:\"$1\",containment:\"self\",autoPlay:0, useOnMobile: true, mute:0, opacity:1, startAt: 1, showControls: false, stopMovieOnBlur: false, remember_last_time: true, addRaster: true, optimizeDisplay: true, showYTLogo: false, ratio: \"auto\"}'>$3</div></div>"); // Recherche pour enlever la balise
        contenu = contenu.replace(/\[slide couleur=(.+?)](.*?)\[\/slide]/gs, "<div class='slide' style='background-color:$1;'>$2</div>"); // Recherche pour enlever la balise
        contenu = contenu.replace(/\[slide fond=(.+?) titre=(.+?)](.*?)\[\/slide]/gs, "<div class='slide' id='$2' style='background-image:url(/images/$1);'>$3</div>"); // Recherche pour enlever la balise
        contenu = contenu.replace(/\[slide fond=(.+?)](.*?)\[\/slide]/gs, "<div class='slide' style='background-image:url(/images/$1);'>$2</div>"); // Recherche pour enlever la balise

        // Titre
        contenu = contenu.replace(/\[titre=(.+?)]/g, '<div class="$1">'); // Recherche pour enlever la balise ainsi que recuperer le titre
        contenu = contenu.replace(/\[\/titre]/g, '</div>');

        // Couleur
        contenu = contenu.replace(/\[couleur=(.+?)]/g, '<div style="color: $1;">'); // Recherche pour enlever la balise ainsi que recuperer la couleur
        contenu = contenu.replace(/\[\/couleur]/g, '</div>');

        // Couleur de fond
        contenu = contenu.replace(/\[couleurfond=(.+?)]/g, '<div style="background-color: $1;">'); // Recherche pour enlever la balise ainsi que recuperer la couleur de fond
        contenu = contenu.replace(/\[\/couleurfond]/g, '</div>');
        // contenu = contenu.replace(/<input(.+?)>/g, '[TableauEntrée]');

        return contenu;
    }

    // Fonction qui va permettre de chercher des résultats dans les inputs
    function autoCompletion(idInput, categorieRecherche) {
        $(function() { // Fonction autocompletion
            $.widget("custom.catcomplete", $.ui.autocomplete, { // Création des catégories
                _create: function() {
                    this._super();
                    this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
                },
                _renderMenu: function(ul, items) {
                    var that = this,
                        currentCategory = "";
                    $.each(items, function(index, item) {
                        var li;
                        if (item.category != currentCategory) {
                            ul.append("<li class='ui-autocomplete-category'><strong>" + item.category + "</strong>");
                            currentCategory = item.category;
                        }
                        li = that._renderItemData(ul, item);
                        if (item.category) {
                            li.attr("aria-label", item.category + " : " + item.label);
                        }
                    });
                }
            });

            $('#' + idInput).catcomplete({
                    source: function(request, response) {
                        recherche = $('#' + idInput).val();
                        // $('#' + idInput).val().split('"').pop(), // On récupère la valeur de l'input de recherche, le dernier mot dans l'input sera cherhcé
                        $.ajax({
                            url: "/recherche_autocompletion.php",
                            data: {
                                recherche: recherche, // Les données post envoyés à la page de traitement
                                categorieRecherche: categorieRecherche
                            },
                            type: "post",
                            dataType: "json",
                            error: function() {
                                alert("Erreur");
                            },
                            success: function(donnees) {
                                response($.map(donnees, function(item) {
                                    return {
                                        image: item.image,
                                        url: item.url,
                                        date: item.date,
                                        id: item.id,
                                        value: item.value,
                                        category: item.category
                                    }
                                }));
                            }
                        });
                    },
                    minLength: 1,
                    delay: 2,

                    open: function() {
                        if (idInput == "recherche") {
                            $("ul.ui-menu").width($(this).innerWidth()); // Si c'est l'input recherche navbar, on met la taille de l'autocomplétion
                        }
                    },
                    select: function(event, ui) { // Quand on sélectionne un résultat, on va vers sa page
                        if (categorieRecherche != "Jeux" && categorieRecherche != "Plateformes" && categorieRecherche != "Genres") { // Si on est sur la page de création de news, pas besoin de redirigé quand on clique
                            if (ui.item.category == "Jeux") { // Vers page des jeux
                                document.location.href = "/jeu/" + ui.item.url + "-" + ui.item.id;
                            }
                            if (ui.item.category == "Articles") { // Vers page des articles
                                document.location.href = "/news/" + ui.item.url + "-" + ui.item.id;
                            }
                        } else {
                            $('#' + idInput).before("<span class='badge badge-info tag' contenteditable='true' style='margin-left: 5px;'>" + ui.item.label + ' <i class="far fa-window-close" onclick="$(this).parent().remove()";></i></span>');
                            // $('#' + idInput).text("<span class='badge badge-info'>" + $('#' + idInput).val() + '"' + ui.item.label + '"</span>'); // On ajoute le mot recherché
                            $(this).val(''); // On remet la valeur de l'input à zéro
                            return false;
                        }
                    }
                })
                .data('custom-catcomplete')._renderItem = function(ul, item) { // On ajoute les images pour les résultats
                    if (item.category == "Jeux") { // Si c'est un jeu, on change la présentation et l'url
                        return $('<li></li>')
                            .data('ui-item-autocomplete', item)
                            .append('<div><img src="/Jeux/' + item.url + '/miniature/' + item.image + '" onerror="this.oneerror=null; this.src="/1.jpg"; class="img-fluid" style="width:30%; height: auto; max-width:50 px; max-height:150px; border-style: none !important; background-color:transparent;">' + '     ' + item.value + '</div>') // image
                            .appendTo(ul)
                    } else if (item.category == "Articles") { // Si c'est un article, on change la présentation et l'url
                        return $('<li></li>')
                            .data('ui-item-autocomplete', item)
                            .append('<div><img src="/Articles/' + item.date + '/' + item.url + '/miniature/' + item.image + '" onerror="this.oneerror=null; this.src="/1.jpg"; class="img-fluid" style="width:30%; height: auto; max-width:50 px; max-height:150px; background-color:transparent;">' + '     ' + item.value + '</div>') // image
                            .appendTo(ul)
                    } else if (item.category == "Plateformes") { // Si c'est une plateforme, on change la présentation
                        return $('<li></li>')
                            .data('ui-item-autocomplete', item)
                            .append('<div><img src="/images/plateformes/' + item.image + '" onerror="this.oneerror=null; this.src="/1.jpg"; class="img-fluid" style="width:10%; height: auto; max-width:50 px; max-height:150px; background-color:transparent;">' + '     ' + item.value + '</div>') // image
                            .appendTo(ul)
                    } else if (item.category == "Genres") { // Si c'est un genre, on change la présentation
                        return $('<li></li>')
                            .data('ui-item-autocomplete', item)
                            .append('<div>' + item.value + '</div>')
                            .appendTo(ul)
                    }

                }

        });
    }

    // Active le plugin fullpage
    function fullPage(activerSonVideoBackground) {
        $(function() {
            var countSection = 0; // On ajoute l'id de la section                
            var sonPlayer = []; // Va servir pour savoir si un player est mute
            // var color = [];
            var tableauAnchors = [];
            $('#menu').remove();
            $('#menuPlacement').append('<ul style="position: fixed; z-index: 5; border: 5px solid; border-radius: 10px; padding: 0; display: inline-block; margin-left: 10%; margin-right: 10%; top: 7%;" class="row d-none d-sm-block bg-info" id="menu"> </ul>')
            $('.inlinePlayButton').remove();
            $('.section').each(function() {
                // $('#section0').css('background-color', $(this).css('background-color')); // On met l'id de la section qui servira pour le style
                countSlide = 0; // On remet à zéro à chaque nouvelle section
                countSection++;
                // On met l'id de la section qui servira pour le style
                if (this.id) { // Si id, on le prend en tant que nom de la section
                } else {
                    $(this).prop('id', 'Section' + countSection); // Si pas d'id, on a met un
                }
                $('#menu').append('<li style="display: inline;"><a class="btn" style="border: 3px solid; background-color: white; margin: 3px;" href="#' + this.id + '">' + this.id + '</a></li>'); // On ajoute un tooltip par page
                tableauAnchors.push(this.id); // On ajoute chaque ancre      

                sonPlayer.push(false);

                $(this).children('.slide').each(function() { // On parcours chaque slide de section
                    countSlide++;

                    if (this.id) { // Si id, on le prend en tant que nom de slide
                    } else {
                        $(this).prop('id', 'Slide' + countSlide); // Si pas d'id, on a met un
                    }
                    sonPlayer.push(new Array());
                    sonPlayer[countSection].push = false; // On déclare les vidéos aussi dans les slides
                });

                // color.push($(this).css('background-color'));
            })
            var utilisateurInteragit = false;

            // alert(color);
            $('#fullpage').fullpage({
                // A selector used to specify the menu to link with the sections
                menu: '#menu',
                // Whether anchors in the URL will have any effect at all in the library
                lockAnchors: false,
                // Defines the anchor links
                // anchors: tableauAnchors,
                // An array of tooltips
                navigationTooltips: tableauAnchors,
                // Enables active tooltip
                showActiveTooltip: true,
                // Enables navigation
                navigation: true,
                // Shows a navigation for each landscape slide
                slidesNavigation: true,
                slidesNavPosition: 'bottom',
                // Shows browser scrollbar
                scrollBar: true,
                // Creates a scroll for the section/slide in case its content is bigger than the height of it
                // scrollOverflow: true,
                licenseKey: '84707616-4D064813-8B74EF30-87FC83A4',
                // Auto fits sections to the viewport
                fitToSection: true,
                // Enables <a href="https://www.jqueryscript.net/tags.php?/parallax/">parallax</a> backgrounds effects
                parallax: true,
                // Parallax options
                parallaxOptions: {
                    type: 'reveal',
                    percentage: 62,
                    property: 'translate'
                },
                afterLoad: function(origin, destination, direction) {
                    // alert(destination.index); 
                    $('#menu li:nth-child(' + (origin.index + 1) + ') a').css('background-color', 'white');
                    $('#menu li:nth-child(' + (destination.index + 1) + ' ) a').css('background-color', 'blue');

                    section_precedente = (origin.index + 1);
                    section_suivante = (destination.index + 1);

                    if ($('#bgndVideo' + (origin.index + 1)).length) { // Avant d'effectuer des opérations, on regarde si c'était bien une vidéo dans la section
                        $('#bgndVideo' + (origin.index + 1)).YTPPause();
                    }
                    else if ($('#bgndVideo' + section_precedente + '_1').length) { // Avant d'effectuer des opérations, on regarde si c'était bien une vidéo dans la section pour les slides
                        $('#bgndVideo' + section_precedente + '_1').YTPPause();
                    }

                    if ($('#bgndVideo' + (destination.index + 1)).length) { // Avant d'effectuer des opérations, on regarde si c'était bien une vidéo dans la section
                        if (utilisateurInteragit == true && sonPlayer[destination.index] == false && activerSonVideoBackground == 'true') { // On regarde si l'utilisateur à activer le son
                            $('#bgndVideo' + (destination.index + 1)).YTPUnmute(); // Si c'est mute, on unmute
                            sonPlayer[destination.index + 1] = true;
                        }
                        $('#bgndVideo' + (destination.index + 1)).YTPPlay();
                    } 
                    else if ($('#bgndVideo' + section_suivante + '_1').length) { // Avant d'effectuer des opérations, on regarde si c'était bien une vidéo dans le slide, une section rempli de slider ne va pas être compter comme une section, c'est pour ça qu'on doit vérifier aussi comme ça
                        if (utilisateurInteragit == true  && activerSonVideoBackground == 'true') { // On regarde si l'utilisateur à activer le son
                            $('#bgndVideo' + section_suivante + '_1').YTPUnmute(); // Si c'est mute, on unmute
                            sonPlayer[destination.index + 1] = true;
                        }
                        $('#bgndVideo' + section_suivante + '_1').YTPPlay();
                    }
                },
                afterRender: function() {
                    $("body,html").one("touchstart touchmove scroll mousedown DOMMouseScroll mousewheel keyup", function(e) { // On regarde si l'utilisateur à interagit
                        utilisateurInteragit = true; // On unmute
                        if ($('#bgndVideo' + (fullpage_api.getActiveSection().index + 1)).length && activerSonVideoBackground == 'true') {
                            $('#bgndVideo' + (fullpage_api.getActiveSection().index + 1)).YTPUnmute();
                        }
                        else if ($('#bgndVideo' + (fullpage_api.getActiveSection().index + 1) + '_1').length && activerSonVideoBackground == 'true') {
                            $('#bgndVideo' + (fullpage_api.getActiveSection().index + 1) + '_1').YTPUnmute();
                        }
                    });
                    // alert(fullpage_api.getActiveSlide().index + 1);
                    setTimeout(function() {
                        if (($('#bgndVideo' + (fullpage_api.getActiveSection().index + 1)).length)) { // La vidéo se lance après le chargement
                            $('#bgndVideo' + (fullpage_api.getActiveSection().index + 1)).YTPPlay();
                        }
                        else if (($('#bgndVideo' + (fullpage_api.getActiveSection().index + 1) + '_1').length)) { // La vidéo se lance après le chargement pour les slides
                            $('#bgndVideo' + (fullpage_api.getActiveSection().index + 1) + '_1').YTPPlay();
                        }
                    }, 4000);
                },
                afterSlideLoad: function(section, origin, destination, direction) {
                    // alert(destination.index); 
                    //  $('#menu li:nth-child(' + (origin.index + 1) + ') a').css('background-color', 'white');
                    //  $('#menu li:nth-child(' + (destination.index + 1) + ' ) a').css('background-color', 'blue');

                    slide_precedent = (section.index + 1) + '_' + (origin.index + 1);
                    slide_suivant = (section.index + 1) + '_' + (destination.index + 1);

                    if ($('#bgndVideo' + slide_precedent).length) { // Avant d'effectuer des opérations, on regarde si c'était bien une vidéo dans le slide
                        $('#bgndVideo' + slide_precedent).YTPPause();
                    }

                    if ($('#bgndVideo' + slide_suivant).length) { // Avant d'effectuer des opérations, on regarde si c'était bien une vidéo dans le slide
                        if (utilisateurInteragit == true && sonPlayer[destination.index] == false && activerSonVideoBackground == 'true') { // On regarde si l'utilisateur à activer le son
                            $('#bgndVideo' + slide_suivant).YTPUnmute(); // Si c'est mute, on unmute
                            sonPlayer[destination.index + 1] = true;
                        }
                        $('#bgndVideo' + slide_suivant).YTPPlay();
                    }
                }
            });
        });
    }

    // Permet de scroller vers une ancre
    function scrollerVersAncre(idAncre) {
        $("html, body").animate({
            scrollTop: $('#' + idAncre).offset().top
        }, "slow"); // Scrolling vers le formulaire de modification
    }

     // Active le plugin fullpage
     function fullPageJeu(activerSonVideoBackground) {
        $(function() {
          var countSection = 0; // On ajoute l'id de la section                
          var sonPlayer = []; // Va servir pour savoir si un player est mute
          // var color = [];
          var tableauAnchors = [];
          $('#menu').remove();
          $('#menuPlacement').append('<ul style="position: fixed; z-index: 5; border: 5px solid; border-radius: 10px; padding: 0; display: inline-block; margin-left: 10%; margin-right: 10%; top: 7%;" class="row d-none d-sm-block bg-info" id="menu"> </ul>')
           $('#jeu_onglet').prepend($('div[name=sectionPresentationDebut]')); // On déplace la section du début pour pouvoir la faire reconnaitre par le plugin, essayé de penser à une façon d'optimiser la position de cette section sans la déplacer
           $('#jeu_onglet').append($('div[name=sectionPresentationFin]')); // On déplace la section de fin pour pouvoir la faire reconnaitre par le plugin
         // $('#sectionPresentationDebut').remove(); // On met une section autour du début de la présentation du jeu, première section après l'Ajax.
         $('.inlinePlayButton').remove();
          $('.section').each(function() {
              // alert($(this).prop('id'));
            // $('#section0').css('background-color', $(this).css('background-color')); // On met l'id de la section qui servira pour le style
            countSlide = 0; // On remet à zéro à chaque nouvelle section
            countSection++;
            // On met l'id de la section qui servira pour le style
            if (this.id) { // Si id, on le prend en tant que nom de la section
            } else {
              $(this).prop('id', 'Section' + countSection); // Si pas d'id, on a met un
            }
            $('#menu').append('<li style="display: inline;"><a class="btn btn_menu" style="border: 3px solid; background-color: white; margin: 3px;" href="#">' + this.id + '</a></li>'); // On ajoute un tooltip par page
            // $('#menu').append('<li style="display: inline;"><a class="btn" style="border: 3px solid; background-color: white; margin: 3px;" href="#' + this.id + '">' + this.id + '</a></li>'); // On ajoute un tooltip par page

              tableauAnchors.push(this.id); // On ajoute chaque ancre      

              sonPlayer.push(false);

              $(this).children('.slide').each(function() { // On parcours chaque slide de section
                countSlide++;

                if (this.id) { // Si id, on le prend en tant que nom de slide
                } else {
                  $(this).prop('id', 'Slide' + countSlide); // Si pas d'id, on a met un
                }
                sonPlayer.push(new Array());
                sonPlayer[countSection].push = false; // On déclare les vidéos aussi dans les slides
              });

              // color.push($(this).css('background-color'));
            })
            var utilisateurInteragit = false;

            // alert(color);
            $('#fullpage').fullpage({
              // A selector used to specify the menu to link with the sections
              menu: '#menu',
              // Whether anchors in the URL will have any effect at all in the library
              lockAnchors: false,
              // Defines the anchor links
              // anchors: tableauAnchors,
              // An array of tooltips
              navigationTooltips: tableauAnchors,
              // Enables active tooltip
              showActiveTooltip: true,
              // Enables navigation
              navigation: true,
              // Shows a navigation for each landscape slide
              slidesNavigation: true,
              slidesNavPosition: 'bottom',
              // Shows browser scrollbar
              scrollBar: true,
              // Creates a scroll for the section/slide in case its content is bigger than the height of it
              // scrollOverflow: true,
              licenseKey: '84707616-4D064813-8B74EF30-87FC83A4',
              autoScrolling: true,
              // Auto fits sections to the viewport
              fitToSection: false,
              // Enables <a href="https://www.jqueryscript.net/tags.php?/parallax/">parallax</a> backgrounds effects
              parallax: true,
              // Parallax options
              parallaxOptions: {
                type: 'reveal',
                percentage: 62,
                property: 'translate'
              },
              afterLoad: function(origin, destination, direction) {
                // alert(destination.index); 
                $('#menu li:nth-child(' + (origin.index + 1) + ') a').css('background-color', 'white');
                $('#menu li:nth-child(' + (destination.index + 1) + ' ) a').css('background-color', 'blue');

                section_precedente = (origin.index + 1);
                section_suivante = (destination.index + 1);

               // alert(section_suivante);

                if ($('#bgndVideo' + (origin.index + 1)).length) { // Avant d'effectuer des opérations, on regarde si c'était bien une vidéo dans la section
                  $('#bgndVideo' + (origin.index + 1)).YTPPause();
                } else if ($('#bgndVideo' + section_precedente + '_1').length) { // Avant d'effectuer des opérations, on regarde si c'était bien une vidéo dans la section pour les slides
                  $('#bgndVideo' + section_precedente + '_1').YTPPause();
                }

                if ($('#bgndVideo' + (destination.index + 1)).length) { // Avant d'effectuer des opérations, on regarde si c'était bien une vidéo dans la section
                  if (utilisateurInteragit == true && sonPlayer[destination.index] == false && activerSonVideoBackground == 'true') { // On regarde si l'utilisateur à activer le son
                    $('#bgndVideo' + (destination.index + 1)).YTPUnmute(); // Si c'est mute, on unmute
                    sonPlayer[destination.index + 1] = true;
                  }
                  $('#bgndVideo' + (destination.index + 1)).YTPPlay();
                } else if ($('#bgndVideo' + section_suivante + '_1').length) { // Avant d'effectuer des opérations, on regarde si c'était bien une vidéo dans le slide, une section rempli de slider ne va pas être compter comme une section, c'est pour ça qu'on doit vérifier aussi comme ça
                  if (utilisateurInteragit == true && activerSonVideoBackground == 'true') { // On regarde si l'utilisateur à activer le son
                    $('#bgndVideo' + section_suivante + '_1').YTPUnmute(); // Si c'est mute, on unmute
                    sonPlayer[destination.index + 1] = true;
                  }
                  $('#bgndVideo' + section_suivante + '_1').YTPPlay();
                }
              },
              afterRender: function() {
                fullpage_api.reBuild();

                $("body,html").one("touchstart touchmove scroll mousedown DOMMouseScroll mousewheel keyup", function(e) { // On regarde si l'utilisateur à interagit
                  utilisateurInteragit = true; // On unmute
                  if ($('html').hasClass('fp-enabled')) {
                  if ($('#bgndVideo' + (fullpage_api.getActiveSection().index + 1)).length && activerSonVideoBackground == 'true') {
                    $('#bgndVideo' + (fullpage_api.getActiveSection().index + 1)).YTPUnmute();
                  } else if ($('#bgndVideo' + (fullpage_api.getActiveSection().index + 1) + '_1').length && activerSonVideoBackground == 'true') {
                    $('#bgndVideo' + (fullpage_api.getActiveSection().index + 1) + '_1').YTPUnmute();
                  }
                }
                });
                // alert(fullpage_api.getActiveSlide().index + 1);
                setTimeout(function() {
                      // Destruction des sections si le plugin est initialisé
                      if ($('html').hasClass('fp-enabled')) {
                  if (($('#bgndVideo' + (fullpage_api.getActiveSection().index + 1)).length)) { // La vidéo se lance après le chargement
                    $('#bgndVideo' + (fullpage_api.getActiveSection().index + 1)).YTPPlay();
                  } else if (($('#bgndVideo' + (fullpage_api.getActiveSection().index + 1) + '_1').length)) { // La vidéo se lance après le chargement pour les slides
                    $('#bgndVideo' + (fullpage_api.getActiveSection().index + 1) + '_1').YTPPlay();
                  }
                }
                }, 4000);
              },
              afterSlideLoad: function(section, origin, destination, direction) {
                // alert(destination.index); 
                //  $('#menu li:nth-child(' + (origin.index + 1) + ') a').css('background-color', 'white');
                //  $('#menu li:nth-child(' + (destination.index + 1) + ' ) a').css('background-color', 'blue');

                slide_precedent = (section.index + 1) + '_' + (origin.index + 1);
                slide_suivant = (section.index + 1) + '_' + (destination.index + 1);

                if ($('#bgndVideo' + slide_precedent).length) { // Avant d'effectuer des opérations, on regarde si c'était bien une vidéo dans le slide
                  $('#bgndVideo' + slide_precedent).YTPPause();
                }

                if ($('#bgndVideo' + slide_suivant).length) { // Avant d'effectuer des opérations, on regarde si c'était bien une vidéo dans le slide
                  if (utilisateurInteragit == true && sonPlayer[destination.index] == false && activerSonVideoBackground == 'true') { // On regarde si l'utilisateur à activer le son
                    $('#bgndVideo' + slide_suivant).YTPUnmute(); // Si c'est mute, on unmute
                    sonPlayer[destination.index + 1] = true;
                  }
                  $('#bgndVideo' + slide_suivant).YTPPlay();
                }
              }
            });
            $(".btn_menu").click(function(event) {
              event.preventDefault(); // On récupère les click du menu pour ne pas que la page se recharge
              $(this).parent().parent().find('a').removeClass('active');
              $(this).parent().parent().find('a').css('background-color', 'white');

              $(this).addClass('active');
              $(this).css('background-color', 'blue');
              fullpage_api.moveTo($(this).parent().index() + 1);
            });
          });
     }

    // Script pour changer de page avec les fleches ou un swipe
    function changerPage(lienPagePrecendente, lienPageSuivante, nbPagePrecedente, nbPageSuivante) {
        $(document).on("keydown", function(e) {
            if (!$(e.target).is(":input") && (($('#ajout_commentaire').val() == "") || !document.getElementById('#ajout_commentaire'))) { // Si l'utilisateur n'est pas dans un input, on peut changer de page
                if (nbPagePrecedente > 0) // On regarde si il y a une page précédente
                {
                    if (e.which == "37") { // Si la flèche gauche est appuyé, on va à la page précédente
                        document.location.href = lienPagePrecendente;
                    }
                }
                if (nbPageSuivante > 0) {
                    if (e.which == "39") // Si la flèche droite est appuyé, on va à la page suivante
                    {
                        document.location.href = lienPageSuivante;
                    }
                }
            }
        });
        $('body').each(function(e) {
            delete Hammer.defaults.cssProps.userSelect; // Pour laisser le copié coller
            var hammertime = new Hammer(this);
            if (!$(e.target).is(":input") && (($('#ajout_commentaire').val() == "") || !document.getElementById('#ajout_commentaire'))) { // Si l'utilisateur n'est pas dans un input, on peut changer de page
                if (nbPagePrecedente > 0) // On regarde si il y a une page précédente
                {
                    hammertime.on('swipeleft', function(ev) { // Si un swipe gauche, on va à la page précédente
                        if (ev.pointerType === 'touch') {
                            document.location.href = lienPagePrecendente;
                        }
                    });
                }
                if (nbPageSuivante > 0) {
                    hammertime.on('swiperight', function(ev) { // Si un swipe droite, on va à la page précédente
                        if (ev.pointerType === 'touch') {
                            document.location.href = lienPageSuivante;
                        }
                    })
                }
            }
        });
    }

    // Permet de jouer un son avec un hover
    function jouerSonBruitage() {
        $(document).ready(function() {
            $("a.liste-item-sans-bordure, a.liste-item-news").hover(function() { // Pour jouer son
                    document.getElementById('audio_bouton').pause();
                    var promise = document.getElementById('audio_bouton').play();
                    if (promise) {
                        //Older browsers may not return a promise, according to the MDN website
                        promise.catch(function(error) {
                            console.error(error);
                        });
                    }
                    document.getElementById('audio_bouton').muted = false;
                },
                function() {
                    document.getElementById('audio_bouton').load();
                });
        });
    }


    // La fonction va servir à une fois que le numéro de la page à été entrer et que la touche entrée est pressé, va etre rediger vers la page demandé
    function inputPageRecherche() {
        $('#page').on('keypress', function(e) { // Si on est sur l'input
            if (e.which == 13) { // Si la touche entrée est pressé
                $(this).attr("disabled", "disabled"); // On désactive l'input pour éviter une multiple saisie

                lien = "<?php echo "/recherche.php"; ?>?recherche=<?php echo $_GET['recherche'];
                                                                    if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['categorie_jeu'])) echo '&categorie_jeu=' . $_GET['categorie_jeu']; ?>&page=" + this.value;
                document.location.href = lien; // Redirection
                $(this).removeAttr("disabled");
            }
        });
    }

    // La fonction va servir à savoir les différents tags renseignés de l'article
    function tagsCreationArticles(idTags, idDivLierValeurs) {
        $(function() {

                $("#creation_news, #modifier_news, #modifier_jeu, #creation_jeu").submit(function()
                    // Permet de récuperer chaque tag avec le formulaire
                    {
                        var tags = "";
                        i = 0; // Permet de savoir si on c'est le premier tag, du coup, on aura pas besoin d'ajouter une virgule
                        $('#' + idDivLierValeurs + ' span').each(function() { // les valeurs des tags renseignés
                            if (i > 0) {
                                tags = tags + "," + $(this).text().slice(0, -1); // Ajout de la virgule
                            } else {
                                tags = tags + $(this).text().slice(0, -1);
                            }

                            i += 1;

                        });
                        // alert(tags);
                        $(this).append('<input type="hidden" name="' + idTags + '" id="' + idTags + '">') // Valeur input à remplir via les tags au moment où on soumet le formulaire 
                        $('#' + idTags).val(tags);
                        // alert(tags);      
                    });
            }

            // Il faudra changer la valeur de l'input tags au moment envoyer
        )
    }


    // Fonction qui prend en paramètre un contenu et va le mettre dans un champ
    function modifierContenuChamp(idContenu, idChamp) {

        var contenuChamp1 = $("#" + idContenu).html();
        $("#" + idChamp).text(contenuChamp1);
    }

    // Fonction qui va ajouter le contenu d'un commentaire ainsi que son id dans le lien du formulaire et affiche le formulaire de modif
    function ajoutModificationCommentaire(idContenu, idChamp, idFormulaireCommentaire, idCommentaire, idFormulaireAjoutCommentaire, type_id) {

        modifierContenuChamp(idContenu, idChamp);
        var valeurActionFormulaireAvant = $('#' + idFormulaireCommentaire).attr('action');

        $("#" + idFormulaireCommentaire).attr('action', valeurActionFormulaireAvant + '&' + type_id + '=' + idCommentaire); // Passe l'id du commentaire à la valeur du formulaire
        if (type_id == 'id_avis') { // Si on modifie un avis, on transmet la valeur de la note
            note = $('input[name="note' + idCommentaire + '"]:checked').val(); // On récupère la note

            $('#note_label').text(note); // On la met
            $('input[name="note_modifier"]').rating('select', note);
        }
        $('#' + idFormulaireCommentaire).show(); // Rend visible le formulaire
        $('#' + idFormulaireAjoutCommentaire).hide(); // Rend caché l'autre formulaire

        $("html, body").animate({
            scrollTop: $('#' + idFormulaireCommentaire).offset().top
        }, "slow"); // Scrolling vers le formulaire de modification
    }

    // Fonction qui va faire apparaitre un formulaire et en caché un autre
    function changementMenu(idFormulaireACacher, idFormulaireAFaireApparaitre) {
        $('#' + idFormulaireAFaireApparaitre).show(); // Rend visible le formulaire
        $('#' + idFormulaireACacher).hide(); // Rend caché l'autre formulaire
    }
</script>