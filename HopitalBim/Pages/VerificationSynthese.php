<?php
	include ('../Config/Menupage.php');
	
	  
//****************************** REQUETE MySQL ******************************************
    $req_services =$auth_user->runQuery("SELECT nomService FROM Services"); // connaiter total interv meme si rdv = 0	
	$req_services->execute();
	$a_services= reqToArrayPlusAtt($req_services);
	$req_services->closeCursor();
	unset($a_services['informatique']);		
	
// --- Entete = nb TOTAL : recupere toutes les lignes correspondant dans la bdd puis compte le nombre de ligne
	$a_total= [];
    $totalInterv = $auth_user->runQuery('SELECT * FROM CreneauxInterventions WHERE YEAR(CreneauxInterventions.date_rdv) = YEAR(CURRENT_DATE)'); //total demandes tt services confondu
    $totalInterv->execute();
	$a_total["Total demandé"] = $totalInterv->rowCount();
	$totalInterv->closeCursor();	

	for ($i=0; $i<=3; $i++)
	{  
		$totalIntervUrg0 = $auth_user->runQuery('SELECT * FROM CreneauxInterventions WHERE niveauUrgence = :NU AND YEAR(CreneauxInterventions.date_rdv) = YEAR(CURRENT_DATE)'); 
		$totalIntervUrg0->execute(array('NU'=>$i));
		$a_total["Urgence ".$i] = $totalIntervUrg0->rowCount();
		$totalIntervUrg0->closeCursor();
	}

	
	$liste=['Prévu'=>'p','Réalisé'=>'r','Annulé'=>'a','Remplacé'=>'s','Facturé'=>'f'];
	foreach ($liste as $k=>$v)
	{
		$totalIntervPrePrevu = $auth_user->runQuery("SELECT * FROM CreneauxInterventions WHERE statut = :statut AND YEAR(CreneauxInterventions.date_rdv) = YEAR(CURRENT_DATE)"); 
		$totalIntervPrePrevu->execute(array('statut'=>$v));
		$a_total[$k] = $totalIntervPrePrevu->rowCount();
		$totalIntervPrePrevu->closeCursor();
	}
	
	
	
