<?php
	include ('../Config/Menupage.php');
	//REQUETE
	$lien= 'FichePatientModifier.php';

	if(isset($_POST['btn-modifier']))
	{	 
		
		$text_departement = trim($_POST['text_departement'], ' ' );	
		$text_pays = ucfirst(trim($_POST['text_pays'], ' '))	;
		
		$text_ville = ucfirst(trim($_POST['text_ville'], ' '))	;
		$text_codepostal = trim($_POST['text_codepostal'], ' ');	
		
		$text_numero = trim($_POST['text_numero'], ' ' );	
		$text_rue = ucfirst(trim($_POST['text_rue'], ' '))	;

		$text_numSS = trim($_POST['text_numSS'], ' ' );	
		$text_nom =  ucfirst(trim($_POST['text_nom'], ' '))	;
		$text_prenom = ucfirst(trim($_POST['text_prenom'], ' '))	;
		$text_dateNaissance = strip_tags($_POST['text_dateNaissance']);	
			
		$text_telephone = trim($_POST['text_telephone'], ' ' );
		$text_mail = strip_tags($_POST['text_mail']);	
		$text_sexe = strip_tags($_POST['text_sexe']);	
		$text_taille = preg_replace("/[^0-9]/", "",trim($_POST['text_taille'], ' '));	
		$text_poids = preg_replace("/[^0-9]/", "",trim($_POST['text_poids'], ' '));	
		$text_commentaires = strip_tags($_POST['text_commentaires']);	

		
		
		
		
		// ici je pense faire un include de $dep a $adresse tout foutre dans un seul et meme document car c est chiant a regarder 
			 // Gestion des erreurs :
/*			 
		if ($text_utilisateur==""){$error[] = "Il faut un selectionner un utilisateur !"; }
		else if($text_utilisateur=="admin00")	{$error[] = "Impossible de supprimer l'Admin"; }
		else 
		{ 
			
			try 
			{
				$ajoutchef = $auth_user->conn->prepare("DELETE FROM CompteUtilisateurs WHERE 
														idEmploye=:text_utilisateur");
				$ajoutchef->bindparam(":text_utilisateur", $text_utilisateur);
				$ajoutchef->execute();
				$auth_user->redirect('CompteUtilSupprimer.php?Valide');
			}
			catch(PDOException $e)
			{			
				echo $e->getMessage();
			}	
			
		}
		*/
	}
?>	

<!DOCTYPE html PUBLIC >
<html>
	<head>
		<link rel="stylesheet" href=Style.css">
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<title>Modifier fiche patient</title>
	</head>
	<body>
		<?php // affichage
			If (!array_key_exists("patient",$_SESSION )) 
			{
				include ('../Formulaires/RecherchePatient.php');; // recherche patient existe pas (redirection fiche patient)
			}
			else
			{
				$req_patient = $auth_user->runQuery("SELECT * 
										FROM Patients Join Adresses JOIN Villes
										WHERE Patients.AdressesidAdresse = Adresses.idAdresse
										AND Adresses.idAdresse = Villes.idVilles
										AND Patients.numSS = :numSS");
	
				$req_patient->execute(array("numSS"=>$_SESSION['patient']));
				$patientInfo=$req_patient -> fetch(PDO::FETCH_ASSOC);
				include ('../Formulaires/FichePatientModifier.php');; // recherche patient existe pas (redirection fiche patient)
				
			}
		?>
	</body>

</html>