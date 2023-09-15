<?php

	include ('../Config/Menupage.php');
	$lien ='CompteUtilModifier.php';

	if ($_SESSION["idEmploye"] != 'admin00')
	{
		$auth_user->redirect('../Pageprincipale.php');
	}
?>	

<!DOCTYPE html PUBLIC >
<html>
	<head>
		<title>Modifier un utilisateur</title>
		<link rel="stylesheet" href="Style.css">
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
	</head>

	<body>
		<?php // affichage
			If (!array_key_exists("utilisateurModifier",$_SESSION )) 
			{
				include ('../Formulaires/Formulaire_RechercheUtilisateur.php');; // recherche le service
			}
			else
			{
				$req_utilisateur = $auth_user->runQuery("SELECT * 
														FROM Employes Join Adresses JOIN Villes
														WHERE Employes.AdressesidAdresse = Adresses.idAdresse
														AND Adresses.VillesidVilles = Villes.idVilles
														AND  CompteUtilisateursidEmploye = :utilisateur");
				$req_utilisateur->execute(array("utilisateur"=>$_SESSION['utilisateurModifier']));
				$utilisateurInfo=$req_utilisateur -> fetch(PDO::FETCH_ASSOC);
				include ('../Formulaires/Formulaire_CompteUtilModifier.php');; // recherche patient existe pas (redirection fiche patient)
			}
		?>
	</body>
</html>