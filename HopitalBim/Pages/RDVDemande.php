<!-- Chose a faire : 
		- Regarder la gestion des maj et des minuscules des entrées dans la base de donnée
		   proposition : nom de famille tout en majuscule et prenom tout en minuscule.
		   ou : ucfirst() - Make a string's first character uppercase
		   
		-// si $patient = "" alors redirect vers page principale
		- Afficher d'autre info relatif au patient : comme le nom et le prenom, date de naissance etc ..
		
		-- utiliser explode pour transfo string to array
		-- implode array to string
		-- ARAY_map = appliquer une instruction à tout un tableau 
		
		-->
<?php
include ('../Config/Menupage.php'); //menu de navigation
include ('../Fonctions/RDV.php'); // fonctions specifiques à demande RDV

$lien= 'RDVDemande.php';

				
	
if(isset($_POST['btn_demandeRDV'])) // si utilisateur clique sur le bouton demande de rendez vous
{
	$patient=$_SESSION["patient"]; // recupration et traitement des informations saisies
	$text_nomPathologie = ucfirst(trim($_POST['text_nomPathologie'], ' '));	// trim enleve les espaces en debut et fin mais pas au milieu 
	$text_indicationPathologie= trim($_POST['text_indicationPathologie'], ' ');

	$text_idIntervention = preg_replace("/[^0-9]/", "",trim($_POST['text_idIntervention'], ' '));
	// $text_indicationIntervention = ucfirst(trim($_POST['text_indicationIntervention'], ' ')); !!!!! on l'a supprimé de la bdd
echo $text_idIntervention,$text_idIntervention,$text_idIntervention,$text_idIntervention,$text_idIntervention;
	$text_niveauUrgence = trim($_POST['text_urgence'], ' ');
	$text_commentaires = trim($_POST['text_commentaires'], ' ');	

	if ($text_idIntervention == "")  // Gestion erreur : nom de d'intervention non renseigné
	{
		$error[] =  " Saisir le nom de l'intervention souhaitée";  
	} 
	else if ($text_nomPathologie == "" )  // Gestion erreur : nom de de la pathologie non renseigné
	{
		$error[] =  "Saisir le nom de la pathologie"; 
	}
	else
	{
		// Gestion erreur : nom de d'intervention invalide
		$req_idInt = $auth_user->runQuery(" SELECT idIntervention FROM Interventions WHERE idIntervention = :idIntervention" ); // recherche l'idIntervention dans la bdd
		$req_idInt->execute(array('idIntervention'=> $text_idIntervention));
		$id= $req_idInt-> fetchColumn();
		if ($id == FALSE) // nom de d'intervention abscent de la base de donnée
		{
			$error[] =  "Saisir un nom d'intervention valide";										  
		}
		//else ;
		//{
		
		//+++ il faut tester si tuple = patho + indication existe et recuperer la valeur de l'id pour affecter à $idPatho,
		// sinon saisir l'entrer dans bdd.Patholoegie et recuperer l'idée (MEME requete separé par ;) 
		// $text_nomPathologie + $text_indicationPathologie
		
		
		//+++ Si niveauUrgence == 0 alors on cherche le prochain (le dernier créneaux)
		//switch ($text_niveauUrgence)
		//{
		//	case 0:
		//		echo "i égal 0"; alors on insert à la suite 
		//	case 1:
				// +++ ON TEST SI Niveau d'urgence = niveau urgence INcompatible
					//  => notif
						// ecris dans tableau notif
					// si notif = selectionné et validé alors supprimé de la table notif *** pour notif page
					// si notif = validé et acceptée alors niveau urgence = modifié *** pour notif page
				
				//POUR INSERTION :
				// on test si on peut inserer en respectant le delais sur creneaux dispo
						//on fixe l'heure limite
							//$now=ProchaineHeureArrondie();
							//$heureN1=heurePlus15($a,'+360 minutes');		// tester si heure requete < a heure attendu  $heureN1
						//  => on insert 
					// !!! gestion erreur : test si retour = vide ( tous creneaux dans le delais = occupés par rdv plus urgents)
						// =>> alors on cherche le prem rdv dispo MIN(dont niveau inferieur, pour meme acte et jour et heure > mtn)
				// Sinon
						//à partir de cette date on recupère tous les rdv de la journée et on ajoute + 15 
						//$test = $auth_user->runQuery("Select * FROM CreneauxInterventions
						//							 WHERE
						//							 idIntervention = :$text_idIntervention
						//							 date_rdv = :dateN1 // ou jours d'après selon le niveau d'urgence
						//							heure_rdv = :heureN1
						//							 niveauUrgence < $text_niveauUrgence
						//							 ");
						//					// !!! gestion erreur : test si retour = vide ( tous creneaux dans le delais = occupés par rdv plus urgents)
						//					// =>> alors on cherche le prem rdv dispo MIN(dont niveau inferieur, pour meme acte et jour et heure > mtn)
						//$test-> execute();
						//$testt= reqToArrayPlusligne($test) ;	 //reqToArrayPlusAttASSO 
						//
						//array_map (heurePlus15($a,'+15 minutes'), $testt["heure_rdv"]=heurePlus15);
						//
						// test si heure max > horaire service => notif chef de service*
				// rearrangeement manuel ??? 
				
		//	case 2:
		//$now=ProchaineHeureArrondie();
		//$heureN2=heurePlus15($a,'+1440 minutes');		// tester si heure requete < a heure attendu 
		//
		//  case 3:
		//$heureN3=
		//	default:
		//}
		
		
		
		// Il faut récuperer le statut pour savoir si c'est le dernier rdv prévu => rajouter plus 15 min,!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		// sinon si on fait rien heure RDV = heureRDV_annulé!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		//$req_infoDateHeure = $auth_user->runQuery(" SELECT MIN(dateR), MIN(heureR), statutR 
		//	FROM  (
		//	SELECT MAX(dateR1) as dateR, MAX(heureR1) as heureR, statutR
		//	FROM  (
		//	(SELECT max(date_rdv) as dateR1, max(heure_rdv)  as heureR1, CreneauxInterventions.statut as statutR       
		//	FROM CreneauxInterventions JOIN Interventions  JOIN Services 
		//	WHERE  CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
		//	AND Interventions.ServicesnomService = Services.nomService
		//	AND date_rdv = CURDATE() 
		//	AND heure_rdv > CURRENT_TIMESTAMP()
		//	AND InterventionsidIntervention = '4'
		//	AND CreneauxInterventions.statut != 'a'
		//	AND heure_rdv > (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = '4' AND Interventions.ServicesnomService = Services.nomService)
		//	AND heure_rdv < (SELECT horaire_fermeture FROM Interventions  JOIN Services WHERE idIntervention = '4'  AND Interventions.ServicesnomService = Services.nomService)
		//	) 
		//	UNION
		//	(SELECT  max(date_rdv) as dateR1, max(heure_rdv)  as heureR1, CreneauxInterventions.statut as statutR        
		//	FROM CreneauxInterventions JOIN Interventions  JOIN Services 
		//	WHERE  CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
		//	AND Interventions.ServicesnomService = Services.nomService
		//	AND date_rdv > CURDATE() 
		//	AND InterventionsidIntervention = '4'
		//	AND CreneauxInterventions.statut != 'a'
		//	AND heure_rdv > (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = '4' AND Interventions.ServicesnomService = Services.nomService)
		//	AND heure_rdv < (SELECT horaire_fermeture FROM Interventions  JOIN Services WHERE idIntervention = '4'  AND Interventions.ServicesnomService = Services.nomService)
		//	) )as d1
		//	UNION
		//	(SELECT max(date_rdv) as dateR, max(heure_rdv)  as heureR, CreneauxInterventions.statut as statutR      
		//	FROM CreneauxInterventions JOIN Interventions  JOIN Services 
		//	WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
		//	AND Interventions.ServicesnomService = Services.nomService
		//	AND date_rdv = CURDATE() # !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! >
		//	AND heure_rdv > CURRENT_TIMESTAMP() # !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! >
		//	AND CreneauxInterventions.statut = 'a'
		//	AND InterventionsidIntervention = '4'
		//	AND heure_rdv > (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = '4' AND Interventions.ServicesnomService = Services.nomService)
		//	AND heure_rdv < (SELECT horaire_fermeture FROM Interventions  JOIN Services WHERE idIntervention = '4'  AND Interventions.ServicesnomService = Services.nomService)
		//	)
		//	UNION
		//	(SELECT max(date_rdv) as dateR, max(heure_rdv)  as heureR, CreneauxInterventions.statut as statutR   
		//	FROM CreneauxInterventions JOIN Interventions  JOIN Services 
		//	WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
		//	AND Interventions.ServicesnomService = Services.nomService
		//	AND date_rdv > CURDATE() # !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! >
		//	AND CreneauxInterventions.statut = 'a'
		//	AND InterventionsidIntervention = '4'
		//	AND heure_rdv > (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = '4' AND Interventions.ServicesnomService = Services.nomService)
		//	AND heure_rdv < (SELECT horaire_fermeture FROM Interventions  JOIN Services WHERE idIntervention = '4'  AND Interventions.ServicesnomService = Services.nomService)
		//	) 
		//	) as d
		//");
		//
		//$req_infoDateHeure->execute(array()); // modifier variables
		//$a_infoDateHeure = reqToArrayPlusAttASSO($req_infoDateHeure);
		//$req_infoDateHeure->closeCursor();
		// 
		// //info heure si non urgent 
		//if ($a_infoDateHeure["MIN(heureR)"] == NULL ) // si pas de rdv prevu dans la journée, retour requete = null
		//{
		//	$a_infoDateHeure["MIN(heureR)"] = ProchaineHeureArrondie();
		//	$date = $a_infoDateHeure["MIN(dateR)"] = date("Y-m-d");
		//}
		////if ($a_infoDateHeure["statut"] != 'a') // a verif si heure retournée : rdv = annulé ou dernier rdv !!!!!! a décommenter quand requete OKAY
		////{
		////	$heure=heurePlus15($a_infoDateHeure["MIN(heureR)"],'+15 minutes');
		////} 
		////else
		////{
		////	$heure=$a_infoDateHeure["MIN(heureR)"];
		////}
		//
		//
		//// insertion rdv
		//$ajoutRDV = $auth_user->runQuery("INSERT INTO CreneauxInterventions (date_rdv, heure_rdv, InterventionsidIntervention,
		//								niveauUrgence, pathologie, commentaires, PatientsnumSS, EmployesCompteUtilisateursIdEmploye) 
		//								VALUES (:date_rdv, :heure_rdv, :InterventionsidIntervention, :niveauUrgence, :pathologie, :commentaires,:PatientsnumSS,
		//								:EmployesCompteUtilisateursIdEmploye)");
		//
		//
		//	
		//$ajoutRDV->execute(array('date_rdv'=> $date,
		//						'heure_rdv'=> $heure,
		//						'InterventionsidIntervention'=> $text_idIntervention,
		//						'niveauUrgence'=> $text_niveauUrgence,
		//						'pathologie'=> $idPatho, // a rechercher !!!!!!!!!!!
		//						'commentaires'=> $text_commentaires,
		//						'PatientsnumSS'=> $patient,
		//						'EmployesCompteUtilisateursIdEmploye'=> $user_id));
		//
		//}
	}
}
?>

