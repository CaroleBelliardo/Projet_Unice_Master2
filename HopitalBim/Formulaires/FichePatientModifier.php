<div class="containerFormu">
        <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Modifier une fiche patient</h2> <hr />
            <?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div id="error"> &nbsp; <?php echo $error; ?> </div> <!-- Alert alert-danger-->
                     <?php
				}
			}
			else if(isset($_GET['Valide']))
			{
				 ?>
                 <div id="Valide"> <!-- Alert alert-info-->
                      Patient enregistré avec succés ! <a href='../Pages/RDVDemande.php'>
					  Demande de rendez-vous avec ce patient ? </a>
                 </div>
                 <?php
			}
			?>

			<p><i>Complétez le formulaire. Les champs marqués par </i><em>*</em> sont <em>obligatoires.</em></p>

           	<div class="form-group" >
			<fieldset>
			<legend> Patient </legend> <!-- Titre du fieldset -->

				<label for="text_numSS">N° Sécurité Sociale <em>* </em> </label>
				<input type="text" class="" name="text_numSS" pattern="[0-9]{15}" title="Caractère numérique, 15 caractères acceptés"        placeholder=" <?php echo $patientInfo[' numSS'] ;?>" value="<?php if(isset($error)){echo $text_numSS;}else {echo $patientInfo['numSS'];}?>" /><br>

				<label for="text_nom">Nom <em>* </em></label>
				<input type="text" class="" name="text_nom" pattern="[A-Za-z]{1-25}" title="Caractère alphabétique, 25 caractères maximum"     placeholder=" <?php echo $patientInfo[' nom'] ;?>" value="<?php if(isset($error)){echo $text_nom;}else {echo $patientInfo['nom'];}?>" /><br>

				<label for="text_prenom">Prénom <em>* </em></label>
				<input type="text" class="" name="text_prenom" pattern="[A-Za-z]{1-25}" title="Caractère alphabétique, 25 caractères maximum"  placeholder="<?php echo $patientInfo['prenom'] ;?>" value="<?php if(isset($error)){echo $text_prenom;}else {echo $patientInfo['prenom'];}?>" /><br>

				<!-- GESTION d'erreur pour la date : problème -->
				<label for="text_dateNaissance">Date de Naissance <em>* </em></label>
				<input type="date" class="" name="text_dateNaissance" placeholder="<?php echo $patientInfo['dateNaissance'] ;?>" value="<?php if(isset($error)){echo $text_dateNaissance;}else {echo $patientInfo['dateNaissance'];}?>" /><br>

				<label for="text_telephone">Téléphone </label>
				<input type="tel" class="" name="text_telephone" pattern="[0-9]{0-15}" title="Caractère numérique, 15 caractères acceptés"    placeholder="<?php echo $patientInfo['telephone'] ;?>" value="<?php if(isset($error)){echo $text_telephone;}else {echo $patientInfo['telephone'];}?>" /><br>

	<!-- ne pas mettre type="email", type non supporté par I.E et safari -->
				<label for="text_mail">Mail </label>
				<input type="text" class="" name="text_mail" placeholder="<?php echo $patientInfo['mail'] ;?>" value="<?php if(isset($error)){echo $text_mail;}else {echo $patientInfo['mail'];}?>" /><br>

				<label for="text_sexe" >Sexe </label>
				<input type="radio"  name="text_sexe" value="M" checked="checked"/> Masculin
				<input type="radio"  name="text_sexe" value="F" /> Féminin <br>

				<label for="text_taille"> Taille (cm) </label>
				<input type="text" class="" name="text_taille" pattern="[0-9]{0-3}" title="Caractère numérique, 15 caractères acceptés"        placeholder="<?php echo $patientInfo['taille_cm'] ;?>" value="<?php if(isset($error)){echo $text_taille;}else {echo $patientInfo['taille_cm'];}?>" /><br>

				<label for="text_poids"> Poids (kg) </label>
				<input type="text" class="" name="text_poids"  pattern="[0-9]{0-3}" title="Caractère numérique, 15 caractères acceptés"        placeholder="<?php echo $patientInfo['poids_kg'] ;?>" value="<?php if(isset($error)){echo $text_poids;}else {echo $patientInfo['poids_kg'];}?>" /><br>

				<label for="text_commentaires"> Commentaires </label>
				<textarea type="text" class="" name="text_commentaires" placeholder="<?php echo $patientInfo['commentaires'] ;?>" value="<?php if(isset($error)){echo $text_commentaires;}else {echo $patientInfo['commentaires'];}?>" /> </textarea><br>

			</fieldset> <br>

			<fieldset>
			<legend> Adresse du patient </legend> <!-- Titre du fieldset -->

				<label for="text_numero"> Numéro de la rue <em>* </em></label>
				<input type="text" class="" name="text_numero" pattern="[0-9]{1-6}" title="Caractère numérique, 6 caractères acceptés"          placeholder="<?php echo $patientInfo['numero'] ;?>" value="<?php if(isset($error)){echo $text_numero;}else {echo $patientInfo['numero'];}?>" /><br>

				<label for="text_rue"> Nom de la rue <em>* </em></label>
				<input type="text" class="" name="text_rue"    pattern="{1-100}" title="Caractère alphabétique, 100 caractères maximum" placeholder="<?php echo $patientInfo['rue'] ;?>" value="<?php if(isset($error)){echo $text_rue;}else {echo $patientInfo['rue'];}?>" /><br>

				<label for="text_ville"> Ville <em>* </em></label>
				<input type="text" class="" name="text_ville"  pattern="[A-Za-z]{1-150}" title="Caractère alphabétique, 150 caractères maximum" placeholder="<?php echo $patientInfo['nomVilles'] ;?>" value="<?php if(isset($error)){echo $text_ville;}else {echo $patientInfo['nomVilles'];}?>" /><br>

				<label for="text_codepostal"> Code Postal </label>
				<input type="text" class="" name="text_codepostal" pattern="{5}" title="Caractère numérique, 5 caractères maximum"         placeholder="<?php echo $patientInfo['codepostal'] ;?>" value="<?php if(isset($error)){echo $text_codepostal;}else {echo $patientInfo['codepostal'];}?>" /><br>

				<label for="text_departement"> Département </label>
				<input type="text" class="" name="text_departement"   pattern="{1-3}" title="3 caractères maximum"                              placeholder="<?php echo $patientInfo['departement'] ;?>" value="<?php if(isset($error)){echo $text_departement;}else {echo $patientInfo['departement'];}?>" /><br>

				<label for="text_pays"> Pays <em>* </em></label>
				<input type="text" class="" name="text_pays"   pattern="[A-Za-z]{1-25}" title="Caractère alphabétique, 25 caractères maximum"   placeholder="<?php echo $patientInfo['pays'] ;?>" value="<?php if(isset($error)){echo $text_pays;}else {echo $patientInfo['pays'];}?>" /><br>

			</fieldset> <br>
			</div> <!-- form-group // Formulaire principal -->

            <div class="form-group">
            	<button type="submit" class="btn btn-primary" name="btn-modifier">Valider</button>
				<?php
				if(isset($_GET['Valide']))
				{
				 ?>

				 <button type='submit' class='btn btn-primary' name="redirection">Demander un RDV</button>

                 <?php
					}
				?>
			</div> <!-- form-group // Bouton Valider -->

    </form> <!-- form-signin -->

</div> <!-- containerFormu -->

<button class="abandon">
<?php quitter1() ?>
</button>

<?php include ('../Config/Footer.php'); //menu de navigation ?>
