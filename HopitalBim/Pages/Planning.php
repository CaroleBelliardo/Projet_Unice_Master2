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
	
	$a_ref=infoPlanning ($auth_user);
	$a_heures = $a_ref['heure'];
	$a_idActes = $a_ref['actes'];
	$infoServiceJours = $a_ref['info'];

// **************************  AFFICHAGE PAGE ********************************************   

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="Style.css" type="text/css"  />  
		<title>Bonjour</title>
	</head>
	
	<CENTER><table>
		<tr>
			<td width=33%>
				<fieldset>
			 <legend> Date </legend> 
			 <form method="post" action="traitement.php">
				 <p>
					 <input type="date" />
					 <input type="submit" value="Envoyer" />
				 </p>
			 </form>
		 </fieldset>
			</td>
			<td width=33%>
			  <?php 
					 include ('../Formulaires/RechercheService.php');; // recherche service
		 ?>
			</td>
		</tr>
	</table></CENTER>
	<body>
		
		 
	 
		
	 
		 <table  BORDER="1",ALIGN="CENTER", VALIGN="MIDDLE " >
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
		 <?php
						 echo $infoServiceJours[$h][$acte]["nom"]." ".$infoServiceJours[$h][$acte]["prenom"]."\n".$infoServiceJours[$h][$acte]["numSS"]."\n";
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
		 </table>
		 <?php 	quitter1()	?>	
		 
		 
		
		
	</body>
</html>



