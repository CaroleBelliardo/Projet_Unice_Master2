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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="style.css" type="text/css"  />

<style>
.navbar {
    overflow: hidden;
    background-color: #333;
    font-family: Arial;
}

.navbar a {
    float: left;
    font-size: 16px;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

.dropdown {
    float: left;
    overflow: hidden;
}

.dropdown .dropbtn {
    font-size: 16px;    
    border: none;
    outline: none;
    color: white;
    padding: 14px 16px;
    background-color: inherit;
}

.navbar a:hover, .dropdown:hover .dropbtn {
    background-color: red;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content a {
    float: none;
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    text-align: left;
}

.dropdown-content a:hover {
    background-color: #ddd;
}

.dropdown:hover .dropdown-content {
    display: block;
}

</style>
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
		<div class="dropdown">
			<button class="dropbtn">Services 
				<i class="fa fa-caret-down"></i>
			</button>
			<div class="dropdown-content">
			  <a href="<?php echo $LienSite ?>Pages/ServiceCreer.php">Création</a>
			  <a href="<?php echo $LienSite ?>Pages/ServiceModifier.php">Modification</a>
			  <a href="<?php echo $LienSite ?>Pages/ServiceSupprimer.php">Suppression</a>
			</div>
		</div>	<div class="dropdown">
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

	<p class="h4">  <?php  echo($a_utilisateur[0]." ".$a_utilisateur[1]." <br> Service ".$a_utilisateur[2]); ?></p> <!--affichage nom prenom service user-->



	<p class="h4">Page Principale</p> 
    <p class="" style="margin-top:5px;">


    ICI les conneries regardant le gars connecté.
   
    </p>
    
    




</body>



</html>
