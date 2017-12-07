<?php

if(isset($_POST['btn_utilisateur'])) // action du bouton btn_facture
{	 	
	$_SESSION["utilisateurModifier"] = trim($_POST['text_utilisateur'], ' ');
	$auth_user->redirect($lien);
}	
?>

<div class="containerFormu">
	
	<form method="post" class="form-signin">
								
		<h2 class="form-signin-heading">Rechercher un(e) employé(e)</h2><hr />
			
			<div class="form-group" >	

				<fieldset>
				<legend> Employé(e) </legend> <!-- Titre du fieldset --> 
										
					<!-- Affichage formulaire : moteur recherche du patient-->
					<label for="text_utilisateur"> Identifiant employé </label>
					<input list="text_utilisateur" name="text_utilisateur" size='85'> 
						<datalist id="text_utilisateur" >
							<?php 
								$req_utilisateur = $auth_user->runQuery("SELECT * FROM Employes 
								WHERE CompteUtilisateursidEmploye !='' "); // permet de rechercher les utilisateur ayant encore un identifiant
								$req_utilisateur->execute(); // la meme 
								while ($utilisateur = $req_utilisateur->fetch(PDO::FETCH_ASSOC))
									{
										echo "<option value='".$utilisateur['CompteUtilisateursidEmploye']."' 
										label='".$utilisateur['CompteUtilisateursidEmploye'].$utilisateur['nom'].$utilisateur['prenom']."'>".$utilisateur['CompteUtilisateursidEmploye'].$utilisateur['nom'].$utilisateur['prenom']."</option>";
									}
							?>
						</datalist> </br >
										
				</fieldset>	<br>	
			</div> <!-- form-group // Formulaire principal --> 			
								
			<div class="form-group">
				<button type="submit" class="btn btn-primary" name="btn_utilisateur">Valider</button>
			</div> <!-- Valider -->

	</form>
	
</div> <!-- containerFormu -->

<button class="abandon">
	<?php quitter1($auth_user) ?>
</button>

<?php include ('../Config/Footer2.php'); //menu de navigation?>
