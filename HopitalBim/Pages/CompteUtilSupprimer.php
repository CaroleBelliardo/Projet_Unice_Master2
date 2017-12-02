<?php
	// fonction qui permet d afficher les requetes sql et donc permet de jouer avec les données 
	/*  A FAIRE : 
		- champs html du departement et du pays afficher une liste ( plutot que de le taper) 
		- les includes a faire.
	*/

include ('../Config/Menupage.php');
	
if(isset($_POST['btn-signup']))
{	 
	$text_utilisateur=$_POST['text_utilisateur'];

	 
	// ici je pense faire un include de $dep a $adresse tout foutre dans un seul et meme document car c est chiant a regarder 
		 // Gestion des erreurs : 
	if ($text_utilisateur==""){$error[] = "Il faut un selectionner un utilisateur !"; }
	else if($text_utilisateur=="admin00")	{$error[] = "Impossible de supprimer l'Admin"; }
	else 
	{ 
		
		try 
		{
			$ajoutchef = $auth_user->conn->prepare("DELETE FROM CompteUtilisateurs WHERE 
													idEmploye=:text_utilisateur");
			$ajoutchef->bindparam(":text_utilisateur", $text_utilisateur);
			$ajoutchef->execute();
			$auth_user->redirect('CompteUtilSupprimer.php?Valide');
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

<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>Supprimer un utilisateur</title>
</head>

<body>

    <p class="" style="margin-top:5px;">
<div class="signin-form">

<div class="container">
    	
<form method="post" class="form-signin">
            <h2 class="form-signin-heading">Supprimer un utilisateur</h2><hr />
            <?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
			?>
                    <div class="alert alert-danger">
                    <i class=""></i> &nbsp; <?php echo $error; ?>
                    </div>
					<?php
				}
			}
			else if(isset($_GET['Valide']))
			{
					?>
                <div class="alert alert-info">
                <i class=""></i>Utilisateur supprimé avec succes<a href='../Pageprincipale.php'>Page principale</a>
                </div>
            <?php
			}
			?>
			
            <div class="form-group" >
			<fieldset>
			<legend> Compte utilisateur </legend> <!-- Titre du fieldset --> 
			<p>
				Recherche d'un utilisateur :
				<input list="text_utilisateur" name="text_utilisateur" size='35'> 
				<datalist id="text_utilisateur" >
				<?php 
				$stmt = $auth_user->runQuery("SELECT * FROM Employes"); // permet de rechercher le nom d utilisateur 
				$stmt->execute(); // la meme 
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				echo "<option value='".$row['CompteUtilisateursidEmploye']."'>".$row['CompteUtilisateursidEmploye']." ".$row['nom']." ".$row['prenom']." ".$row['ServicesnomService']."</option>";
				}?></datalist>
			
			</p>
			</fieldset>		
			</br >
			</div>
			
			
			</div>
			
            <div class="clearfix"></div><hr />
            <div class="form-group">
            	<button type="submit" class="btn btn-primary" name="btn-signup">
                	<i class=""></i>Valider
                </button>
            </div>
        </form>

       </div>
</div>

</div>
<?php quitter1() ?>	

</body>


</html>
