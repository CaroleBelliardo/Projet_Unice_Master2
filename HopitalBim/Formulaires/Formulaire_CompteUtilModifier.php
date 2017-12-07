<?php
	
if(isset($_POST['btn-modifierutilisateur']))
{	
	 // ici je pense faire un include de $dep a $adresse tout foutre dans un seul et meme document car c est chiant a regarder 
	$text_pays = strip_tags($_POST['text_pays']);	
	$text_departement = strip_tags($_POST['text_departement']);	
	$text_codepostal = strip_tags($_POST['text_codepostal']);			
	$text_ville = strip_tags($_POST['text_ville']);
	$text_rue = strip_tags($_POST['text_rue']);	
	$text_numero = strip_tags($_POST['text_numero']);	

	$text_nom = strip_tags($_POST['text_nom']);	
	$text_prenom = strip_tags($_POST['text_prenom']);	
	$text_telephone = strip_tags($_POST['text_telephone']);	
	$text_mail = strip_tags($_POST['text_mail']);	
	
	$text_nomService = strip_tags($_POST['text_nomService']);	
	$text_motdepasse = strip_tags($_POST['text_motdepasse']);

		 // Gestion des erreurs : 
	if ($text_nom==""){$error[] = "Il faut un nom !"; }
	else if($text_prenom=="")	{$error[] = "Il faut un prénom !"; }
	else if ($text_motdepasse=="")	{$error[] = "Il faut un mot de passe !"; }
	
		else 
		{
			//Ajout des informations dans la base de donnée :
	
			try
			{
				//creation de l'utilisateur
				$stmt = $auth_user->runQuery("SELECT * FROM Villes 
										WHERE codepostal=:text_codepostal AND nomVilles=:text_ville 
										AND departement=:text_departement AND pays=:text_pays");
				$stmt->execute(array('text_codepostal'=>$text_codepostal, 'text_ville'=>$text_ville, 'text_departement'=>$text_departement, 'text_pays'=>$text_pays));
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
				$BDDidVilles=$row['idVilles'];
				if ($row['codepostal']==$text_codepostal and  $row['nomVilles']==$text_ville and $row['departement']==$text_departement and $row['pays']==$text_pays) 
				{
					// Test de l'adresse
					$stmt = $auth_user->runQuery("SELECT * FROM Adresses 
										WHERE numero=:text_numero AND rue=:text_rue AND VillesidVilles=:BDDidVilles");
					$stmt->execute(array('text_numero'=>$text_numero, 'text_rue'=>$text_rue, 'BDDidVilles'=>$BDDidVilles));
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
					$BDDidAdresse=$row['idAdresse'];
					if ($row['numero']==$text_numero and  $row['rue']==$text_rue and $row['VillesidVilles']==$BDDidVilles )
					{
						$modifierEmployes = $auth_user->runQuery("
																UPDATE Employes 
																SET 
																nom =:text_nom,
																prenom=:text_prenom,
																telephone=:text_telephone,
																mail=:text_mail,
																ServicesnomService=:text_nomService,
																AdressesidAdresse=:BDDidAdresse
																WHERE  CompteUtilisateursidEmploye =:utilisateurModif");
					
						$modifierEmployes->execute(array(":text_nom"=>$text_nom,
														":text_prenom"=>$text_prenom,
														":text_telephone"=>$text_telephone,
														":text_mail"=>$text_mail,
														":text_nomService"=>$text_nomService,
														":BDDidAdresse"=>$BDDidAdresse,
														":utilisateurModif"=>$utilisateurInfo['CompteUtilisateursidEmploye']));						
					}
					else 
					{
						$stmtAdresses = $auth_user->conn->prepare("INSERT INTO Adresses (numero, rue, VillesidVilles) 
											VALUES (:text_numero, :text_rue, :BDDidVilles )");			
						$stmtAdresses->execute(array(":text_numero"=>$text_numero,
													  ":text_rue"=>$text_rue,
													  ":BDDidVilles"=>$BDDidVilles));
						$stmt = $auth_user->runQuery("SELECT * FROM Adresses 
										WHERE numero=:text_numero AND rue=:text_rue AND VillesidVilles=:BDDidVilles");
						$stmt->execute(array('text_numero'=>$text_numero, 'text_rue'=>$text_rue, 'BDDidVilles'=>$BDDidVilles));
						$row=$stmt->fetch(PDO::FETCH_ASSOC);
						$BDDidAdresse=$row['idAdresse'];
						// -- Ajout Employes
					$modifierEmployes = $auth_user->runQuery("
																UPDATE Employes 
																SET 
																nom =:text_nom,
																prenom=:text_prenom,
																telephone=:text_telephone,
																mail=:text_mail,
																ServicesnomService=:text_nomService,
																AdressesidAdresse=:BDDidAdresse
																WHERE  CompteUtilisateursidEmploye =:utilisateurModif");
					
						$modifierEmployes->execute(array(":text_nom"=>$text_nom,
														":text_prenom"=>$text_prenom,
														":text_telephone"=>$text_telephone,
														":text_mail"=>$text_mail,
														":text_nomService"=>$text_nomService,
														":BDDidAdresse"=>$BDDidAdresse,
														":utilisateurModif"=>$utilisateurInfo['CompteUtilisateursidEmploye']));	
					}
				}
				else 
				{
					// -- Ajout dans la table ville 
					$stmtville = $auth_user->conn->prepare("INSERT INTO Villes ( codepostal, nomVilles, departement, pays) 
													VALUES ( :text_codepostal, :text_ville, :text_departement, :text_pays)");	
					$stmtville->execute(array(":text_codepostal"=>$text_codepostal,
											  ":text_ville"=>$text_ville,
											  ":text_departement"=>$text_departement,
											  ":text_pays"=>$text_pays));										  
					$stmt = $auth_user->runQuery("SELECT * FROM Villes 
											WHERE codepostal=:text_codepostal AND nomVilles=:text_ville 
											AND departement=:text_departement AND pays=:text_pays");
					$stmt->execute(array('text_codepostal'=>$text_codepostal, 
										 'text_ville'=>$text_ville, 
										 'text_departement'=>$text_departement, 
										 'text_pays'=>$text_pays));
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
					$BDDidVilles=$row['idVilles'];
					// -- Ajout dans adresse
					$stmtAdresses = $auth_user->conn->prepare("INSERT INTO Adresses (numero, rue, VillesidVilles) 
													VALUES (:text_numero, :text_rue, :BDDidVilles )");	
												
					$stmtAdresses->execute(array('text_numero'=>$text_numero, 
										 'text_rue'=>$text_rue, 
										 'BDDidVilles'=>$BDDidVilles));
					$stmt = $auth_user->runQuery("SELECT * FROM Adresses 
												WHERE numero=:text_numero AND rue=:text_rue AND VillesidVilles=:BDDidVilles");
					$stmt->execute(array('text_numero'=>$text_numero, 'text_rue'=>$text_rue, 'BDDidVilles'=>$BDDidVilles));
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
					$BDDidAdresse=$row['idAdresse'];
					// -- Ajout Employes
		$modifierEmployes = $auth_user->runQuery("
																UPDATE Employes 
																SET 
																nom =:text_nom,
																prenom=:text_prenom,
																telephone=:text_telephone,
																mail=:text_mail,
																ServicesnomService=:text_nomService,
																AdressesidAdresse=:BDDidAdresse
																WHERE  CompteUtilisateursidEmploye =:utilisateurModif");
					
						$modifierEmployes->execute(array(":text_nom"=>$text_nom,
														":text_prenom"=>$text_prenom,
														":text_telephone"=>$text_telephone,
														":text_mail"=>$text_mail,
														":text_nomService"=>$text_nomService,
														":BDDidAdresse"=>$BDDidAdresse,
														":utilisateurModif"=>$utilisateurInfo['CompteUtilisateursidEmploye']));	
				}
			$auth_user->redirect('CompteUtilModifier.php?Valide');
			}
			catch(PDOException $e)
			{			
				echo $e->getMessage();
			}	
		}
		
	

}

	
?>

<div class="containerFormu">
    	
	<form method="post" class="form-signin">

        <h2 class="form-signin-heading">Modifier un utilisateur </h2><hr />

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

            <div id="valide"> 
                Utilisateur modifié avec succés ! <a href='../Pageprincipale.php'>Page principale</a>
            </div>

            <?php
			}
			?>
			
			<p><i>Complétez le formulaire. Les champs marqués par </i><em>*</em> sont <em>obligatoires.</em></p>

            <div class="form-group" >

				<fieldset>
				<legend> Employé </legend> <!-- Titre du fieldset --> 
			
					<label for="text_nom">Nom <em>* </em> </label>
					<input type="text" class="form-control" name="text_nom" pattern="[A-Za-z]{1-25}" title="Caractère alphabétique, 25 caractères maximum"     placeholder="<?php echo $utilisateurInfo['nom'] ;?>" value="<?php if(isset($error)){echo $text_nom;}else {echo $utilisateurInfo['nom'];}?>" /><br>

					<label for="text_prenom">Prénom <em>* </em> </label>
					<input type="text" class="form-control" name="text_prenom" pattern="[A-Za-z]{1-25}" title="Caractère alphabétique, 25 caractères maximum"  placeholder="<?php echo $utilisateurInfo['prenom'] ;?>" value="<?php if(isset($error)){echo $text_prenom;}else {echo $utilisateurInfo['prenom'];}?>" /><br>

					<label for="text_telephone">Téléphone </label>
					<input type="tel" class="form-control" name="text_telephone" pattern="[0-9]{1-15}" title="Caractère numérique, 15 caractères acceptés"    placeholder="<?php echo $utilisateurInfo['telephone'] ;?>" value="<?php if(isset($error)){echo $text_telephone;}else {echo $utilisateurInfo['telephone'];}?>" /><br>

					<label for="text_mail">Mail </label>
					<input type="text" class="form-control" name="text_mail" pattern="{1-60}" title="Caractère numérique, 15 caractères acceptés" placeholder="<?php echo $utilisateurInfo['mail'] ;?>" value="<?php if(isset($error)){echo $text_mail;}else {echo $utilisateurInfo['mail'];}?>" /><br>			

					<label for="text_service"> Service </label>
						<input list="text_service" name="text_service" size='85'>
						<datalist id="text_service">
							<?php liste_Services($auth_user) ?> 
						</datalist> <br>

					<label for="text_motdepasse">Mot de passe <em>* </em> </label>
					<input type="text" class="form-control" name="text_motdepasse" pattern="[A-Za-z]{1-25}" title="Caractère alphabétique, 25 caractères maximum"  placeholder=" xxxxxxxx " value="<?php if(isset($error)){echo $text_motdepasse;}?>" /><br>

				</fieldset> <br>
			
				<fieldset>
				<legend> Adresse employé </legend> <!-- Titre du fieldset --> 
			
					<label for="text_numero">Numéro de la rue </label>
					<input type="number" class="form-control" min="1" name="text_numero" pattern="[0-9]{1-6}" title="Caractère numérique, 6 caractères acceptés" placeholder="<?php echo $utilisateurInfo['numero'] ;?>" value="<?php if(isset($error)){echo $text_numero;}else {echo $utilisateurInfo['numero'];}?>" /><br>	

					<label for="text_numSS">Rue </label>
					<input type="text" class="form-control" name="text_rue"    pattern="[A-Za-z]{1-100}" title="Caractère alphabetique, 100 caractères maximum" placeholder="<?php echo $utilisateurInfo['rue'] ;?>" value="<?php if(isset($error)){echo $text_rue;}else {echo $utilisateurInfo['rue'];}?>" /><br>

					<label for="text_numSS">Ville </label>
					<input type="text" class="form-control" name="text_ville"  pattern="[A-Za-z]{1-150}" title="Caractère alphabetique, 150 caractères maximum" placeholder="<?php echo $utilisateurInfo['nomVilles'] ;?>" value="<?php if(isset($error)){echo $text_ville;}else {echo $utilisateurInfo['nomVilles'];}?>" /><br>

					<label for="text_numSS">Code Postal </label>
					<input type="text" class="form-control" name="text_codepostal" pattern="[0-9]{5}" title="Caractère numérique, 5 caractères maximum"         placeholder="<?php echo $utilisateurInfo['codepostal'] ;?>" value="<?php if(isset($error)){echo $text_codepostal;}else {echo $utilisateurInfo['codepostal'];}?>" /><br>

					<label for="text_numSS">Département </label>
					<input type="text" class="form-control" name="text_departement" pattern="[0-9]{2}" title="Caractère numérique, 5 caractères maximum"        placeholder="<?php echo $utilisateurInfo['departement'] ;?>" value="<?php if(isset($error)){echo $text_departement;}else {echo $utilisateurInfo['departement'];}?>" /><br>

					<label for="text_numSS">Pays </label>
					<input type="text" class="form-control" name="text_pays"   pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"   placeholder="<?php echo $utilisateurInfo['pays'] ;?>" value="<?php if(isset($error)){echo $text_pays;}else {echo $utilisateurInfo['pays'];}?>" /><br>
			
				</fieldset> <br>

			</div> <!-- form-group -->
            
            <div class="form-group">
            	<button type="submit" class="btn btn-primary" name="btn-modifierutilisateur">Valider</button>
            </div>

    </form>

</div> <!-- containerFormu -->

<button class="abandon">
<?php quitter1() ?>	
</button>

<?php include ('../Config/Footer.php'); //menu de navigation ?> <!-- Footer grande page -->

