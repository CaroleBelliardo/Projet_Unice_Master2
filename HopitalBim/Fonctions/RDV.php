<?php 
	
function ProchaineHeureArrondie(){ // fonction qui permet de recuperer l'heure actuelle et de l'arrondir au prochain creneaux de 15 mins
$current_time = strtotime(date('H:i')); // recupere l'heure actuelle sous format hh:mm
$frac = 900;                            // pour definir le creneaux horaire :  15 min x 60 sec = 900 
$r = $current_time % $frac;             // calcul le nombre de minutes necessaire a ajouter.
$heureArrondie = date('H:i',($current_time + ($frac-$r))); // calcule combien de minute il faut ajouter pour passer au prochain creneaux de 15 mins
return ($heureArrondie); // retourne la prochaine heure arrondie
}

    function heurePlus15($h,$temps) // prend en entré une string h:m et renvoye l'heure + 15minutes
	{
		$heure = strtotime($h);
		$heurePlus15 =date("H:i", strtotime( $temps,$heure));
		return ($heurePlus15);
	}
