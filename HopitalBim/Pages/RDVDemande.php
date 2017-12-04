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
		// Si la demande respect un certain delais (specifique a chaque niveau d'urgence) alors on ne perturbe pas le planning et on insert le rdv à la suite du planning ou a la place d'un rdv annule
		// Sinon on place le rdv à la suite des rendez vous de même niveau et on décale les suivants au risque de dépasser les horraires d'ouverture du service
			If ($niveauUrgence !=0) //  determine le delais a respecter
			{
				$now=ProchaineHeureArrondie();  // si rdv = urgent -> determine le delais relatif au niveau d'urgence
				echo "2. Rendez vous urgent niveau ",$niveauUrgence,"on est :",$now."<br>";
				
				
				switch ($niveauUrgence) // fixe un delais selon niveau urgence
				{
					case 3:
						$heureDelais=heurePlus15($now,'+180 minutes');
						if ($heureDelais < $now)
						{
							$dateDelais = date('Y-m-d', strtotime('+1 day'));
						}else {$dateDelais= date('Y-m-d');}
						echo $dateDelais.$heureDelais." est le delais imposé par switch delais : cas 3 <br>";
						break;//alors on insert à la suite 
					case 2:
						$heureDelais=heurePlus15($now,'+360 minutes');
						if ($heureDelais < $now)
						{
							$dateDelais = date('Y-m-d', strtotime('+1 day'));
						}else {$dateDelais= date('Y-m-d');}
						echo $dateDelais.$heureDelais." est le delais imposé par switch delais : cas 2 <br>";
						break;
					case 1:
						$dateDelais = date('Y-m-d', strtotime('+1 day'));
						$heureDelais=$now;
						echo $dateDelais.$heureDelais." est le delais imposé par switch delais : cas 1 <br>";
						break;
				}
				
				if (($a_infoDateHeure["dateR"] >  $dateDelais   ) or (($a_infoDateHeure["dateR"] =  $dateDelais ) and   
				($a_infoDateHeure["heureR"] > $heureDelais))) // si premier creneau dispo est hors delais on recherche un autre creneaux dont rdv < urgent et on decale les rendez-vous suivant
				{
					echo " dateR >> dateDelais ou dateR == dateDelais mais dateR == dateDelais et heureR > heureDelais<br>";
				//-- Recherche le dernier creneau dont niveau d'urgence >= au niveau d'urgence
					$a_infoDateHeureUrgence=prochainCreneauxUrgent($auth_user,$niveauUrgence,$idIntervention ); 
					echo "dernier creneaux ou niveau urgence =< niveau demandé(",$a_infoDateHeureUrgence['niveauUrgenceR'],$a_infoDateHeureUrgence['dateR'],$a_infoDateHeureUrgence['heureR']."<br>";
	
	
					if ($a_infoDateHeureUrgence['statutR'] == 'p')
					{
						$a_infoDateHeure["heureR"]= heurePlus15($a_infoDateHeureUrgence["heureR"],'+15 minutes');					
						echo "statut creneauUrg = p : Donc creneauUrg + 15 minutes pour creneaux suivant",$a_infoDateHeureUrgence['dateR']." ".$a_infoDateHeureUrgence['heureR']."<br>";
					}
					else
					{
						$a_infoDateHeure["heureR"]= $a_infoDateHeureUrgence["heureR"];					
						echo "statut creneauUrg = a donc creneaux suivant = crendeauUrg : ".$a_infoDateHeureUrgence['dateR']." ".$a_infoDateHeureUrgence['heureR']."<br>";
					} 


				//-- Decale rdv suivant  SI niveau urgence != 0
				//recupere tous les creneaux suivant
					echo 'est ce qu on decale les rdv suivant ?';
					$req_CreneauSuivant = $auth_user->runQuery(" Select id_rdv, heure_rdv, statut FROM CreneauxInterventions 
															WHERE InterventionsidIntervention = :idIntervention
															AND date_rdv = :date
															AND heure_rdv >= :heure" ); 
					$req_CreneauSuivant->execute(array('idIntervention'=> $idIntervention,
														'date'=> $a_infoDateHeureUrgence["dateR"] ,
														'heure'=> $a_infoDateHeure["heureR"]));
					
					$a_creneauSuiv= reqToArrayPlusligne($req_CreneauSuivant);
					$req_CreneauSuivant->closeCursor(); 
				
				
				// upDate toutes les heures suivantes 
					$req_upDateHoraire = $auth_user->runQuery(" UPDATE CreneauxInterventions
																SET heure_rdv = :newHeure,
																niveauUrgence = (niveauUrgence + '1') 
																WHERE id_rdv = :id_rdv" );
				
					if (array_key_exists("id_rdv",$a_creneauSuiv))
					echo "il y a des rdv pendant ou après le creneaux selectionné <br>";
					{
						echo " pour chaque id_rdv : ";
						foreach($a_creneauSuiv["id_rdv"] as $k=>$v) /////  ???  faire le while sur le fetch ??????????????????????????????????????
						{
							echo "id : $k <br>";
							
							if ($a_creneauSuiv["statut"][$k]  == 'a')
							{
								echo ' rencontre un rdv annulé -> arrete de décalier',$a_creneauSuiv["statut"]["id_rdv"];
								break;
							}
							else
							{
								echo ' rencontre un rdv  == décale '.$a_creneauSuiv["statut"]["id_rdv"];
								$newHeure= heurePlus15($a_creneauSuiv["heure_rdv"][$k],'+15 minutes');
								$req_upDateHoraire->execute(array("id_rdv" => $a_creneauSuiv["id_rdv"][$k],
																  "newHeure" =>$newHeure
																  ));
								$req_upDateHoraire->closeCursor();
							}
						}
						echo "OKAY : on a decalé les rdv suivants et l'heure du rdv a inserer est : ", $a_infoDateHeure['heureR'];
					}// fin d'instruction si dispo = hors delais
					// test si il y a surbooking	
					Eval_notif_Surbooking ($auth_user,$idIntervention,$a_infoDateHeureUrgence,$a_horaireFermeture);
				} 
					
			} // fin instruction si urgence !=0
				// INSERTION rdv
				
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
