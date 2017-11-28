<?php

include ('../Config/Menupage.php');
include ('../Fonctions/Affichage.php');
include ('../Fonctions/ReqTraitement.php');
require_once("../session.php"); // requis pour se connecter la base de donnée 
require_once("../classe.Systeme.php"); // va permettre d effectuer les requettes sql en orienté objet.



//unset($_SESSION["Patient"]); // TEST 



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

// If array_key_exist( $patient= $_SESSION['Patient']){ alors saut la page } else {affiche le menu déroulant}




//if (array_key_exists("Patient",$_SESSION )){}
//		else
//		{$auth_user->redirect('FichePatientCreer.php'); 
//	// dans le cas ou aucun patient a été selectionné ou ajouté, alors il redirige vers creer un patient ( cas ou on arrive dessus par erreur ) 
//		}


if(isset($_POST['btn_facturation']))
{	 
// recuperer tous les actes realisées par le service du chef de service
// afficher liste ou pdf direct

	$text_numSS = preg_replace("/[^0-9]/", "",trim($_POST['text_patient'], ' '));		
	$_SESSION["patient"] = $text_numSS;

	$date_rdv = '2017-11-02';
	$heure_rdv = '08:00:00';

//	$ajoutRDV->execute(array('patient'=> $numSS,
//                            ));//$donnees = mysqli_fetch_array($Actes)
//	$ajoutRDV->closeCursor();
	$auth_user->redirect('Facturation.php');
}	


?>

<!DOCTYPE html PUBLIC >
<html>
<head>

<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>Demande RDV</title>
</head>

<body>
    <p class="h4">  <?php  echo($a_utilisateur[0]." ".$a_utilisateur[1]." <br> Service ".$a_utilisateur[2]); ?></p> 
    
	
	
	<?php // affichage
		If (!array_key_exists("patient",$_SESSION )) // recherche si patient existe (redirection fiche patient)
		{
	?>
			<p class="" style="margin-top:5px;">
				<div class="signin-form">
					<form method="post" class="form-signin">
								
								<h2 class="form-signin-heading">Facturation </h2><hr />
	<?php
								if(isset($error)) // affichage messages erreurs si valeurs != format attendu
								{
									foreach($error as $error) // pour chaque champs
									{
	?>
										<div class="alert alert-danger">
										<i class=""></i> &nbsp; <?php echo $error; ?>
										</div>.
	<?php
									}
								}
								else if(isset($_GET['Valide'])) // si toutes les valeurs de champs ok et que bouton valider
								{
	?>
									<div class="alert alert-info">
									<i class=""></i> Rendez-vous fixé le (date) à (heure) <a href='../Pageprincipale.php'>Page principale</a>
									<!--insertion fonction pour editer pdf--> 
									</div>
	<?php
								}
	?>
								<!-- Affichage formulaire -->
								<fieldset>
									<legend> Nom et prénom patient </legend> <!-- Titre du fieldset --> 
										<p>
											<!-- Affichage formulaire : moteur recherche-->
											<input list="text_patient" name="text_patient" size='85'> 
											<datalist id="text_patient" >
	<?php 
												$req_patient = $auth_user->runQuery("SELECT numSS, nom, prenom FROM Patients"); // permet de rechercher le nom d utilisateur 
												$req_patient->execute(); // la meme 
												while ($row_patient = $req_patient->fetch(PDO::FETCH_ASSOC))
												{
													echo "<option value='".$row_patient['numSS']."' 
													label='"."(".$row_patient['numSS'].")"." -- ".$row_patient['prenom']." ".$row_patient['nom']."'>".$row_patient['prenom']." ".$row_patient['nom']."</option>";
												}
	?>
											</datalist>
										</br >
										</p>
								</fieldset>					
								
								
								
								<!-- bouton validé -->
						</div>
						<div class="clearfix"></div><hr />
						<div class="form-group">
							<button type="submit" class="btn btn-primary" name="btn_facturation">
								<i class=""></i>Valider
							</button>
	<?php
						quitter1()
	?>	
						</div>
					</form>
			</p>
	<?php
			//include ('../Fonctions/patientRecherche.php');
		}
		else
		{
			echo $_SESSION["patient"];
		}
	?>
	
	
		
</body>


</html>
