<?php

	session_start();
	unset($_SESSION["session"]); // Si l'utilisateur ferme la page alors la session est detruite.
	require_once 'classe.Systeme.php';
	$session = new Systeme();
	
	// if user session is not active(not loggedin) this page will help 'home.php and profile.php' to redirect to login page
	// put this file within secured pages that users (users can't access without login)
	if(!$session->estConnecte())
	{
		// session no set redirects to login page
		$session->redirect('Pageprincipale.php');
	}
	
	?>