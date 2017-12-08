<?php
	 $LienSite = 'http://'.$_SERVER['HTTP_HOST'].'/projetm2/HopitalBim/';
	
	include ('Fonctions/Fonctions_Affichage.php');
	include ('Fonctions/Fonctions_ReqTraitement.php');
	require_once("session.php"); // requis pour se connecter à la base de donnée 
	require_once("classe.Systeme.php"); // va permettre d'effectuer les requêtes sql en orienté objet.
	
	// reinitialise les variables d'utilisation
	unset($_SESSION["patient"]);
	unset($_SESSION["serviceModifier"]);
	unset($_SESSION['utilisateurModifier']);
	unset($_SESSION["dateModifier"]);
	unset($_SESSION["servicePlanning"]);
	unset($_SESSION["rdvModifier"]);

	//variables Globales
	$auth_user = new Systeme(); // Connection bdd	
	$user_id = $_SESSION['idEmploye']; // IDENTIFIANT compte utilisateur !!!!!
	//info identité utilisateur
	$Req_utilisateur = $auth_user->runQuery("SELECT  CompteUtilisateursidEmploye, Employes.ServicesnomService as service,nom, prenom, EmployesCompteUtilisateursidEmploye as chef
											FROM Employes LEFT JOIN ChefServices ON Employes.CompteUtilisateursidEmploye  = ChefServices.EmployesCompteUtilisateursidEmploye
											WHERE Employes.CompteUtilisateursidEmploye = :user_name
											"); // NOM utilisateur = >> à mettre dans menuPage !!!!!!!!!!!! TOUTES PAGES 
	$Req_utilisateur->execute(array("user_name"=>$user_id)); 
	$a_utilisateur= reqToArrayPlusAttASSO($Req_utilisateur);  // Nom prénom et service utilisateur 	
	$Req_utilisateur->closeCursor();
	if ($a_utilisateur["chef"] != NULL)
	{
		$_SESSION["chefService"]= TRUE;
		}
	else
	{
		$_SESSION["chefService"]= FALSE;
	}
$_SESSION['service']=$a_utilisateur['service'];
	
	global $auth_user, $a_utilisateur;

?>

<!DOCTYPE html>
<html>
	<head>
		<title> Bienvenue </title> <!-- Titre de l'onglet -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" href="Config/Style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
	</head>

	<body>
		<div class="Page">
		<div id="container"> <!-- Motif à mettre sur chaque page-->
		   <img name="logo" src="Images/logo.png" alt="Logo hopital">
	
		   <div id="containerTitre">
			  <div id="entete">  
			  Planning Hopital Bim
			  </div>
	
			  <div class="accroche"> 
			  Pour gérer votre planning en un clin d'oeil 
			  </div>
		   </div> <!-- containerTitre -->
	    </div> <!-- container -->

		<div class="navbar"> 
		<a href="<?php echo $LienSite ?>Pages/Planning.php">Planning</a>
		<a href="<?php echo $LienSite ?>Pages/RDVDemande.php">Demande de rendez-vous </a>
		<div class="dropdown">
			<button class="dropbtn">Patient </button>
			<div class="dropdown-content">
			  <a href="<?php echo $LienSite ?>Pages/FichePatientCreer.php">Création</a>
			  <a href="<?php echo $LienSite ?>Pages/FichePatientModifier.php">Modification</a>
			</div>
		</div>

	<?php
			if (( $_SESSION["chefService"] == TRUE ) and ($_SESSION["idEmploye"] != "admin00"))
			{
	?>			
				<a href="<?php echo $LienSite ?>Pages/Facturation.php">Facturation</a>
				<a href="<?php echo $LienSite ?>Pages/VerificationNotification.php">Notifications</a>
	<?php
			}
			if ($_SESSION["idEmploye"]== "admin00")
			{
	?>
				<div class="dropdown">
					<button class="dropbtn">Services </button>
					<div class="dropdown-content">
					  <a href="<?php echo $LienSite ?>Pages/ServiceCreer.php">Création</a>
					  <a href="<?php echo $LienSite ?>Pages/ServiceModifier.php">Modification</a>
					  <a href="<?php echo $LienSite ?>Pages/ServiceSupprimer.php">Archiver</a>
					   <a href="<?php echo $LienSite ?>Pages/ActeCreer.php">Ajout un acte</a>
					  <a href="<?php echo $LienSite ?>Pages/ActeSupprimer.php">Archiver un acte</a>
					</div>
				</div>
			
				<div class="dropdown">
					<button class="dropbtn">Compte Utilisateur </button>
					<div class="dropdown-content">
					  <a href="<?php echo $LienSite ?>Pages/CompteUtilCreer.php">Création</a>
					  <a href="<?php echo $LienSite ?>Pages/CompteUtilModifier.php">Modification</a>
					  <a href="<?php echo $LienSite ?>Pages/CompteUtilSupprimer.php">Suppression</a>
					</div>
				</div>
			
				<div class="dropdown">
					<button class="dropbtn">Vérification </button>
					<div class="dropdown-content">
					  <a href="<?php echo $LienSite ?>Pages/VerificationSynthese.php">Synthèse des demandes</a>
					  <a href="<?php echo $LienSite ?>Pages/VerificationNotification.php">Notifications</a>
					</div>
				</div>

	
<?php
			}
	?>
		<a name="Déco" href="<?php echo $LienSite ?>logout.php?logout=true"><img name="logout" src="Images/logout.png" alt="Logout logo" > Déconnexion</a>
	
	
</div>
	<div id=PagePrincipale>
		<p class="Bienvenue">Bienvenue sur votre espace personnel ! </p>

		<?php 
			if (($_SESSION["idEmploye"]== "admin00") and (!array_key_exists("contact",$_SESSION)) and (!array_key_exists("conditionsUtilisation",$_SESSION)))
			{

		?>
			<p class="infoUser"> Bonjour <em><?php  echo($a_utilisateur['prenom']." ".$a_utilisateur['nom']); ?></em>, <br><br>
				 Nous sommes aujourd'hui le <?php echo($today=date("j / m / Y")) ?>, <br><br>
				 Vous êtes connecté en tant qu’<em>administrateur</em> du système. <br><br>

				Vous serez informé des incompatibilités détectées par le système entre les interventions et les niveaux d’urgence demandées par les utilisateurs : En cliquant sur « <em>Notification</em> » dans l’onglet « <em>Vérification</em> ». A partir de ce même onglet, vous pouvez aussi avoir accès au tableau de synthèse des demandes d’interventions enregistrées par le système. <br><br>

				L’onglet « <em>Services</em> » vous permet de créer, modifier ou supprimer un service de l’hôpital. A partir de cet onglet, vous pouvez aussi ajouter ou supprimer un acte médical réalisé par l’un de ces services. <br><br>

				L’onglet « <em>Compte utilisateur</em> » vous permet de créer, modifier ou supprimer un compte d’un utilisateur. A partir de cet onglet, vous pouvez aussi ajouter ou supprimer un acte médical réalisé par l’un de ces services. <br><br>

				Enfin, vous pouvez tester n’importe quelle fonctionnalité disponibles pour tous les utilisateurs du système (gestion du planning, demande de rendez-vous, gestion des fiches patients et facturation). <br>

			</p> <br> <br> <br>
				
		<?php 
			}
		if (($_SESSION["chefService"] == TRUE ) and ($_SESSION["idEmploye"] != "admin00") and (!array_key_exists("contact",$_SESSION)) and (!array_key_exists("conditionsUtilisation",$_SESSION)))
			{
		?>

			<p class="infoUser"> Bonjour <em><?php  echo($a_utilisateur['prenom']." ".$a_utilisateur['nom']); ?></em>, <br><br>
				Nous sommes aujourd'hui le <?php echo($today=date("j / m / Y")) ?>, <br><br>
				Vous êtes connecté en tant que chef du service <em><?php echo($a_utilisateur['service']); ?></em>. <br><br>

				Vous pouvez consultez le planning de votre service en cliquant sur l’onglet Planning. <br><br>

				Vous pouvez demander une intervention pour un patient enregistré dans la base de données du système en cliquant sur l’onglet « <em>Demande de rendez-vous</em> ». <br><br>

				Si vous souhaitez demander un rendez-vous sur un nouveau patient, vous devez lui créer une fiche en cliquant sur « <em>Création</em> » dans l’onglet « <em>Patient</em> ». Vous pouvez également sur cet onglet modifier les informations d’un patient. <br><br>

				En tant que chef de service, vous pouvez imprimer les factures des actes médicaux effectués au sein de votre service pour chacun des patients en cliquant sur « <em>Facturation</em> ». <br><br>

				En cliquant sur l’onglet « <em>Notification</em> », vous serez informé des interventions qui nécessitent un surbooking du planning. Pour gérer ce surbooking, vous pouvez modifier le planning en cliquant sur l’onglet « <em>Planning </em>». <br><br>

				Si vous rencontrez un problème, veuillez contacter l’administrateur du site via l’adresse : <em>admin00@hopitalbim.fr</em> <br> <br>

			</p> <br> <br> <br>

		<?php
			}
		if (($_SESSION["chefService"] != TRUE ) and (!array_key_exists("contact",$_SESSION)) and (!array_key_exists("conditionsUtilisation",$_SESSION)))
			{
		?>

			<p class="infoUser"> Bonjour <em><?php  echo($a_utilisateur['prenom']." ".$a_utilisateur['nom']); ?></em>, <br><br>
				Nous sommes aujourd'hui le <?php echo($today=date("j / m / Y")) ?>, <br><br>
				Vous êtes connecté en tant que médecin de l’hôpital, au sein du service <em><?php echo($a_utilisateur['service']); ?></em>. <br><br>

				Vous pouvez consultez le planning de votre service en cliquant sur l’onglet Planning. <br><br>

				Vous pouvez demander une intervention pour un patient enregistré dans la base de données du système en cliquant sur l’onglet « <em>Demande de rendez-vous</em> ». <br><br>

				Si vous souhaitez demander un rendez-vous sur un nouveau patient, vous devez lui créer une fiche en cliquant sur « <em>Création </em>» dans l’onglet « <em>Patient </em>». Vous pouvez également sur cet onglet modifier les informations d’un patient. <br><br>

				Si vous rencontrez un problème, veuillez contacter l’administrateur du site via l’adresse : <em>admin00@hopitalbim.fr </em> <br> <br>

				</p> <br> <br> <br>

		<?php
			}
		?>

		<!-- 
    	<div class="profile">
  			<div class="photo">
  			<img src="Images/User.png" alt="Image utilisateur"/>
  			</div>

			<div class="content">
    			<div class="text">
				<h3> <?php  echo($a_utilisateur['prenom']." ".$a_utilisateur['nom']); ?> </h3>
      			<h6> Service : <?php echo($a_utilisateur['service']); ?> </h6>  			</div>
  			</div>
		</div>

		<p class="infoUser"> Bonjour <?php  echo($a_utilisateur['prenom']); ?>, <br><br> 
			Nous sommes aujourd'hui le <?php echo($today=date("j / m / Y")) ?>. 
		</p>

    	<img name="stetho" src="Images/stetho.png" alt="Image stethoscope bleu" height="30%" width="30%">
     </div> -->

    </div> <!-- Page --> 

<?php include ('Config/Footer2.php'); //menu de navigation ?>
   

	</body>
</html>
