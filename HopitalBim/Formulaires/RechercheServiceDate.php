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
?>    		<div class="error"> <?php echo $error; ?> </div>
<?php
		}
	}
	 	if(isset($_GET['Valide']))
		{
			if (($_SESSION['chefService'] == TRUE ) and ($_SESSION['service'] == $_SESSION['servicePlanning'] ))
			{
?>		
				<div id="valide"> <!-- Alert alert-info-->
					L'intervention a été réalisée !
					<a href='../Pages/Facturation.php'>Demande de rendez-vous avec ce patient ?</a>
				</div>
<?php		}
			else
			{
?>				<div id="valide"> <!-- Alert alert-info-->
						L'intervention a été réalisée !
						<a href='../Pages/Facturation.php'>Demande de rendez-vous avec ce patient ?</a>
					</div>
<?php		}
		}
		if(isset($_GET['?Suppression']))
		{
		?>       
			<div id="valide"> Le RDV a été annulé ! </div>
<?php	}
?>
		<table id="legendPlanning"> <!--legende correspondant aux boutons-->
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
			</table> <br> <!--Selection du jour et de la date-->
 			<label for="text_date">Date  </label>
 			<input type="date" class="" name="text_date" placeholder="<?php echo $_SESSION['dateModifier'];?>" value="<?php echo $_SESSION['dateModifier'];?>" /> <br>
			<br>
 			<label for="text_nomservice">Service </label> 
 			<div class="recherchePlanning">
		
			<div class="form-group" > 
				<select name="text_nomService" selected= "<?php echo $_SESSION['servicePlanning'] ?>" > 
	<?php  
				  $stmt = $auth_user->runQuery("SELECT nomService FROM Services"); // permet de rechercher le nom d utilisateur  
				  $stmt->execute(); // la meme  
				  while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
				  { 
					echo "<option value='".$row['nomService']."'>".$row['nomService']."</option>"; 
				  }
	?> 			  </select> 
				</div> 
			</div>
		</fieldset>	<br>
		<div class="form-group">
			<button type="submit" class="btn btn-primary" name="btn-planning"> Valider </button>
		</div> 
	</form>
</div>

