<div class="containerFormu">

        <form method="post" class="form-signin">

            <h2 class="form-signin-heading">Enregistrer une fiche patient</h2> <hr/>

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
			else if(isset($_GET['Valide']))
			{
			?>

            <div id="valide"> <!-- Alert alert-info-->
                Patient enregistré avec succés ! <a href='../Pages/RDVDemande.php'> 
				Demande de rendez-vous avec ce nouveau patient ?</a>
            </div>

            <?php
			}
			?>

			<p><i>Complétez le formulaire. Les champs marqués par </i><em>*</em> sont <em>obligatoires.</em></p>

            <div class="form-group" >
			<fieldset>
			<legend> Patient </legend> <!-- Titre du fieldset -->

				<label for="text_numSS">N° Sécurité Sociale <em>* </em> </label>
				<input type="text" class="" name="text_numSS" pattern="[0-9]{15}" title="Caractère numérique, 15 caractères acceptés"        placeholder=" xxxxxxxxxxxxxxx " value="<?php if(isset($error)){echo $text_numSS;}?>"/> <br>

				<label for="text_nom">Nom <em>* </em></label>
				<input type="text" class="" name="text_nom" pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"     placeholder=" Nom" value="<?php if(isset($error)){echo $text_nom;}?>" /><br>

				<label for="text_prenom">Prénom <em>* </em></label>
				<input type="text" class="" name="text_prenom" pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"  placeholder=" Prénom" value="<?php if(isset($error)){echo $text_prenom;}?>" /><br>

				<label for="text_dateNaissance">Date de Naissance <em>* </em></label>
				<input type="date" class="" name="text_dateNaissance" placeholder="" value="<?php if(isset($error)){echo $text_dateNaissance;}?>" /><br>

				<label for="text_telephone">Téléphone </label>
				<input type="tel" class="" name="text_telephone" pattern="[0-9]{0-15}" title="Caractère numérique, 15 caractères acceptés"    placeholder=" 06xxxxxxxx" value="<?php if(isset($error)){echo $text_telephone;}?>" /><br>

	<!-- ne pas mettre type="email", type non supporté par I.E et safari -->
				<label for="text_mail">Mail </label>
				<input type="text" class="" name="text_mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" placeholder=" np@mail.fr " value="<?php if(isset($error)){echo $text_mail;}?>" /><br>

				<label for="text_sexe" >Sexe </label>
				<input type="radio"  name="text_sexe" value="M" checked="checked"/> Masculin
				<input type="radio"  name="text_sexe" value="F" />Féminin <br>

				<label for="text_taille"> Taille (cm) </label>
				<input type="text" class="" name="text_taille" pattern="[0-9]{0-3}" title="Caractère numérique, 15 caractères acceptés"        placeholder=" Taille" value="<?php if(isset($error)){echo $text_taille;}?>" /><br>

				<label for="text_poids"> Poids (kg) </label>
				<input type="text" class="" name="text_poids"  pattern="[0-9]{0-3}" title="Caractère numérique, 15 caractères acceptés"        placeholder=" Poids" value="<?php if(isset($error)){echo $text_poids;}?>" /><br>

				<label for="text_commentaires"> Commentaires </label>
				<textarea type="text" name="text_commentaires" value="<?php if(isset($error)){echo $text_commentaires;}?>"/></textarea><br>

			</fieldset> <br>

			<fieldset>
			<legend> Adresse du patient </legend> <!-- Titre du fieldset -->

				<label for="text_numero"> Numéro de la rue <em>* </em></label>
				<input type="number" class="" min="1" name="text_numero" pattern="[0-9]{1-6}" title="Caractère numérique, 6 caractères acceptés"         placeholder=" Numéro" value="<?php if(isset($error)){echo $text_numero;}?>" /><br>

				<label for="text_rue"> Rue <em>* </em></label>
				<input type="text" class="" name="text_rue"    pattern="{1-100}" title="Caractère alphabetique, 100 caractères maximum" placeholder=" Rue" value="<?php if(isset($error)){echo $text_rue;}?>" /><br>

				<label for="text_ville"> Ville <em>* </em></label>
				<input type="text" class="" name="text_ville"  pattern="{1-150}" title="Caractère alphabetique, 150 caractères maximum" placeholder=" Ville" value="<?php if(isset($error)){echo $text_ville;}?>" /><br>

				<label for="text_codepostal"> Code Postal </label>
				<input type="text" class="" name="text_codepostal"        placeholder=" Code postal" value="<?php if(isset($error)){echo $text_codepostal;}?>" /><br>

				<label for="text_departement"> Département </label>
				<input type="text" class="" name="text_departement"    placeholder=" Département" value="<?php if(isset($error)){echo $text_departement;}?>" /><br>

				<label for="text_pays"> Pays <em>* </em></label>
				<input type="text" class="" name="text_pays"   pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum" placeholder=" Pays" value="<?php if(isset($error)){echo $text_pays;}?>" /><br>
			</fieldset> <br>
			</div> <!-- form-group // Formulaire principal -->

            <div class="form-group">
            	<button type="submit" class="btn btn-primary" name="btn-signup">Valider</button>
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

 <!-- bouton abandon redirection Page principale -->
<?php quitter1() ?>