<!DOCTYPE html PUBLIC >
<html>
<head>
<link rel="stylesheet" href=Style.css">
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>Demande RDV</title>
</head>

<body>
 
 
	<?php // affichage
		If (!array_key_exists("patient",$_SESSION )) 
		{
			include ('../Formulaires/RecherchePatient.php');; // recherche patient existe pas (redirection fiche patient)
		}
		else
		{
			include ('../Formulaires/DemandeRDV.php');; // recherche patient existe pas (redirection fiche patient)
			
		}
	?>

 

 
 
   
</body>


</html>
<?php //***
//
//SELECT MIN(dateR), MIN(heureR)
//FROM  (
//(SELECT max(date_rdv) as dateR, max(heure_rdv)  as heureR      
//FROM CreneauxInterventions JOIN Interventions  JOIN Services 
//WHERE  CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
//AND Interventions.ServicesnomService = Services.nomService
//AND date_rdv >= CURDATE() 
//AND heure_rdv >= CURRENT_TIMESTAMP()
//AND InterventionsidIntervention = '4'
//AND CreneauxInterventions.statut = 'p'
//AND heure_rdv >= (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = 4 AND Interventions.ServicesnomService = Services.nomService)
//AND heure_rdv < (SELECT horaire_fermeture FROM Interventions  JOIN Services WHERE idIntervention = 4  AND Interventions.ServicesnomService = Services.nomService)
//) 
//UNION
//(SELECT max(date_rdv) as dateR, max(heure_rdv)  as heureR     
//FROM CreneauxInterventions JOIN Interventions  JOIN Services 
//WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
//AND Interventions.ServicesnomService = Services.nomService
//
//AND date_rdv >= CURDATE() # !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! >
//AND heure_rdv >= CURRENT_TIMESTAMP() # !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! >
//AND CreneauxInterventions.statut = 'a'
//
//AND InterventionsidIntervention = 5 
//AND heure_rdv >= (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = 5 AND Interventions.ServicesnomService = Services.nomService)
//AND heure_rdv < (SELECT horaire_fermeture FROM Interventions  JOIN Services WHERE idIntervention = 5  AND Interventions.ServicesnomService = Services.nomService)
//) 
//) as date
//
//INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `pathologie`, `commentaires`, `VerifCoherencePathoUrgences`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-11-30', '06:30:00', '4', '0', 'p', NULL, NULL, NULL, '178854747412138', 'ly12454');
//INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `pathologie`, `commentaires`, `VerifCoherencePathoUrgences`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-11-30', '12:00:00', '4', '0', 'a', NULL, NULL, NULL, '178854747412138', 'ly12454');
//INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `pathologie`, `commentaires`, `VerifCoherencePathoUrgences`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-11-30', '16:30:00', '4', '0', 'p', NULL, NULL, NULL, '178854747412138', 'ly12454');
//INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `pathologie`, `commentaires`, `VerifCoherencePathoUrgences`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-11-30', '17:00:00', '4', '0', 'a', NULL, NULL, NULL, '178854747412138', 'ly12454');

