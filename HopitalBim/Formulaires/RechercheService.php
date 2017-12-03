

<?php

if(isset($_POST['btn_nomService'])) // action du bouton btn_facture
{	 
	$text_nomService = trim($_POST['text_nomService'], ' ');		
	$_SESSION["serviceModifier"] = $text_nomService;
	$auth_user->redirect($lien);
}	


?>

<!DOCTYPE html PUBLIC >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>Modifier un service</title>
</head>

<body>

			<p class="" style="margin-top:5px;">
				<div class="signin-form">
					<form method="post" class="form-signin">
								
								<h2 class="form-signin-heading">Rechercher un service</h2><hr />
							
								<!-- Affichage formulaire -->
								<fieldset>
									<legend> Service </legend> <!-- Titre du fieldset --> 
										<p>
											<!-- Affichage formulaire : moteur recherche du patient-->
											<input list="text_nomService" name="text_nomService" size='85'> 
											<datalist id="text_nomService" >
	<?php 
												$req_service = $auth_user->runQuery("SELECT * FROM Services"); // permet de rechercher le nom d utilisateur 
												$req_service->execute(); // la meme 
												while ($row_service = $req_service->fetch(PDO::FETCH_ASSOC))
												{
													echo "<option value='".$row_service['nomService']."' 
													label='".$row_service['nomService']."'>".$row_service['nomService']."</option>";
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
						<button type="submit" class="btn btn-primary" name="btn_nomService">
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
