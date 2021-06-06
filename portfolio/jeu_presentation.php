<div>
    <p class="d-flex justify-content-center"><em>Sortie le <?php echo htmlspecialchars($donnees['date_jeu']); ?></em></p>
</div>
<img src="/Jeux/<?php echo $donnees['nom']; ?>/miniature/<?php echo htmlspecialchars($donnees['nom_miniature']); ?>" onerror="this.oneerror=null; this.src='/banniere.jpg';" class="d-block img-fluid mx-auto" style="margin:1vh">
<!-- <img src="/miniature/<?php echo $donnees['nom_miniature'] ?>" onerror="this.oneerror=null; this.src='/1.jpg';" class="d-block img-fluid" style="width:800vh; height:50vh; margin:1vh"> -->
<p class="justify-content-center text-break text-justify">
    <div class="contenu-jeu"><?php echo remplacementBBCode(nl2br(htmlspecialchars($donnees['contenu'])), true, false); ?></div>
</p>
<?php
$reponse->closeCursor();
?>