<?php
	include ('../Config/Menupage.php');
	
	function endKey($array)
	{
	end($array);
	return key($array);
	}
	
	function extractReq ($inReq,$manip,$att1,$att2){ //TODO : recupere les valeurs de la première variable = > a supprimant en creant le tableau avant 
		$a_temp=[];
		while ($temp =  $inReq-> fetch(PDO::FETCH_ASSOC))
		{
			$a_temp[$temp[$att1]] = [$manip=>$temp[$att2]];
		}
		return($a_temp);
	}   
	function extractReq2 ($intab,$inReq,$manip,$att1,$att2){ // recupère les valeurs des exp
		while ($temp2 =  $inReq-> fetch(PDO::FETCH_ASSOC))
		{
			$tempo=$intab[$temp2[$att1]];
			$tempo[$manip] = $temp2[$att2];
			$intab[$temp2[$att1]] = $tempo;
		}
		return($intab);
	
	}
	function extractReq3 ($intab,$inReq){
		while ($temp2 =  $inReq-> fetch(PDO::FETCH_ASSOC))
		{
			if ($temp2["ServicesnomService"] != 'Informatique')
			{
				$tempo=$intab[$temp2["ServicesnomService"]]; //stock le tableau des stats du service  
				$a_info= ["idEmploye" => $temp2["EmployesCompteUtilisateursidEmploye"],  
				"Patient" => $temp2["numSS"]]; 
				array_push($tempo,["Med_Patient-MultiUrgence"=>$a_info]); 
				$intab[$temp2["ServicesnomService"]] = $tempo;
			}
		}
		return($intab);
	}   
//****************************** REQUETE MySQL ******************************************
    $req_services =$auth_user->runQuery("SELECT nomService FROM Services"); // connaiter total interv meme si rdv = 0	
	$req_services->execute();
	$a_services= reqToArrayPlusAtt($req_services);
	$req_services->closeCursor();
	unset($a_services['informatique']);		
	
// --- Entete = nb TOTAL : recupere toutes les lignes correspondant dans la bdd puis compte le nombre de ligne
$a_total= [];
    $totalInterv = $auth_user->runQuery('SELECT * FROM CreneauxInterventions'); //total demandes tt services confondu
    $totalInterv->execute();
	$a_total["Total demandé"] = $totalInterv->rowCount();
	$totalInterv->closeCursor();	

	$totalIntervUrg0 = $auth_user->runQuery('SELECT * FROM CreneauxInterventions WHERE niveauUrgence = 0 '); 
    $totalIntervUrg0->execute();
	$a_total["Urgence 0"] = $totalIntervUrg0->rowCount();
	$totalIntervUrg0->closeCursor();	   
	$totalIntervUrg1 = $auth_user->runQuery('SELECT * FROM CreneauxInterventions WHERE niveauUrgence = 1 '); 
    $totalIntervUrg1->execute();
	$a_total["Urgence 1"] = $totalIntervUrg1->rowCount();
	$totalIntervUrg1->closeCursor();	
    $totalIntervUrg2 = $auth_user->runQuery('SELECT * FROM CreneauxInterventions WHERE niveauUrgence = 2 '); 
    $totalIntervUrg2->execute();
	$a_total["Urgence 2"] = $totalIntervUrg2->rowCount();
	$totalIntervUrg2->closeCursor();	
	$totalIntervUrg3 = $auth_user->runQuery('SELECT * FROM CreneauxInterventions WHERE niveauUrgence = 3 '); 
    $totalIntervUrg3->execute();
	$a_total["Urgence 3"] = $totalIntervUrg3->rowCount();
	$totalIntervUrg3->closeCursor();
	
	$totalIntervPrePrevu = $auth_user->runQuery("SELECT * FROM CreneauxInterventions WHERE statut =  'p'"); 
    $totalIntervPrePrevu->execute();
	$a_total["Prévu"] = $totalIntervPrePrevu->rowCount();
	$totalIntervPrePrevu->closeCursor();
    $totalIntervRealise = $auth_user->runQuery("SELECT * FROM CreneauxInterventions WHERE statut = 'r' "); 
    $totalIntervRealise->execute();
	$a_total["Réalisé"] = $totalIntervRealise->rowCount();
	$totalIntervRealise->closeCursor();
    $totalIntervAnnule = $auth_user->runQuery("SELECT * FROM CreneauxInterventions WHERE statut = 'a' "); 
    $totalIntervAnnule->execute();
	$a_total["Annulé"] = $totalIntervAnnule->rowCount();
	$totalIntervAnnule->closeCursor();	   
    $totalIntervSubtit = $auth_user->runQuery("SELECT * FROM CreneauxInterventions WHERE statut = 's' "); 
    $totalIntervSubtit->execute();
	$a_total["Remplacé"] = $totalIntervSubtit->rowCount();
	$totalIntervSubtit->closeCursor(); 
    $totalIntervFacture = $auth_user->runQuery("SELECT * FROM CreneauxInterventions WHERE statut = 'f' "); 
    $totalIntervFacture->execute();
	$a_total["Facturé"] = $totalIntervFacture->rowCount();
	$totalIntervFacture->closeCursor();
	
	
