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
    </head>
    
    <body>
    <div id="entete">  <!-- Motif à mettre sur chaque page--> 
    Web Planning
    </div>

    <div class="accroche"> Pour gérer votre planning en un clin d'oeil </div>

    <div id="menu"> <!-- Menu à mettre sur chaqye page -->
    Patient Planning Services Compte_Utilisateur Vérification ///// Se_déconnecter
    </div> 
    
    <img src="Images/lenval2.jpg" alt="Hopital lenval Nice vue côté mer">

    <form method="post" id="login-form"> 

        <fieldset> 
        <legend> <h2> Authentification </h2> </legend>

        <div class="Authentification"> 

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
        
        <div class="formuAccueil">
            <input type="text" class="form-control" name="txt_uname" placeholder="Nom d'utilisateur :" required />
        </div>
        
        <div class="formuAccueil">
            <input type="password" class="form-control" name="txt_password" placeholder="Mot de passe :" />
        </div>
        
        </br>

        <div class="bouton">
            <button type="submit" name="btn-login" class="btn btn-default"> Valider </button>
        </div>

        </div> <!-- Authentification -->

        </fieldset>

    </form>

    </body>
</html>