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
	function reqToArray($requete)
	{
		$a_out_reqToArray=[];
		while ($temp = $requete->fetchColumn())
		{
			array_push(	$a_out_reqToArray,$temp );
		}
		return ($a_out_reqToArray);
	}
	
// Requetes ---	
	$user_id = $_SESSION['idEmploye']; // CompteUtilisateur.idEmploye == Employes.idUtilisateur
	$Service = $bdd->prepare("SELECT ServicesnomService 
						  FROM Employes
						  WHERE CompteUtilisateursidEmploye = :user_id "); 
	$Service->execute(array('user_id'=> $user_id));
	$service= ($Service->fetchColumn());
	$Service->closeCursor();
	
	$idActes = $bdd->prepare("SELECT acte
						  FROM Interventions
						  WHERE ServicesnomService = :service");
	$idActes->execute(array('service'=> $service));//$donnees = mysqli_fetch_array($Actes)
	$a_idActes=reqToArray($idActes);
	$idActes->closeCursor();

	
	
	$heure='08:00:00';
	$date='2017-11-02';
	$intervention='1';
	$infoRDV = $bdd->prepare("SELECT Patients.nom, Patients.prenom, Patients.numSS, CreneauxInterventions.niveauUrgence, CreneauxInterventions.statut 
						FROM CreneauxInterventions JOIN Patients
						WHERE CreneauxInterventions.PatientnumSS = Patients.numSS
						"); // !!!heure= heureRDV
	//AND CreneauxInterventions.InterventionsidIntervention = :intervention
	//					AND CreneauxInterventions.dateRdv = :date
	//					AND CreneauxInterventions.heure =:heure
	//$infoRDV->execute(array('intervention'=> $intervention,
	//						'date'=> $service,
	//						'heure'=> $service,
	//				  ));
	$infoRDV->execute();
	$a=[];
		while ($a_infoRDV=($infoRDV->fetch(PDO::FETCH_ASSOC)))
		{
			array_push(	$a,$a_infoRDV );
		}
		//return ($a_out_reqToArray);
		//}
	Dumper($a);
	
	$infoRDV->closeCursor();

	//function reqToArray($requete)
	//{
	//	$a_out_reqToArray=[];
	
	$a_heure = ["8","9","10","11","12"]; // recup heure service
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

	
	
	<table  BORDER="1",ALIGN="CENTER", VALIGN="MIDDLE " >
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
	
	
	
	
	
	
	
</body>
</html>