//VERIF

//incompatibilité
$req_nbDemandeINCMax=$auth_user->runQuery('SELECT *
		FROM CreneauxInterventions JOIN Interventions JOIN InterventionsPatho
		WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
        AND Interventions.idIntervention = InterventionsPatho.InterventionsidIntervention
		AND CreneauxInterventions.niveauUrgence > InterventionsPatho.niveauUrgenceMax');
$req_nbDemandeINCMax->execute();
$a_total["Incomp. nUrg Max"] = $req_nbDemandeINCMax->rowCount();
$req_nbDemandeINCMax->closeCursor();

$req_nbDemandeINCMin=$auth_user->runQuery('SELECT *
		FROM CreneauxInterventions JOIN Interventions JOIN InterventionsPatho
		WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
        AND Interventions.idIntervention = InterventionsPatho.InterventionsidIntervention
		AND CreneauxInterventions.niveauUrgence < InterventionsPatho.niveauUrgenceMin');
$req_nbDemandeINCMax->execute();
$a_total["Incomp. nUrg Min"] = $req_nbDemandeINCMax->rowCount();
$req_nbDemandeINCMax->closeCursor();
	
	
$reqMedPatientTotal = $auth_user->runQuery('SELECT *
		FROM Employes  JOIN CreneauxInterventions t1 JOIN Patients
		WHERE niveauUrgence != 0
		AND t1.EmployesCompteUtilisateursidEmploye = Employes.CompteUtilisateursidEmploye
		AND t1.PatientsnumSS= Patients.numSS
		AND EXISTS (
		SELECT *
		FROM CreneauxInterventions t2
		WHERE   t1.heure_rdv <> t2.heure_rdv
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

$liste=['Prévu'=>'p','Réalisé'=>'r','Annulé'=>'a','Remplacé'=>'s','Facturé'=>'f'];
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
$reqMedPatient = $auth_user->runQuery('SELECT ServicesnomService, EmployesCompteUtilisateursidEmploye, Patients.numSS, COUNT(*)
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
$a_info=extractReq3($totaldemande,$reqMedPatient);


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
			
				<td> <!-- le corps du tableau (sauf colonne/ligne : Total ) -->

					<?php
						if (array_key_exists($nomService,$a_info))
						{
							if (array_key_exists($colonne,$a_info[$nomService]))
							{
								if($valueTotal == $last_key)
								{
									if(array_key_exists("COUNT(*)",$a_info[$nomService][$last_key]))
									{
										echo $a_info[$nomService][$last_key]["COUNT(*)"]."<br>";
										echo $a_info[$nomService][$last_key]["EmployesCompteUtilisateursidEmploye"]."<br>".
										$a_info[$nomService][$last_key]["PatientsnumSS"]."<br>";
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
