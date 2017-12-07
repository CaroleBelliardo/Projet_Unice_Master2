<?php
	$lien ='ServiceModifier.php';

	$req_utilisateur = $auth_user->runQuery("SELECT CreneauxInterventions.date_rdv,  CreneauxInterventions.heure_rdv,
											 CreneauxInterventions.niveauUrgence, CreneauxInterventions.PatientsnumSS,
											Pathologies.nomPathologie, Pathologies.indication,
											CreneauxInterventions.commentaires, CreneauxInterventions.InterventionsidIntervention
											
											FROM CreneauxInterventions Join Pathologies
											WHERE  CreneauxInterventions.PathologiesidPatho = Pathologies.idPatho
											AND CreneauxInterventions.id_rdv = :idr");
				
	$req_utilisateur->execute(array("idr"=>$_SESSION['rdvModifier']));
	$utilisateurInfo=$req_utilisateur -> fetch(PDO::FETCH_ASSOC);
	$req_utilisateur->closeCursor();
	
	
	if(isset($_POST['btn-modifier']))
{
	

	
	//traitement des sorti
	$heure=trim($_POST['text_heure'], ' ');
	$date=trim($_POST['text_date'], ' ');
	$patient=trim($_POST['text_numSS'], ' ');
	$nomPathologie = ucfirst(trim($_POST['text_nomPathologie'], ' '));	
	$indicationPathologie= trim($_POST['text_indicationPathologie'], ' ');
	$idIntervention = preg_replace("/[^0-9]/", "",trim($_POST['text_idIntervention'], ' '));
	$niveauUrgence = trim($_POST['text_urgence'], ' ');
	$commentaires = trim($_POST['text_commentaires'], ' ');
	
	
	// Gestion erreur  : 
	if($heure=="")	{
		$error[] = "Il faut ajouter une heure"; }
	elseif ($date=="")	{
		$error[] = "Il faut ajouter une date"; }
	elseif ($patient=="")	{
		$error[] = "Il faut ajouter un numero le securité sociale du patient";}
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
			$error[] = "Il faut saisir un numéro de sécurité socile valide"; }	
		else
		{
			$req_dateheure = $auth_user->runQuery("SELECT id_rdv FROM CreneauxInterventions WHERE date_rdv=:date and heure_rdv = :heure and statut = 'p'  ");
			$req_dateheure->execute(array('heure_rdv'=> $heure,'date_rdv'=> $date ));
			$dateheure=$req_dateheure->fetch(PDO::FETCH_ASSOC);
			$req_dateheure->closeCursor();
			if( $existnumSS == FALSE )
			{
				$error[] = "Ce créneaux est déjà occupé par un autre rendez-vous, il faut modifier la date ou l'heure"; }	
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
					$req_patho = $auth_user->runQuery("SELECT idPathologie FROM Pathologies WHERE nomPathologie = :nomPatho and indication = :indic ");
					$req_patho->execute(array('nomPatho'=> $nomPathologie,'indic'=> $indicationPathologie));
					$idPatho=$req_patho->fetch(PDO::FETCH_ASSOC);
					$a_idPatho->closeCursor();
					if ($patho == FALSE)
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
						$idPatho= $a_idPatho["idPathologie"];
					}
					
					$req_modifRDV = $auth_user->runQuery("UPDATE CreneauxInterventions
													SET
														date_rdv= :date_rdv,
														heure_rdv= :heure_rdv,
														InterventionsidIntervention= :InterventionsidIntervention,
														niveauUrgence= :niveauUrgence,
														PathologiesidPatho= :pathologie,
														commentaires= :commentaires,
														PatientsnumSS= :PatientsnumSS");

					$req_modifRDV->execute(array('date_rdv'=> $date,
										'heure_rdv'=> $heure,
										'InterventionsidIntervention'=> $idIntervention,
										'niveauUrgence'=> $niveauUrgence,
										'pathologie'=> $idPatho, 
										'commentaires'=> $commentaires,
										'PatientsnumSS'=> $patient));
					$req_modifRDV->closeCursor();
				}
			}
		}

	}
}
?>

 <!--Formulaire-->
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
					}Dumper($utilisateurInfo);
?>
							
					<fieldset>
				<legend> Nom et prénom patient </legend> <!-- Titre du fieldset --> 
										
					<!-- Affichage formulaire : moteur recherche du patient-->
					<label for="text_patient"> Patient </label>
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
				
				</fieldset>	</br >
				<fieldset>
						<legend> Pathologie du patient </legend> <!-- Titre du fieldset --> 
							<p>
							<label for="text_date">Date </label>
							<input type="date" class="form-control" name="text_date" value="<?php if(isset($error)){echo $date;}else {echo $utilisateurInfo['date_rdv'];}?>" /><br>
										
							<label for="text_heure">Heure </label>
							<input type="time" class="form-control" name="text_heure" value="<?php if(isset($error)){echo $heure;}else {echo $utilisateurInfo['heure_rdv'];}?>" /><br>
			

							</p>
					</fieldset>	
					<fieldset>
						<legend> Pathologie du patient </legend> <!-- Titre du fieldset --> 
							<p>
							<input type="text" class="" name="text_nomPathologie"  pattern="[a-zA-Z]{1-100}" title="Caractère alphabetique, 100 caractères maximum"  placeholder=" <?php $utilisateurInfo["nomPathologie"] ;?>"  value="<?php if(isset($error)){echo $utilisateurInfo["nomPathologie"] ;}?>" /><br><br>
							<input type="text" class="" name="text_indicationPathologie" pattern="[a-zA-Z]{0-30}" title="Caractère alphabetique, 30 caractères maximum"       placeholder=" <?php echo $utilisateurInfo["indication"] ;?>" value="<?php if(isset($error)){echo $indicationPathologie;}?>" /><br><br>
 
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
									//while ($row_serviceacte = $req_serviceacte->fetch(PDO::FETCH_ASSOC))
									//{
									//	echo "<option label='".$row_serviceacte['acte']." ".$row_serviceacte['ServicesnomService']."' 
									//	value='"."(".$row_serviceacte['idIntervention'].")"."  ".$row_serviceacte['acte']." -- ".$row_serviceacte['ServicesnomService']."'>".$row_serviceacte['acte']." ".$row_serviceacte['ServicesnomService']."</option>" placeholder="<?php echo $utilisateurInfo['idIntervention'] ;?>";
									//
									//}
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
								<textarea type="text" class="" name="text_commentaires"   value="<?php if(isset($error)){echo $commentaires;}?>" ></textarea><br>
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

