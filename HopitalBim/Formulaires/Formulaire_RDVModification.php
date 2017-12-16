<?php
	$lien ='ServiceModifier.php';

	$req_utilisateur = $auth_user->runQuery("SELECT *
											FROM CreneauxInterventions Join Pathologies
											WHERE  CreneauxInterventions.PathologiesidPatho = Pathologies.idPatho
											AND CreneauxInterventions.id_rdv = :idr");
				
	$req_utilisateur->execute(array("idr"=>$_SESSION['rdvModifier']));
	$utilisateurInfo=$req_utilisateur -> fetch(PDO::FETCH_ASSOC);
	$req_utilisateur->closeCursor();

	
	if(isset($_POST['btn_demandeRDV']))
{
	//traitement des sorti
	$heure=trim($_POST['text_heure'], ' ');
	$date=trim($_POST['text_date'], ' ');
	$patient=trim($_POST['text_patient'], ' ');
	$nomPathologie = ucfirst(trim($_POST['text_nomPathologie'], ' '));	
	$indicationPathologie= trim($_POST['text_indicationPathologie'], ' ');
	$idIntervention = preg_replace("/[^0-9]/", "",trim($_POST['text_idIntervention'], ' '));
	$niveauUrgence = $_POST['text_urgence'];
	$commentaires = $_POST['text_commentaires'];
	// Gestion erreur  : 
	if($heure=="")	{
		$error[] = "Il faut ajouter une heure"; }
	elseif ($date=="")	{
		$error[] = "Il faut ajouter une date"; }
	elseif ($patient=="")	{
		$error[] = "Il faut ajouter un numéro de sécurité sociale pour le patient";}
	elseif ($nomPathologie=="")	{
		$error[] = "Il faut ajouter un nom de pathologie"; }
	elseif ($idIntervention=="")	{
		$error[] = "Il faut ajouter un nom d'intervention"; }	
	else
	{
		$req_patient = $auth_user->runQuery("SELECT numSS FROM Patients WHERE numSS = :numSS");		
		$req_patient->execute(array("numSS"=>$patient));
		$existnumSS=$req_patient -> fetch(PDO::FETCH_ASSOC);
		$req_patient->closeCursor();
		if( $existnumSS == FALSE )
		{
			$error[] = "Il faut saisir un numéro de sécurité sociale valide"; }	
		else
		{
			$req_dateheure = $auth_user->runQuery("SELECT id_rdv FROM CreneauxInterventions WHERE date_rdv=:date and heure_rdv = :heure and statut = 'p'  ");
			$req_dateheure->execute(array('heure'=> $heure,'date'=> $date ));
			$dateheure=$req_dateheure->fetch(PDO::FETCH_ASSOC);
			$req_dateheure->closeCursor();
			if( $dateheure != FALSE )
			{
				$error[] = "Ce créneaux est déjà occupé par un autre rendez-vous, veuillez modifier la date ou l'heure"; }	
			else
			{
				$req_interv = $auth_user->runQuery("SELECT idIntervention FROM Interventions WHERE idIntervention=:idIntervention ");
				$req_interv->execute(array('idIntervention'=> $idIntervention));
				$interv=$req_interv->fetch(PDO::FETCH_ASSOC);
				$req_interv->closeCursor();
				if( $interv == FALSE )
				{
					$error[] = "Il faut saisir un nom d'intervention valide"; }	
				else
				{
					$req_patho = $auth_user->runQuery("SELECT idPatho FROM Pathologies WHERE nomPathologie = :nomPatho and indication = :indic ");
					$req_patho->execute(array('nomPatho'=> $nomPathologie,'indic'=> $indicationPathologie));
					$a_idPatho=$req_patho->fetch(PDO::FETCH_ASSOC);
					$req_patho->closeCursor();
					if ($a_idPatho == FALSE)
					{
						// on enregistre
						$req_pathoID = $auth_user->runQuery("SELECT MAX(idPatho)+1 FROM Pathologies ");
						$req_pathoID->execute();
						$idPatho=$req_pathoID->fetchColumn();
						$req_pathoID->closeCursor();

						
						$ajoutPatho = $auth_user->runQuery("INSERT INTO Pathologies (idPatho, nomPathologie, indication) 
										VALUES (:idPatho, :nomPathologie, :indication)");
						$ajoutPatho->execute(array('idPatho'=> $idPatho,
												'nomPathologie'=>$nomPathologie,
												'indication'=> $indicationPathologie));
						$ajoutPatho->closeCursor();
					}
					else
					{
						$idPatho= $a_idPatho["idPatho"];
					}
					$req_ajouter= $auth_user->runQuery(
					"INSERT INTO CreneauxInterventions (date_rdv,heure_rdv, InterventionsidIntervention, niveauUrgence, PathologiesidPatho, commentaires, PatientsnumSS,EmployesCompteUtilisateursidEmploye) 
					VALUES (:date_rdv, :heure_rdv, :InterventionsidIntervention, :niveauUrgence, :pathologie, :commentaires, :PatientsnumSS, :UtilisateurActuel)");
					$req_ajouter->execute(array('date_rdv'=> $date,
										'heure_rdv'=> $heure,
										'InterventionsidIntervention'=> $idIntervention,
										'niveauUrgence'=> $niveauUrgence,
										'pathologie'=> $idPatho, 
										'commentaires'=> $commentaires,
										'PatientsnumSS'=> $patient,
										':UtilisateurActuel'=>$utilisateurInfo['EmployesCompteUtilisateursidEmploye']));
					
					

				// recherche s'il y a des rdv annulé au nouvel horaire : met a jour la valeur du statut
					$req_rdvAnnule = $auth_user-> runQuery(" SELECT CreneauxInterventions.id_rdv
															FROM CreneauxInterventions
															WHERE statut ='a'
															AND date_rdv = :date
															AND heure_rdv = :heure
															AND InterventionsidIntervention = :idInt
															");
					$req_rdvAnnule->execute(array('date'=>$date,
												   'heure'=>$heure,
												   'idInt'=>$idIntervention
												   ));
					$existAnnule= $req_rdvAnnule-> fetchColumn();
					$req_rdvAnnule->closeCursor();
					if ($existAnnule != "" )
					{
						$req_modifAn = $auth_user->runQuery("UPDATE CreneauxInterventions
															SET
																statut= 's'
																WHERE id_rdv=:idRDV");

						$req_modifAn->execute(array('idRDV'=>$existAnnule));
						$req_modifAn->closeCursor();
					}					
							
							
				//	met a jour la valeur de l'ancien rdv
					$req_modifRDV = $auth_user->runQuery("UPDATE CreneauxInterventions
															SET
																statut= 'a'
																WHERE id_rdv=:idRDV");

					$req_modifRDV->execute(array('idRDV'=>$utilisateurInfo['id_rdv']));
					$req_modifRDV->closeCursor();
				}
			}
		}

	}
	$auth_user->redirect('RDVModification.php?Valide');
}
?>

 <!--Formulaire-->

<div class="containerFormu">

	<form method="post" class="form-signin">

		<h2 class="form-signin-heading">Demande de rendez-vour le patient <?php echo $utilisateurInfo["PatientsnumSS"]; ?></h2><hr /> <!--nom patient !!!!!!!!-->
					
			<?php
			if(isset($error)) // affichage messages erreurs si valeurs != format attendu
			{
			foreach($error as $error) // pour chaque champs
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
				La modification a été effectuée avec succès <a href='../Pageprincipale.php'>Page principale</a>
			</div>

<!-- test -->
<?php
	}
?>

		<div class="form-group" >
		<fieldset>
		<legend> Patient </legend> <!-- Titre du fieldset --> 
										
					<!-- Affichage formulaire : moteur recherche du patient-->
					<label for="text_patient"> Numéro sécu </label>
					<input list="text_patient" name="text_patient" size='85' placeholder="<?php echo $utilisateurInfo["PatientsnumSS"] ;?>" value="<?php if(isset($error)){echo $patient;}else {echo $utilisateurInfo["PatientsnumSS"];}?>">
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
				
		</fieldset>	</br >

		<fieldset>
		<legend> Créneau rendez-vous </legend> <!-- Titre du fieldset --> 
							
			<label for="text_date">Date </label>
			<input type="date" class="form-control" name="text_date" value="<?php if(isset($error)){echo $date;}else {echo $utilisateurInfo['date_rdv'];}?>" /><br>
										
			<label for="text_heure">Heure </label>
			<input type="time" class="form-control" name="text_heure" value="<?php if(isset($error)){echo $heure;}else {echo $utilisateurInfo['heure_rdv'];}?>" /><br>
				
		</fieldset>	<br>
		
		<fieldset>
		<legend> Pathologie du patient </legend> <!-- Titre du fieldset --> 
			
			<label for="text_nomPathologie">Pathologie </label>				
			<input type="text" class="" name="text_nomPathologie"  pattern="{1-100}" title="Caractère alphabétique, 100 caractères maximum"  placeholder=" <?php echo $utilisateurInfo["nomPathologie"] ;?>"  value="<?php if(isset($error)){echo $nomPathologie;}else {echo $utilisateurInfo['nomPathologie'];}?>" /> <br>

			<label for="text_indicationPathologie"> Indications </label>		
			<input type="text" class="" name="text_indicationPathologie" pattern="{0-30}" title="Caractère alphabetique, 30 caractères maximum"       placeholder=" <?php echo $utilisateurInfo["indication"] ;?>" value="<?php if(isset($error)){echo $indicationPathologie;}else {echo $utilisateurInfo['indication'];}?>" /><br>

		</fieldset>	<br>

		<fieldset>
		<legend> Intervention demandée </legend> <!-- Titre du fieldset --> 
							
			<!-- Affichage formulaire : moteur recherche-->
			<label for="text_idIntervention">Identifiant </label>
			<input list="text_idIntervention" name="text_idIntervention" size='35' placeholder="<?php echo $utilisateurInfo["InterventionsidIntervention"] ;?>" value="<?php if(isset($error)){echo $idIntervention;}else {echo $utilisateurInfo["InterventionsidIntervention"];}?>"> 
				
				<datalist id="text_idIntervention"  >
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

								<label   class="form-control" > Niveau d'urgence   </label>    
									<input type="radio"  name="text_urgence" value="0" checked="checked"/>0
									<input type="radio"  name="text_urgence" value="1"/>1
									<input type="radio"  name="text_urgence" value="2" />2
									<input type="radio"  name="text_urgence" value="3" />3 	
							
					</fieldset> <br>

					<fieldset>
						<legend> Commentaires </legend> <!-- Titre du fieldset --> 
							
								<textarea type="text" class="" name="text_commentaires"   placeholder=" <?php echo $utilisateurInfo["commentaires"] ;?>" value="<?php if(isset($error)){echo $commentaires;}else {echo $utilisateurInfo['commentaires'];}?>"></textarea><br>
							
						
		</fieldset>	<br>
							
		</div> <!-- form group -->

				<div class="form-group">
					<button type="submit" class="btn btn-primary" name="btn_demandeRDV"> 
						Valider
					</button>
				</div>

		</form>
</div>

		<?php 
		quitter1();
		?>