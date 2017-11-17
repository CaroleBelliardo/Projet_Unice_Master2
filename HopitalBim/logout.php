<?php
	require_once('session.php');
	require_once('classe.Systeme.php');
	$user_logout = new Systeme();
	
	if($user_logout->is_loggedin()!="")
	{
		$user_logout->redirect('Pageprincipale.php');
	}
	if(isset($_GET['logout']) && $_GET['logout']=="true")
	{
		$user_logout->doLogout();
		$user_logout->redirect('Accueil.php');
	}
?>