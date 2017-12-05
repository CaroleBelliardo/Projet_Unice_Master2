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
	<title>Modifier un service</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" href="../Config/Style.css" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
</head>

<body>

	<div class="containerFormu">

		<form method="post" class="form-signin">
								
			<h2 class="form-signin-heading">Rechercher un service</h2><hr />
							
				<fieldset> <!-- Affichage formulaire -->
									
					<legend> Service </legend> <!-- Titre du fieldset --> 
									
						<!-- Affichage formulaire : moteur recherche du patient-->
						<label for="text_nomService"> Nom du service </label>
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
				
				</fieldset>	</br >
										
				<div class="form-group">
					<button type="submit" class="btn btn-primary" name="btn_nomService">Valider</button>
				</div>							

		</form>

	</div> <!-- containerFormu -->	

	<div class="abandon">	
	<?php
											quitter1($auth_user)
	?>	
	</div> <!-- abandon -->

</body>
</html>
