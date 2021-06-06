<?php
// Calcule la distance optimale 
function minDistance($lesMaisons)
{        
    $distanceMinimale = 0; // Distance minimale entre les maisons

    for ($i = 0; $i < Count($lesMaisons); $i++) { // Chaque choix de maison pour Tony sera testé
        $distancePourUneMaison = 0;

        for ($j = 0; $j < Count($lesMaisons); $j++) { // Distance par rapport aux maisons
            $distancePourUneMaison = $distancePourUneMaison + (abs($lesMaisons[$i] - $lesMaisons[$j])); // On ajoute le résultat des distances des autres maisons
        }

        if ($distanceMinimale == 0 || ($distanceMinimale > $distancePourUneMaison)) { // Si la distance est inférieure à la plus inférieure qu'on a testé, on la remplace
            $distanceMinimale = $distancePourUneMaison;
        }
    }
    return $distanceMinimale; // Distance Optimale
}

$lesMaisons = array(3000);
$lesMaisons[0] = 1;
$lesMaisons[1] = 2;
$lesMaisons[2] = 4;
$lesMaisons[3] = 5;

echo minDistance($lesMaisons);
?>