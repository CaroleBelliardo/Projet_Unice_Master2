<?php

	require_once("session.php");
	
	require_once("classe.Systeme.php");
	$auth_user = new Systeme();
	$user_id = $_SESSION['idEmploye'];
	$stmt = $auth_user->runQuery("SELECT * FROM CompteUtilisateurs WHERE idEmploye=:user_name");
	$stmt->execute(array(":user_name"=>$user_id));
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC); 

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">

<link rel="stylesheet" href="style.css" type="text/css"  />
<title>Bonjour</title>
</head>

<body>
<?php include ('./Config/Menupage.php'); ?>
    
	<p class="h4">User Home Page</p> 
    <p class="" style="margin-top:5px;">
	<label class="h5">Bonjour : <?php print($userRow['idEmploye']); ?></label> </br>

    ICI les conneries regardant le gars connecté.
   
    </p>
    <p class="h4">Systeme</p> 
    <button type="button" onclick="window.location = './Config/CreerFicheP.php'">Ajouter un utilisateur </button>
	<button type="button" onclick="window.location = './Config/CreerFicheP.php'">Modifier un utilisateur</button>
	<p class="h4">Autre</p> 
    <button type="button" onclick="alert('Hello world!')">Je sais pas encore</button>
	<button type="button" onclick="alert('Hello world!')">qui sait ?</button>
 







</body>
<?php
session_start();
require_once('classe.Systeme.php');
$user = new Systeme();

if($user->is_loggedin()!="")
{
	$user->redirect('Pageprincipale.php');
}

if(isset($_POST['btn-signup']))
{
	$uname = strip_tags($_POST['txt_uname']);
	$upass = strip_tags($_POST['txt_upass']);	
	
	if($uname=="")	{
		$error[] = "Il faut fournir un identifiant !";	
	}
	else if($upass=="")	{
		$error[] = "Il faut un mot de passe !";
	}
	else if(strlen($upass) < 6){
		$error[] = "6 Characteres minimum";	
	}
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
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coding Cage : Sign up</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="style.css" type="text/css"  />
</head>
<body>

<div class="signin-form">

<div class="container">
    	
        <form method="post" class="form-signin">
            <h2 class="form-signin-heading"Ajouter un utilisateur</h2><hr />
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
            <input type="text" class="form-control" name="txt_uname" placeholder="Enter Username" value="<?php if(isset($error)){echo $uname;}?>" />
            </div>
            <div class="form-group">
            	<input type="password" class="form-control" name="txt_upass" placeholder="Enter Password" />
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


</html>