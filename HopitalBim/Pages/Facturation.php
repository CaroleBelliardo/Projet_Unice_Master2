<?php
	include ('../Config/Menupage.php');

	$lien='Facturation.php';

	If ($_SESSION['chefService'] != TRUE) 
	{
		$auth_user->redirect('../PagePrincipale.php'); // recherche le service
	}
	else
	{
		If (!array_key_exists("patient",$_SESSION )) // recherche si patient existe (redirection fiche patient)
		{
			include ('../Formulaires/Formulaire_RecherchePatient.php');
		}
		else
		{
			include ('../Fonctions/Fonctions_Facturation.php');
		
		}
	}
	include ('../Config/Footer.php'); //menu de navigation

?>
