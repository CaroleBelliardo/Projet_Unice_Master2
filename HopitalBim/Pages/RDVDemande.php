<!-- Chose a faire : 
		- Regarder la gestion des maj et des minuscules des entrées dans la base de donnée
		   proposition : nom de famille tout en majuscule et prenom tout en minuscule.
		   ou : ucfirst() - Make a string's first character uppercase
		   
		-// si $patient = "" alors redirect vers page principale
		- Afficher d'autre info relatif au patient : comme le nom et le prenom, date de naissance etc ..
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
$patient= $_SESSION['Patient'];
if (array_key_exists("Patient",$_SESSION )){}
		else
		{$auth_user->redirect('FichePatientCreer.php'); 
	// dans le cas ou aucun patient a été selectionné ou ajouté, alors il redirige vers creer un patient ( cas ou on arrive dessus par erreur ) 
		}
if(isset($_POST['btn_demandeRDV']))
{	 
	echo $patient;
	echo $user_id;
	$text_nomPathologie = ucfirst(trim($_POST['text_nomPathologie'], ' '));	// trim enleve les espaces en debut et fin mais pas au milieu 
	$text_indicationActe = ucfirst(trim($_POST['text_indicationActe'], ' '));	
	$text_idIntervention = trim($_POST['text_idIntervention'], ' ');		
	$text_urgence = trim($_POST['text_urgence'], ' ');
	$text_commentaires = trim($_POST['text_commentaires'], ' ');	
	echo ($text_idIntervention);
	$text_idIntervention = 2;
	$ajoutRDV = $auth_user->runQuery("INSERT INTO CreneauxInterventions (date_rdv, heure_rdv, InterventionidIntervention, niveauUrgence, pathologie, commmentaires, PatientsnumSS, EmployesCompteUtilisateursIdEmploye) 
	VALUES (:date_rdv, :heure_rdv, :InterventionidIntervention, :niveauUrgence, :pathologie, :commmentaires, :PatientsnumSS, :EmployesCompteUtilisateursIdEmploye)");
	

   $today = date("h:i:s");

    echo $today;
	
	$date_rdv = date("Y-m-d");
	$heure_rdv = date("h:i:s");
	echo $date_rdv ;echo ': DATE RDV <br>';
	echo $heure_rdv ;echo ': heure <br>';
	echo $text_idIntervention ;echo ':InterventionidIntervention <br>';
	echo  $text_urgence ;echo ' :niveauUrgence <br>';
	echo $text_nomPathologie ;echo ':text_nomPathologie <br>';
	echo $text_commentaires ;echo ': commentaires<br>';
	echo  $patient;echo ': PatientsnumSS <br>';
	echo $user_id ;echo ': user_id <br>';

	$ajoutRDV->execute(array('date_rdv'=> $date_rdv,
							'heure_rdv'=> $heure_rdv,
							'InterventionidIntervention'=> $text_idIntervention,
							'niveauUrgence'=> $text_urgence,
							'pathologie'=> $text_nomPathologie,
							'commentaires'=> $text_commentaires,
							'PatientsnumSS'=> $patient,
							'EmployesCompteUtilisateursIdEmploye'=> $user_id));
							//$donnees = mysqli_fetch_array($Actes)
							
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
					<h2 class="form-signin-heading">Demande de rendez-vour le patient <?php echo $patient ?></h2><hr />
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
						</div>
					<?php
					}
					?>
					
					
					<!-- Affichage formulaire -->
					<fieldset>
						<legend> Pathologie du patient </legend> <!-- Titre du fieldset --> 
							<p>
							<input type="text" class="" name="text_nomPathologie"  pattern="[a-zA-Z]{1-100}" title="Caractère alphabetique, 150 caractères maximum"  placeholder="Entrer le nom de la pathologie :" value="<?php if(isset($error)){echo $text_nomPathologie;}?>" /><br><br>
							<input type="text" class="" name="text_indicationActe" pattern="[a-zA-Z]{1-30}" title="Caractère numérique, 5 caractères maximum"       placeholder="Entrer les indactions :" value="<?php if(isset($error)){echo $text_indicationacte;}?>" /><br>
          
							</p>
					</fieldset>
					<fieldset>
						<legend> Intervention demandée </legend> <!-- Titre du fieldset --> 
							<p>
								<!-- Affichage formulaire : moteur recherche-->
									<input list="text_idIntervention" name="text_idIntervention" size='35'> 
									<datalist id="text_idIntervention" >
								<?php 
									$req_serviceacte = $auth_user->runQuery("SELECT idIntervention, acte, ServicesnomService FROM Interventions"); // permet de rechercher le nom d utilisateur 
									$req_serviceacte->execute(); // la meme 
									while ($row_serviceacte = $req_serviceacte->fetch(PDO::FETCH_ASSOC)){
									echo "<option label='".$row_serviceacte['acte']." ".$row_serviceacte['ServicesnomService']."' 
									value='"."(".$row_serviceacte['idIntervention'].")"." -- ".$row_serviceacte['acte']." ".$row_serviceacte['ServicesnomService']."'>".$row_serviceacte['acte']." ".$row_serviceacte['ServicesnomService']."</option>";
									}?></datalist>
									</br >

								<label   class="form-control" > Niveau d'urgence :&nbsp;&nbsp;      
									<input type="radio"  name="text_urgence" value="0" checked="checked"  style="display: inline; !important;"/>0&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio"  name="text_urgence" value="1" style="display: inline;!important;" />1
									<input type="radio"  name="text_urgence" value="2" style="display: inline;!important;" />2
									<input type="radio"  name="text_urgence" value="3" style="display: inline;!important;" />3
								</label><br>									
							</p>
					</fieldset>
					<fieldset>
						<legend> Commentaires </legend> <!-- Titre du fieldset --> 
							<p>
								<textarea type="text" class="" name="text_commentaires"   value="<?php if(isset($error)){echo $text_commentaires;}?>" ></textarea><br>
							</p>
						
					</fieldset>
					
					
					
					
					<!-- bouton validé -->
			</div>
			<div class="clearfix"></div><hr />
			<div class="form-group">
				<button type="submit" class="btn btn-primary" name="btn_demandeRDV">
					<i class=""></i>Valider
				</button>
			<?php quitter1() ?>	
			</div>
		</form>
</body>


</html>