//SELECT MIN(dateR), MIN(heureR)
//		FROM  (
//		(SELECT max(date_rdv) as dateR, max(heure_rdv)  as heureR      
//		FROM CreneauxInterventions JOIN Interventions  JOIN Services 
//		WHERE  CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
//		AND Interventions.ServicesnomService = Services.nomService
//		AND date_rdv = CURDATE() 
//		AND heure_rdv > CURRENT_TIMESTAMP()
//		AND InterventionsidIntervention = '4'
//		AND CreneauxInterventions.statut = 'p'
//		AND heure_rdv > (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = '4' AND Interventions.ServicesnomService = Services.nomService)
//		AND heure_rdv < (SELECT horaire_fermeture FROM Interventions  JOIN Services WHERE idIntervention = '4'  AND Interventions.ServicesnomService = Services.nomService)
//		) 
//		UNION
//		(SELECT max(date_rdv) as dateR, max(heure_rdv)  as heureR      
//		FROM CreneauxInterventions JOIN Interventions  JOIN Services 
//		WHERE  CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
//		AND Interventions.ServicesnomService = Services.nomService
//		AND date_rdv > CURDATE() 
//		AND InterventionsidIntervention = '4'
//		AND CreneauxInterventions.statut = 'p'
//		AND heure_rdv > (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = '4' AND Interventions.ServicesnomService = Services.nomService)
//		AND heure_rdv < (SELECT horaire_fermeture FROM Interventions  JOIN Services WHERE idIntervention = '4'  AND Interventions.ServicesnomService = Services.nomService)
//		) 
//		 
//		UNION
//		(SELECT max(date_rdv) as dateR, max(heure_rdv)  as heureR     
//		FROM CreneauxInterventions JOIN Interventions  JOIN Services 
//		WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
//		AND Interventions.ServicesnomService = Services.nomService
//		AND date_rdv = CURDATE() # !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! >
//		AND heure_rdv > CURRENT_TIMESTAMP() # !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! >
//		AND CreneauxInterventions.statut = 'a'
//		AND InterventionsidIntervention = '4'
//		AND heure_rdv > (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = '4' AND Interventions.ServicesnomService = Services.nomService)
//		AND heure_rdv < (SELECT horaire_fermeture FROM Interventions  JOIN Services WHERE idIntervention = '4'  AND Interventions.ServicesnomService = Services.nomService)
//		)
//		UNION
//		(SELECT max(date_rdv) as dateR, max(heure_rdv)  as heureR     
//		FROM CreneauxInterventions JOIN Interventions  JOIN Services 
//		WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
//		AND Interventions.ServicesnomService = Services.nomService
//		AND date_rdv > CURDATE() # !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! >
//		AND CreneauxInterventions.statut = 'a'
//		AND InterventionsidIntervention = '4'
//		AND heure_rdv > (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = '4' AND Interventions.ServicesnomService = Services.nomService)
//		AND heure_rdv < (SELECT horaire_fermeture FROM Interventions  JOIN Services WHERE idIntervention = '4'  AND Interventions.ServicesnomService = Services.nomService)
//		) 
//		) as date

?>