<?php // action quand valide le formulaire 
	if(isset($_POST['btn-ajoutActe']))
	{
		$text_nomActe = ucfirst(trim($_POST['text_nomActe'], ' '));
		$text_tarif = preg_replace("/[^0-9]/", "",trim($_POST['text_tarif'], ' '));
	// Gestion des erreurs : 
		if ($text_nomActe==""){$error[] = "Il faut un entrer le nom de l'acte !"; }
		
		elseif (($text_tarif=="")or (preg_match('/[0-9]+/',$text_tarif) == 0))
			{$error[] = "Il faut un entrer un tarif valide!"; }	
		else 
		{ 
			$req_existeActe = $auth_user->runQuery("SELECT idIntervention 
							FROM Interventions
							WHERE acte = :acte
							AND ServicesnomService = :nomService");
			$req_existeActe->execute(array("nomService"=>$_SESSION['serviceModifier'],
										"acte"=>$text_nomActe));
			$existeActe=$req_existeActe-> fetch(PDO::FETCH_ASSOC);
			if ($existeActe != FALSE){$error[] = "Cet acte existe déjà pour ce service !"; }
			else
			{
				$req_insertActe = $auth_user->runQuery("INSERT INTO Interventions (acte, ServicesnomService) 
									VALUE ( :acte, :nomService)");
				$req_insertActe->execute(array('acte'=>$text_nomActe,
								'nomService'=>$_SESSION['serviceModifier']));
				$req_insertActe->closeCursor();
				
				$req_idLastActe = $auth_user->runQuery("SELECT MAX(idIntervention) FROM Interventions ");
				$req_idLastActe->execute();
				$idLastActe = $req_idLastActe -> fetchColumn();
				$req_idLastActe->closeCursor();
				
				$req_insertActeTarif = $auth_user->runQuery("INSERT INTO Tarifications (InterventionsidIntervention, tarif_euros) 
									VALUE ( :id, :tarif)");
				$req_insertActeTarif->execute(array('id'=>$idLastActe,
								'tarif'=> floatval($text_tarif)));
				$req_insertActeTarif->closeCursor();
				$auth_user->redirect('ActeCreer.php?Valide');
			}
		}
	}
?>
<!--mise en page du formulaire-->
<div class="containerFormu">
        <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Ajouter un nouvel acte médical</h2> <hr />
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
		<div id="valide"> <!-- Alert alert-info-->
		     L'acte médical a été ajouté avec succés ! <a href='../Pageprincipale.php'> Page principale </a>
		</div>
        <?php
		}
	?>
		<p><i>Complétez le formulaire. Les champs marqués par </i><em>*</em> sont <em>obligatoires.</em></p>
		<div class="form-group">
			<fieldset>
			<legend> L'acte médical pour le service <?php echo $_SESSION['serviceModifier'] ?> </legend> <!-- Titre du fieldset --> 

				<label for="text_nomActe">Nom de l'acte <em>* </em> </label>
				<input type="text" class="form-control" name="text_nomActe" pattern="[A-Za-z]{1-35}" title="Majuscule en première lettre"        placeholder="Acte" value="<?php if(isset($error)){echo $text_nomActe;}?>" /><br>

				<label for="text_tarif">Tarif (en €)  <em>* </em></label>
				<input type="tel" class="form-control" name="text_tarif" pattern="[0-9]{1-10}" title="Charactere numerique uniquement"    placeholder=" " value="<?php if(isset($error)){echo $text_tarif;}?>" /><br>
			</fieldset> <br>
		</div> <!-- form-group  Formulaire principal --> 

		<div class="form-group">
		    <button type="submit" class="btn btn-primary" name="btn-ajoutActe">Valider</button>
		</div> <!-- form-group  Bouton Valider -->
        </form> <!-- form-signin -->

</div> <!-- containerFormu -->


<?php quitter1() ?>

