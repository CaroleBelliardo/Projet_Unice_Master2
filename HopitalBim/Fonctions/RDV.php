<?php 
	
function ProchaineHeureArrondie(){ // affichage des valeurs des tableaux
$current_time = strtotime(date('H:i'));
$frac = 900;
$r = $current_time % $frac;
$heureArrondie = date('H:i',($current_time + ($frac-$r)));
return ($heureArrondie);
}


