<?php

	require_once('session.php');
	
	require_once("classe.Systeme.php");
	$auth_user = new Systeme();
	$user_id = $_SESSION['idEmploye'];
	$stmt = $auth_user->runQuery("SELECT * FROM CompteUtilisateurs WHERE idEmploye=:user_name");
	$stmt->execute(array(":user_name"=>$user_id));
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC); 
	if (array_key_exists("Patient",$_SESSION )){
		echo $_SESSION['Patient'] ;}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"> 
<link href="../bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
<link rel="stylesheet" href="style.css" type="text/css"  />
<title>Bonjour</title>
</head>

<body>
<?php include ('./Config/Menupage.php'); ?>
	<p class="h4">Page Principale</p> 
    <p class="h4">Session : <?php print($userRow['idEmploye']); ?>
    <p class="" style="margin-top:5px;">


    ICI les conneries regardant le gars connecté.
   
    </p>
    
    




</body>



</html>
