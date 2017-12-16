<?php
  // fonction qui permet d afficher les requetes sql et donc permet de jouer avec les données 
  /*  A FAIRE : 
    - champs html du departement et du pays afficher une liste ( plutot que de le taper) 
    - les includes a faire.
  */ 
	include ('../Config/Menupage.php');
	if ($_SESSION["idEmploye"] != 'admin00')
	{
		$auth_user->redirect('../Pageprincipale.php');
	}

	if(isset($_POST['btn-suppr_CU']))
	{   
		$text_utilisateur=$_POST['text_utilisateur'];
		$req_utilisateur = $auth_user->runQuery("SELECT * 
												FROM CompteUtilisateurs
												WHERE idEmploye =:idEmploye");
				
				$req_utilisateur->execute(array("idEmploye"=>$text_utilisateur));
				$utilisateurSupprimer=$req_utilisateur -> fetch(PDO::FETCH_ASSOC);
		if ($text_utilisateur=="")
		{
			$error[] = "Il faut un sélectionner un utilisateur !";
		}
		else if($text_utilisateur=="admin00")
		{
			$error[] = "Il est interdit de supprimer l'admin !!";
		}
		else if($utilisateurSupprimer['idEmploye']=="")
		{
			$error[] = "Il faut un sélectionner un utilisateur existant !";
		}
		else 
		{
			if  ($_SESSION["chefService"] == TRUE)
			{
				try
				{
					// Recherche si la personne à supprimer est chef de service
					$req_chefDeService = $auth_user-> runQuery("Select * 
																FROM ChefServices 
																WHERE EmployesCompteUtilisateursidEmploye =:idEmploye");
																
					$req_chefDeService->execute(array ('idEmploye'=>$text_utilisateur));
					$utilisateurChef= $req_chefDeService ->fetch(PDO::FETCH_ASSOC);
					if ($utilisateurChef != NULL )
					{
						$error[] = "Impossible de supprimer un chef de service";
					}
					else 
					{
						$archiverEmployer = $auth_user->runQuery("INSERT INTO EmployesArchive 
																		SELECT *   
																		FROM Employes 
																		WHERE CompteUtilisateursidEmploye=:employe");
						$archiverEmployer->execute(array('employe'=>$text_utilisateur));
						$req_supprimer = $auth_user->runQuery("DELETE FROM CompteUtilisateurs 
															WHERE 
															idEmploye=:text_utilisateur");
						$req_supprimer->bindparam(":text_utilisateur", $text_utilisateur);
						$req_supprimer->execute();
						$auth_user->redirect('CompteUtilSupprimer.php?Valide');
					}
			
				}
				catch(PDOException $e)
				{
					echo $e->getMessage();
				}  
			}
		}
	} 
?>
<!DOCTYPE html PUBLIC >
<html>
		
	<head>
		<title>Supprimer un utilisateur</title>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"> 
		<link rel="stylesheet" href="../Config/Style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">	
	</head>

	<body>
			
		<div class="containerFormu">
				
			<form method="post" class="form-signin">

				<h2 class="form-signin-heading">Supprimer un utilisateur</h2><hr />

					<?php
						if(isset($error))
						{
							foreach($error as $error)
							{
 					?>
								
					<div id="error"> <?php echo $error; ?> </div>
 
 					<?php
							}
						}
						else if(isset($_GET['Valide']))
						{
					?>
							
					<div id="valide">
						Utilisateur supprimé avec succés !<a href='../Pageprincipale.php'>Page principale</a>
					</div>
	
					<?php
						}
					?>
						
					<div class="form-group" > 

						<fieldset>
						<legend> Compte utilisateur </legend> <!-- Titre du fieldset --> 

							<label for="text_utilisateur"> Recherche utilisateur</label>
							<input list="text_utilisateur" name="text_utilisateur" size='35'> 
							<datalist id="text_utilisateur" >
								<?php $stmt = $auth_user->runQuery("SELECT * FROM Employes"); // permet de rechercher le nom d utilisateur 
								$stmt->execute(); // la meme 
								while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
								{
								echo "<option value='".$row['CompteUtilisateursidEmploye']."'>".$row['CompteUtilisateursidEmploye']." ".$row['nom']." ".$row['prenom']." ".$row['ServicesnomService']."</option>";
								}
								?>
							</datalist>
						</fieldset></br >
					 </div>
						
					<div class="form-group">
						<button type="submit" class="btn btn-primary" name="btn-suppr_CU">Valider</button>
					</div>
			</form>
		</div>
		<?php quitter1();  ?>  
		<?php include ('../Config/Footer2.php'); ?>

	</body>
</html>
