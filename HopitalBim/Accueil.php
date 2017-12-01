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

<!DOCTYPE html> <!-- version HTML 5 : plus besoin de mettre DOCTYPE html PUBLIC "..."-->
<html>
    <head>
    <title> Accueil </title> <!-- Titre de l'onglet -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="Config/style.css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
    </head>
    
    <body>
    <div id="entete">  <!-- Motif à mettre sur chaque page--> 
    Planning Hopital Bim
    </div>

    <div class="accroche"> Pour gérer votre planning en un clin d'oeil </div>

    <div id="menu"> <!-- Menu à mettre sur chaque page -->
    </br>
    </div> 

   <div id="login">
      <form method="post" id="login-form">
        <header> S'authentifier <hr></header>

            <div id="error">
            <?php
                if(isset($error))
                    {
                        ?>
                            <div class="alert alert-danger">
                            <?php echo $error; ?> !
                            </div>
                        <?php
                     }
            ?>
            </div>
        
        <span> <img src="Images/User.png" alt="User logo" height="60%" width="60%"> </span>
          <input type="text" id="user" name="txt_uname" placeholder="Nom d'utilisateur" required >
       
        <span> <img src="Images/cadenas.png" alt="User logo" height="60%" width="60%"></span>
          <input type="password" id="password" name="txt_password" placeholder="Mot de passe">
        
        <button type="submit" name="btn-login" class="btn btn-default" value="Login"> Valider</button>

        </form>
    </div>

    <div id="footer">
    Conditions d'utilisation | Contact | © 2017
    </div>

    </body>
</html>