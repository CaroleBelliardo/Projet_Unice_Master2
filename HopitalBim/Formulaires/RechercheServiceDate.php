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

				<label for="text_date">Date  </label>
				<input type="date" class="" name="text_date" placeholder="<?php echo $_SESSION['dateModifier'];?>" value="<?php echo $_SESSION['dateModifier'];?>" /><br>
			
				<label for="text_nomservice">Service </label>
				<input list="text_nomservice" name="text_nomservice" size='85'>
				<datalist id="text_nomservice">
					<?php liste_Services($auth_user) ?> <!-- valeurs par defaut -->
				</datalist><br>

		</fieldset>	<br>

		<div class="form-group">
			<button type="submit" class="btn btn-primary" name="btn-planning"> Valider </button>
		</div> 

	</form>

</div>

