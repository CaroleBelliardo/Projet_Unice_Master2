<?php $LienSite = 'http://'.$_SERVER['HTTP_HOST'].'/projetm2/HopitalBim/';?> 
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

</body>
</html>
