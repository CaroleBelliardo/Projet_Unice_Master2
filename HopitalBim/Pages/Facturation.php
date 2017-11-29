<?php
include ('../Config/Menupage.php');

$lien='Facturation.php'

// variables 

?>

<!DOCTYPE html PUBLIC >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>Demande RDV</title>
</head>

<body>

	<?php // affichage
		if ($user_id != 'admin00' and $test_chef == FALSE)  // si pas chef de service 
			{
				$auth_user->redirect('../Pageprincipale.php');
				echo "medecin";
			}
			Else 
			{
				If (!array_key_exists("patient",$_SESSION )) // recherche si patient existe (redirection fiche patient)
				{
					include ('../Pages/RecherchePatient.php');;
			?>
				
			<?php
					//include ('../Fonctions/patientRecherche.php');
				}
				else
				{
					quitter1($auth_user);
					?>

				
				<?php
				}
			}	
	?>
	
	
		
</body>


</html>
