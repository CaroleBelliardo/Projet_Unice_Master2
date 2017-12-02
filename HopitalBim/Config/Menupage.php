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
	<div id="container"> <!-- Motif à mettre sur chaque page-->
        <img name="logo" src="../Images/logo.png" alt="Logo hopital">

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
		<button class="dropbtn">Patient </button>
		<div class="dropdown-content">
		  <a href="<?php echo $LienSite ?>Pages/FichePatientCreer.php">Création</a>
		  <a href="<?php echo $LienSite ?>Pages/FichePatientModifier.php">Modification</a>
		</div>
	</div>

	<div class="dropdown">
		<button class="dropbtn">Services </button>
		<div class="dropdown-content">
		  <a href="<?php echo $LienSite ?>Pages/ServiceCreer.php">Création</a>
		  <a href="<?php echo $LienSite ?>Pages/ServiceModifier.php">Modification</a>
		  <a href="<?php echo $LienSite ?>Pages/ServiceSupprimer.php">Suppression</a>
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

	<a name="Déco" href="<?php echo $LienSite ?>logout.php?logout=true"><img name="logout" src="../Images/logout.png" alt="Logout logo" > Déconnexion</a>

</div>

<!-- TTTTTEEESSSSSTTT -->
<div id=PagePrincipale>
    	<div class="profile">
  			<div class="photo">
  			<img src="../Images/User.png" alt="Image utilisateur"/>
  			</div>

			<div class="content">
    			<div class="text">
      			<h3> <?php  echo($a_utilisateur['prenom']." ".$a_utilisateur['nom']); ?> </h3>
      			<h6> Service : <?php echo($a_utilisateur['ServicesnomService']); ?> </h6>
    			</div>
  			</div>
		</div>
     </div>

</body>
</html>
