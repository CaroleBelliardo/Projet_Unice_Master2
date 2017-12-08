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

