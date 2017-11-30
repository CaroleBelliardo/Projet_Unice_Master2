<?php 
	
function ProchaineHeureArrondie()
    { // affichage des valeurs des tableaux
        $current_time = strtotime(date('H:i'));
        $frac = 900;
        $r = $current_time % $frac;
        $heureArrondie = date('H:i',($current_time + ($frac-$r)));
        return ($heureArrondie);
    }

    function heurePlus15($h) // prend en entré une string h:m et renvoye l'heure + 15minutes
	{
		$heure = strtotime($h);
		$heurePlus15 =date("H:i", strtotime('+15 minutes',$heure));
		return ($heurePlus15);
	}

