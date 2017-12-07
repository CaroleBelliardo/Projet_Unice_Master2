<?php

	include ('../Config/Menupage.php');
	$lien ='VerificationNotification.php';

	If ($_SESSION['chefService'] != TRUE) 
	{
		$auth_user->redirect('../PagePrincipale.php'); // recherche le service
	}
	else
	{
		$req_notif= $auth_user->runQuery("SELECT * 
			FROM Notifications JOIN CreneauxInterventions JOIN Patients JOIN Employes
			WHERE Notifications.CreneauxInterventionsidRdv = CreneauxInterventions.id_rdv
			AND Patients.numSS=CreneauxInterventions.PatientsnumSS
			AND Employes.CompteUtilisateursidEmploye =CreneauxInterventions.EmployesCompteUtilisateursidEmploye
			AND  Notifications.ServicesnomService = :service");
		
		$req_notif->execute(array("service"=>$_SESSION['service']));
		$a_infoNotif=reqToArrayPlusligne($req_notif) ;
	}

?>	

<!DOCTYPE html PUBLIC >
<html>
	<head>
		<title>Notifications utilisateur</title>
		<link rel="stylesheet" href="Style.css">
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	</head>

	<body>
	<?php
		if($a_infoNotif == FALSE)
		{
			echo "Aucune notifications";
		}
		else
		{
	?>
			<CENTER>
				<table BORDER="1",ALIGN="CENTER", VALIGN="MIDDLE ">
					<tr><th></th>

				</table>
			</CENTER>
	<?php
		}
		
	?>	
		
		
		
<?php
	include ('../Config/Footer.php'); //menu de navigation
?>
	</body>
</html>