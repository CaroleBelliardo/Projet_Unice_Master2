<?php $LienSite = 'http://'.$_SERVER['HTTP_HOST'].'/projetm2/HopitalBim/';

include ('../Fonctions/Affichage.php'); // lien Page principale
include ('../Fonctions/ReqTraitement.php');
require_once("../session.php"); // requis pour se connecter la base de donnée 
require_once("../classe.Systeme.php"); // va permettre d effectuer les requettes sql en orienté objet.


//variables Globales
$auth_user = new Systeme(); // Connection bdd	
$user_id = $_SESSION['idEmploye']; // IDENTIFIANT compte utilisateur !!!!!
$Req_utilisateur = $auth_user->runQuery("SELECT DISTINCT nom,prenom,ServicesnomService
										FROM CompteUtilisateurs JOIN Employes
										WHERE CompteUtilisateurs.idEmploye=Employes.CompteUtilisateursidEmploye
										AND CompteUtilisateurs.idEmploye= :user_name
										"); // NOM utilisateur = >> à mettre dans menuPage !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! TOUTES PAGES 
$Req_utilisateur->execute(array("user_name"=>$user_id)); 
$a_utilisateur= reqToArrayPlusAttASSO($Req_utilisateur);  // Nom prénom et service utilisateur 

global $auth_user, $a_utilisateur;

?>


<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="<?php echo $LienSite.'Config/Style.css'; ?>" >

</head>

<body>

<div class="navbar"> 
	<a href="<?php echo $LienSite ?>Pages/RDVDemande.php">Demande de rendez-vous </a>
	
	<div class="dropdown">
		<button class="dropbtn">Patient 
			<i class="fa fa-caret-down"></i>
		</button>
		<div class="dropdown-content">
		  <a href="<?php echo $LienSite ?>Pages/FichePatientCreer.php">Création</a>
		  <a href="<?php echo $LienSite ?>Pages/FichePatientModifier.php">Modification</a>
		</div>
	</div>
	<a href="<?php echo $LienSite ?>Pages/Planning.php">Planning</a>
	<a href="<?php echo $LienSite ?>Pages/Faturation.php">Facturation</a>
	<div class="dropdown">
		<button class="dropbtn">Services 
			<i class="fa fa-caret-down"></i>
		</button>
		<div class="dropdown-content">
		  <a href="<?php echo $LienSite ?>Pages/ServiceCreer.php">Création</a>
		  <a href="<?php echo $LienSite ?>Pages/ServiceModifier.php">Modification</a>
		  <a href="<?php echo $LienSite ?>Pages/ServiceSupprimer.php">Suppression</a>
		</div>
	</div>
	<div class="dropdown">
		<button class="dropbtn">Compte Utilisateur 
			<i class="fa fa-caret-down"></i>
		</button>
		<div class="dropdown-content">
		  <a href="<?php echo $LienSite ?>Pages/CompteUtilCreer.php">Création</a>
		  <a href="<?php echo $LienSite ?>Pages/CompteUtilModifier.php">Modification</a>
		  <a href="<?php echo $LienSite ?>Pages/CompteUtilSupprimer.php">Suppresion</a>
		</div>
	</div>
	<div class="dropdown">
		<button class="dropbtn">Verification 
			<i class="fa fa-caret-down"></i>
		</button>
		<div class="dropdown-content">
		  <a href="<?php echo $LienSite ?>Pages/VerificationSynthese.php">Synthèse des demandes</a>
		  <a href="<?php echo $LienSite ?>Pages/VerificationNotification.php">Notifications</a>
		</div>
	</div>
	<a href="<?php echo $LienSite ?>logout.php?logout=true">Déconnection</a>
</div>

<p class="h4">  <?php  echo($a_utilisateur['prenom']." ".$a_utilisateur['nom']." <br> Service ".$a_utilisateur['ServicesnomService']); ?></p> <!--affichage nom prenom service user-->

</body>
</html>
