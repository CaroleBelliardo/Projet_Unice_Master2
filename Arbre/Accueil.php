<?php
session_start();
require_once("classe.Systeme.php");
$login = new Systeme();

if($login->is_loggedin()!="")
{
	$login->redirect('Pageprincipale.php');
}

if(isset($_POST['btn-login']))
{
	$uname = strip_tags($_POST['txt_uname']);
	$upass = strip_tags($_POST['txt_password']);
		
	if($login->authentification($uname,$upass))
	{
		$login->redirect('Pageprincipale.php');
	}
	else
	{
		$error = "Erreur de saisie";
	}	
}
?>
<!DOCTYPE html PUBLIC ">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Accueil</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="Config/style.css" type="text/css"  />
</head>
<body>

<div class="signin-form">

	<div class="container">
     
        
       <form class="form-signin" method="post" id="login-form">
      
        <h2 class="form-signin-heading">Authentification</h2><hr />
        
        <div id="error">
        <?php
			if(isset($error))
			{
				?>
                <div class="alert alert-danger">
                   <i class=""></i><?php echo $error; ?> !
                </div>
                <?php
			}
		?>
        </div>
        
        <div class="form-group">
        <input type="text" class="form-control" name="txt_uname" placeholder="Nom d'utilisateur !" required />
        <span id="check-e"></span>
        </div>
        
        <div class="form-group">
        <input type="password" class="form-control" name="txt_password" placeholder="Votre mot de passe!" />
        </div>
       
     	<hr />
        
        <div class="form-group">
            <button type="submit" name="btn-login" class="btn btn-default">
                	<i class=""></i>Valider
            </button>
        </div>  
      	<br />
      </form>

    </div>
    
</div>

</body>
</html>