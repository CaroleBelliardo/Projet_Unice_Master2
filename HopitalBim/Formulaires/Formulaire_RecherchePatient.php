<?php

if(isset($_POST['btn_facturation'])) // action du bouton btn_facture
{	 
	$text_numSS = preg_replace("/[^0-9]/", "",trim($_POST['text_patient'], ' '));
	// Gestion erreur : idPatient n'existe pas dans la bdd
		$req_numSS = $auth_user->runQuery(" SELECT numSS
											FROM Patients
										WHERE numSS = :numSS" ); // recherche le numSS dans la bdd
	$req_numSS->execute(array('numSS'=> $text_numSS));
	$numSS= $req_numSS-> fetchColumn();

	if ($numSS == "") // nom de d'INTERVENTION absent de la base de données
	{
		$error[] =  "Veuillez saisir un numéro de sécurité sociale valide !";
	}
	else 
	{
		$_SESSION["patient"] = $text_numSS;
		$auth_user->redirect($lien);
	}
}	


?>

<!DOCTYPE html PUBLIC >
<html>
	<head>
		<title>Demande RDV</title>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<link rel="stylesheet" href="../Config/Style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
	</head>

	<body>
		<div class="containerFormu">

			<form method="post" class="form-signin">
								
				<h2 class="form-signin-heading">Rechercher un patient </h2><hr />

					<?php
						if(isset($error)) // affichage messages erreurs si valeurs != format attendu pour les champs
						{
							foreach($error as $error) // pour chaque champs
							{
					?>
						<div id="error"> &nbsp; <?php echo $error; ?> </div>
					
					<?php
						}
					}
						else if(isset($_GET['Valide'])) // si toutes les valeurs de champs ok et que bouton valider
						{
					?>

					<div id="valide">
						Rendez-vous fixé le (date) à (heure) <a href='../Pageprincipale.php'>Page principale</a>
						<!--insertion fonction pour editer pdf--> 
					</div>
	
					<?php
						}
					?>

				<fieldset>
				<legend> Nom et prénom patient </legend> <!-- Titre du fieldset --> 
										
					<!-- Affichage formulaire : moteur recherche du patient-->
					<label for="text_patient"> Patient </label>
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
				
				</fieldset>	</br >				
					
				<div class="form-group">
					<button type="submit" class="btn btn-primary" name="btn_facturation">
					Valider
					</button>
				</div>

					
			</form>
		</div> <!-- containerFormu -->

		<button class="abandon">
			<?php
				quitter1($auth_user)
			?>	
		</button> <!-- abandon -->

		<?php include ('../Config/Footer2.php'); //menu de navigation ?> 

	</body>
</html>
