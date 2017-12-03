<!-- Chose a faire : 
		- Regarder la gestion des maj et des minuscules des entrées dans la base de donnée
		   proposition : nom de famille tout en majuscule et prenom tout en minuscule.
		   ou : ucfirst() - Make a string's first character uppercase
		   
		-// si $patient = "" alors redirect vers page principale
		- Afficher d'autre info relatif au patient : comme le nom et le prenom, date de naissance etc ..
		
		-- utiliser explode pour transfo string to array
		-- implode array to string
		-->
<?php
include ('../Config/Menupage.php'); //menu de navigation
include ('../Fonctions/RDVDemande.php'); // fonctions specifiques à demande RDV
$lien= 'RDVDemande.php';

if(isset($_POST['btn_demandeRDV'])) // si utilisateur clique sur le bouton demande de rendez vous
{
// *************************     RECUPERATION ET TRAITEMENT DES VALEURS SAISIE DANS LE FORMULAIRE     *******************************************
//-- trim enleve les espaces en debut et fin mais pas au milieu
//-- ucfirst formate la premiere lettre en majuscule et le reste en minuscule 
	$nomPathologie = ucfirst(trim($_POST['text_nomPathologie'], ' '));	
	$indicationPathologie= trim($_POST['text_indicationPathologie'], ' ');
	$idIntervention = preg_replace("/[^0-9]/", "",trim($_POST['text_idIntervention'], ' '));
	$niveauUrgence = trim($_POST['text_urgence'], ' ');
	$commentaires = trim($_POST['text_commentaires'], ' ');
	
// *******************************        GESTION D'ERREUR SAISIES       ************************************************************************
	if ($idIntervention == "")  // Gestion erreur : nom de d'intervention non renseigné
	{
		$error[] =  " Saisir le nom de l'intervention souhaitée";  
	} 
	else if ($nomPathologie == "" )  // Gestion erreur : nom de de la pathologie non renseigné
	{
		$error[] =  "Saisir le nom de la pathologie"; 
	}
	else 
	{
		// Gestion erreur : nom de d'intervention invalide
		$req_idInt = $auth_user->runQuery(" SELECT idIntervention
											FROM Interventions
											WHERE idIntervention = :idIntervention" ); // recherche l'idIntervention dans la bdd
		$req_idInt->execute(array('idIntervention'=> $idIntervention));
		$id= $req_idInt-> fetchColumn();
		if ($id == FALSE) // nom de d'INTERVENTION abscent de la base de donnée
		{
			$error[] =  "Saisir un nom d'intervention valide";										  
		}
		else ; // Saisie OK
		{
// ************************************** RECHERCHE LES VALEURS des variables NECESSAIRE  POUR ENREGISTRER LE RDV *********************************
		// recup horraire de fin de service prévu 
			$req_horraireFermeture= $auth_user->runQuery(" SELECT horaire_ouverture, horaire_fermeture, Services.nomService FROM Services JOIN Interventions
															WHERE Services.nomService = Interventions.ServicesnomService 
															AND Interventions.idIntervention = :idIntervention   "); // Renseigne les valeurs de priorité = par default :0
			$req_horraireFermeture->execute(array("idIntervention" => $idIntervention));
			$a_horaireFermeture = $req_horraireFermeture->fetch(PDO::FETCH_ASSOC);
			$req_horraireFermeture->closeCursor();
		//-- recherche si l'association (nomPathologie -- indication) existe deja, si oui recupere la cle primaire sinon insert l'entree
			$req_PathoExist = $auth_user->runQuery(" SELECT idPatho 
												   FROM Pathologies
												   WHERE nomPathologie = :nomPatho
												   And indication = :indication" ); 
			$req_PathoExist->execute(array('nomPatho'=> $nomPathologie, 
										   'indication'=> $indicationPathologie));
			
			$idPatho= $req_PathoExist-> fetchColumn();
			$req_PathoExist->closeCursor();
			if ($idPatho == "" ) // si (nomPathologie -- indication) n'existe pas dans bdd alors insertion
			{
			// Enregistre la pathologie
				$req_idPathoInsert = $auth_user->runQuery(" INSERT INTO Pathologies (nomPathologie, indication) 
															VALUES ( :nomPatho, :indication)");  // insert la patho et son indication
				$req_idPathoInsert->execute(array('nomPatho'=> $nomPathologie, 
												'indication'=> $indicationPathologie)); 
				$req_idPathoInsert->closeCursor();
				
			// Association de niveau d'urgence de reference a la nouvelle pathologie
				$req_idPathoRecup = $auth_user->runQuery(" SELECT MAX(idPatho)
															FROM Pathologies");  // recupere l'idPatho
				$req_idPathoRecup->execute(array());  
				$idPatho= $req_idPathoRecup-> fetchColumn(); // affecte l'id de ce dernier insert à la variable $PathoExist
				$req_idPathoRecup->closeCursor();
				
			}
		//-- recherche niveauUrgence de reference
			$req_IntervPathoExist = $auth_user->runQuery(" SELECT niveauUrgenceMax, niveauUrgenceMin
												   FROM InterventionsPatho
												   WHERE PathologiesidPatho = :idPatho
												   And InterventionsidIntervention = :idIntervention" ); 
			$req_IntervPathoExist->execute(array('idPatho'=> $idPatho, 
										   'idIntervention'=> $idIntervention));
			$a_niveauUrgence= $req_IntervPathoExist-> fetch(PDO::FETCH_ASSOC);			
			$req_IntervPathoExist->closeCursor();
			
		// Si l'association interv--patho existe pas dans la bdd alors affecte des valeurs par defaut
			if ( $a_niveauUrgence["niveauUrgenceMax"] == "") // si patho existe pas dans bdd
			{
				
				$req_refUrgence = $auth_user->runQuery("INSERT INTO InterventionsPatho (InterventionsidIntervention, PathologiesidPatho)
														VALUES ( :idInter, :idPatho)"); // Renseigne les valeurs de priorité = par default :0
				$req_refUrgence->execute(array('idInter'=> $idIntervention,
											   'idPatho'=> $idPatho));
				$req_refUrgence->closeCursor();
				$a_niveauUrgence=array( "niveauUrgenceMax" => 0,"niveauUrgenceMin" =>0); // affectation niveauUrgence de reference pour test administrateur
			}
// **************************************          Recherche l'HEURE de creneau dispo la plus proche         *********************************
		
			// Recherche le prochain creneau disponible pour l'intervention demandé ( dernier creneaux enregistré +15 min ou premier creneau annulé)
			$a_infoDateHeure=prochainCreneauxDispo($auth_user,$idIntervention);
			if ($a_infoDateHeure["MIN(heureR)"] == NULL ) // gestion erreur : si PAS DE RDV prevu pour ce service dans le planning, retour requete = null 
			{
				$a_infoDateHeure["MIN(heureR)"] = ProchaineHeureArrondie(); //=> on affecte date & heure actuelles		
				$a_infoDateHeure["MIN(dateR)"] = date('Y-m-d');	
				if ($a_infoDateHeure["MIN(heureR)"] >= $a_horaireFermeture["horaire_fermeture"]  ) // gestion erreur : si service = ferme avant minuit 
				{
					$a_infoDateHeure["MIN(heureR)"] = $a_horaireFermeture["horaire_ouverture"]; //=> on affecte date & heure actuelles
					$a_infoDateHeure["MIN(dateR)"] = date('Y-m-d', strtotime('+1 day'));	
				}
				elseif ($a_infoDateHeure["MIN(heureR)"] <= $a_horaireFermeture["horaire_ouverture"]  )  // gestion erreur : si service = ferme après minuit  
				{
					$a_infoDateHeure["MIN(heureR)"] = $a_horaireFermeture["horaire_ouverture"]; //=> on affecte date & heure actuelles
					$a_infoDateHeure["MIN(dateR)"] = date('Y-m-d');
				}				
			}
			elseif (($a_infoDateHeure["MIN(heureR)"] != NULL ) and ($a_infoDateHeure["MIN(statutR)"] = 'p'))//or ($a_infoDateHeure["MIN(heureR)"] !=  $a_horaireFermeture["heure_ouverture"] )) // a verif si creneaux = prevu; alors ajoute  15 min pour obtenir l'heure de creneau a affecter au RDV 
			{
				$a_infoDateHeure["MIN(heureR)"]=heurePlus15($a_infoDateHeure["MIN(heureR)"],'+15 minutes');
			}
			Dumper($a_infoDateHeure);
			

// **************************************          Recherche Horaire APPROPRIEE SI niveau urgence != 0         *********************************
		// Si la demande respect un certain delais (specifique a chaque niveau d'urgence) alors on ne perturbe pas le planning et on insert le rdv à la suite du planning ou a la place d'un rdv annule
		// Sinon on place le rdv à la suite des rendez vous de même niveau et on décale les suivants au risque de dépasser les horraires d'ouverture du service
			If ($niveauUrgence !=0) //  determine le delais a respecter
			{
				$now=ProchaineHeureArrondie();  // si rdv = urgent -> determine le delais relatif au niveau d'urgence
				switch ($niveauUrgence) // fixe un delais selon niveau urgence
				{
					case 3:
						$finDelais=heurePlus15($now,'+180 minutes'); 
						break;//alors on insert à la suite 
					case 2:
						$finDelais=heurePlus15($now,'+360 minutes');
						break;
					case 1:
						$datedelais = date('Y-m-d', strtotime('+1 day'));
						$finDelais=$now;
						break;
				}
				
				if ($a_infoDateHeure["MIN(heureR)"] > $finDelais) // si premier creneau dispo est hors delais on recherche un autre creneaux dont rdv < urgent et on decale les rendez-vous suivant
				{
				//-- Recherche le dernier creneau dont niveau d'urgence >= au niveau d'urgence
					$a_infoDateHeureUrgence=prochainCreneauxUrgent($auth_user,$niveauUrgence,$idIntervention );  
				//-- Decale rdv suivant  SI niveau urgence != 0
				//recupere tous les creneaux suivant
					
					$req_CreneauSuivant = $auth_user->runQuery(" Select id_rdv, heure_rdv, statut FROM CreneauxInterventions 
															WHERE InterventionsidIntervention = :idIntervention
															AND date_rdv = :date
															AND heure_rdv > :heure" ); 
					$req_CreneauSuivant->execute(array('idIntervention'=> $idIntervention,
														'date'=> $a_infoDateHeureUrgence["MIN(dateR)"] ,
														'heure'=> $a_infoDateHeureUrgence["MIN(heureR)"]));
					
					$a_creneauSuiv= reqToArrayPlusligne($req_CreneauSuivant);
					$req_CreneauSuivant->closeCursor(); 
				// upDate toutes les heures suivantes 
					$req_upDateHoraire = $auth_user->runQuery(" UPDATE CreneauxInterventions
																SET heure_rdv = :newHeure,
																niveauUrgence = (niveauUrgence + '1') 
																WHERE id_rdv = :id_rdv" );
				
				
					foreach($a_creneauSuiv["id_rdv"] as $k=>$v) /////  ???  faire le while sur le fetch ??????????????????????????????????????
					{
						if ($a_creneauSuiv["statut"][$k]  == 'a')
						{
							break;
						}
						else
						{
							$newHeure= heurePlus15($a_creneauSuiv["heure_rdv"][$k],'+15 minutes');
							$req_upDateHoraire->execute(array("id_rdv" => $a_creneauSuiv["id_rdv"][$k],
															  "newHeure" =>$newHeure
															  ));
							$req_upDateHoraire->closeCursor();
						}
					}
	
				//-- Notification de Surbooking **********************************************************************************			
					// recup horraire de fin de service reel pour le jour ou l'intervention demandée est insérée
					$req_heureFinJour = $auth_user->runQuery(" SELECT MAX(heure_rdv)
																FROM CreneauxInterventions
																WHERE InterventionsidIntervention = :idIntervention
																AND  date_rdv= :date   "); 
					$req_heureFinJour->execute(array("idIntervention" => $idIntervention,
													 "date" => $a_infoDateHeureUrgence["MIN(heureR)"]));
					$a_horaireFermeture["horaire_reel"] = $req_heureFinJour->fetchColumn();
					$req_heureFinJour->closeCursor();
					
					// Enregistre la notif

							
					if ($a_horaireFermeture["horaire_reel"]> $a_horaireFermeture["horaire_fermeture"]) 
					{
						$req_notifService = $auth_user->runQuery(" INSERT INTO Notifications (CreneauxInterventionsidRdv, ServicesnomService, indication)
																			VALUES ( :idCreneau, :service, 'Surbooking')"); // Renseigne les valeurs de priorité = par default :0
						$req_notifService->execute(array('service'=> $horraireFermeture["Services.nomService"]));
						$req_notifService->closeCursor();
					}									
				} // fin d'instruction si dispo = hors delais
					
			} // fin instruction si urgence !=0
				// INSERTION rdv
				
			Dumper($a_infoDateHeure);	
			$ajoutRDV = $auth_user->runQuery("INSERT INTO CreneauxInterventions (date_rdv, heure_rdv, InterventionsidIntervention,
										niveauUrgence, PathologiesidPatho, commentaires, PatientsnumSS, EmployesCompteUtilisateursIdEmploye) 
										VALUES (:date_rdv, :heure_rdv, :InterventionsidIntervention, :niveauUrgence, :pathologie,
										:commentaires,:PatientsnumSS, :EmployesCompteUtilisateursIdEmploye)");
			$ajoutRDV->execute(array('date_rdv'=> $a_infoDateHeure["MIN(dateR)"],
								'heure_rdv'=> $a_infoDateHeure["MIN(heureR)"],
								'InterventionsidIntervention'=> $idIntervention,
								'niveauUrgence'=> $niveauUrgence,
								'pathologie'=> $idPatho, 
								'commentaires'=> $commentaires,
								'PatientsnumSS'=> $_SESSION["patient"],
								'EmployesCompteUtilisateursIdEmploye'=> $user_id));
			$ajoutRDV->closeCursor();
			
		}	// si tous les champs du formulaire sont renseignés et valide
			
		
// ************************************** NOTIFICATIONS UTLISATEURS *********************************
	// -- TEST CAS D'UTILISATION "VERIFICATION" admin
		// recup id creneau
		$req_idCreneau= $auth_user->runQuery(" SELECT MAX( id_rdv) FROM CreneauxInterventions"); // Renseigne les valeurs de priorité = par default :0
		$req_idCreneau->execute();
		$idCreneau = $req_idCreneau->fetchColumn();
		$req_idCreneau->closeCursor();
		
		// Enregistre la notif
		if ($niveauUrgence > $a_niveauUrgence["niveauUrgenceMax"])
		{
			$req_notif = $auth_user->runQuery(" INSERT INTO Notifications (CreneauxInterventionsidRdv, ServicesnomService, indication)
																VALUES ( :idCreneau, 'Informatique', 'Max')"); // Renseigne les valeurs de priorité = par default :0
			$req_notif->execute(array('idCreneau'=> $idCreneau));
			$req_notif->closeCursor();
		}
		elseif ($niveauUrgence < $a_niveauUrgence["niveauUrgenceMin"])
		{
			$req_notif = $auth_user->runQuery(" INSERT INTO Notifications (CreneauxInterventionsidRdv, ServicesnomService, indication)
																VALUES ( :idCreneau, 'Informatique', 'Min')"); // Renseigne les valeurs de priorité = par default :0
			$req_notif->execute(array(	'idCreneau'=> $idCreneau));
			$req_notif->closeCursor();
		}
	
	
	} // fin des instructions realisées si niveauUrgence !=0

	
} // tout ce qui est fait par le bouton
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
