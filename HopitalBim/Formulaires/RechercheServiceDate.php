

<?php

	if(isset($_POST['btn-planning'])) // action du bouton btn_facture
	{	 
		$_SESSION["serviceModifier"] = $_POST['text_nomService'];
		$_SESSION["dateModifier"] = $_POST['text_date'];

		$auth_user->redirect($lien);
	}	
	

?>

 <form method="post" >
	<fieldset>
 	<legend> Choisir une date et/ou un service </legend> 

	<CENTER>
		<table>
		<tr>
			<td width=33%>
				<input type="date" class="" name="text_date" placeholder="<?php echo $_SESSION['dateModifier'];?>" value="<?php echo $_SESSION['dateModifier'];?>" /><br>

			</td>
			<td width=33%>
				Service : <?php liste_Services($auth_user) ?> <!-- valeurs par defaut -->
			</td>
		</tr>
		<tr>
		<td>
			<div class="form-group">
			<button type="submit" class="btn btn-primary" name="btn-planning">
				<i class=""></i>Valider
			</button>
		</td>
			</div>
			</table></CENTER>

	</fieldset>					
	</form>


