<?php
	include ('../Config/Menupage.php');
	$lien='ActeCreer.php';
?>

<!DOCTYPE html PUBLIC >
<html>
	<head>
		<title> Ajouter un acte </title> <!-- Titre de l'onglet -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" href="../Config/Style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">	
	</head>

	<body>
		
		<?php // affichage
			if ($_SESSION["idEmploye"] != 'admin00')
			{
				$auth_user->redirect('../Pageprincipale.php');
			}
			else
			{
				If (!array_key_exists("serviceModifier",$_SESSION )) 
				{ 
					include ('../Formulaires/Formulaire_RechercheService.php');; // recherche le service
				}
				else
				{
					include ('../Formulaires/Formulaire_acteCreer.php');
				}
				include ('../Config/Footer2.php'); //menu de navigation
			}
		?>

	</body>
</html>
