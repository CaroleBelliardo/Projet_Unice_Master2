<?php
	// fonction qui permet d afficher les requetes sql et donc permet de jouer avec les données 
	/*  A FAIRE : 
		- champs html du departement et du pays afficher une liste ( plutot que de le taper) 
		- les includes a faire.
	*/
	include ('../Config/Menupage.php');
	
if(isset($_POST['btn-supprimerService']))
{	 
	$text_nomService=$_POST['text_nomService'];

	 
	// ici je pense faire un include de $dep a $adresse tout foutre dans un seul et meme document car c est chiant a regarder 
		 // Gestion des erreurs : 
	if ($text_nomService==""){$error[] = "Il faut un sélectionner un service !"; }
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
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
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
                    <div id="error"> &nbsp; <?php echo $error; ?> </div> <!-- Alert alert-danger-->
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
			
            <div class="form-group" >
			<fieldset>
			<legend> Service </legend> <!-- Titre du fieldset --> 
			
			<br>
			<?php liste_Services($auth_user) ?> 
			<br>

			</fieldset> <br>
			</div>
            
            <div class="form-group">
            	<button type="submit" class="btn btn-primary" name="btn-supprimerService"> Valider </button>
            </div>

        </form> <!-- form-signin -->

</div> <!-- containerFormu -->

<div class="abandon">
<?php quitter1() ?>
</div>

</body>
</html>