//VERIF
	
	//incompatibilité
	$req_nbDemandeINCMax=$auth_user->runQuery('SELECT *
			FROM CreneauxInterventions JOIN Interventions JOIN InterventionsPatho
			WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
			AND Interventions.idIntervention = InterventionsPatho.InterventionsidIntervention
			AND YEAR(CreneauxInterventions.date_rdv) = YEAR(CURRENT_DATE)
			AND CreneauxInterventions.niveauUrgence > InterventionsPatho.niveauUrgenceMax');
	$req_nbDemandeINCMax->execute();
	$a_total["Incomp. nUrg Max"] = $req_nbDemandeINCMax->rowCount();
	$req_nbDemandeINCMax->closeCursor();
	
	$req_nbDemandeINCMin=$auth_user->runQuery('SELECT *
			FROM CreneauxInterventions JOIN Interventions JOIN InterventionsPatho
			WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
			AND Interventions.idIntervention = InterventionsPatho.InterventionsidIntervention
			AND YEAR(CreneauxInterventions.date_rdv) = YEAR(CURRENT_DATE)
			AND CreneauxInterventions.niveauUrgence < InterventionsPatho.niveauUrgenceMin');
	$req_nbDemandeINCMax->execute();
	$a_total["Incomp. nUrg Min"] = $req_nbDemandeINCMax->rowCount();
	$req_nbDemandeINCMax->closeCursor();
		
		
	$reqMedPatientTotal = $auth_user->runQuery('SELECT *
			FROM Employes  JOIN CreneauxInterventions t1 JOIN Patients
			WHERE niveauUrgence != 0
			AND t1.EmployesCompteUtilisateursidEmploye = Employes.CompteUtilisateursidEmploye
			AND t1.PatientsnumSS= Patients.numSS
			AND YEAR(t1.date_rdv) = YEAR(CURRENT_DATE)
			AND EXISTS (
			SELECT *
			FROM CreneauxInterventions t2
			WHERE   t1.heure_rdv <> t2.heure_rdv
			AND YEAR(t2.date_rdv) = YEAR(CURRENT_DATE)
			AND   t1.PatientsnumSS = t2.PatientsnumSS
			AND t1.EmployesCompteUtilisateursidEmploye = t2.EmployesCompteUtilisateursidEmploye)');
	$reqMedPatientTotal->execute();
	$a_total["Med_Patient-MultiUrgence"] = $reqMedPatientTotal->rowCount();
	$reqMedPatientTotal->closeCursor();
	
	
	// --- Detail PAR service
	// total
	$req_nbDemandeParService = $auth_user->runQuery('SELECT ServicesnomService, COUNT(*)
				FROM CreneauxInterventions JOIN Interventions
				WHERE Interventions.idIntervention = CreneauxInterventions.InterventionsidIntervention
				GROUP BY ServicesnomService');
	$req_nbDemandeParService->execute();
	$totaldemande=extractReq($req_nbDemandeParService,"Total demandé","ServicesnomService","COUNT(*)");
	
	
				
	//-- RDV niveauUrgence
	$req_nbDemandeURGParService=$auth_user->runQuery('SELECT ServicesnomService, COUNT(*)
				FROM CreneauxInterventions JOIN Interventions
				WHERE Interventions.idIntervention = CreneauxInterventions.InterventionsidIntervention
				AND niveauUrgence = :urg
				GROUP BY Interventions.ServicesnomService');
	for ($i=0; $i<=3; $i++)
	{  
		$req_nbDemandeURGParService->execute(['urg'=>$i]);
		$totaldemande=extractReq2($totaldemande,$req_nbDemandeURGParService,"nb Urgences ".$i,"ServicesnomService","COUNT(*)");
	}
	
	//-- RDV statut
	$req_nbDemandeStatutParService=$auth_user->runQuery('SELECT ServicesnomService, COUNT(*)
				FROM CreneauxInterventions JOIN Interventions
				WHERE Interventions.idIntervention = CreneauxInterventions.InterventionsidIntervention
				AND statut = :statut
				GROUP BY Interventions.ServicesnomService');
	
	foreach ($liste as $k=>$v)
	{  
		$req_nbDemandeStatutParService->execute(['statut'=>$v]);
		$totaldemande=extractReq2($totaldemande,$req_nbDemandeStatutParService,$k,"ServicesnomService","COUNT(*)");
	}
	
	//incompatibilité
	$req_nbDemandeINCParServiceMax=$auth_user->runQuery('SELECT Interventions.ServicesnomService, COUNT(*)
			FROM CreneauxInterventions JOIN Interventions JOIN InterventionsPatho
			WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
			AND Interventions.idIntervention = InterventionsPatho.InterventionsidIntervention
			AND CreneauxInterventions.niveauUrgence > InterventionsPatho.niveauUrgenceMax
			GROUP BY Interventions.ServicesnomService');
	$req_nbDemandeINCParServiceMax->execute();
	$totaldemande=extractReq2($totaldemande,$req_nbDemandeINCParServiceMax,"Incomp. nUrg Max","ServicesnomService","COUNT(*)");
	
	$req_nbDemandeINCParServiceMin=$auth_user->runQuery('SELECT Interventions.ServicesnomService, COUNT(*)
			FROM CreneauxInterventions JOIN Interventions JOIN InterventionsPatho
			WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
			AND Interventions.idIntervention = InterventionsPatho.InterventionsidIntervention
			AND CreneauxInterventions.niveauUrgence < InterventionsPatho.niveauUrgenceMin
			GROUP BY Interventions.ServicesnomService');
	$req_nbDemandeINCParServiceMin->execute();
	$totaldemande=extractReq2($totaldemande,$req_nbDemandeINCParServiceMin,"Incomp. nUrg Max","ServicesnomService","COUNT(*)");
	
	//retourne liste (medecin + patient) pour lequels il a plus d'une demande ( ligne avec h ou j dif)
	//avec niveau urgent
	$reqMedPatient = $auth_user->runQuery('SELECT Employes.ServicesnomService, EmployesCompteUtilisateursidEmploye, Patients.numSS, Patients.nom as pnom,Patients.prenom as pprenom, COUNT(*)
			, Employes.nom as Enom,  Employes.prenom as Eprenom
			FROM Employes  JOIN CreneauxInterventions t1 JOIN Patients
			WHERE niveauUrgence != 0
			AND t1.EmployesCompteUtilisateursidEmploye = Employes.CompteUtilisateursidEmploye
			AND t1.PatientsnumSS= Patients.numSS
			AND EXISTS (
			SELECT *
			FROM CreneauxInterventions t2
			WHERE   t1.heure_rdv <> t2.heure_rdv
			AND   t1.PatientsnumSS = t2.PatientsnumSS
			AND t1.EmployesCompteUtilisateursidEmploye = t2.EmployesCompteUtilisateursidEmploye)
			Group by EmployesCompteUtilisateursidEmploye');
	$reqMedPatient->execute();
	$a_info=$totaldemande;
	while ($temp2 =  $reqMedPatient-> fetch(PDO::FETCH_ASSOC))
		{
			//if ($temp2["ServicesnomService"] != 'Informatique')
			//{
				$a_info[$temp2['ServicesnomService']]['Med_Patient-MultiUrgence'][$temp2["EmployesCompteUtilisateursidEmploye"]]=  
				["Patient" => $temp2["numSS"],  "patientprenom" => $temp2["pprenom"], "patientnom" => $temp2["pnom"],
				  "Eprenom" => $temp2["Eprenom"], "Enom" => $temp2["Enom"],
				 "nb_demandes" => $temp2["COUNT(*)"]]; 
			//}
		}	
	$last_key = endKey($a_total);
?>
	

<!DOCTYPE html>
<html>
	<head>
		<title>Synthèse</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="../Config/Style.css" type="text/css"  /> 
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">	
	</head>
	
	<body>

		<div class="containerTab">

		<table id="synthese" border="1", ALIGN="CENTER", VALIGN="MIDDLE " >
		<caption> Tableau de synthèse des demandes d'intervention </caption> <!-- légende du tableau -->
			
			<tr>  <!-- 1ère ligne  - -->

				<th class="haut"> Service </th>  <!-- en tête - 1ère ligne / 1ère colonne - -->

					<?php // header
					foreach ($a_total as $colonne=>$value)
					{
					?>
					
				<th class="haut"> <?php echo $colonne ?> </th>  <!-- en tête - 1ère ligne / tt les autres colonnes - -->

					<?php
					}
					?>
			
			</tr>
				
					<?php
					foreach ($a_services as $idx=>$nomService) 
					{	
					?>

			<tr> <!-- toutes les autres lignes (sauf Total) -->

				<th class="colonne"> <?php echo $nomService ?> </th> <!-- 1ère colonne sauf : Total et Service -->

					<?php
					foreach ($a_total as $colonne=>$valueTotal) 
					{
					?>
			
				<td class="tdsynthese"> <!-- le corps du tableau (sauf colonne/ligne : Total ) -->

					<?php
						if (array_key_exists($nomService,$a_info))
						{
							if (array_key_exists($colonne,$a_info[$nomService]))
							{
								if($colonne == $last_key)
								{ 
									if(array_key_exists($last_key,$a_info[$nomService]))
									{
										$flag=0;
										foreach  ($a_info[$nomService][$last_key] as $id=>$inf)
										{
											if ($flag > 0){ echo '------------<br>';}
											echo 'L\'employé '.$inf["Enom"].' '.$inf["Eprenom"]."<br>".
											'a fait '.$inf["nb_demandes"].' demandes urgentes'."<br>".
											'pour  '.$inf["patientnom"].' '.$inf["patientprenom"].'<br>'
											.'n° SS : '.$inf["Patient"]."<br>";
											
											
											$flag=$flag+1;
											
										}
									}
									else
									{
									echo "0";
									}
								}
								else
								{
									 echo $a_info[$nomService][$colonne];
								}
							}
							else
							{
							echo "0";
							}
						}
						else
						{
							echo "0";
						}
			
					?>

				</td>

					<?php
					} 
					?>

			</tr>

				<?php
				} 
				?>

			<tr> <!-- dernière ligne  -->

				<th class="Totalth"> Total </th>  <!-- Colonne - 2ème ligne, 1ère colonne - -->

					<?php 	// Total
					foreach ($a_total as $colonne=>$value)
					{
					?>
					
				<td class="Totaltd"> <?php echo $value ?> </td> <!-- 2ème ligne / tt les autres colonnes de la ligne : Total -->
		
					<?php
					}   
					?>

			</tr>

		</table>
	

		</div> <!-- div containerTab -->
	
	<?php include ('../Config/Footer.php'); //menu de navigation?>		

	</body>
</html>
