<!--TODO :
- Supprimer la requete extractReq --=extractReq2
- requete = tableau
- nomattribut = creneau.incomp
- rendez-vous annulés 
- ajour header 
-->
<?php
	include ('../Config/Menupage.php'); //menu de navigation
	include ('../Fonctions/Fonctions_planning.php'); //menu de navigation

	$lien= 'Planning.php'; // redirection
	
	$a_ref=infoPlanning ($auth_user,$a_utilisateur);
	$a_heures = $a_ref['heure'];
	$a_idActes = $a_ref['actes'];
	$infoServiceJours = $a_ref['info'];

	if (isset($_POST['btn-supp'])=="suppr_rdv")
	{
	//substr($_POST['btn-supp'],-15);
	//$req_supprimerRDV = $auth_user->runQuery("UPDATE CreneauxInterventions 
	//										SET statut = 'a'
	//										WHERE idCreneau=:$num");
	//$req_supprimerRDV->execute(array('idCreneau'=>$_POST["idCrenaux_supp"]));
	$auth_user->redirect('Planning.php');
	}
		if (isset ($_POST["btn-Annuler"]))
	{
		/*echo $_POST["btn-Annuler"];//ajouter la requette avec $idRDV le id du rdv
		$req_annulerRDV = $auth_user-> runQuery(" UPDATE CreneauxInterventions
													SET statut = 'a' 
													WHERE id_rdv =:rdvannulation");
		$req_annulerRDV->execute(array('rdvannulation'=>$_POST["btn-Annuler"])); */
		$auth_user->redirect('Planning.php?DemandeAnnuler');

	}


// **************************  AFFICHAGE PAGE ********************************************   

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="Style.css" type="text/css"  />  
		<title>Bonjour</title>
	</head>
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
	else if(isset($_GET['Annuler'])) // si toutes les valeurs de champs ok et que bouton valider
	{
	?>
	<div class="alert alert-info">
	<i class=""></i> Voulez vous annuler le RDV ? </br>
	</br><button type='submit' class='btn btn-primary' value="oui" name='btn-annuler-confirmation'>Oui</button>;
	<button type='submit' class='btn btn-primary' value="non" name='btn-annuler-confirmation'>Non</button>;
	
	<?php if (isset($_POST['btn-annuler-confirmation'])=="oui")
	{
	substr($_POST['btn-supp'],-15);
	$req_supprimerRDV = $auth_user->runQuery("UPDATE CreneauxInterventions 
											SET statut = 'a'
											WHERE idCreneau=:$num");
	$req_supprimerRDV->execute(array('idCreneau'=>$_POST["idCrenaux_supp"]));
	$auth_user->redirect('Planning.php');
	}
	if (isset ($_POST["btn-annuler-confirmation"])=="non")
	{
		$auth_user->redirect('Planning.php');

	}?>
	
	</div>
	<?php
	}
	?>
							

	<body>
	<?php include ("../Formulaires/RechercheServiceDate.php"); ?>	
		 
	<br> 
	 
		<CENTER> <table  BORDER="1",ALIGN="CENTER", VALIGN="MIDDLE " >
		 <tr><th>Heure</th>
		 <?php 
			 foreach ($a_idActes as $idx=>$acte) // affichage de l'en-tete
			 {
		 ?>
				 <th> <?php echo $acte ?></th> 
		 <?php
			 }
		 ?>
		 </tr>	
		 <?php							// affichage du tableau
			 foreach ($a_heures as $idx=>$h) // Pour chaque ligne
			 {
		 ?>
			 <tr><th><?php echo $h ?></th> 
		 <?php 
				 foreach ($a_idActes as $idx=>$acte) // Pou chaque colonne
				 {
					 if (array_key_exists($h,$infoServiceJours))
					 {
						 if (array_key_exists($acte,$infoServiceJours[$h]))
						 {
		 ?>
					 <td class= $infoServiceJours[$h][$acte]["statut"]>
											<form method="post" >

		 <?php
						
						//$num=$infoServiceJours[$h][$acte]["id_rdv"];
						//$tempo="<input name='suppr_rdv' value=$num type='submit'>";
						//echo $tempo;
						echo $infoServiceJours[$h][$acte]["nom"]." ".$infoServiceJours[$h][$acte]["prenom"]."\n".$infoServiceJours[$h][$acte]["numSS"]."\n";
						echo "</br><button type='submit' class='btn btn-primary' value=".$infoServiceJours[$h][$acte]["id_rdv"]." name='btn-realiser'>RDV annulé</button>";
						echo "<button type='submit' class='btn btn-primary' value=".$infoServiceJours[$h][$acte]["id_rdv"]." name='btn-Annuler'>RDV réalisé</button>";
		?>				
						
						
			<?php			
						
						
						
							}
						else
						{
		 ?>
							 <td class="pasRDV">
		 <?php
							 echo "Libre";
						}
					 }
					 else
					 {
		 ?>
					 <td class="pasRDV">
		 <?php
					 echo "Libre";
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
		 <?php 	quitter1()	?>	
	
		 
<div id="footer"> <!-- Faire les liens vers les documents  -->
    <a href="<?php echo $LienSite ?>Pages/readme.php"> Conditions d'utilisation </a> |
    <a href="<?php echo $LienSite ?>Pages/contact.php"> Contact </a> | © 2017
    </div>		 
		
	</body>
</html>



