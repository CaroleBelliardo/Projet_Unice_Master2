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
