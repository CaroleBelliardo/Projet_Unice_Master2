<!--TODO :
- Supprimer la requete extractReq --=extractReq2
- requete = tableau
- nomattribut = creneau.incomp
- rendez-vous annulés 
- ajour header 
-->

<?php
	require_once("../session.php"); 
	require_once("../classe.Systeme.php");
	$auth_user = new Systeme();
	$bdd=$auth_user->conn;

	function Dumper ($var){ // affichage des valeurs des variables tableaux
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
	}
	
	function reqToArray1Att($requete)
	{
		$a_out_reqToArray=[];
		while ($temp = $requete->fetchColumn())
		{
			array_push(	$a_out_reqToArray,$temp );
		}
		return ($a_out_reqToArray);
	}
	function reqToArrayPlusAtt($requete)
	{
		$a_out_reqToArray=[];
		while ($temp = $requete->fetch(PDO::FETCH_ASSOC))
		{
			foreach ( $temp as $cle=>$valeur)
			{
				array_push($a_out_reqToArray,$valeur);
			}
		
		}
		return ($a_out_reqToArray);
	}
		function reqToArrayPlusligne($requete)
	{
		$a_out_reqToArray=[];
		while ($temp = $requete->fetch(PDO::FETCH_ASSOC))
		{
			foreach ( $temp as $cle=>$valeur)
			{
				echo $cle."...".$valeur."f";
				if(array_key_exists($cle,$a_out_reqToArray))
				{
					$tempo=$a_out_reqToArray[$cle];
					push($tempo,$valeur);
					$a_out_reqToArray[$cle]=$tempo;
				}
				else
				{
					$a_out_reqToArray[$cle]=[$valeur];
				}
			}
		
		}
		return ($a_out_reqToArray);
	} 
// Requetes ---
	// -- Service de l'utilisateur
	$user_id = $_SESSION['idEmploye']; // CompteUtilisateur.idEmploye == Employes.idUtilisateur
	$Service = $bdd->prepare("SELECT ServicesnomService 
						  FROM Employes
						  WHERE CompteUtilisateursidEmploye = :user_id "); 
	$Service->execute(array('user_id'=> $user_id));
	$service= ($Service->fetchColumn());
	$Service->closeCursor();
	
	// -- actes réalisés par le service 
	$idActes = $bdd->prepare("SELECT acte
						  FROM Interventions
						  WHERE ServicesnomService = :service");
	$idActes->execute(array('service'=> $service));//$donnees = mysqli_fetch_array($Actes)
	$a_idActes=reqToArray1Att($idActes);
	$idActes->closeCursor();

	$dateCourant = date("d-m-y");
	//$heureCourante = date("H:i:s");
	$dateCourant = '2017-11-02'; /*test*/
	
	
	//-- PLANNING
	//-- min max
	$heureMinMax = $bdd->prepare("SELECT min(heure),max(heure) 
		FROM `CreneauxInterventions` JOIN Interventions
		WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
		AND dateRdv= :date
		AND ServicesnomService = :service ");
	$heureMinMax->execute(array('service'=> $service,
								'date'=>$dateCourant));//$donnees = mysqli_fetch_array($Actes)
	$a_heureMinMax=reqToArrayPlusAtt($heureMinMax);
	$heureMinMax->closeCursor(); /*a recuperer sur le array pour limiter nb req*/
	Dumper($a_heureMinMax);
	//Dumper($a_heure);
	
	
	
	$range=range(strtotime($a_heureMinMax[0]),strtotime($a_heureMinMax[1]),15*60);
	foreach($range as $time){
        echo date("H:i",$time)."\n";}

	
	
	
	//$test=H_range($a_heureMinMax[0],$a_heureMinMax[1]);
	//Dumper(H_range);
	//
	
	
	
	
	
	//Dumper($a_heure);
	//Dumper($a_heureMinMax);
	//echo $a_heureMinMax["min(heure)"];
	//echo $a_heureMinMax["max(heure)"];
	
	// -- data
	$infoServiceJour = $bdd->prepare("SELECT Patients.nom, Patients.prenom, Patients.numSS, CreneauxInterventions.niveauUrgence, CreneauxInterventions.statut 
		FROM `CreneauxInterventions` JOIN Interventions JOIN Patients
		WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
		AND CreneauxInterventions.PatientnumSS = Patients.numSS
		AND dateRdv= '2017-11-02'/*:date*/
		AND ServicesnomService = 'cardio'/*:service*/ ");
	$infoServiceJour->execute(array('service'=> $service,
								'date' => $dateCourant));//$donnees = mysqli_fetch_array($Actes)
	
	//while ($test = $infoServiceJour->fetch())
	//	{
	//		Dumper(	$test );
	//	}
	//$a_heureMinMax=reqToArray1Att($heureMinMax);
	$infoServiceJour->closeCursor(); /*a recuperer sur le array pour limiter nb req*/
	///***
	
	
	
	
	
	
	//############################## 
	
	//$infoRDV = $bdd->prepare("SELECT Patients.nom, Patients.prenom, Patients.numSS, CreneauxInterventions.niveauUrgence, CreneauxInterventions.statut 
	//					FROM CreneauxInterventions JOIN Patients
	//					WHERE CreneauxInterventions.PatientnumSS = Patients.numSS
	//					"); // !!!heure= heureRDV
	//
	//AND CreneauxInterventions.InterventionsidIntervention = :intervention
	//					AND CreneauxInterventions.dateRdv = :date
	//					AND CreneauxInterventions.heure =:heure
	//$infoRDV->execute(array('intervention'=> $intervention,
	//						'date'=> $service,
	//						'heure'=> $service,
	//				  ));
	//$infoRDV->execute();
	
	//$a=[];
	//	while ($a_infoRDV=($infoRDV->fetch(PDO::FETCH_ASSOC)))
	//	{
	//		array_push(	$a,$a_infoRDV );
	//	}
	//	//return ($a_out_reqToArray);
	//	//}
	//Dumper($a);
	
	//$infoRDV->closeCursor();

	//function reqToArray($requete)
	//{
	//	$a_out_reqToArray=[];
		// ou heure max et heure min de la journé



// **************************  AFFICHAGE PAGE ********************************************   
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="style.css" type="text/css"  />  <!--a faire ----->
<title>Bonjour</title>
</head>

<body>
    <?php include ('../Config/Menupage.php');?> 
	<?php echo($user_id);
	?>

	
	
<!--	<table  BORDER="1",ALIGN="CENTER", VALIGN="MIDDLE " >
	<tr><th>Heure</th>
	<?php
		foreach ($a_idActes as $idx=>$acte)
		{
	?>
			<th> <?php echo $acte ?></th>
	<?php
		}
	?>
	</tr>	
	<?php
		foreach ($a_heure as $idx=>$h)
		{
	?>
		<tr><td><?php echo $h ?></td>
	<?php
			foreach ($a_idActes as $idx=>$acte)
			{
				echo $acte;
				//if (array_key_exists($row,$a_info))
				//{
	?>
	// REQUETE
			<td class=$infoRDV["statut"]>
	<?php
					//echo $infoRDV["nom"]." ".$infoRDV["prenom"]."\n".$infoRDV["numSS"]."\n".$infoRDV["statut"]
				// Dumper ($a_info[$row][$value]);//.$row.'colonne'.$val;
				//}
				//else
				//{
	//			?>
	//// REQUETE
	//		<td class="pasRDV">
	//<?php
				//	echo "libre";
				//}
	?> 
			</td>
	<?php
			} 
	?>		
		</tr>
	<?php
		} 
	?>
	</table>
	
	
	-->
	
	
	
	
</body>
</html>
