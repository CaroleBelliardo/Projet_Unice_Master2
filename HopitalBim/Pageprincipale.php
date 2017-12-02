<?php
	 $LienSite = 'http://'.$_SERVER['HTTP_HOST'].'/projetm2/HopitalBim/';
	
	include ('Fonctions/Affichage.php');
	include ('Fonctions/ReqTraitement.php');
	require_once("session.php"); // requis pour se connecter la base de donnée 
	require_once("classe.Systeme.php"); // va permettre d effectuer les requettes sql en orienté objet.
	
	unset($_SESSION["patient"]);
	unset($_SESSION["nomService"]);
	
	//variables Globales
	$auth_user = new Systeme(); // Connection bdd	
	$user_id = $_SESSION['idEmploye']; // IDENTIFIANT compte utilisateur !!!!!
	$Req_utilisateur = $auth_user->runQuery("SELECT DISTINCT nom,prenom,ServicesnomService
											FROM CompteUtilisateurs JOIN Employes
											WHERE CompteUtilisateurs.idEmploye=Employes.CompteUtilisateursidEmploye
											AND CompteUtilisateurs.idEmploye= :user_name
											"); // NOM utilisateur = >> à mettre dans menuPage !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! TOUTES PAGES 
	$Req_utilisateur->execute(array("user_name"=>$user_id)); 
	$a_utilisateur= reqToArrayPlusAtt($Req_utilisateur);  // Nom prénom et service utilisateur 
	
	global $auth_user, $a_utilisateur;

?>

<!DOCTYPE html>
<html>
	<head>
	<title> Bienvenue </title> <!-- Titre de l'onglet -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="Config/Style.css" type="text/css">
	</head>

	<body>
	<div id="page">

	<div id="entete">  <!-- Motif à mettre sur chaque page--> 
    Planning Hopital Bim
    </div>

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

		<a href="<?php echo $LienSite ?>Pages/Faturation.php">Facturation</a>
		
		<div class="dropdown">
			<button class="dropbtn">Patient</button>
			<div class="dropdown-content">
			  <a href="<?php echo $LienSite ?>Pages/FichePatientCreer.php">Création</a>
			  <a href="<?php echo $LienSite ?>Pages/FichePatientModifier.php">Modification</a>
			</div>
		</div>

		<div class="dropdown">
			<button class="dropbtn">Services</button>
			<div class="dropdown-content">
			  <a href="<?php echo $LienSite ?>Pages/ServiceCreer.php">Création</a>
			  <a href="<?php echo $LienSite ?>Pages/ServiceModifier.php">Modification</a>
			  <a href="<?php echo $LienSite ?>Pages/ServiceSupprimer.php">Suppression</a>
			</div>
		</div>	

		<div class="dropdown">
			<button class="dropbtn">Compte Utilisateur</button>
			<div class="dropdown-content">
			  <a href="<?php echo $LienSite ?>Pages/CompteUtilCreer.php">Création</a>
			  <a href="<?php echo $LienSite ?>Pages/CompteUtilModifier.php">Modification</a>
			  <a href="<?php echo $LienSite ?>Pages/CompteUtilSupprimer.php">Suppresion</a>
			</div>
		</div>

		<div class="dropdown">
			<button class="dropbtn">Vérification</button>
			<div class="dropdown-content">
			  <a href="<?php echo $LienSite ?>Pages/VerificationSynthese.php">Synthèse des demandes</a>
			  <a href="<?php echo $LienSite ?>Pages/VerificationNotification.php">Notifications</a>
			</div>
		</div>


		<a name="Déco" href="<?php echo $LienSite ?>logout.php?logout=true"><img src="Images/logout2.png" alt="Logout logo" height="30%" width="30%"> Déconnexion</a>

	</div>

	<div id=PagePrincipale>
		<p class="Bienvenue">Bienvenue sur votre espace personnel ! </p> 

		<p class="user">  
			<?php  echo($a_utilisateur[0]." ".$a_utilisateur[1]." <br> Service ".$a_utilisateur[2]); ?>
		</p> <!--affichage nom prenom service user-->

    	<p class="infoUser">
    	ICI les conneries regardant le gars connecté.
    	</p>

    	<img src="Images/stetho.png" alt="Image stethoscope bleu" height="30%" width="30%">
     </div>

     <div id="footer"> <!-- Faire les liens vers les documents  -->
    <a href="<?php echo $LienSite ?>Pages/readme.php"> Conditions d'utilisation </a> |
    <a href="<?php echo $LienSite ?>Pages/contact.php"> Contact </a> | © 2017
    </div>

    </div> <!-- Page -->
	</body>
</html>
