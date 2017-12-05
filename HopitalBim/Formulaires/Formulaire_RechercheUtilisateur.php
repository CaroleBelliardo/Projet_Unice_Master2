

<?php

if(isset($_POST['btn_utilisateur'])) // action du bouton btn_facture
{	 	
	$_SESSION["utilisateurModifier"] = trim($_POST['text_utilisateur'], ' ');
	$auth_user->redirect($lien);
}	
?>

<!DOCTYPE html PUBLIC >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>Modifier employé</title>
</head>

<body>

			<p class="" style="margin-top:5px;">
				<div class="signin-form">
					<form method="post" class="form-signin">
								
								<h2 class="form-signin-heading">Rechercher un(e) employé(e)</h2><hr />
							
								<!-- Affichage formulaire -->
								<fieldset>
									<legend> Employé(e) </legend> <!-- Titre du fieldset --> 
										<p>
											<!-- Affichage formulaire : moteur recherche du patient-->
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
											</datalist>
										</br >
										</p>
								</fieldset>					
							
								<!-- bouton validé -->
						</div>
					<div class="clearfix"></div>
					<hr />
					<div class="form-group">
						<button type="submit" class="btn btn-primary" name="btn_utilisateur">
							<i class=""></i>Valider
						</button>
	<?php
						quitter1($auth_user)
	?>	
					</div>
				</form>
			</p>
			
	
	
		
</body>


</html>
