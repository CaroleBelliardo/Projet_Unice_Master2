<?php

	require_once("../session.php");
	
	require_once("../classe.Systeme.php");
	$auth_user = new Systeme();
	$user_id = $_SESSION['idEmploye'];
	$stmt = $auth_user->runQuery("SELECT * FROM CompteUtilisateurs WHERE idEmploye=:user_name");
	$stmt->execute(array(":user_name"=>$user_id));
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
	
	require_once('../class.patient.php');
	$patient = new FICHEPATIENT();
	if(isset($_POST['btn-signup']))
	{
	$text_idAdresse = strip_tags($_POST['text_idAdresse']);	
	$text_numero = strip_tags($_POST['text_numero']);	
	$text_rue = strip_tags($_POST['text_rue']);	
	$text_CodePostauxcodepostal = strip_tags($_POST['text_CodePostauxcodepostal']);
	
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
	$text_AdressesidAdresse = strip_tags($_POST['text_AdressesidAdresse']);	
	
	
	$patient->register($text_idAdresse,$text_numero ,$text_rue,$text_CodePostauxcodepostal,$text_numSS,$text_nom ,$text_prenom,$text_dateNaissance,$text_telephone,$text_mail ,$text_sexe,$text_taille,$text_poids,$text_commentaires,$text_AdressesidAdresse);
	
	if($text_numSS=="")	{
		$error[] = "Il faut fournir un numSS !";	
	}
	else if($text_nom=="")	{
		$error[] = "Il faut un nom !";
	}
	// Ajouter autant de elseif que l on veut pour gerer les erreurs 
	else
	{
		try
		{
			$stmt = $user->runQuery("SELECT idEmploye FROM CompteUtilisateurs WHERE idEmploye=:uname");
			$stmt->execute(array(':uname'=>$uname));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['idEmploye']==$uname)  {
				$error[] = "sorry username already taken !";
			}
			else
			{
				if($patient->register($uname,$upass)){	
					$user->redirect('sign-up.php?joined');
				}
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
	<label class="h5">Bonjour : <?php print($userRow['idEmploye']); ?></label> </br>

    ICI les conneries regardant le gars connecté.
    </p>
    




<div class="signin-form">

<div class="container">
    	
        <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Sign up.</h2><hr />
            <?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                     </div>
                     <?php
				}
			}
			else if(isset($_GET['joined']))
			{
				 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered <a href='Accueil.php'>login</a> here
                 </div>
                 <?php
			}
			?>
            <div class="form-group">
            <input type="text" class="form-control" name="text_numSS" placeholder="Numero Securité" value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_nom" placeholder="Nom" value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_prenom" placeholder="Prénom" value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_dateNaissance" placeholder="date de naissance" value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_telephone" placeholder="Numero de telephone" value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_mail" placeholder="Mail" value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_sexe" placeholder="Entrer sexe" value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_taille" placeholder="Entrer taille" value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_poids" placeholder="Entrer poids en kg " value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_commentaires" placeholder="Entrer commentaires" value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_AdressesidAdresse" placeholder="text_AdressesidAdresse" value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_idAdresse" placeholder="Entrer idadresse" value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_numero" placeholder="Entrer numero" value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_rue" placeholder="Entrer rue" value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_CodePostauxcodepostal" placeholder="Entrer code postal" value="<?php if(isset($error)){echo $uname;}?>" />
            <input type="text" class="form-control" name="text_AdressesidAdresse" placeholder="adresse id fiche patient" value="<?php if(isset($error)){echo $uname;}?>" />
			
			</div>

            <div class="clearfix"></div><hr />
            <div class="form-group">
            	<button type="submit" class="btn btn-primary" name="btn-signup">
                	<i class="glyphicon glyphicon-open-file"></i>&nbsp;SIGN UP
                </button>
            </div>
            <br />
            <label>have an account !<a href="Accueil.php">Sign In</a></label>
        </form>
       </div>
</div>

</div>

</body>
</html>