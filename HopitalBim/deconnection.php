<?php
	require_once('session.php');
	require_once('classe.Systeme.php');
	$utilisateurDeconnection = new Systeme();
	
	if($utilisateurDeconnection->estConnecte()!="")
	{
		$utilisateurDeconnection->redirect('Pageprincipale.php');
	}
	if(isset($_GET['logout']) && $_GET['logout']=="true")
	{
		$utilisateurDeconnection->seDeconnecter();
		$utilisateurDeconnection->redirect('Accueil.php');
	}
?>
