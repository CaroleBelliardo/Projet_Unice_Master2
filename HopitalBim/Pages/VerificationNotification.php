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
		<title>Notifications </title>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<link rel="stylesheet" href="Style.css">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
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
											 AND WEEK(CreneauxInterventions.date_rdv) = WEEK( CURRENT_DATE) 
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

		<div class="containerTab">
				
		<CENTER>
		<table id="synthese" border="1",ALIGN="CENTER", VALIGN="MIDDLE ">
		<caption> Tableau des Notifications </caption> <!-- légende du tableau -->

		<tr> <!-- 1ère ligne  - -->

			<?php
							foreach ($a_infoNotif as $col=>$line) // $col = colonne
							{
			?>						

			<th class="haut"> <?php echo $col ?> </th> <!-- en tête  -->

	
			<?php							
				  }
			?>					

		</tr>
	
			<?php
						$nb_notifs= count($a_infoNotif["Identifiant_RDV"]);
						for ($i = 0; $i < $nb_notifs; $i++)
						{
			?>
							
		<tr> <!-- lignes suivantes -->
	
			<?php
				foreach ($a_infoNotif as $col=>$line) // $col = colonne
				{
			?>
				<td> <?php // echo "<button type='submit' class='btn btn-primary' value=".$infoServiceJours[$h][$acte]["id_rdv"]." name='btn-Valider'>   Valider   </button>";
			?>
				</td>
			<td> <?php echo $a_infoNotif[$col][$i] ?> </td>

			<?php					
				}
			?>
							
		</tr>

			<?php						
				}

			?>				
					
		</table>
		</CENTER>

		<?php
			}
		?>

		</div> <!-- div containerTab -->

	<?php include ('../Config/Footer2.php'); //menu de navigation ?>

	</body>

</html>