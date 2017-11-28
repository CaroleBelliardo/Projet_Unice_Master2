		-->
<?php

include ('../Config/Menupage.php');
include ('../Fonctions/Affichage.php');

require_once("../session.php"); // requis pour se connecter la base de donnée 
require_once("../classe.Systeme.php"); // va permettre d effectuer les requettes sql en orienté objet.
$auth_user = new Systeme(); // PRIMORDIAL pour les requetes 
$user_id = $_SESSION['idEmploye']; // permet de conserver la session

$Req_utilisateur = $auth_user->runQuery("SELECT * FROM CompteUtilisateurs WHERE idEmploye=:user_name"); // permet de rechercher le nom d utilisateur 
$Req_utilisateur->execute(array(":user_name"=>$user_id)); // la meme 

// variables 
$utilisateur=$Req_utilisateur->fetchColumn(); // permet d afficher l identifiant du gars sur la page, ce qui faudrai c est le nom
// If array_key_exist( $patient= $_SESSION['Patient']){ alors saut la page } else {affiche le menu déroulant}

if(isset($_POST['btn_facturation']))
{	 
//	echo $patient;
//	echo $user_id; ## !!! finir eclairsir

// recuperer tous les actes realisées par le service du chef de service
// afficher liste ou pdf direct

	$text_ = trim($_POST['text_commentaires'], ' ');	
	echo ($text_idIntervention);
	$text_idIntervention = 2;
	$ajoutRDV = $auth_user->conn->prepare("INSERT INTO CreneauxInterventions (date_rdv, heure_rdv, InterventionidIntervention, niveauUrgence, pathologie, commmentaires, PatientsnumSS, EmployesCompteUtilisateursIdEmploye) 
	VALUES (:date_rdv, :heure_rdv, :InterventionidIntervention, :niveauUrgence, :pathologie, :commmentaires, :PatientsnumSS, :EmployesCompteUtilisateursIdEmploye)");

	$date_rdv = '2017-11-02';
	$heure_rdv = '08:00:00';

	$ajoutRDV->execute(array('patient'=> $numSS,
                            ));//$donnees = mysqli_fetch_array($Actes)


}
?>

<!DOCTYPE html PUBLIC >
<html>
<head>

<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>Demande RDV</title>
</head>

<body>
    <p class="h4">Session : <?php print($utilisateur); ?></p> 
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
							</div>
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
                                        echo "<option label='".$row_patient['prenom']." ".$row_patient['nom']." -- "."(".$row_patient['numSS'].")' 
                                        value='"."(".$row_patient['numSS'].")"." -- ".$row_patient['prenom']." ".$row_patient['nom']."'>".$row_patient['prenom']." ".$row_patient['nom']."</option>";
                                    }
                                    $req_patient->closeCursor();
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
                <?php quitter1() ?>	
			</div>
		</form>
</body>


</html>
