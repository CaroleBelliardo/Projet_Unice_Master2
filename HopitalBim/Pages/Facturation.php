<?php

include ('../Config/Menupage.php');
include ('../Fonctions/Affichage.php');
include ('../Fonctions/ReqTraitement.php');
require_once("../session.php"); // requis pour se connecter la base de donnée 
require_once("../classe.Systeme.php"); // va permettre d effectuer les requettes sql en orienté objet.





// variables 
$auth_user = new Systeme(); // Connection bdd

$user_id = $_SESSION['idEmploye']; // IDENTIFIANT compte utilisateur !!!!!
$Req_utilisateur = $auth_user->runQuery("SELECT DISTINCT nom,prenom,ServicesnomService
										FROM CompteUtilisateurs JOIN Employes
										WHERE CompteUtilisateurs.idEmploye=Employes.CompteUtilisateursidEmploye
										AND CompteUtilisateurs.idEmploye= :user_name
										"); // NOM utilisateur = >> à mettre dans menuPage !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! TOUTES PAGES 
$Req_utilisateur->execute(array("user_name"=>$user_id)); 
$a_utilisateur= reqToArrayPlusAtt($Req_utilisateur);  // Nom prénom et service utilisateur 

	
?>

<!DOCTYPE html PUBLIC >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>Demande RDV</title>
</head>

<body>
    <p class="h4">  <?php  echo($a_utilisateur[0]." ".$a_utilisateur[1]." <br> Service ".$a_utilisateur[2]); ?></p> <!--affichage nom prenom service user-->
	
	<?php // affichage
		if ($user_id != 'admin00' and $test_chef == FALSE)  // si pas chef de service 
			{
				$auth_user->redirect('../Pageprincipale.php');
				echo "medecin";
			}
			Else 
			{
				If (!array_key_exists("patient",$_SESSION )) // recherche si patient existe (redirection fiche patient)
				{
					include ('../Pages/RecherchePatient.php');;
			?>
				
			<?php
					//include ('../Fonctions/patientRecherche.php');
				}
				else
				{
					quitter1($auth_user);
					?>

				
				<?php
				}
			}	
	?>
	
	
		
</body>


</html>
