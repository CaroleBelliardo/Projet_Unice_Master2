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

	if (!array_key_exists("dateModifier",$_SESSION))
	{
		$_SESSION["dateModifier"] = date("Y-m-d");
	}
	$lien= 'Planning.php'; // redirection
	
	$dateCourant = date("Y-m-d");
	$heureCourante = date("H:i:s");
	$a_ref=infoPlanning ($auth_user,$a_utilisateur,$dateCourant,$heureCourante);
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
		$_SESSION['idRDV']= $_POST["btn-Modifier"];
		$auth_user->redirect('RDVModification.php');
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
	 if(isset($_GET['Valide']))
	{
				 	?>
                 <div id="valide"> <!-- Alert alert-info-->
                      L'intervention a été réalisée !
					  <a href='../Pages/RDVDemande.php'>Demande de rendez-vous avec ce nouveau patient ?</a>
                 </div>
                 <?php
	}
	if(isset($_GET['?Suppression']))
			{
				 	?>
                 <div id="valide"> <!-- Alert alert-info-->
                    Le RDV a été annulé !
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
							echo $infoServiceJours[$h][$acte]["nom"]." ".$infoServiceJours[$h][$acte]["prenom"]."</br>";
							
							//bouton modifier -- annuler
							// s'affiche si l'utilisateur est chef du service consulté ou la personne qui a effectué la requete
							// et si si la date d'auj < date consultée
							If ((( $_SESSION["chefService"] == 'TRUE') and ($_SESSION["service"] == $_SESSION['servicePlanning'] )) // chef du service
								or ($infoServiceJours[$h][$acte]["idEmploye"] == $_SESSION["idEmploye"] )) // utilisateur a demandé rdv
							{
								
								if (( $dateCourant < $_SESSION["dateModifier"]) // jour précédent
								or  (( $dateCourant >= $_SESSION["dateModifier"])  and ( $heureCourante <= $h ))) // jour actuel mais heure précédent
								{
									echo "<button type='submit' class='btn btn-primary' value=".$infoServiceJours[$h][$acte]["id_rdv"]." name='btn-Modifier'>   M   </button>";
									echo "<button type='submit' class='btn btn-primary' value='' name='btn-Annuler".$infoServiceJours[$h][$acte]["id_rdv"]."'>X</button>";						
									if (isset ($_POST["btn-Annuler".$infoServiceJours[$h][$acte]["id_rdv"]]))
									{
										echo "<br> Annuler le RDV ? <br>";//ajouter la requette avec $idRDV le id du rdv
										echo "<button type='submit' class='btn btn-primary' value=".$infoServiceJours[$h][$acte]["id_rdv"]." name='btn-AnnulerOui'>Oui</button>";
										echo "<button type='submit' class='btn btn-primary' value='' name='btn-AnnulerNon'>Non</button>";
									}
								}
							}

							// bouton pour confirmer que l'acte a été réalisé
							// s'afiche si la date d'auj > date consultée, si la date = et heure actuel >= heure prevu, et si le statut du rdv est "prévu"
							if ($infoServiceJours[$h][$acte]["statut"] != 'r' )
							{
								if (( $dateCourant > $_SESSION["dateModifier"]) or (( $dateCourant == $_SESSION["dateModifier"]) and ( $heureCourante >= $h )))
								{
								// si le rdv est passé :
								// cad la page consultée est celle d'un jour précédent ou si la page est celle du jour actuel et que l'heure est = ou  < à l'heure actuelle
									echo "<button type='submit' class='btn btn-primary' value=".$infoServiceJours[$h][$acte]["id_rdv"]." name='btn-Realise'>R</button>";
								}
								elseif ($infoServiceJours[$h][$acte]["statut"] == 'r' )
								// si le rdv est passé :
								// cad la page consultée est celle d'un jour précédent ou si la page est celle du jour actuel et que l'heure est = ou  < à l'heure actuelle
								{
									echo "réalisé";
								}					
							}
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
		 <?php 	quitter1();
		include ('../Config/Footer.php'); //menu de navigation
	?>	
	
		 
		
	</body>
</html>



