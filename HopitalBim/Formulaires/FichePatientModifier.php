<div class="signin-form">

<div class="container">
    	
        <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Enregistrer une fiche patient</h2><hr />
            <?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div class="alert alert-danger">
                        <i class=""></i> &nbsp; <?php echo $error; ?>
                     </div>
                     <?php
				}
			}
			else if(isset($_GET['Valide']))
			{
				 ?>
                 <div class="alert alert-info">
                      <i class=""></i>Patient enregistré avec succes<a href='../RDVDemande.php'></br>
					  Demande de rendez-vous.</a>
                 </div>
                 <?php
			}
			?>
			
			
            <div class="form-group" >
			<fieldset>
			<legend> Patient </legend> <!-- Titre du fieldset --> 
			<p>
				<input type="text" class="" name="text_numSS" pattern="[0-9]{15}" title="Caractère numérique, 15 caractères acceptés"          placeholder="<?php echo $patientInfo['numSS'] ;?>" value="<?php if(isset($error)){echo $text_numSS;}?>" /><br>
				<input type="text" class="" name="text_nom" pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"     placeholder="<?php echo $patientInfo['nom'] ;?>" value="<?php if(isset($error)){echo $text_nom;}?>" /><br>
				<input type="text" class="" name="text_prenom" pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"  placeholder="<?php echo $patientInfo['prenom'] ;?>" value="<?php if(isset($error)){echo $text_prenom;}?>" /><br>
				<!-- GESTION d erreur pour la date probleme --> 
				<input type="text" class="" name="text_dateNaissance"                                                                          placeholder="<?php echo date ('d-m-Y', strtotime($patientInfo['dateNaissance'] )) ;?>" value="<?php if(isset($error)){echo $text_dateNaissance;}?>" /><br>
				<input type="text" class="" name="text_telephone" pattern="[0-9]{0-15}" title="Caractère numérique, 15 caractères acceptés"    placeholder="<?php echo $patientInfo['telephone'] ;?>" value="<?php if(isset($error)){echo $text_telephone;}?>" /><br>
				<input type="text" class="" name="text_mail"                                                                                   placeholder="<?php echo $patientInfo['mail'] ;?>" value="<?php if(isset($error)){echo $text_mail;}?>" /><br>
				
				<label   class="form-control" > Sexe :&nbsp;&nbsp;      
				<input type="radio"  name="text_sexe" value="M" checked="checked"  style="display: inline; !important;"/>Masculin&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio"  name="text_sexe" value="F" style="display: inline;!important;" />Feminin
				</label><br>			
				<input type="text" class="" name="text_taille" pattern="[0-9]{0-3}" title="Caractère numérique, 15 caractères acceptés"        placeholder="<?php echo $patientInfo['taille_cm'] ;?>" value="<?php if(isset($error)){echo $text_taille;}?>" /><br>
				<input type="text" class="" name="text_poids"  pattern="[0-9]{0-3}" title="Caractère numérique, 15 caractères acceptés"        placeholder="<?php echo $patientInfo['poids_kg'] ;?>" value="<?php if(isset($error)){echo $text_poids;}?>" /><br>
				<input type="text" class="" name="text_commentaires"                                                                           placeholder="<?php echo $patientInfo['commentaires'] ;?>" value="<?php if(isset($error)){echo $text_commentaires;}?>" /><br>
			</p>
			</fieldset>
			
			<fieldset>
			<legend> Adresse du patient </legend> <!-- Titre du fieldset --> 
			<p>
				<input type="text" class="" name="text_numero" pattern="[0-9]{1-6}" title="Caractère numérique, 6 caractères acceptés"          placeholder="<?php echo $patientInfo['numero'] ;?>" value="<?php if(isset($error)){echo $text_numero;}?>" /><br>
				<input type="text" class="" name="text_rue"    pattern="[A-Za-z]{1-100}" title="Caractère alphabetique, 100 caractères maximum" placeholder="<?php echo $patientInfo['rue'] ;?>" value="<?php if(isset($error)){echo $text_rue;}?>" /><br>
				<input type="text" class="" name="text_ville"  pattern="[A-Za-z]{1-150}" title="Caractère alphabetique, 150 caractères maximum" placeholder="<?php echo $patientInfo['nomVilles'] ;?>" value="<?php if(isset($error)){echo $text_ville;}?>" /><br>
				<input type="text" class="" name="text_codepostal" pattern="[0-9]{5}" title="Caractère numérique, 5 caractères maximum"         placeholder="<?php echo $patientInfo['codepostal'] ;?>" value="<?php if(isset($error)){echo $text_codepostal;}?>" /><br>
				<input type="text" class="" name="text_departement"   pattern="{1-3}" title="3 caractères maximum"                              placeholder="<?php echo $patientInfo['nomVilles'] ;?>" value="<?php if(isset($error)){echo $text_departement;}?>" /><br>
				<input type="text" class="" name="text_pays"   pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"   placeholder="<?php echo $patientInfo['pays'] ;?>" value="<?php if(isset($error)){echo $text_pays;}?>" /><br>
			</p>
			</fieldset>
			
			
			</div>
            <div class="clearfix"></div><hr />
            <div class="form-group">
            	<button type="submit" class="btn btn-primary" name="btn-modifier">
                	<i class=""></i>Valider
                </button>
				<?php 
				if(isset($_GET['Valide']))
				{
				 ?>
			
				 <button type='submit' class='btn btn-primary' name="redirection">
					<i class=''></i>Demander un RDV
                </button>
                
                 <?php
					}
			?>		
				
            </div>
        </form>
       </div>
</div>

</div>
<?php quitter1() ?>	

</body>

 
</html>
