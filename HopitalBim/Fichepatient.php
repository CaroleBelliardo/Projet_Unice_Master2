<?php

	require_once("session.php");
	
	require_once("class.user.php");
	$auth_user = new USER();
	$user_id = $_SESSION['idEmploye'];
	$stmt = $auth_user->runQuery("SELECT * FROM Compteutilisateurs WHERE idEmploye=:user_name");
	$stmt->execute(array(":user_name"=>$user_id));
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
	
	require_once('class.patient.php');
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
	
	if($text_numSS=="")	{
		$error[] = "Il faut fournir un numSS !";	
	}
	else if($text_nom=="")	{
		$error[] = "Il faut un mot de passe !";
	}
	// Ajouter autant de elseif que l on veut
	else
	{
		try
		{
			$stmt = $user->runQuery("SELECT idEmploye FROM Compteutilisateurs WHERE idEmploye=:uname");
			$stmt->execute(array(':uname'=>$uname));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['idEmploye']==$uname)  {
				$error[] = "sorry username already taken !";
			}
			else
			{
				if($user->register($uname,$upass)){	
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"> 
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="jquery-1.11.3-jquery.min.js"></script>
<link rel="stylesheet" href="style.css" type="text/css"  />
<title>Bonjour</title>
</head>

<body>



  
<div class="container-fluid" style="margin-top:0px;">
    <div class="container">
        <hr />
        <h1>
        <a href="Pageprincipale.php"><span class="glyphicon glyphicon-home"></span> home</a> &nbsp; 
        <a href="profile.php"><span class="glyphicon glyphicon-user"></span> profile</a> &nbsp; 
		<a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>Sign Out</a>&nbsp;
        <a href="Fichepatient.php"><span class=""></span> Fiche Patient</a> &nbsp; 
		</h1>
       	<hr />
        
        <p class="h4">User Home Page</p> 
 
    <p class="" style="margin-top:5px;">
	<label class="h5">Bonjour : <?php print($userRow['idEmploye']); ?></label> </br>

    ICI les conneries regardant le gars connecté.
    <a href="http://www.codingcage.com/2015/04/php-login-and-registration-script-with.html">tutorial link</a>
    </p>
    </div>

</div>


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
            <input type="text" class="form-control" name="text_idAdresse" placeholder="Enter Username" value="<?php if(isset($error)){echo $uname;}?>" />
            </div>
            <div class="form-group">
            	<input type="password" class="form-control" name="text_idAdresse" placeholder="Enter Password" />
            </div>
            <div class="clearfix"></div><hr />
            <div class="form-group">
            	<button type="submit" class="btn btn-primary" name="btn-signup">
                	<i class="glyphicon glyphicon-open-file"></i>&nbsp;SIGN UP
                </button>
            </div>
            <br />
            <label>have an account ! <a href="Accueil.php">Sign In</a></label>
        </form>
       </div>
</div>

</div>

</body>
</html>