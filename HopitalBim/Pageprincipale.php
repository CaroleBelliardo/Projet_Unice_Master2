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
	<link rel="stylesheet" href="Config/style.css" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Josefin+Slab:600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
	</head>

	<body>
	<div id="page">

	<div id="entete">  <!-- Motif à mettre sur chaque page--> 
    Planning Hopital Bim
    </div>

    <div class="accroche"> Pour gérer votre planning en un clin d'oeil </div>

	<div class="navbar"> 

		<a href="<?php echo $LienSite ?>Pages/Planning.php">Planning</a>

		<a href="<?php echo $LienSite ?>Pages/RDVDemande.php">Demande de rendez-vous </a>
		
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
		<p class="h4">  
			<?php  echo($a_utilisateur[0]." ".$a_utilisateur[1]." <br> Service ".$a_utilisateur[2]); ?>
		</p> <!--affichage nom prenom service user-->

		<p class="h4">Page Principale </p> 

    	<p class="" style="margin-top:5px;">
    	ICI les conneries regardant le gars connecté.
    	
    	Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un peintre anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker. <br>

    	On sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même. L'avantage du Lorem Ipsum sur un texte générique comme 'Du texte. Du texte. Du texte.' est qu'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles de mise en page ou éditeurs de sites Web ont fait du Lorem Ipsum leur faux texte par défaut, et une recherche pour 'Lorem Ipsum' vous conduira vers de nombreux sites qui n'en sont encore qu'à leur phase de construction. Plusieurs versions sont apparues avec le temps, parfois par accident, souvent intentionnellement (histoire d'y rajouter de petits clins d'oeil, voire des phrases embarassantes).`

    	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc dictum ligula rhoncus nunc consectetur facilisis. Proin ultrices hendrerit ultricies. In pharetra, lectus ac bibendum tempor, lacus magna aliquet ligula, nec rutrum risus nibh eu libero. Integer dapibus laoreet dui eleifend pellentesque. Suspen

    	</p>
     </div>

     <div id="footer">
    Conditions d'utilisation | Contact | © 2017
    </div>

    </div> <!-- Page -->
	</body>
</html>
