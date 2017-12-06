 <!--Formulaire-->
 <p class="" style="margin-top:5px;">
			<div class="signin-form">
				<form method="post" class="form-signin">
							<h2 class="form-signin-heading">Demande de rendez-vour le patient <?php echo $_SESSION["patient"]; ?></h2><hr /> <!--nom patient !!!!!!!!-->
							<?php
							if(isset($error)) // affichage messages erreurs si valeurs != format attendu
							{
								foreach($error as $error) // pour chaque champs
								{
?>
									<div class="alert alert-danger">
									<i class=""></i> &nbsp; <?php echo $error; ?>
									</div>
<?php
								}
							}
							else if(isset($_GET['Valide'])) // si toutes les valeurs de champs ok et que bouton valider
							{
?>
								<div class="alert alert-info">
								<i class=""></i> Rendez-vous fixé le (date) à (heure) <a href='../Pageprincipale.php'>Page principale</a>
								</div>
<?php
							}
?>
							
							
							<!-- Affichage formulaire -->
							<fieldset>
								<legend> Pathologie du patient </legend> <!-- Titre du fieldset --> 
									<p>
									<input type="text" class="" name="text_nomPathologie"  pattern="[a-zA-Z]{1-100}" title="Caractère alphabetique, 100 caractères maximum"  placeholder="Entrer le nom de la pathologie :" value="<?php if(isset($error)){echo $nomPathologie;}?>" /><br><br>
									<input type="text" class="" name="text_indicationPathologie" pattern="[a-zA-Z]{0-30}" title="Caractère alphabetique, 30 caractères maximum"       placeholder="Entrer les indactions :" value="<?php if(isset($error)){echo $indicationPathologie;}?>" /><br><br>
		 
									</p>
							</fieldset>
							<fieldset>
								<legend> Intervention demandée </legend> <!-- Titre du fieldset --> 
									<p>
										<!-- Affichage formulaire : moteur recherche-->
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
										</datalist>
										</br >
		
										<label   class="form-control" > Niveau d'urgence :&nbsp;&nbsp;      
											<input type="radio"  name="text_urgence" value="0" checked="checked"/>0
											<input type="radio"  name="text_urgence" value="1"/>1
											<input type="radio"  name="text_urgence" value="2" />2
											<input type="radio"  name="text_urgence" value="3" />3
										</label><br><br>		
<!--	Attribut supprimé de la table	<input type="text" class="" name="text_indicationIntervention" pattern="[a-zA-Z]{0-30}" title="Caractère alphabetique, 30 caractères maximum"       placeholder="Entrer les indactions :" value="<?php if(isset($error)){echo $text_indicationIntervention;}?>" /><br>								
-->									</p>
							</fieldset>
							<fieldset>
								<legend> Commentaires </legend> <!-- Titre du fieldset --> 
									<p>
										<textarea type="text" class="" name="text_commentaires"   value="<?php if(isset($error)){echo $text_commentaires;}?>" ></textarea><br>
									</p>
								
							</fieldset>
							
							
							
							
							<!-- bouton validé -->
					</div>
					<div class="clearfix"></div><hr />
					<div class="form-group">
						<button type="submit" class="btn btn-primary" name="btn_demandeRDV">
							<i class=""></i>Valider
						</button>
					<?php quitter1() ?>	
					</div>
				</form>

