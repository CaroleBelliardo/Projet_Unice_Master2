<?php
	include ('../Config/Menupage.php');
	if ($_SESSION["idEmploye"] != 'admin00')
	{
		$auth_user->redirect('../Pageprincipale.php');
	}
?>

<!DOCTYPE html PUBLIC >
<html>
	<head>
		<title>Ajouter un utilisateur</title>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<link rel="stylesheet" href="../Config/Style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
	</head>

	<body>
		<?php
		include ('../Formulaires/Formulaire_CompteUtilCreer.php');  
		include ('../Config/Footer.php'); //pied de page ?>
	</body>
	
</html>
