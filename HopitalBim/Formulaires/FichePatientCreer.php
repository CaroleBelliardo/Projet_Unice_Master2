<div class="containerFormu"> 
    	
        <form method="post" class="form-signin">

            <h2 class="form-signin-heading">Enregistrer une fiche patient</h2> <hr/>

            <?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div id="error"> &nbsp; <?php echo $error; ?> </div>  <!-- Alert alert-danger-->
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
				<input type="text" class="" name="text_numSS" pattern="[0-9]{15}" title="Caractère numérique, 15 caractères acceptés"        placeholder=" *************** " value="<?php if(isset($error)){echo $text_numSS;}?>"/> <br>

				<label for="text_nom">Nom </label>
				<input type="text" class="" name="text_nom" pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"     placeholder="Nom" value="<?php if(isset($error)){echo $text_nom;}?>" /><br>

				<label for="text_prenom">Prénom </label>
				<input type="text" class="" name="text_prenom" pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"  placeholder="Prénom" value="<?php if(isset($error)){echo $text_prenom;}?>" /><br>

				<label for="text_dateNaissance">Date de Naissance </label>
				<input type="date" class="" name="text_dateNaissance" placeholder="" value="<?php if(isset($error)){echo $text_dateNaissance;}?>" /><br>

				<label for="text_telephone">Téléphone </label>
				<input type="text" class="" name="text_telephone" pattern="[0-9]{0-15}" title="Caractère numérique, 15 caractères acceptés"    placeholder=" 06xxxxxxxx" value="<?php if(isset($error)){echo $text_telephone;}?>" /><br>

				<label for="text_mail">Mail </label>
				<input type="text" class="" name="text_mail" placeholder=" np@mail.fr " value="<?php if(isset($error)){echo $text_mail;}?>" /><br>
				
				<label for="text_sexe" >Sexe </label>
				<input type="radio"  name="text_sexe" value="M" checked="checked"  style="display: inline; !important;"/>Masculin
				<input type="radio"  name="text_sexe" value="F" style="display: inline;!important;" />Féminin <br>	

				<label for="text_taille"> Taille (cm) </label>
				<input type="text" class="" name="text_taille" pattern="[0-9]{0-3}" title="Caractère numérique, 15 caractères acceptés"        placeholder="Taille" value="<?php if(isset($error)){echo $text_taille;}?>" /><br>

				<label for="text_poids"> Poids (kg) </label>
				<input type="text" class="" name="text_poids"  pattern="[0-9]{0-3}" title="Caractère numérique, 15 caractères acceptés"        placeholder="Poids" value="<?php if(isset($error)){echo $text_poids;}?>" /><br>

				<label for="text_commentaires"> Commentaires </label>
				<input type="text" class="" name="text_commentaires" placeholder="Commentaires :" value="<?php if(isset($error)){echo $text_commentaires;}?>" /><br>

			</p>
			</fieldset> <br>
			
			<fieldset>
			<legend> Adresse du patient </legend> <!-- Titre du fieldset --> 
			<p>
				<input type="text" class="" name="text_numero" pattern="[0-9]{1-6}" title="Caractère numérique, 6 caractères acceptés"         placeholder="Numéro de la rue :" value="<?php if(isset($error)){echo $text_numero;}?>" /><br>
				<input type="text" class="" name="text_rue"    pattern="[A-Za-z]{1-100}" title="Caractère alphabetique, 100 caractères maximum" placeholder="Nom de la rue :" value="<?php if(isset($error)){echo $text_rue;}?>" /><br>
				<input type="text" class="" name="text_ville"  pattern="[A-Za-z]{1-150}" title="Caractère alphabetique, 150 caractères maximum" placeholder="Nom de la ville :" value="<?php if(isset($error)){echo $text_ville;}?>" /><br>
				<input type="text" class="" name="text_codepostal" pattern="[0-9]{5}" title="Caractère numérique, 5 caractères maximum"       placeholder="Code postal :" value="<?php if(isset($error)){echo $text_codepostal;}?>" /><br>
				<input type="text" class="" name="text_departement"   pattern="{1-3}" title="3 caractères maximum"   placeholder="Département :" value="<?php if(isset($error)){echo $text_departement;}?>" /><br>
				<input type="text" class="" name="text_pays"   pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum" placeholder="Pays :" value="<?php if(isset($error)){echo $text_pays;}?>" /><br>
			</p>
			</fieldset> <br>
			</div>

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
				
            </div>
        </form>

</div> <!-- containerFormu -->

<div class="abandon">
<?php quitter1() ?>
</div>