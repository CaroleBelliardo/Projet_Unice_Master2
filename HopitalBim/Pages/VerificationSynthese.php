<!--TODO :
- Supprimer la requete extractReq --=extractReq2
- requete = tableau
- nomattribut = creneau.incomp
- rendez-vous annulés 
- ajour header 
-->

<?php
	include ('../Config/Menupage.php');

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
			//$tempo=$intab[$temp2[$att1]]; //stock le tableau des stats du service 
			//$a_info= ["idEmploye" => $temp2["EmployesCompteUtilisateursidEmploye"], 
			//"Patient" => $temp2["PatientnumSS"]];
			//array_push($tempo,["Med_Patient-MultiUrgence"=>$a_info]);
			//$intab[$temp2[$att1]] = $tempo;
			
			
	  $tempo=$intab[$temp2["ServicesnomService"]]; //stock le tableau des stats du service  
      $a_info= ["idEmploye" => $temp2["EmployesCompteUtilisateursidEmploye"],  
      "Patient" => $temp2["PatientnumSS"]]; 
      array_push($tempo,["Med_Patient-MultiUrgence"=>$a_info]); 
      $intab[$temp2["ServicesnomService"]] = $tempo; 
		}
		return($intab);
	}   
//****************************** REQUETE MySQL ******************************************
    $req_services =$auth_user->runQuery("SELECT nomService FROM Services"); // connaiter total interv meme si rdv = 0	
	$req_services->execute();
	$a_services= reqToArrayPlusAtt($req_services);
	$req_services->closeCursor();
			
	
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
	    


// !!! 
	//$totalIncomp = $auth_user->runQuery('SELECT * FROM CreneauxInterventions WHERE incompDecte != 0'); 
    $nb_totalIncomp =0;// $totalIncomp->rowCount();

	
	// --- Detail PAR service
	// total
$req_nbDemandeParService = $auth_user->runQuery('SELECT ServicesnomService, COUNT(*)
            FROM CreneauxInterventions JOIN Interventions
            WHERE Interventions.idIntervention = CreneauxInterventions.InterventionsidIntervention
			GROUP BY ServicesnomService');
$req_nbDemandeParService->execute();
$totaldemande=extractReq($req_nbDemandeParService,"Total demandé","ServicesnomService","COUNT(*)");
//Dumper($totaldemande);


            
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
$reqMedPatient = $auth_user->runQuery('SELECT ServicesnomService, EmployesCompteUtilisateursidEmploye, PatientsnumSS, COUNT(*)
		FROM Employes Natural JOIN CreneauxInterventions t1
		WHERE niveauUrgence != 0
		AND EXISTS (
		SELECT *
		FROM CreneauxInterventions t2
		WHERE t1.date_rdv <> t2.date_rdv
		OR   t1.heure_rdv <> t2.heure_rdv
		AND   t1.PatientsnumSS = t2.PatientsnumSS
		AND t1.EmployesCompteUtilisateursidEmploye = t2.EmployesCompteUtilisateursidEmploye)
		Group by EmployesCompteUtilisateursidEmploye');

$a_info=extractReq3($totaldemande,$reqMedPatient);

Dumper($totaldemande);
?>
	
	

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="style.css" type="text/css"  /> // a faire ---
	<title>Synthèse</title>
</head>

<body>
		</CENTER><table  BORDER="1",ALIGN="CENTER", VALIGN="MIDDLE " >
			<tr><th>Service</th> 
			<?php // header
				foreach ($a_total as $colonne=>$value)
				{
			?>
					<th> <?php echo $colonne ?></th>
			<?php
				}
			?>
			</tr>
			<tr><th>Total</th>
			<?php // Total
				foreach ($a_total as $colonne=>$value)
				{
			?>
					<td> <?php echo $value ?></td>
			<?php
				}
				
				
				
				//Corps du tableau
			?>
			</tr>
			<?php
				foreach ($a_services as $row=>$col)
				{
			?>
				<tr><td><?php echo $col ?></td>
			<?php
					foreach ($a_total as $colonne=>$value)
					{
			?>
					<td>
			<?php
						if (array_key_exists($row,$a_info))
						{
							if (array_key_exists($value,$a_info[$row]))
							{
								echo $a_info[$row][$value];
							}
							else
							{
							echo "0";
							}
						// Dumper ($a_info[$row][$value]);//.$row.'colonne'.$val;
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
		</table></CENTER>
	
		<?php quitter1();
			include ('../Config/Footer.php'); //menu de navigation
		?>		
	</body>
</html>
