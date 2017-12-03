<?php	// Requetes ---
		// -- Service à affiché, par defaut celui du service d'appartenance de l'utilisateur
	function infoPlanning ($auth_user)
	{
		if (!array_key_exists('serviceModifier',$_SESSION))
	{
		$_SESSION['serviceModifier'] =  $a_utilisateur["ServicesnomService"];
	
	}
	
	$idActes = $auth_user->runQuery("SELECT acte
							  FROM Interventions 
							  WHERE ServicesnomService = :service");
		$idActes->execute(array('service'=> $_SESSION['serviceModifier']));//$donnees = mysqli_fetch_array($Actes)
		$a_idActes=reqToArray1Att($idActes); // tableau 1D , k: idx, v : nom acte proposé par le service
		$idActes->closeCursor();
	
	// -- date du jour
	if (array_key_exists("dateModifier", $_SESSION ))
		{
			$dateCourant=$_SESSION["dateModifier"];
			if ( $dateCourant != date("d-m-y"))
			{
				$heureCourante = '08:00:00' ; // ? a remplacer par h debut service 
			}
		}
	else
	{
			$dateCourant = date("Y-m-d");
			$heureCourante = date("H:i:s");
	}
	
	//-- PLANNING du service ***
	//-- horaires reel de début et fin de rendez-vous Pour la journée 
	$heureMinMax = $auth_user->runQuery("SELECT  min(heure_rdv), max(heure_rdv) 
		FROM `CreneauxInterventions` JOIN Interventions
		WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
		AND date_rdv= :date
		AND ServicesnomService = :service ");
	$heureMinMax->execute(array('service'=> $_SESSION['serviceModifier'],
								'date'=>$dateCourant));
	$a_heureMinMax=reqToArrayPlusAtt($heureMinMax);
	$heureMinMax->closeCursor(); /*a recuperer sur le array pour limiter nb req*/

	// --heure prevu debut et fin de service 
	$req_horraireTravail= $auth_user->runQuery(" SELECT horaire_ouverture, horaire_fermeture
													FROM Services 
													WHERE  nomService = :service   "); // Renseigne les valeurs de priorité = par default :0
	$req_horraireTravail->execute(array("service" => $_SESSION['serviceModifier']));
	$horraireTravail = $req_horraireTravail->fetch(PDO::FETCH_ASSOC);
	$req_horraireTravail->closeCursor();

	//-- Gestion erreur : aucun rdv prevu pour la journée
	if ($a_heureMinMax[1] >= $horraireTravail['horaire_fermeture'] ) 
	{
		$heureFin= $a_heureMinMax[1];
		
	}
	elseif ($a_heureMinMax[1] < $horraireTravail['horaire_fermeture'] )
	{
		$heureFin= $horraireTravail['horaire_fermeture'];
	}
	
	if ($a_heureMinMax[0] < $horraireTravail['horaire_ouverture'] )
	{
		$heureDebut= $a_heureMinMax[1];
	}
	else
	{
		$heureDebut= $horraireTravail['horaire_ouverture'];
	}
	
	
	 //-- liste des creneaux à afficher 
	$a_heures=[]; // liste des créneaux  
	$range_heure=range(strtotime($heureDebut),strtotime($heureFin),15*60);
	foreach($range_heure as $time) // genere une liste d'heure
	{
        array_push($a_heures,date("H:i",$time));
	}
	
	// PLANNING du service ***
	//-- horaires de début et fin de rendez-vous Pour la journée 
		
		
	$infoServiceJour = $auth_user->runQuery("SELECT TIME_FORMAT(CreneauxInterventions.heure_rdv,'%H:%i'),
			Interventions.acte, Patients.nom, Patients.prenom, Patients.numSS,
			CreneauxInterventions.niveauUrgence, CreneauxInterventions.statut 
			FROM `CreneauxInterventions` JOIN Interventions JOIN Patients
			WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
			AND CreneauxInterventions.PatientsnumSS = Patients.numSS
			AND CreneauxInterventions.statut != 'a'
			AND date_rdv= :date
			AND ServicesnomService = :service ");
		$infoServiceJour->execute(array('date' => $dateCourant,
										'service'=> $_SESSION['serviceModifier']));
		$infoServiceJours=[];	
		while ($row = $infoServiceJour->fetch(PDO::FETCH_ASSOC))
			{
				if (array_key_exists($row["TIME_FORMAT(CreneauxInterventions.heure_rdv,'%H:%i')"],$infoServiceJours)) //initialisation des infos sur l'interv
				{
					$infoServiceJours[$row["TIME_FORMAT(CreneauxInterventions.heure_rdv,'%H:%i')"]][$row["acte"]]=[	"nom"=>$row["nom"],
																													"prenom"=>$row["prenom"],
																													"numSS"=>$row["numSS"],
																													"niveauUrgence"=>$row["niveauUrgence"],
																													"statut"=>$row["statut"]];				
				}
				else
				{
					$infoServiceJours[$row["TIME_FORMAT(CreneauxInterventions.heure_rdv,'%H:%i')"]]=array($row["acte"] => ["nom"=>$row["nom"],
										"prenom"=>$row["prenom"],
										"numSS"=>$row["numSS"],
										"niveauUrgence"=>$row["niveauUrgence"],
										"statut"=>$row["statut"]]
										);
				}
			}
			$infoServiceJour->closeCursor(); /*a recuperer sur le array pour limiter nb req*/
	 		$a_ref=['heure'=> $a_heures, 'actes' => $a_idActes, 'info'=>$infoServiceJours];
			return $a_ref;
	}
?>