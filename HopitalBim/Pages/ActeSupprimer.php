<?php
	include ('../Config/Menupage.php');
	


?>

<!DOCTYPE html PUBLIC >
<html>
	<head>
		<title>Archiver un service</title>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<link rel="stylesheet" href="../Config/Style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">	
	</head>
	<?php
			if ($_SESSION["idEmploye"] != 'admin00')
			{
				$auth_user->redirect('../Pageprincipale.php');
			}
			else
			{
				include ('../Formulaires/Formulaire_ActeSupprimer.php');  
	?>	
	<body>
	<?php	
				include ('../Config/Footer2.php'); 
			}
	?>
	</body>
</html>
