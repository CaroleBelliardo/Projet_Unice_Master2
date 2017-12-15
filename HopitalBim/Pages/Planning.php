<!--TODO :
- Supprimer la requete extractReq --=extractReq2
- requete = tableau
- nomattribut = creneau.incomp
- rendez-vous annulés 
- ajout header 
-->
<?php
	include ('../Config/Menupage.php'); //menu de navigation
	include ('../Fonctions/Fonctions_planning.php'); // fonctions spécifiques à la page planning

	if (!array_key_exists("dateModifier",$_SESSION)) // si aucun jour selectionner on attribut le jour d'aujourd hui
	{
		$_SESSION["dateModifier"] = date("Y-m-d");
	}
	$lien= 'Planning.php'; // valeur pour redirection
	
	$dateCourant = date("Y-m-d");
	$heureCourante = date("H:i:s");
	$a_ref=infoPlanning ($auth_user,$a_utilisateur,$dateCourant,$heureCourante); // recuperation des info du jour pour le service
	$a_heures = $a_ref['heure']; // reaffectation des variables pour simplifier la manipulation des données 
	$a_idActes = $a_ref['actes'];
	$infoServiceJours = $a_ref['info'];
	unset ($a_ref); 
	
	if (isset($_POST['btn-supp'])=="suppr_rdv")
	{
	//substr($_POST['btn-supp'],-15);
	//$req_supprimerRDV = $auth_user->runQuery("UPDATE CreneauxInterventions 
	//										SET statut = 'a'
	//										WHERE idCreneau=:$num");
	//$req_supprimerRDV->execute(array('idCreneau'=>$_POST["idCrenaux_supp"]));
	//$auth_user->redirect('Planning.php');
	echo ' Rendez-vous supprimé';
	}
	
	if (isset ($_POST["btn-AnnulerOui"]))
	{
		$req_annulerRDV = $auth_user-> runQuery(" UPDATE CreneauxInterventions
													SET statut = 'a' 
													WHERE id_rdv =:rdvannulation");
		$req_annulerRDV->execute(array('rdvannulation'=>$_POST["btn-AnnulerOui"]));
		$auth_user->redirect('Planning.php?Suppression');

	}
	if (isset ($_POST["btn-AnnulerNon"]))
	{
		$auth_user->redirect('Planning.php');
	}
	if (isset ($_POST["btn-Realise"]))
	{
		$req_realiseRDV = $auth_user-> runQuery(" UPDATE CreneauxInterventions
													SET statut = 'r' 
													WHERE id_rdv =:rdvRealise");
		$req_realiseRDV->execute(array('rdvRealise'=>$_POST["btn-Realise"]));
		$auth_user->redirect('Planning.php?Valide');
	}

if (isset ($_POST["btn-Modifier"]))
	{
		$_SESSION['rdvModifier']= $_POST["btn-Modifier"];
		$auth_user->redirect('RDVModification.php');
	}
	

// **************************  AFFICHAGE PAGE ********************************************   

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Planning</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="Style.css" type="text/css" />  
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
	</head>

	<body>
					
		<?php include ("../Formulaires/RechercheServiceDate.php"); ?>	
		<br>
		
	 	<div class="containerTab">

			<CENTER><table id="synthese" border="1", ALIGN="CENTER", VALIGN="MIDDLE " >
			<caption> Planning de service </caption>

			<tr>

				<th class="haut">Heure</th>
		 
		 		<?php 
					foreach ($a_idActes as $idx=>$acte) // affichage de l'en-tete
			 		{
		 		?>
				 
				<th class="haut"> <?php echo $acte ?></th> 

		 		<?php
				 	}
				?>
		 
		 	</tr>

	<?php	
			 	foreach ($a_heures as $idx=>$h) // Pour chaque creneaux composant la journée de travail du service
			 	{
	?>
			<tr>
				<th class="colonne"> <?php echo $h ?> </th> 
	<?php 
				 	foreach ($a_idActes as $idx=>$acte) // Pour chaque intervention proposée par le service
				 	{
					 if (array_key_exists($h,$infoServiceJours)) // test si il existe un rdv l'heure parcourue par la boucle :  $h
					 {
						 if (array_key_exists($acte,$infoServiceJours[$h])) // test si il existe un rdv pour l'intervention parcourue par la boucle :  $acte
						 {
	?>
							<td >  <!--creation d'une colonne contenant les info du rdv -->
							<form method="post" >
							<?php echo $infoServiceJours[$h][$acte]["nom"]." ".$infoServiceJours[$h][$acte]["prenom"]."</br>";
							
							$NU = $infoServiceJours[$h][$acte]["niveauUrgence"]; // mise en page selon niveau d'urgence
							if ($NU==0)
							{ 
	?> 	 
								<div class="NU_0">
								NU : 0 
								</div>
	<?php 
							} 
							elseif ($NU==1) {
	?>
								<div class="NU_1">
								NU : 1 
								</div>
	<?php 
							}
							elseif ($NU==2) {
	?>
								<div class="NU_2">
								NU : 2
								</div> 
	<?php
	}
							elseif ($NU ==3) {
	?>
								<div class="NU_3">
								NU : 3
								</div>
	<?php 
							}
	?>
	<?php 
							$Statut = $infoServiceJours[$h][$acte]["statut"]; // mise en page info statut du rdv
							if (($Statut == 'p') or ($Statut == 'm')) {
	?>
							<div class="Statut_p">
							Prévue
							</div>
	<?php 
	}
							elseif ($Statut== 'r') {
	?>
							<div class="Statut_r">
							Réalisée
							</div>
	<?php } 
							elseif ($Statut== 'f') {
	?>
								<div class="Statut_r">
								Facturé
								</div>
	<?php					}
	?>
						<?php
						//bouton modifier -- annuler
							// s'affiche si l'utilisateur est chef du service consulté ou la personne qui a effectué la requete
							// et si si la date d'auj < date rdv
							If ((( $_SESSION["chefService"] == 'TRUE') and ($_SESSION["service"] == $_SESSION['servicePlanning'] )) // test si chef du service
								or ($infoServiceJours[$h][$acte]["idEmploye"] == $_SESSION["idEmploye"] )) // ou si utilisateur a demandé rdv
							{
								
								if (( $dateCourant < $_SESSION["dateModifier"]) // jour précédent
								or  (( $dateCourant >= $_SESSION["dateModifier"])  and ( $heureCourante <= $h ))) // jour actuel mais heure précédent
								{
									echo "<button type='submit' class='btn btn-primary' value=".$infoServiceJours[$h][$acte]["id_rdv"]." name='btn-Modifier'>   M   </button>";
									echo "<button type='submit' class='btn btn-primary' value='' name='btn-Annuler".$infoServiceJours[$h][$acte]["id_rdv"]."'>X</button>";						
									if (isset ($_POST["btn-Annuler".$infoServiceJours[$h][$acte]["id_rdv"]]))
									{
										echo "<br> Annuler le RDV ? <br>"; //ajouter la requette avec $idRDV le id du rdv
										echo "<button type='submit' class='btn btn-primary' value=".$infoServiceJours[$h][$acte]["id_rdv"]." name='btn-AnnulerOui'>Oui</button>";
										echo "<button type='submit' class='btn btn-primary' value='' name='btn-AnnulerNon'>Non</button>";
									}
								}
							}
						// bouton pour confirmer que l'acte a été réalisé et pourra alors etre facturé dans l'onglet facturation du chef du service
							// s'afiche si la date d'auj > date rdv, si la date = et heure actuel >= heure du rdv, et si le statut du rdv est "prévu"
							if ($infoServiceJours[$h][$acte]["statut"] != 'r' )
							{
								if (( $dateCourant > $_SESSION["dateModifier"]) or (( $dateCourant == $_SESSION["dateModifier"]) and ( $heureCourante >= $h )))
								{
								// si le rdv est passé :
									// la page consultée est celle d'un jour précédent ou si la page est celle du jour actuel et que l'heure est = ou  < à l'heure actuelle
									echo "<button type='submit' class='btn btn-primary' value=".$infoServiceJours[$h][$acte]["id_rdv"]." name='btn-Realise'>R</button>";
								}					
							}
						}
						else
						{
						?>
				<td class="pasRDV">
		 
		 			<?php echo "Libre";
							}
					 		}
							else
					 		{
		 			?>
				<td class="pasRDV">
		 			<?php echo "Libre";
							 }
		 			?>
				</td>
		 		<?php
				 	} 
		 		?>	
			</tr>
		 	<?php
			 	} 
			?>
			</table></CENTER>
		</div>
			<?php quitter1() // bouton abandon redirection Page principale ?>  
		<?php include ('../Config/Footer.php'); // pied de page ?>
	</body>
</html>



