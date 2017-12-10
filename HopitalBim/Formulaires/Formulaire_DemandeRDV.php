<div class="containerFormu">  

	<form method="post" class="form-signin"> <!--Formulaire-->

		<h2 class="form-signin-heading">Demande de rendez-vous avec le patient <?php echo $_SESSION["patient"]; ?></h2><hr /> 

		<?php
		if(isset($error)) // affichage messages erreurs si valeurs != format attendu
		{
			foreach($error as $error) // pour chaque champ
		{
		?>
		
		<div id="error"> &nbsp; <?php echo $error; ?> </div>

		<?php
			}
		}
			else if(isset($_GET['Valide'])) // si toutes les valeurs de champs ok et que bouton valider
			{
		?>
						
		<div id="valide">
			Rendez-vous fixé pour le patient <?php  
			$req_dateHeureRDV = $auth_user->runQuery("SELECT * FROM CreneauxInterventions 
											WHERE id_rdv = (SELECT MAX(id_rdv) 
											FROM CreneauxInterventions) AND PatientsnumSS=:patientnumss");
			$req_dateHeureRDV->execute(array('patientnumss'=>$_SESSION['patient']));
			$dateHeureRDV=$req_dateHeureRDV->fetch(PDO::FETCH_ASSOC);
			echo " à ".$dateHeureRDV['heure_rdv']." le ".$dateHeureRDV['date_rdv'];
			?>
			<a href='../Pageprincipale.php'>Page principale</a>
		</div>

		<?php
			}
		?>
				
		<div class="form-group" >			
					
			<fieldset>
			<legend> Pathologie du patient </legend> <!-- Titre du fieldset --> 

				<label for="text_numSS">Pathologie <em>* </em> </label>
				<input type="text" class="" name="text_nomPathologie"  pattern="[a-zA-Z]{1-100}" title="Caractère alphabétique, 100 caractères maximum"  placeholder=" Nom de la pathologie" value="<?php if(isset($error)){echo $nomPathologie;}?>" /><br>

				<label for="text_numSS">Indications </label>
				<input type="text" class="" name="text_indicationPathologie" pattern="[a-zA-Z]{0-30}" title="Caractère alphabétique, 30 caractères maximum" placeholder=" Indications" value="<?php if(isset($error)){echo $indicationPathologie;}?>" /><br>
							
			</fieldset> <br>

			<fieldset>
			<legend> Intervention demandée </legend> <!-- Titre du fieldset --> 
							
				<!-- Affichage formulaire : moteur recherche-->
				<label for="text_numSS">Interventions <em>* </em> </label>
				<input list="text_idIntervention" name="text_idIntervention" size='35'> 
				<datalist id="text_idIntervention" >
					<?php 
						$req_serviceacte = $auth_user->runQuery("SELECT idIntervention, acte, ServicesnomService FROM Interventions"); // permet de rechercher le nom d utilisateur 
						$req_serviceacte->execute(); // la meme 
						while ($row_serviceacte = $req_serviceacte->fetch(PDO::FETCH_ASSOC))
						{
							echo "<option label='".$row_serviceacte['acte']." ".$row_serviceacte['ServicesnomService']."' 
							value='"."(".$row_serviceacte['idIntervention'].")"."  ".$row_serviceacte['acte']." -- ".$row_serviceacte['ServicesnomService']."'>".$row_serviceacte['acte']." ".$row_serviceacte['ServicesnomService']."</option>";
						}
					?>
				</datalist> </br >

				<label for="text_urgence">Niveau d'urgence </label>
				<input type="radio"  name="text_urgence" value="0" checked="checked"/> 0
				<input type="radio"  name="text_urgence" value="1"/> 1
				<input type="radio"  name="text_urgence" value="2" /> 2
				<input type="radio"  name="text_urgence" value="3" /> 3  <br>		

<!--	Attribut supprimé de la table	<input type="text" class="" name="text_indicationIntervention" pattern="[a-zA-Z]{0-30}" title="Caractère alphabetique, 30 caractères maximum"       placeholder="Entrer les indications :" value="<?php if(isset($error)){echo $text_indicationIntervention;}?>" /><br>								
-->									
			</fieldset> </br>

			<fieldset>
			<legend> Commentaires sur le patient ou l'intervention </legend> <!-- Titre du fieldset --> 
							
			<textarea type="text" name="text_commentaires"   value="<?php if(isset($error)){echo $commentaires;}?>" ></textarea><br>
										
			</fieldset>	<br>
		
		</div> <!-- form-group // Formulaire principal --> 

		<div class="form-group">
			<button type="submit" class="btn btn-primary" name="btn_demandeRDV">
				Valider
			</button>
		</div>

	</form>  <!-- form-signin -->

</div> <!-- containerFormu -->

 <!-- bouton abandon redirection Page principale -->
<?php quitter1() ?>

<?php include ('../Config/Footer.php'); //menu de navigation ?> <!-- Footer grande page -->

