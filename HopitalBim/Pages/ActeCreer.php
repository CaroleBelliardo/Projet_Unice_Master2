<?php
	include ('../Config/Menupage.php');

?>

	
<!DOCTYPE html PUBLIC >
<html>
	<head>
		<title> Nouvelle intervention </title> <!-- Titre de l'onglet -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" href="../Config/Style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">	
	</head>

	<body>
		
		<?php // affichage
			If (!array_key_exists("serviceModifier",$_SESSION )) 
			{
				include ('../Formulaires/RechercheService.php');; // recherche le service
			}
			else
			{
				include ('../Formulaires/Formulaire_AjoutActe.php');
		
			}
			include ('../Config/Footer2.php'); //menu de navigation
		?>

	</body>
</html>
