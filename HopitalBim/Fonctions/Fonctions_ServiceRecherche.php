<p class="" style="margin-top:5px;">
				<div class="signin-form">
					<form method="post" class="form-signin">
								
								<h2 class="form-signin-heading">Facturation </h2><hr />
	<?php
								if(isset($error)) // affichage messages erreurs si valeurs != format attendu
								{
									foreach($error as $error) // pour chaque champs
									{
	?>
										<div class="alert alert-danger">
										<i class=""></i> &nbsp; <?php echo $error; ?>
										</div>.
	<?php
									}
								}
								else if(isset($_GET['Valide'])) // si toutes les valeurs de champs ok et que bouton valider
								{
	?>
									<div class="alert alert-info">
									<i class=""></i> Rendez-vous fixé le (date) à (heure) <a href='../Pageprincipale.php'>Page principale</a>
									<!--insertion fonction pour editer pdf--> 
									</div>
	<?php
								}
	?>
								<!-- Affichage formulaire -->
								<fieldset>
									<legend> Nom et prénom patient </legend> <!-- Titre du fieldset --> 
										<p>
											<!-- Affichage formulaire : moteur recherche-->
											<input list="text_patient" name="text_patient" size='85'> 
											<datalist id="text_patient" >
	<?php 
												$req_patient = $auth_user->runQuery("SELECT numSS, nom, prenom FROM Patients"); // permet de rechercher le nom d utilisateur 
												$req_patient->execute(); // la meme 
												while ($row_patient = $req_patient->fetch(PDO::FETCH_ASSOC))
												{
													echo "<option value='".$row_patient['numSS']."' 
													label='"."(".$row_patient['numSS'].")"." -- ".$row_patient['prenom']." ".$row_patient['nom']."'>".$row_patient['prenom']." ".$row_patient['nom']."</option>";
												}
	?>
											</datalist>
										</br >
										</p>
								</fieldset>					

								
								<!-- bouton validé -->
						</div>
						<div class="clearfix"></div><hr />
						<div class="form-group">
							<button type="submit" class="btn btn-primary" name="btn_facturation">
								<i class=""></i>Valider
							</button>
							<?php quitter1() ?>	
						</div>
					</form>
			</p>