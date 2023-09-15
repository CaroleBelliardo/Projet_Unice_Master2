<?php

if(isset($_POST['btn_utilisateur'])) // action du bouton btn_facture
{	 	
	
	$req_util = $auth_user->runQuery("SELECT idEmploye FROM Employes 
								WHERE CompteUtilisateursidEmploye = :employe ");
	$req_util->execute(array('employe' =>  trim($_POST['text_utilisateur'], ' ')));
	$util = $req_util->fetchColumn();
	if (($util == ""))
	{
		$error[] = "Entrer un nom d'utilisateur valide !";
	}
	else
	{
		$_SESSION["utilisateurModifier"] = trim($_POST['text_utilisateur'], ' ');
		$auth_user->redirect($lien);
	}

}	
?>

<div class="containerFormu">
	
	<form method="post" class="form-signin">
								
		<h2 class="form-signin-heading">Rechercher un(e) employé(e)</h2><hr />
		<?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div id="error"> &nbsp; <?php echo $error; ?> </div>  
                     <?php
				}
			}
		?>
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


<?php quitter1($auth_user) ?>

<?php include ('../Config/Footer2.php'); //menu de navigation?>
