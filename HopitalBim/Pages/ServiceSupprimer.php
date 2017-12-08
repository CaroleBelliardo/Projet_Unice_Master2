<?php
	// fonction qui permet d afficher les requetes sql et donc permet de jouer avec les données 
	/*  A FAIRE : 
		- champs html du departement et du pays afficher une liste ( plutot que de le taper) 
		- les includes a faire.
	*/
	include ('../Config/Menupage.php');
	
	{
		$auth_user->redirect('../PagePrincipale.php');
	}
	
	if(isset($_POST['btn-supprimerService']))
	{	 
		$text_nomService=trim($_POST['text_nomService'], ' ');
		$req_Service = $auth_user->runQuery("SELECT nomService FROM Services 
									WHERE nomService = :nomService");
		$req_Service->execute(array('nomService' => $text_nomService));
		$service = $req_Service->fetch(PDO::FETCH_ASSOC);
		if (($service['nomService'] == "")) {
			$error[] = "Entrer un service valide !";}
		else if ($text_nomService==""){
			$error[] = "Il faut un sélectionner un service !"; }
		else 
		{ 
			
			try 
			{
				$archiverService = $auth_user->runQuery("INSERT INTO ServicesArchive 
															SELECT *   
															FROM Services
															WHERE nomService=:nomService");
				$archiverService->execute(array('nomService'=>$text_nomService));
				$supprimerService = $auth_user->conn->prepare("DELETE FROM Services WHERE 
														nomService=:nomService");
				$supprimerService->execute(array('nomService'=>$text_nomService));
				$auth_user->redirect('ServiceSupprimer.php?Valide');
			}
			catch(PDOException $e)
			{			
				echo $e->getMessage();
			}	
			
		}
	}
?>

<!DOCTYPE html PUBLIC >
<html>
	<head>
		<title>Supprimer un service</title>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<link rel="stylesheet" href="../Config/Style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
	</head>
	
	<body>
		<div class="containerFormu">
				
			<form method="post" class="form-signin">

				<h2 class="form-signin-heading">Supprimer un service</h2><hr />

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
					
				<div id="valide"> <!-- Alert alert-danger-->
					Service supprimé avec succés ! <a href='../Pageprincipale.php'>Page principale</a>
				</div>
			
				<?php
					}
				?>
					
				<div class="form-group">

					<fieldset>

						<legend> Service </legend> <!-- Titre du fieldset --> 		
			
							<label for="text_nomService"> Liste des services </label>
							<input list="text_nomService" name="text_nomService" size='85'> 
							<datalist id="text_nomService" >
							<?php 
							$req_service = $auth_user->runQuery("SELECT * FROM Services"); // permet de rechercher le nom d utilisateur 
							$req_service->execute(); // la meme 
							while ($row_service = $req_service->fetch(PDO::FETCH_ASSOC))
							{
								echo "<option value='".$row_service['nomService']."'label='".$row_service['nomService']."'>".$row_service['nomService']."</option>";
							}
							?>
						</datalist> </br >

					</fieldset> <br>

				</div> <!-- form-group -->

				<div class="form-group">
					<button type="submit" class="btn btn-primary" name="btn-supprimerService"> Valider </button>
				</div> <!-- form-group -->

			</form> <!-- form-signin -->
		
		</div> <!-- containerFormu -->

		<button class="abandon">
			<?php quitter1(); ?>
		</button>

		<?php include ('../Config/Footer2.php'); //menu de navigation ?>
	
	</body>
</html>
