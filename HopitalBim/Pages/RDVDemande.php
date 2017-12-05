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
include ('../Fonctions/Notifications.php'); // fonctions specifiques à demande RDV
include ('../Fonctions/GestionUrgences.php'); // fonctions specifiques à demande RDV

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
			if (!array_key_exists('heureR', $a_infoDateHeure )) // gestion erreur : si PAS DE RDV prevu pour ce service dans le planning, retour requete = null 
			{
				echo "prochainCreneauDispo = ok"."<br>";
				$a_infoDateHeure["heureR"] = ProchaineHeureArrondie(); //=> on affecte date & heure actuelles		
				$a_infoDateHeure["dateR"] = date('Y-m-d');	
				if ($a_infoDateHeure["heureR"] >= $a_horaireFermeture["horaire_fermeture"]  ) // gestion erreur : si service = ferme avant minuit 
				{
					echo "prochainCreneauDispo = supp ou = a l'heure de fermeture"."<br>";
					$a_infoDateHeure["heureR"] = $a_horaireFermeture["horaire_ouverture"]; //=> on affecte date & heure actuelles
					$a_infoDateHeure["dateR"] = date('Y-m-d', strtotime('+1 day'));	
				}
				elseif (( $a_infoDateHeure["heureR"] <= $a_horaireFermeture["horaire_ouverture"]  )  // gestion erreur : si service = ferme après minuit  
						and ( $a_infoDateHeure["heureR"] > $a_horaireFermeture["horaire_fermeture"]  )) 
				{
					echo "prochainCreneauDispo = inf ou = à l'heure d'ouverture et > heure de fermeture"."<br>";
					$a_infoDateHeure["heureR"] = $a_horaireFermeture["horaire_ouverture"]; //=> on affecte date & heure actuelles
					$a_infoDateHeure["dateR"] = date('Y-m-d');
				}				
			}
			elseif (((array_key_exists('heureR', $a_infoDateHeure )) and ($a_infoDateHeure["statutR"] = 'p')))//or ($a_infoDateHeure["heureR"] !=  $a_horaireFermeture["heure_ouverture"] )) // a verif si creneaux = prevu; alors ajoute  15 min pour obtenir l'heure de creneau a affecter au RDV 
			{
				echo " il y a un creneau prevu ";
				$a_infoDateHeure["heureR"]=heurePlus15($a_infoDateHeure["heureR"],'+15 minutes'); 
			}
			echo " 1. prochainCrenauDispo = ".$a_infoDateHeure['dateR'],$a_infoDateHeure['heureR']."<br>";

// **************************************          Recherche Horaire APPROPRIEE SI niveau urgence != 0         *********************************

			$a_infoDateHeure=gestionUrgence($auth_user,$idIntervention,$niveauUrgence, $a_infoDateHeure,$a_horaireFermeture);
			
// *************************************************           INSERTION rdv         **********************************************************************************

				//
				
			$ajoutRDV = $auth_user->runQuery("INSERT INTO CreneauxInterventions (date_rdv, heure_rdv, InterventionsidIntervention,
										niveauUrgence, PathologiesidPatho, commentaires, PatientsnumSS, EmployesCompteUtilisateursIdEmploye) 
										VALUES (:date_rdv, :heure_rdv, :InterventionsidIntervention, :niveauUrgence, :pathologie,
										:commentaires,:PatientsnumSS, :EmployesCompteUtilisateursIdEmploye)");
			$ajoutRDV->execute(array('date_rdv'=> $a_infoDateHeure["dateR"],
								'heure_rdv'=> $a_infoDateHeure["heureR"],
								'InterventionsidIntervention'=> $idIntervention,
								'niveauUrgence'=> $niveauUrgence,
								'pathologie'=> $idPatho, 
								'commentaires'=> $commentaires,
								'PatientsnumSS'=> $_SESSION["patient"],
								'EmployesCompteUtilisateursIdEmploye'=> $user_id));
			$ajoutRDV->closeCursor();
			
		}	// si tous les champs du formulaire sont renseignés et valide
		
		 Eval_notif_incompUrgence($auth_user,$niveauUrgence,$a_niveauUrgence);
	
	
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
