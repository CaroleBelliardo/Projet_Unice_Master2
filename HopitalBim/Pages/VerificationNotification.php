<?php

	include ('../Config/Menupage.php');
	$lien ='VerificationNotification.php';

	If ($_SESSION['chefService'] != TRUE) 
	{
		$auth_user->redirect('../Pageprincipale.php'); // recherche le service
	}

	if (isset ($_POST["btn-Accepter"]))
	{
		//recup info rdv en question
		$req_info = $auth_user->runQuery(" SELECT Pathologies.nomPathologie, CreneauxInterventions.InterventionsidIntervention, niveauUrgence,
										InterventionsPatho.niveauUrgenceMax, InterventionsPatho.niveauUrgenceMin
										FROM InterventionsPatho JOIN CreneauxInterventions JOIN Pathologies
										WHERE id_rdv = :idrdv
										AND CreneauxInterventions.PathologiesidPatho = Pathologies.idPatho
										AND CreneauxInterventions.PathologiesidPatho = InterventionsPatho.PathologiesidPatho
										AND CreneauxInterventions.InterventionsidIntervention = InterventionsPatho.InterventionsidIntervention" );
		//recup info rdv en question
		$req_info = $auth_user->runQuery(" SELECT CreneauxInterventions.PathologiesidPatho, CreneauxInterventions.InterventionsidIntervention,
										 CreneauxInterventions.niveauUrgence,
										 InterventionsPatho.niveauUrgenceMin, InterventionsPatho.niveauUrgenceMax
										FROM CreneauxInterventions JOIN InterventionsPatho
										WHERE id_rdv = :idrdv
										AND CreneauxInterventions.InterventionsidIntervention = InterventionsPatho.InterventionsidIntervention
										AND CreneauxInterventions.PathologiesidPatho = InterventionsPatho.PathologiesidPatho
										" ); 
		$req_info->execute(array('idrdv'=> $_POST["btn-Accepter"]));
		$a_infoo= $req_info-> fetch(PDO::FETCH_ASSOC);
		$req_info->closeCursor();
		
		if($a_infoo["niveauUrgence"] > $a_infoo["niveauUrgenceMax"])
		{ 
			$req_realiseRDV = $auth_user-> runQuery(" UPDATE InterventionsPatho
													SET niveauUrgenceMax = :nu 
													WHERE PathologiesidPatho =:patho
													AND InterventionsidIntervention =:inter");
			$req_realiseRDV->execute(array('nu'=>$a_infoo["niveauUrgence"],
										   'patho'=>$a_infoo["PathologiesidPatho"],
										   'inter'=>$a_infoo["InterventionsidIntervention"]));
		}
		else 
		{
			$req_realiseRDV = $auth_user-> runQuery(" UPDATE InterventionsPatho
													SET niveauUrgenceMin = :nu 
													WHERE PathologiesidPatho =:patho
													AND InterventionsidIntervention =:inter");
			$req_realiseRDV->execute(array('nu'=>$a_infoo["niveauUrgence"],
										   'patho'=>$a_infoo["PathologiesidPatho"],
										   'inter'=>$a_infoo["InterventionsidIntervention"]));
		}
		//suppr.Notif
		$req_notifVu = $auth_user-> runQuery("DELETE FROM Notifications WHERE 
											CreneauxInterventionsidRdv=:id_rdv");
		$req_notifVu->execute(array('id_rdv'=>$_POST["btn-Accepter"]));
		$auth_user->redirect('VerificationNotification.php?Valide');
	}
	if (isset($_POST["btn-Refuser"]) )
	{
	//suppr.Notif
		$req_notifVu = $auth_user-> runQuery("DELETE FROM Notifications WHERE 
											CreneauxInterventionsidRdv=:id_rdv");
		$req_notifVu->execute(array('id_rdv'=>$_POST["btn-Refuser"]));
		$auth_user->redirect('VerificationNotification.php?Valide');
		
	}
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
			if(isset($error)) // affichage messages erreurs si valeurs != format attendu
			{
				foreach($error as $error) // pour chaque champs
				{
	?>
					<div class="error"> <?php echo $error; ?> </div>
	<?php
				}
			}
	 	if(isset($_GET['?Vu']))
			{
		?>
        <div id="valide"> <!-- Alert alert-info-->
            La notification a été supprimée !
        </div>
        <?php
			}
			if(isset($_GET['?Accepte']))
			{
		?>
        <div id="valide"> <!-- Alert alert-info-->
           Le niveau d'urgence de référence a été mis à jour et la notification a été supprimée !
        </div>
		<?php
			}
			if(isset($_GET['?Refuser']))
			{
		?>
        <div id="valide"> <!-- Alert alert-info-->
           La notification a été supprimée !
        </div>
		<?php
			}
			if ($_SESSION['idEmploye'] == 'admin00') 
			{
				$req_notif= $auth_user->runQuery("SELECT DISTINCT id_rdv as id, niveauUrgence as NU, niveauUrgenceMax as Max, niveauUrgenceMin as Min,
											 Employes.ServicesnomService as Service_demande, Interventions.ServicesnomService as Service_accueil, 
											 date_rdv as Date, heure_rdv as Heure, 
											 Interventions.acte as Interv_demande,
											 statut as Statut, EmployesCompteUtilisateursidEmploye as Employe, Patients.nom as Nom,
											 Patients.prenom as Prenom, CreneauxInterventions.commentaires as Comm
											 
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
				$req_notif= $auth_user->runQuery("SELECT  Notifications.ServicesnomService as Service_recu, id_rdv as id, date_rdv as Date, heure_rdv as Heure, 
											 EmployesCompteUtilisateursidEmploye as Employe, Employes.ServicesnomService as Service_demande,
											 niveauUrgence, statut as Statut,  Patients.nom as Patient_Nom,
											 Patients.prenom as Patient_Prenom, CreneauxInterventions.commentaires as Comm
											 FROM Notifications JOIN CreneauxInterventions JOIN Patients JOIN Employes JOIN Interventions
											
				$req_notif= $auth_user->runQuery("SELECT DISTINCT id_rdv as id, date_rdv as Date, heure_rdv as Heure, 
											 Employes.ServicesnomService as Service_demande,
											 niveauUrgence, statut as Statut, EmployesCompteUtilisateursidEmploye as Employe, Patients.nom as Nom,
											 Patients.prenom as Prenom, CreneauxInterventions.commentaires as Comm
											 FROM Notifications JOIN CreneauxInterventions JOIN Patients JOIN Employes  JOIN Interventions
											 WHERE Notifications.CreneauxInterventionsidRdv = CreneauxInterventions.id_rdv
											
											 AND Patients.numSS=CreneauxInterventions.PatientsnumSS
											 AND Interventions.idIntervention=CreneauxInterventions.InterventionsidIntervention
											 AND Employes.CompteUtilisateursidEmploye =CreneauxInterventions.EmployesCompteUtilisateursidEmploye
											
											 AND Notifications.ServicesnomService = :service ");
			$req_notif->execute(array('service'=>$_SESSION['service']));
			$a_infoNotif=reqToArrayPlusligne($req_notif) ;
			}
		//AND WEEK(CreneauxInterventions.date_rdv) = WEEK( CURRENT_DATE)  // affichage à la semaine

		//Dumper($a_infoNotif);
			if($a_infoNotif == FALSE)
				{ 
			?>

			<div class="containerFormu">
				<?php echo "Aucune notification";
				
				?> 
			</div>

			<?php 
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
			$nb_notifs= count($a_infoNotif["id"]);
			for ($i = 0; $i < $nb_notifs; $i++)
			{ 
			?>
							
		<tr>
			<form method="post" ><!-- lignes suivantes -->
			<td > <?php
				if ($_SESSION['idEmploye'] == 'admin00') 
				{
					 echo "<button type='submit' class='btn btn-primary' value=".$a_infoNotif["id"][$i]." name='btn-Accepter'> A </button>";
					 echo "<button type='submit' class='btn btn-primary' value=".$a_infoNotif["id"][$i]." name='btn-Refuser'> R </button>";
				}
				else
				{
					echo "<button type='submit' class='btn btn-primary' value=".$a_infoNotif["id"][$i]." name='btn-Vu'>   Vu   </button>";
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
			</form>
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

		<div id="legendNotif">

			<table id="legendNotifTab" border="1", ALIGN="CENTER", VALIGN="MIDDLE">

			<tr class="haut">
				<th colspan="2" class="Totalth"> Légende tableau</th>
				
			</tr>

			<tr>
				<td class="infoUser"> <button> A </button> </td> 
				<td> Accepté ! <br> Mise à jour du NU </td>
			</tr>

			<tr>
				<td class="infoUser"> <button> R </button> </td>
				<td> Refusé !  </td>
			</tr>

			<tr>
				<td class="infoUser"> NU </td> 
				<td> Niveau d'urgence </td>
			</tr>

			<tr>
				<td class="infoUser"> Max </td>
				<td> Niveau Maximal d'urgence </td>
			</tr>

			<tr>
				<td class="infoUser"> Min </td> 
				<td> Niveau Minimal d'urgance </td>
			</tr>

			</table> <!--legendNotif-->

		</div> <br> <br> <br> <br>

	<?php include ('../Config/Footer.php'); //menu de navigation ?>

	</body>

</html>