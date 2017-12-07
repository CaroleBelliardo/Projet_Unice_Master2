
 <?php

	include ('../Config/Menupage.php');
	$lien ='RDVModification.php';


?>	

<!DOCTYPE html PUBLIC >
<html>
	<head>
		<title>Modifier un utilisateur</title>
		<link rel="stylesheet" href="Style.css">
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		</head>

	<body>
		<?php // affichage
			If (!array_key_exists("rdvModifier",$_SESSION ) or ($_SESSION["chefService"] != TRUE)) 
			{
				include ('../Pages/Planning.php');; // recherche le service
			}
			else
			{	
				include ('../Formulaires/Formulaire_RDVModification.php');; // recherche patient existe pas (redirection fiche patient)
			}
			include ('../Config/Footer.php'); //menu de navigation
		?>

	</body>
</html>