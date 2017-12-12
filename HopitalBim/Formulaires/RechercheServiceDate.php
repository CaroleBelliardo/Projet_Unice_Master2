<?php

	if(isset($_POST['btn-planning'])) // action du bouton btn_facture
	{	 
		$_SESSION["servicePlanning"] = $_POST['text_nomService'];
		$_SESSION["dateModifier"] = $_POST['text_date'];

		$auth_user->redirect($lien);
	}	
	

?>
 	
<div class="containerFormu">

 	<form method="post" >

		<fieldset>
 		<legend> Choisir une date et/ou un service </legend> 
	<?php
			if(isset($error)) // affichage messages erreurs si valeurs != format attendu
			{
			foreach($error as $error) // pour chaque champs
			{
		?>

		<div class="error"> <?php echo $error; ?> </div>
	
		<?php
			}
			}
	 	if(isset($_GET['Valide']))
			{
		?>
                 
        <div id="valide"> <!-- Alert alert-info-->
            L'intervention a été réalisée !
			<a href='../Pages/RDVDemande.php'>Demande de rendez-vous avec ce patient ?</a>
        </div>

        <?php
			}
			if(isset($_GET['?Suppression']))
			{
		?>
                 
        <div id="valide"> Le RDV a été annulé ! </div>

        <?php
			}
		?>
 			<table id="legendPlanning">
 			
				<tr class="legendPlanning">
					<td class="legendPlanning"> <CENTER> <button> R </button> </CENTER> </td> 
					<td class="legendPlanning"> Réalisé ! </td>
				</tr>

				<tr class="legendPlanning"> 
					<td class="legendPlanning"> <CENTER> <button> X </button> </CENTER> </td>
					<td class="legendPlanning"> Pour annuler </td>
				</tr>

	<?php
		if (( $_SESSION["chefService"] == TRUE ))
		{
	?>	

				<tr class="legendPlanning">
					<td class="legendPlanning"> <CENTER> <button> M </button> </CENTER> </td>
					<td class="legendPlanning"> Pour modifier </td>
				</tr>

	<?php 
		}
	?>
				
			</table> <br>

 			<label for="text_date">Date  </label>
 			<input type="date" class="" name="text_date" placeholder="<?php echo $_SESSION['dateModifier'];?>" value="<?php echo $_SESSION['dateModifier'];?>" /> <br>

 			<label for="text_nomservice">Service </label> 
 			<div class="recherchePlanning">
 			<?php liste_Services($auth_user) ?>
			</div>


		</fieldset>	<br>

		<div class="form-group">
			<button type="submit" class="btn btn-primary" name="btn-planning"> Valider </button>
		</div> 

	</form>

</div>

