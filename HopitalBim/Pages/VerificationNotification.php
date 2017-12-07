<?php

	include ('../Config/Menupage.php');
	$lien ='VerificationNotification.php';

	If ($_SESSION['chefService'] != TRUE) 
	{
		$auth_user->redirect('../PagePrincipale.php'); // recherche le service
	}

	
	if (isset ($_POST["btn-Accepter"]))
	{
		//recup info rdv
		$req_info = $auth_user->runQuery(" SELECT CreneauxInterventions.PathologiesnomPatho, CreneauxInterventions.InterventionsidIntervention, niveauUrgence
										FROM CreneauxInterventions JOIN Inter 
										WHERE id_rdv = :idrdv" ); 
		$req_info->execute(array('idrdv'=> $_POST["btn-Realise"]));
		$a_infoo= $req_info-> fetch(PDO::FETCH_ASSOC);
		$req_info->closeCursor();
		
		if($a_infoNotif["niveauUrgence"] < $a_infoNotif["niveauUrgenceMin"])
		$req_realiseRDV = $auth_user-> runQuery(" UPDATE InterventionsPatho
													SET niveauUrgenceMin :nu 
													WHERE PathologiesidPatho =:patho
													AND InterventionsidIntervention =:inter");
		$req_realiseRDV->execute(array('nu'=>$a_infoo["niveauUrgence"],
									   'patho'=>$a_infoo["PathologiesnomPatho"],
									   'inter'=>$a_infoo["InterventionsidIntervention"]));
		$auth_user->redirect('Planning.php?Valide');
		
		//suppr.Notif
		$req_notifVu = $auth_user-> runQuery("DELETE FROM Notifications WHERE 
											CreneauxInterventionsidRdv=:id_rdv");
		$req_notifVu->execute(array('id_rdv'=>$_POST["btn-Accepter"]));
		$auth_user->redirect('VerificationNotification.php?Valide');
	}
	//if (isset($_POST["btn-Refuser"]) )
	//{
//	//suppr.Notif
	//	$req_notifVu = $auth_user-> runQuery("DELETE FROM Notifications WHERE 
	//										CreneauxInterventionsidRdv=:id_rdv");
	//	$req_notifVu->execute(array('id_rdv'=>$_POST["btn-Vu"]));
	//	$auth_user->redirect('VerificationNotification.php?Valide');
	//}
	if ((isset ($_POST["btn-Vu"])) or (isset($_POST["btn-Refuser"]))) //suppr.Notif
	{ 
		$req_notifVu = $auth_user-> runQuery("DELETE FROM Notifications WHERE 
											CreneauxInterventionsidRdv=:id_rdv");
		$req_notifVu->execute(array('id_rdv'=>$_POST["btn-Vu"]));
		$auth_user->redirect('VerificationNotification.php?Valide');
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
				$req_notif= $auth_user->runQuery("SELECT DISTINCT id_rdv , niveauUrgence, niveauUrgenceMax, niveauUrgenceMin,
											 Employes.ServicesnomService as Service_demande, Interventions.ServicesnomService as Service_Accueil, 
											 date_rdv as Date, heure_rdv as Heure, InterventionsPatho.InterventinsidIntervention as Intervention,
											 Interventions.acte as Intervention_demande,
											 statut as Statut, EmployesCompteUtilisateursidEmploye as Employe, Patients.nom as Nom,
											 Patients.prenom as Prenom, CreneauxInterventions.commentaires as Commentaires,
											 
											 FROM Notifications JOIN CreneauxInterventions JOIN Patients JOIN Employes JOIN InterventionsPatho
											 JOIN Interventions
											 
											 WHERE Notifications.CreneauxInterventionsidRdv = CreneauxInterventions.id_rdv
											 AND Patients.numSS=CreneauxInterventions.PatientsnumSS
											 AND Interventions.idIntervention=CreneauxInterventions.InterventionsidIntervention

											 AND InterventionsPatho.InterventionsidIntervention=CreneauxInterventions.InterventionsidIntervention
											 AND Employes.CompteUtilisateursidEmploye =CreneauxInterventions.EmployesCompteUtilisateursidEmploye
											 AND Notifications.ServicesnomService = :service GROUP BY id_rdv");
				$req_notif->execute(array("service"=>$_SESSION['service']));
				$a_infoNotif=reqToArrayPlusligne($req_notif) ;
				
			}
			else // notif chef de service
			{
				$req_notif= $auth_user->runQuery("SELECT DISTINCT id_rdv, date_rdv as Date, heure_rdv as Heure,
											 Employes.ServicesnomService as Service_demande, Interventions.acte as Intervention_demande
											 niveauUrgence, statut as Statut, EmployesCompteUtilisateursidEmploye as Employe, Patients.nom as Nom,
											 Patients.prenom as Prenom, CreneauxInterventions.commentaires as Commentaires
											 FROM Notifications JOIN CreneauxInterventions JOIN Patients JOIN Employes  JOIN Interventions
											 WHERE Notifications.CreneauxInterventionsidRdv = CreneauxInterventions.id_rdv
											 AND Patients.numSS=CreneauxInterventions.PatientsnumSS
											 AND Interventions.idIntervention=CreneauxInterventions.InterventionsidIntervention
											 AND Employes.CompteUtilisateursidEmploye =CreneauxInterventions.EmployesCompteUtilisateursidEmploye
											 AND Notifications.ServicesnomService = :service GROUP BY id_rdv");
			$req_notif->execute(array("service"=>$_SESSION['service']));
			$a_infoNotif=reqToArrayPlusligne($req_notif) ;
			}
		//AND WEEK(CreneauxInterventions.date_rdv) = WEEK( CURRENT_DATE)  // affichage à la semaine

		//Dumper($a_infoNotif);
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
				<th class="haut"> </th>
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
			$nb_notifs= count($a_infoNotif["id_rdv"]);
			for ($i = 0; $i < $nb_notifs; $i++)
			{ 
			?>
							
		<tr> <!-- lignes suivantes -->
			<td> <?php
				if ($_SESSION['idEmploye'] == 'admin00') 
				{
					 echo "<button type='submit' class='btn btn-primary' value=".$a_infoNotif["id_rdv"][$i]." name='btn-Accepter'>   A   </button>";
					 echo "<button type='submit' class='btn btn-primary' value=".$a_infoNotif["id_rdv"][$i]." name='btn-Refuser'>   R   </button>";
				}
				else
				{
					echo "<button type='submit' class='btn btn-primary' value=".$a_infoNotif["id_rdv"][$i]." name='btn-Vu'>   Vu   </button>";
				}
				
			?>
				</td>
			<?php
				foreach ($a_infoNotif as $col=>$line) // $col = colonne
				{
			?>
			<td> <?php echo $line[$i] ?> </td>
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