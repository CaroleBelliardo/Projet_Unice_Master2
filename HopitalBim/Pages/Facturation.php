<?php
	include ('../Config/Menupage.php');

	$lien='Facturation.php';

	If ($_SESSION['chefService'] != TRUE) 
	{
		$auth_user->redirect('../Pageprincipale.php'); // recherche le service
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

?>

<?php 	include ('../Config/Footer2.php'); //menu de navigation ?>
