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
	</head>
	<body>
		
		<?php // affichage
			if ($_SESSION["idEmploye"] != 'admin00')
			{
				$auth_user->redirect('../PagePrincipale.php');
			}
			else
			{
				If (!array_key_exists("serviceModifier",$_SESSION )) 
				{
					include ('../Formulaires/Formulaire_RechercheService.php');; // recherche le service
				}
				else
				{
					include ('../Formulaires/Formulaire_AjoutActe.php');
			
				}
				include ('../Config/Footer.php'); //menu de navigation
			}
?>
	</body>
</html>
