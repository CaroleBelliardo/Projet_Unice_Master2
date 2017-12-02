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
include ('../Fonctions/RDVDemande.php'); // fonctions specifiques à demande RDV

$lien= 'RDVDemande.php';
//
//$niveauUrgence ='0';
//$idPatho = '5'; 
//$idIntervention = '3';
//
//$var =VerificationPathologie ($auth_user,$niveauUrgence, $idPatho,$idIntervention );
//var_dump ($var);


if(isset($_POST['btn_demandeRDV'])) // si utilisateur clique sur le bouton demande de rendez vous
{
// ************************************** RECUPERE LES VALEURS SAISIE DANS LE FORMULAIRE ********************************************************
	$patient=$_SESSION["patient"]; // recupration et traitement des informations saisies
	$text_nomPathologie = ucfirst(trim($_POST['text_nomPathologie'], ' '));	// trim enleve les espaces en debut et fin mais pas au milieu 
	$text_indicationPathologie= trim($_POST['text_indicationPathologie'], ' ');
	$text_idIntervention = preg_replace("/[^0-9]/", "",trim($_POST['text_idIntervention'], ' '));
	$text_niveauUrgence = trim($_POST['text_urgence'], ' ');
	$text_commentaires = trim($_POST['text_commentaires'], ' ');
	
// ************************************** GESTION D'ERREUR ENTREES ******************************************************************************
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
		$req_idInt = $auth_user->runQuery(" SELECT idIntervention
										  FROM Interventions
										  WHERE idIntervention = :idIntervention" ); // recherche l'idIntervention dans la bdd
		$req_idInt->execute(array('idIntervention'=> $text_idIntervention));
		$id= $req_idInt-> fetchColumn();
		if ($id == FALSE) // nom de d'INTERVENTION abscent de la base de donnée
		{
			$error[] =  "Saisir un nom d'intervention valide";										  
		}
		else ; // Saisie OK
		{
// ************************************** RECHERCHE LES VALEURS des variables NECESSAIRE  POUR ENREGISTRER LE RDV *********************************
			
		//-- recherche idPatho
			$req_PathoExist = $auth_user->runQuery(" SELECT idPatho 
												   FROM Pathologies
												   WHERE nomPathologie = :nomPatho
												   And indication = :indication" ); 
			$req_PathoExist->execute(array('nomPatho'=> $text_nomPathologie, 
										   'indication'=> $text_indicationPathologie));
			
			$idPatho= $req_PathoExist-> fetchColumn();
			$req_PathoExist->closeCursor();
			if ($idPatho == "" ) // si patho existe pas dans bdd
			{
				$req_idPathoInsert = $auth_user->runQuery(" INSERT INTO Pathologies (nomPathologie, indication) 
															VALUES ( :nomPatho, :indication)");  // insert la patho et son indication
				$req_idPathoInsert->execute(array('nomPatho'=> $text_nomPathologie, 
												'indication'=> $text_indicationPathologie)); 
				$req_idPathoInsert->closeCursor();
				
				
				$req_idPathoRecup = $auth_user->runQuery(" SELECT MAX(idPatho)
															FROM Pathologies");  // recupere l'idPatho
				$req_idPathoRecup->execute(array());  
				$idPatho= $req_idPathoRecup-> fetchColumn(); // affecte l'id de ce dernier insert à la variable $PathoExist
				$req_idPathoRecup->closeCursor();
				
				
				$req_refUrgence = $auth_user->runQuery(" INSERT INTO InterventionsPatho (InterventionsidIntervention, PathologiesidPatho)
															VALUES ( :idInter, :idPatho)"); // Renseigne les valeurs de priorité = par default :0
				$req_refUrgence->execute(array('idInter'=> $text_idIntervention,
											   'idPatho'=> $idPatho));
				$req_refUrgence->closeCursor();
			}

		
		
		// -- Recherche l'HEURE de creneau dispo la plus proche
			$a_infoDateHeure=prochainCreneauxDispo($auth_user); // 55555555555555555555555555555555555555555555555555555****
			
			if ($a_infoDateHeure["MIN(heureR)"] == NULL ) // si PAS DE RDV prevu pour ce service dans le planning, retour requete = null => affecte date & heure :mtn
			{
				$a_infoDateHeure["MIN(heureR)"] = ProchaineHeureArrondie();
				$date = $a_infoDateHeure["MIN(dateR)"] = date("Y-m-d");
			}
	
			if ($a_infoDateHeure["statutR"] == 'a') // a verif si creneaux = ANNULE 
			{
				$heure=$a_infoDateHeure["MIN(heureR)"]; 
			} 
			else
			{
				$a_infoDateHeure["MIN(heureR)"]=heurePlus15($a_infoDateHeure["MIN(heureR)"],'+15 minutes');
			}
		
		
		//
		//echo"::'no urg:<br>";
		//echo "heure ===".($a_infoDateHeure["MIN(heureR)"]."<br>"."date ===".$a_infoDateHeure["MIN(dateR)"] );		
		//echo":::";
		//
		// -- RECHERCHE HEURE APPROPRIEE SI niveau urgence != 0*********************************
			If ($text_niveauUrgence !=0) // si rdv = urgent -> determine un delais pour traiter l'urgence
			{
				$now=ProchaineHeureArrondie(); // heure de mtn
				switch ($text_niveauUrgence) // fixe un delais selon niveau urgence
				{
					case 3:
						$delais=heurePlus15($now,'+180 minutes'); 
						break;//alors on insert à la suite 
					case 2:
						$delais=heurePlus15($now,'+360 minutes');
						break;
					case 1:
						$datedelais = date('Y-m-d', strtotime('+1 day'));
						$delais=$now;
						break;
				}
		$a_infoDateHeureUrgence=prochainCreneauxUrgent($auth_user,$text_niveauUrgence); // 55555555555555555555555555555555555555555555555555555****
		
			Dumper($a_infoDateHeureUrgence);

		//echo"::urg:<br>";
		//echo "heure urg====".($a_infoDateHeureUrgence["MIN(heureR)"]."<br>"."date urg===".$a_infoDateHeureUrgence["MIN(dateR)"] );		
		//echo":::";
		//
		//
		
// ************************************** Decal rdv suivant  SI niveau urgence != 0*********************************
		
		// decaler + 15 min !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			//	 !!!! rdv annulé pas de décalage
			//	
			//$test = $auth_user->runQuery("Select * FROM CreneauxInterventions
			//							 WHERE
			//							 idIntervention = :$text_idIntervention
			//							 date_rdv = :dateN1 // ou jours d'après selon le niveau d'urgence
			//							heure_rdv = :heureN1
			//							 niveauUrgence < $text_niveauUrgence
			//							 ");
			 $req_CreneauSuivant = $auth_user->runQuery(" Select * FROM CreneauxInterventions
														WHERE InterventionsidIntervention = '4'
														AND date_rdv ='2017-12-18' 
																AND heure_rdv >'11:00'" ); 
			$req_CreneauSuivant->execute();
			$a_creneauSuiv= reqToArrayPlusligne($req_CreneauSuivant);
			$req_CreneauSuivant->closeCursor();
			Dumper($a_creneauSuiv);
			
			foreach($a_creneauSuiv["id_rdv"] as $k=>$v)
			{
				if ($a_creneauSuiv["statut"][$k]  == 'a')
				{
					
					break;
				}
				else
				{
					$a_creneauSuiv["heure_rdv"][$k] = heurePlus15($a_creneauSuiv["heure_rdv"][$k],'+15 minutes');
				}
			}
			echo ";;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;<br>";
			Dumper($a_creneauSuiv);
			
			
	// +++test si heure max > horaire service => notif chef de service*
			
			}

				// !!! gestion erreur : test si retour = vide ( tous creneaux dans le delais = occupés par rdv plus urgents) !!!!!!! AVOIR !!!!!!
						// =>> alors on cherche le prem rdv dispo MIN(dont niveau inferieur, pour meme acte et jour et heure > mtn)
				
			
		//// INSERTION rdv
			$ajoutRDV = $auth_user->runQuery("INSERT INTO CreneauxInterventions (date_rdv, heure_rdv, InterventionsidIntervention,
										niveauUrgence, pathologie, commentaires, PatientsnumSS, EmployesCompteUtilisateursIdEmploye) 
										VALUES (:date_rdv, :heure_rdv, :InterventionsidIntervention, :niveauUrgence, :pathologie,
										:commentaires,:PatientsnumSS, :EmployesCompteUtilisateursIdEmploye)");
			$ajoutRDV->execute(array('date_rdv'=> $date,
								'heure_rdv'=> $heure,
								'InterventionsidIntervention'=> $text_idIntervention,
								'niveauUrgence'=> $text_niveauUrgence,
								'pathologie'=> $idPatho, // a rechercher !!!!!!!!!!!
								'commentaires'=> $text_commentaires,
								'PatientsnumSS'=> $patient,
								'EmployesCompteUtilisateursIdEmploye'=> $user_id));
			$ajoutRDV->closeCursor();

		}
	// ************************************** TEST CAS D'UTILISATION "VERIFICATION" *********************************
		// +++ ON TEST SI Niveau d'urgence = niveau urgence INcompatible
					//  => notif
						// ecris dans tableau notif
					// si notif = selectionné et validé alors supprimé de la table notif *** pour notif page
					// si notif = validé et acceptée alors niveau urgence = modifié *** pour notif page

		} // fin des instructions realisées si entrée = ok 
					
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
