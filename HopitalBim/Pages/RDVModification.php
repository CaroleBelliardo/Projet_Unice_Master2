<?php
	include ('../Config/Menupage.php');
	include ('../Fonctions/Affichage.php');
	require_once("session.php");	
	require_once("classe.Systeme.php");
  
	$auth_user = new Systeme();
	$user_id = $_SESSION['idEmploye'];
	$stmt = $auth_user->runQuery("SELECT * FROM CompteUtilisateurs WHERE idEmploye=:user_name");
	$stmt->execute(array(":user_name"=>$user_id));
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
	
	require_once('../class.patient.php');
	$patient = new FICHEPATIENT();
	if(isset($_POST['btn-signup']))
{

	$text_departement = strip_tags($_POST['text_departement']);	
	$text_pays = strip_tags($_POST['text_pays']);	
	
	$text_ville = strip_tags($_POST['text_ville']);
	$text_codepostal = strip_tags($_POST['text_codepostal']);	
	
	$text_numero = strip_tags($_POST['text_numero']);	
	$text_rue = strip_tags($_POST['text_rue']);	
	
	$text_numSS = strip_tags($_POST['text_numSS']);	
	$text_nom = strip_tags($_POST['text_nom']);	
	$text_prenom = strip_tags($_POST['text_prenom']);	
	$text_dateNaissance = strip_tags($_POST['text_dateNaissance']);	
	$text_telephone = strip_tags($_POST['text_telephone']);	
	$text_mail = strip_tags($_POST['text_mail']);	
	$text_sexe = strip_tags($_POST['text_sexe']);	
	$text_taille = strip_tags($_POST['text_taille']);	
	$text_poids = strip_tags($_POST['text_poids']);	
	$text_commentaires = strip_tags($_POST['text_commentaires']);	
	
	$text_idAdresse = strip_tags($_POST['text_idAdresse']);	

	if($text_prenom=="a")	{
		$error[] = "Il faut un prenom et amuse toi a debugguer !! !";
	}
	else if($text_nom=="a")	{
		$error[] = "Il faut un nom !";
	}
	// Ajouter autant de elseif que l on veut pour gerer les erreurs 

	else
	{
		try
		{
			/* TEST LE DEPARTEMENT */
			echo 'test dep';
			$stmt = $patient->runQuery("SELECT departement FROM Departements WHERE departement=:text_departement");
			$stmt->execute(array(':text_departement'=>$text_departement));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($row['departement']==$text_departement)  {
				/* TEST LE codepostal  */
				echo 'dep deja pris // test le code postal //  ';
				$stmt = $patient->runQuery("SELECT ville FROM Villes WHERE Departementsdepartement=:text_departement ");
				$stmt->execute(array(':text_ville'=>$text_ville));
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
					if($row['ville']==$text_ville)  {
						/* test ville */ 
						$stmt = $patient->runQuery("SELECT ville FROM Villes WHERE Departementsdepartement=:text_departement ");
						$stmt->execute(array(':text_ville'=>$text_ville));
						$row=$stmt->fetch(PDO::FETCH_ASSOC);
							if($row['ville']==$text_ville)  
							{
								
							}	
							else
							{
							$patient->ajouterville($text_ville,$text_departement,$text_codepostal);
							$auth_user->redirect('CreerFicheP.php?Valide');
							}
					}
					else
					{
					$patient->ajouterville($text_ville,$text_departement,$text_codepostal);
					$auth_user->redirect('CreerFicheP.php?Valide');
					}
			}
			else
			{
			$patient -> ajouterdepartements($text_departement,$text_pays );
			$patient -> ajouterville($text_ville,$text_departement,$text_codepostal);
			$patient -> ajouteradresse($text_idAdresse,$text_numero ,$text_rue,$text_codepostal);
			$patient -> ajouterpatients($text_numSS,$text_nom ,$text_prenom,$text_dateNaissance,$text_telephone,$text_mail ,$text_sexe,$text_taille,$text_poids,$text_commentaires,$text_idAdresse);
			$auth_user-> redirect('CreerFicheP.php?Valide');
			/* if($user->register($uname,$upass)){	
			$user->redirect('sign-up.php?joined');
			} */
			}
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"> 
<link href="../bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
<link rel="stylesheet" href="../style.css" type="text/css"  />
<title>Bonjour</title>
</head>

<body>
<?php include ('../Config/Menupage.php'); ?>

    <p class="h4">Session : <?php print($userRow['idEmploye']); ?></p> 
    <p class="" style="margin-top:5px;">
<div class="signin-form">

<div class="container">
    	
        <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Enregistrer une fiche patient</h2><hr />
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
                      <i class=""></i>Patient enregistré avec succes<a href='Pageprincipale.php'>Page principale</a>
                 </div>
                 <?php
			}
			?>
			
			
            <div class="form-group" >
            <input type="text" class="form-control" name="text_numSS" placeholder="Numero Securité Sociale" value="<?php if(isset($error)){echo $text_numSS;}?>" />
            <input type="text" class="form-control" name="text_nom" placeholder="Nom" value="<?php if(isset($error)){echo $text_nom;}?>" />
            <input type="text" class="form-control" name="text_prenom" placeholder="Prénom" value="<?php if(isset($error)){echo $text_prenom;}?>" />
            <input type="date" class="form-control" name="text_dateNaissance" placeholder="" value="<?php if(isset($error)){echo $text_dateNaissance;}?>" />
            <input type="text" class="form-control" name="text_telephone" placeholder="Numero de telephone" value="<?php if(isset($error)){echo $text_telephone;}?>" />
            <input type="text" class="form-control" name="text_mail" placeholder="Mail" value="<?php if(isset($error)){echo $text_mail;}?>" />   
			<label   class="form-control" > Sexe :&nbsp;&nbsp;      
			<input type="radio"  name="text_sexe" value="M" checked="checked"  style="display: inline; !important;"/>Masculin&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio"  name="text_sexe" value="F" style="display: inline;!important;" />Feminin
			</label>			
            <input type="text" class="form-control" name="text_taille" placeholder="Taille en cm" value="<?php if(isset($error)){echo $text_taille;}?>" />
            <input type="text" class="form-control" name="text_poids" placeholder="Poids en kg " value="<?php if(isset($error)){echo $text_poids;}?>" />
            <input type="text" class="form-control" name="text_commentaires" placeholder="Entrer commentaires" value="<?php if(isset($error)){echo $text_commentaires;}?>" />
            <input type="text" class="form-control" name="text_numero" placeholder="Entrer numero" value="<?php if(isset($error)){echo $text_numero;}?>" />
            <input type="text" class="form-control" name="text_rue" placeholder="Entrer rue" value="<?php if(isset($error)){echo $text_rue;}?>" />
            <input type="text" class="form-control" name="text_codepostal" placeholder="Entrer code postal" value="<?php if(isset($error)){echo $text_codepostal;}?>" />
            <input type="text" class="form-control" name="text_departement" placeholder="dep" value="<?php if(isset($error)){echo $text_departement;}?>" />
			<input type="text" class="form-control" name="text_ville" placeholder="ville" value="<?php if(isset($error)){echo $text_ville;}?>" />
			<input type="text" class="form-control" name="text_idAdresse" placeholder="adresse id fiche patient" value="<?php if(isset($error)){echo $text_idAdresse;}?>" />
			<input type="text" class="form-control" name="text_pays" placeholder="Pays" value="<?php if(isset($error)){echo $text_pays;}?>" />
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
<?php quitter1(); ?>
</body>


</html>
