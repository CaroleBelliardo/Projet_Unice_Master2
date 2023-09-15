<?php

if(isset($_POST['btn_nomService'])) // action du bouton btn_facture
{	 	
	
	$req_Service = $auth_user->runQuery("SELECT nomService FROM Services 
								WHERE nomService = :nomService");
	$req_Service->execute(array('nomService' =>  trim($_POST['text_nomService'], ' ')));
	$service = $req_Service->fetch(PDO::FETCH_ASSOC);
	if (($service['nomService'] == ""))
	{
		$error[] = "Entrer un service valide !";
	}
	else
	{
		$_SESSION["serviceModifier"] = trim($_POST['text_nomService'], ' ');
		$auth_user->redirect($lien);
	}

}	
?>

<div class="containerFormu">
	
	<form method="post" class="form-signin">
								
		<h2 class="form-signin-heading">Rechercher un service</h2><hr />
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
				<legend> Service </legend> <!-- Titre du fieldset --> 
							
					<!-- Affichage formulaire : moteur recherche du patient-->
					<label for="text_nomService"> Liste des services </label>
					<input list="text_nomService" name="text_nomService" size='85'> 
						<datalist id="text_nomService" >
						<?php 
							$req_service = $auth_user->runQuery("SELECT * FROM Services"); // permet de rechercher le nom d utilisateur 
							$req_service->execute(); // la meme 
							while ($row_service = $req_service->fetch(PDO::FETCH_ASSOC))
							{
								echo "<option value='".$row_service['nomService']."'label='".$row_service['nomService']."'>".$row_service['nomService']."</option>";
							}
							?>
						</datalist> </br >
										
				</fieldset>	<br>	
			</div> <!-- form-group // Formulaire principal --> 			
								
			<div class="form-group">
				<button type="submit" class="btn btn-primary" name="btn_nomService">Valider</button>
			</div> <!-- Valider -->

	</form>
	
</div> <!-- containerFormu -->

<?php quitter1($auth_user) ?>


<?php include ('../Config/Footer2.php'); //menu de navigation?>
