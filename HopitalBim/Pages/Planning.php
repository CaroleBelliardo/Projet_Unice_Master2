<!--TODO :
- Supprimer la requete extractReq --=extractReq2
- requete = tableau
- nomattribut = creneau.incomp
- rendez-vous annulés 
- ajour header 
-->
<?php
	include ('../Config/Menupage.php');
	include ('../Fonctions/Affichage.php');
	include ('../Fonctions/ReqTraitement.php');
	require_once("../session.php"); 
	require_once("../classe.Systeme.php");
	$auth_user = new Systeme();
	$bdd=$auth_user->conn;

	date_default_timezone_set('Europe/Paris');
	// --- La setlocale() fonctionnne pour strftime mais pas pour DateTime->format()
	setlocale(LC_TIME, 'fr_FR.utf8','fra');// OK
	// strftime("jourEnLettres jour moisEnLettres annee") de la date courante
	
	function infoo($bdd)// recupere les info de la requete a afficher dans le planning
	// tableau 3D cle1 = H(ex : 08:00, 08:15,...);
	// cle2 = acte (ex : scanner, IRM,...);
	// cle 3 = attribut (ex : nom, prenom, numSS,...);
	{ 
		$infoServiceJour = $bdd->prepare("SELECT TIME_FORMAT(CreneauxInterventions.heure_rdv,'%H:%i'), Interventions.acte, Patients.nom, Patients.prenom, Patients.numSS, CreneauxInterventions.niveauUrgence, CreneauxInterventions.statut 
			FROM `CreneauxInterventions` JOIN Interventions JOIN Patients
			WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
			AND CreneauxInterventions.PatientsnumSS = Patients.numSS
			AND CreneauxInterventions.statut != 'a'
			AND date_rdv= '2017-11-01'/*:date*/
			AND ServicesnomService = 'Cardiologie'/*:service*/ ");
		$infoServiceJour->execute();
		$infoServiceJours=[];
		while ($test = $infoServiceJour->fetch(PDO::FETCH_ASSOC))
			{
				if (array_key_exists($test["TIME_FORMAT(CreneauxInterventions.heure_rdv,'%H:%i')"],$infoServiceJours))
				{																						;
					$infoServiceJours[$test["TIME_FORMAT(CreneauxInterventions.heure_rdv,'%H:%i')"]][$test["acte"]]=["nom"=>$test["nom"],
																										"prenom"=>$test["prenom"],
																										"numSS"=>$test["numSS"],
																										"niveauUrgence"=>$test["niveauUrgence"],
																										"statut"=>$test["statut"]]																					;	
				}
				else
				{
					$infoServiceJours[$test["TIME_FORMAT(CreneauxInterventions.heure_rdv,'%H:%i')"]]=array($test["acte"] => ["nom"=>$test["nom"],
										"prenom"=>$test["prenom"],
										"numSS"=>$test["numSS"],
										"niveauUrgence"=>$test["niveauUrgence"],
										"statut"=>$test["statut"]]
										);
				}
			}
		$infoServiceJour->closeCursor(); /*a recuperer sur le array pour limiter nb req*/
		return ($infoServiceJours);
		
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
	// -- Tous les actes réalisées dans le service de l'utilisateur
	$idActes = $bdd->prepare("SELECT acte
						  FROM Interventions
						  WHERE ServicesnomService = :service");
	$idActes->execute(array('service'=> $service));//$donnees = mysqli_fetch_array($Actes)
	$a_idActes=reqToArray1Att($idActes);
	$idActes->closeCursor();

	// -- date du jour
	$dateCourant = date("d-m-y"); // A SUPPRIMER ^^^^^^^^^^^^^^^^^^^^^^^^^^^
	//$heureCourante = date("H:i:s");
	$dateCourant = '2017-11-02'; /*test*/
	
	
	//-- PLANNING du service ***
	//-- horaires de début et fin de rendez-vous Pour la journée 
	$heureMinMax = $bdd->prepare("SELECT TIME_FORMAT( min(heure_rdv),'%H:%i'),TIME_FORMAT( max(heure_rdv),'%H:%i') 
		FROM `CreneauxInterventions` JOIN Interventions
		WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
		AND date_rdv= :date
		AND ServicesnomService = :service ");
	$heureMinMax->execute(array('service'=> $service,
								'date'=>$dateCourant));//$donnees = mysqli_fetch_array($Actes)
	$a_heureMinMax=reqToArrayPlusAtt($heureMinMax);
	$heureMinMax->closeCursor(); /*a recuperer sur le array pour limiter nb req*/
	
	$a_heures=[]; // liste des créneaux  
	$range_heure=range(strtotime($a_heureMinMax[0]),strtotime($a_heureMinMax[1]),15*60);
	foreach($range_heure as $time) // genere une liste d'heure
	{
        array_push($a_heures,date("H:i",$time));
	}	
	
	$infoServiceJours=infoo($bdd);

	
	
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
	
	
	
<form method="post" class="form-signin">
	 <h2 class="form-signin-heading">Planning du service <?php echo $service,", le ",Affiche_dateFr($dateCourant);; ?></h2><hr />
	 <?php
	 if(isset($error))
	 {
		foreach($error as $error)
		{
	?>
			<div class="alert alert-danger">
				<i class=""></i> &nbsp; <?php echo $error; ?>
            </div>
	<?php
		}
	 }	
		else if(isset($_GET['Valide']))
		{
	?>
		<div class="alert alert-info">
            <i class=""></i>Patient enregistré avec succes<a href='Pageprincipale.php'>Page principale</a>
		</div>
<?php } ?> 
	
	
	
	
   <fieldset>
	    <legend> Date </legend> <!-- Titre du fieldset --> 
		<form method="post" action="traitement.php">
			<p>
				<input type="date" />
				<input type="submit" value="Envoyer" />
			</p>
		</form>
	</fieldset>
	<fieldset>
	    <legend> Services </legend> <!-- Titre du fieldset --> 
		<form method="post" action="traitement.php">
			<p>
				<?php liste_Services($auth_user) ?>
				<input type="submit" value="Envoyer" />
			</p>
		</form>
	</fieldset>
	
	
	
	
	<table  BORDER="1",ALIGN="CENTER", VALIGN="MIDDLE " >
	<tr><th>Heure</th>
	<?php 
		foreach ($a_idActes as $idx=>$acte) // affichage de l'en-tete
		{
	?>
			<th> <?php echo $acte ?></th> 
	<?php
		}
	?>
	</tr>	
	<?php							// affichage du tableau
		foreach ($a_heures as $idx=>$h) // Pour chaque ligne
		{
	?>
		<tr><th><?php echo $h ?></th> 
	<?php 
			foreach ($a_idActes as $idx=>$acte) // Pou chaque colonne
			{
				if (array_key_exists($h,$infoServiceJours))
				{
					if (array_key_exists($acte,$infoServiceJours[$h]))
					{
	?>
				<td class= $infoServiceJours[$h][$acte]["statut"]>
	<?php
					echo $infoServiceJours[$h][$acte]["statut"];
					echo $infoServiceJours[$h][$acte]["nom"]." ".
					$infoServiceJours[$h][$acte]["prenom"]."\n".
					$infoServiceJours[$h][$acte]["numSS"]."\n";
					}
					else
					{
	?>
						<td class="pasRDV">
	<?php
						echo "Libre";
					}
				}
				else
				{
	?>
				<td class="pasRDV">
	<?php
				echo "Libre";
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
	</table>
	
	<!--
	<?php
	if(isset($_POST['submit'])){
	header("Location: ServiceCreer.php");
	exit;
	}
	?>
	<form>
<input type="button" value="Home" class="homebutton" id="btnHome" onClick="<?php header("Location: "); ?>" />
	</form>-->

	<?php 	quitter1()	?>	
	
	
	<?php 
		quitter2()
	?>
	
</body>
</html>
