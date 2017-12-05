<?php
	include ('../Config/Menupage.php');

	$lien='Facturation.php';


	if ($user_id != 'admin00' and $_SESSION["chefService"]= TRUE)  // si pas chef de service 
		{
			//echo "Pas d'accès à cette fonctionnalité de la plateforme";
			//sleep(5);
			$auth_user->redirect('../Pageprincipale.php');
		}
		else
		{
			If (!array_key_exists("patient",$_SESSION )) // recherche si patient existe (redirection fiche patient)
			{
				include ('../Formulaires/RecherchePatient.php');
			}
			else
			{
				include ('../Fonctions/Facturation.php');
			
			}
		}	
?>
