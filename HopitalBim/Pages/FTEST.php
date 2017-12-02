<?php
	// fonction qui permet d afficher les requetes sql et donc permet de jouer avec les données 
	/*  A FAIRE : 
		- champs html du departement et du pays afficher une liste ( plutot que de le taper) 
		- les includes a faire.
	*/
include ('../Config/Menupage.php');
$a=[1,2]	;
 $recupUrgMaxMin = $auth_user->runQuery("UPDATE CreneauxInterventions 
SET niveauUrgence =( niveauUrgence +'1'), statut ='a'
WHERE id_rdv=:a
 

");

foreach ($a as $k=> $v) {

 $recupUrgMaxMin->execute(array('a'=>$v)); 

	
} 
	$recupUrgMaxMin ->closeCursor();
?>
