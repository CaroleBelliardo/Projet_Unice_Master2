<?php

	require_once("session.php");
	
	require_once("classe.Systeme.php");
	$auth_user = new Systeme();
	
	
	$user_id = $_SESSION['idEmploye'];
	
	$stmt = $auth_user->runQuery("SELECT * FROM CompteUtilisateurs WHERE idEmploye=:user_name");
	$stmt->execute(array(":user_name"=>$user_id));
	
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
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
    
    	<label class="h5">welcome : <?php print($userRow['idEmploye']); ?></label>
        <hr />
        
        <h1>
        <a href="Pageprincipale.php"><span class="glyphicon glyphicon-home"></span> home</a> &nbsp; 
        <a href="profile.php"><span class="glyphicon glyphicon-user"></span> profile</a> &nbsp; 
		<a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>Sign Out</a>
        
		</h1>
       	<hr />
    </div>

</div>




<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>