<?php

	include ('../Config/Menupage.php');
	$lien ='VerificationNotification.php';

	If ($_SESSION['chefService'] != TRUE) 
	{
		$auth_user->redirect('../PagePrincipale.php'); // recherche le service
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
		
		if ($_SESSION['idEmploye'] == 'admin00') 
		{
			$req_notif= $auth_user->runQuery("SELECT DISTINCT id_rdv as Identifiant_RDV, niveauUrgence, niveauUrgenceMax, niveauUrgenceMin,
											 Employes.ServicesnomService as Service, date_rdv as Date, heure_rdv as Heure, 
											 statut, EmployesCompteUtilisateursidEmploye, Patients.nom,
											 Patients.prenom, CreneauxInterventions.commentaires
											 FROM Notifications JOIN CreneauxInterventions JOIN Patients JOIN Employes JOIN InterventionsPatho
											 WHERE Notifications.CreneauxInterventionsidRdv = CreneauxInterventions.id_rdv
											 AND Patients.numSS=CreneauxInterventions.PatientsnumSS
											 AND InterventionsPatho.InterventionsidIntervention=CreneauxInterventions.InterventionsidIntervention
											 AND Employes.CompteUtilisateursidEmploye =CreneauxInterventions.EmployesCompteUtilisateursidEmploye
											 AND Notifications.ServicesnomService = :service GROUP BY id_rdv");
			$req_notif->execute(array("service"=>$_SESSION['service']));
			$a_infoNotif=reqToArrayPlusligne($req_notif) ;
				
		}
		else // notif chef de service
		{
			$req_notif= $auth_user->runQuery("SELECT DISTINCT id_rdv as Identifiant_RDV, date_rdv as Date, heure_rdv as Heure,
											 Employes.ServicesnomService as Service,
											 niveauUrgence, statut, EmployesCompteUtilisateursidEmploye, Patients.nom,
											 Patients.prenom, CreneauxInterventions.commentaires
											 FROM Notifications JOIN CreneauxInterventions JOIN Patients JOIN Employes
											 WHERE Notifications.CreneauxInterventionsidRdv = CreneauxInterventions.id_rdv
											 AND Patients.numSS=CreneauxInterventions.PatientsnumSS
											 AND Employes.CompteUtilisateursidEmploye =CreneauxInterventions.EmployesCompteUtilisateursidEmploye
											 AND Notifications.ServicesnomService = :service GROUP BY id_rdv");
			$req_notif->execute(array("service"=>$_SESSION['service']));
			$a_infoNotif=reqToArrayPlusligne($req_notif) ;
		}
		
		
		if($a_infoNotif == FALSE)
			{
				echo "Aucune notifications";
			}
			else
			{

	?>
				<CENTER>
					<table BORDER="1",ALIGN="CENTER", VALIGN="MIDDLE ">
						<tr>
	<?php
							foreach ($a_infoNotif as $col=>$line) // $col = colonne
							{
	?>						<th><?php echo $col ?></th>
	<?php							
							}
	?>					</tr>
	<?php
						
						$nb_notifs= count($a_infoNotif["Identifiant_RDV"]);
						for ($i = 0; $i < $nb_notifs; $i++)
						{
	?>
							<tr>
	<?php
							foreach ($a_infoNotif as $col=>$line) // $col = colonne
							{
	?>
								<td>
									<?php echo $a_infoNotif[$col][$i] ?>
								</td>
	<?php					}
	?>
							</tr>
	<?php
							
							
						}
   
						
	?>				
					</table>
				</CENTER>
		
<?php
			}
	include ('../Config/Footer.php'); //menu de navigation
?>
	</body>
</html>