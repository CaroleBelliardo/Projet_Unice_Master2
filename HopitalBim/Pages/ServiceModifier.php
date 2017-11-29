<?php

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


    ICI les conneries regardant le gars connecté.
   
    </p>
    <p class="h4">Systeme</p> 
    <button type="button" onclick="window.location = './Config/CreerFicheP.php'">Ajouter un utilisateur </button>
	<button type="button" onclick="window.location = './Config/CreerFicheP.php'">Modifier un utilisateur</button>
	<p class="h4">Autre</p> 
    <button type="button" onclick="alert('Hello world!')">Je sais pas encore</button>
	<button type="button" onclick="alert('Hello world!')">qui sait ?</button>
 
<?php quitter1(); ?>	
</body>



</html>
