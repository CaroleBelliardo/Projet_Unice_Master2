<!-- Chose a faire : 
		- Regarder la gestion des maj et des minuscules des entrées dans la base de donnée
		   proposition : nom de famille tout en majuscule et prenom tout en minuscule.
		   ou : ucfirst() - Make a string's first character uppercase
		   
		-// si $patient = "" alors redirect vers page principale
		- Afficher d'autre info relatif au patient : comme le nom et le prenom, date de naissance etc ..
		
		-- utiliser explode pour transfo string to array
		-- implode array to string
		-- ARAY_map = appliquer une instruction à tout un tableau 
		
		-->
<?php
include ('../Config/Menupage.php');

$utilisateur=$_SESSION['idEmploye'];
if (array_key_exists("Patient",$_SESSION )){}
		else
		{
			$auth_user->redirect('FichePatientCreer.php'); 
	// dans le cas ou aucun patient a été selectionné ou ajouté, alors il redirige vers creer un patient ( cas ou on arrive dessus par erreur ) 
		}


		
if(isset($_POST['btn_demandeRDV']))
{	 
	echo $patient;
	echo $user_id;
	$text_nomPathologie = ucfirst(trim($_POST['text_nomPathologie'], ' '));	// trim enleve les espaces en debut et fin mais pas au milieu 
	$text_indicationActe = ucfirst(trim($_POST['text_indicationActe'], ' '));	
	$text_idIntervention = preg_replace("/[^0-9]/", "",trim($_POST['text_idIntervention'], ' '));		
	$text_urgence = trim($_POST['text_urgence'], ' ');
	$text_commentaires = trim($_POST['text_commentaires'], ' ');	
	$text_indicationPathologie= trim($_POST['text_indicationPathologie'], ' ');
	$ajoutRDV = $auth_user->runQuery("INSERT INTO CreneauxInterventions (date_rdv, heure_rdv, InterventionsidIntervention, niveauUrgence, pathologie, commentaires, PatientsnumSS, EmployesCompteUtilisateursIdEmploye) 
	VALUES (:date_rdv, :heure_rdv, :InterventionsidIntervention, :niveauUrgence, :pathologie, :commentaires, :PatientsnumSS, :EmployesCompteUtilisateursIdEmploye)");
	
	$today = date("h:i:s");

	$date_rdv = date("Y-m-d");
	$heure_rdv = date("h:i:s");


	$ajoutRDV->execute(array('date_rdv'=> $date_rdv,
							'heure_rdv'=> $heure_rdv,
							'InterventionsidIntervention'=> $text_idIntervention,
							'niveauUrgence'=> $text_urgence,
							'pathologie'=> $text_nomPathologie,
							'commentaires'=> $text_commentaires,
							'PatientsnumSS'=> $patient,
							'EmployesCompteUtilisateursIdEmploye'=> $user_id));
							//$donnees = mysqli_fetch_array($Actes)
		

	$ajoutRDV->closeCursor();
	
	$lien= 'RDVDemande.php';
 }
?>

<!DOCTYPE html PUBLIC >
<html>
<head>
<link rel="stylesheet" href=Style.css">
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>Demande RDV</title>
</head>

<body>
 
 
	<?php // affichage
		if ($user_id != 'admin00' and $test_chef == FALSE) 
			{
				$auth_user->redirect('../Pageprincipale.php'); // si l'utilisateur est un medecin
			}
		Else 
		{
			If (!array_key_exists("patient",$_SESSION )) 
			{
				include ('../Pages/RecherchePatient.php');; // recherche patient existe pas (redirection fiche patient)
			}
			else
			{
?>
				 <p class="" style="margin-top:5px;">
				<div class="signin-form">
					<form method="post" class="form-signin">
								<h2 class="form-signin-heading">Demande de rendez-vour le patient <?php echo $_SESSION["patient"]; ?></h2><hr /> <!--nom patient !!!!!!!!-->
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
										<input type="text" class="" name="text_nomPathologie"  pattern="[a-zA-Z]{1-100}" title="Caractère alphabetique, 100 caractères maximum"  placeholder="Entrer le nom de la pathologie :" value="<?php if(isset($error)){echo $text_nomPathologie;}?>" /><br><br>
										<input type="text" class="" name="text_indicationPathologie" pattern="[a-zA-Z]{0-30}" title="Caractère alphabetique, 30 caractères maximum"       placeholder="Entrer les indactions :" value="<?php if(isset($error)){echo $text_indicationPathologie;}?>" /><br><br>
			 
										</p>
								</fieldset>
								<fieldset>
									<legend> Intervention demandée </legend> <!-- Titre du fieldset --> 
										<p>
											<!-- Affichage formulaire : moteur recherche-->
											<input list="text_acte" name="text_acte" size='35'> 
											<datalist id="text_acte" >
<?php 
												$req_serviceacte = $auth_user->runQuery("SELECT idIntervention, acte, ServicesnomService FROM Interventions"); // permet de rechercher le nom d utilisateur 
												$req_serviceacte->execute(); // la meme 
												while ($row_serviceacte = $req_serviceacte->fetch(PDO::FETCH_ASSOC))
												{
													echo "<option label='".$row_serviceacte['acte']."' 
													value='".$row_serviceacte['acte']."'>".$row_serviceacte['acte']."</option>";
												}
												$req_serviceacte->closeCursor();
?>
											</datalist>
											</br >
			
											<label   class="form-control" > Niveau d'urgence :&nbsp;&nbsp;      
												<input type="radio"  name="text_urgence" value="0" checked="checked"/>0
												<input type="radio"  name="text_urgence" value="1"/>1
												<input type="radio"  name="text_urgence" value="2" />2
												<input type="radio"  name="text_urgence" value="3" />3
											</label><br><br>		
											<input type="text" class="" name="text_indicationActe" pattern="[a-zA-Z]{0-30}" title="Caractère alphabetique, 30 caractères maximum"       placeholder="Entrer les indactions :" value="<?php if(isset($error)){echo $text_indicationActe;}?>" /><br>								
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
		
<?php
			}
		}
	?>

 
 
 
 
 
   
</body>


</html>
