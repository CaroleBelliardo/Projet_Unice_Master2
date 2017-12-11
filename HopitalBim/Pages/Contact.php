<?php

	include ('../Config/Menupage.php');
	$lien ='VerificationNotification.php';

	If ($_SESSION['chefService'] != TRUE) 
	{
		$auth_user->redirect('../Pageprincipale.php'); // recherche le service
	}

	
	if (isset ($_POST["btn-Accepter"]))
	{
		//recup info rdv en question
		$req_info = $auth_user->runQuery(" SELECT Pathologies.nomPathologie, CreneauxInterventions.InterventionsidIntervention, niveauUrgence,
										niveauUrgenceMax, niveauUrgenceMin
										FROM InterventionsPatho JOIN CreneauxInterventions JOIN Pathologies
										WHERE id_rdv = :idrdv
										AND CreneauxInterventions.PathologiesidPatho = Pathologies.idPatho
										AND CreneauxInterventions.PathologiesidPatho = InterventionsPatho.PathologiesidPatho
										AND CreneauxInterventions.InterventionsidIntervention = InterventionsPatho.InterventionsidIntervention" );
		//recup info rdv en question
		$req_info = $auth_user->runQuery(" SELECT CreneauxInterventions.PathologiesidPatho, CreneauxInterventions.InterventionsidIntervention,
										 CreneauxInterventions.niveauUrgence,
										 InterventionsPatho.niveauUrgenceMin, InterventionsPatho.niveauUrgenceMax
										FROM CreneauxInterventions JOIN InterventionsPatho
										WHERE id_rdv = :idrdv
										AND CreneauxInterventions.InterventionsidIntervention = InterventionsPatho.InterventionsidIntervention
										AND CreneauxInterventions.PathologiesidPatho = InterventionsPatho.PathologiesidPatho
										" ); 
		$req_info->execute(array('idrdv'=> $_POST["btn-Accepter"]));
		$a_infoo= $req_info-> fetch(PDO::FETCH_ASSOC);
		$req_info->closeCursor();
		if($a_infoo["niveauUrgence"] > $a_infoo["niveauUrgenceMax"])
		{ 
			$req_realiseRDV = $auth_user-> runQuery(" UPDATE InterventionsPatho
													SET niveauUrgenceMax = :nu 
													WHERE PathologiesidPatho =:patho
													AND InterventionsidIntervention =:inter");
			$req_realiseRDV->execute(array('nu'=>$a_infoo["niveauUrgence"],
										   'patho'=>$a_infoo["PathologiesidPatho"],
										   'inter'=>$a_infoo["InterventionsidIntervention"]));
		}
		else 
		{
			$req_realiseRDV = $auth_user-> runQuery(" UPDATE InterventionsPatho
													SET niveauUrgenceMin = :nu 
													WHERE PathologiesidPatho =:patho
													AND InterventionsidIntervention =:inter");
			$req_realiseRDV->execute(array('nu'=>$a_infoo["niveauUrgence"],
										   'patho'=>$a_infoo["PathologiesidPatho"],
										   'inter'=>$a_infoo["InterventionsidIntervention"]));
		}
		//suppr.Notif
		$req_notifVu = $auth_user-> runQuery("DELETE FROM Notifications WHERE 
											CreneauxInterventionsidRdv=:id_rdv");
		$req_notifVu->execute(array('id_rdv'=>$_POST["btn-Accepter"]));
		$auth_user->redirect('VerificationNotification.php?Valide');
	}
	if (isset($_POST["btn-Refuser"]) )
	{
	//suppr.Notif
		$req_notifVu = $auth_user-> runQuery("DELETE FROM Notifications WHERE 
											CreneauxInterventionsidRdv=:id_rdv");
		$req_notifVu->execute(array('id_rdv'=>$_POST["btn-Refuser"]));
		$auth_user->redirect('VerificationNotification.php?Valide');
		
	}
	if ((isset ($_POST["btn-Vu"])) or (isset($_POST["btn-Refuser"]))) //suppr.Notif
	{ 
		$req_notifVu = $auth_user-> runQuery("DELETE FROM Notifications WHERE 
											CreneauxInterventionsidRdv=:id_rdv");
		$req_notifVu->execute(array('id_rdv'=>$_POST["btn-Vu"]));
		$auth_user->redirect('VerificationNotification.php?Valide');
	}
?>	




<!DOCTYPE html PUBLIC >
<html>
	<head>
		<title>Contact </title>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<link rel="stylesheet" href="Style.css">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
	</head>

	<body>
		
		<div class="containerFormu">
		<form method="post" class="form-signin">

            <div class="contactFooter" >
			<fieldset>
			<legend> Pour nous contacter </legend> <br>

			<em>Nom :</em> Belliardo <br>
			<em>Prénom :</em> Carole <br>
			<em>Mail :</em> belliardo.carole@hotmail.fr <br> <br>

			<em>Nom :</em> Nachabe <br>
			<em>Prénom :</em> Hussam <br>
			<em>Mail :</em> hussamnachabe@gmail.com <br> <br>

			<em>Nom :</em> Poullet <br>
			<em>Prénom :</em> Marine <br>
			<em>Mail :</em> poullet.m@hotmail.fr <br><br>

			<em>Nom :</em> Truchi <br>
			<em>Prénom :</em> Marin <br>
			<em>Mail :</em> marin.truchi@gmail.com <br> <br>


			</fieldset>
			</div>

		</form>
		</div>

	<?php quitter1() ?>

	<?php include ('../Config/Footer.php'); //menu de navigation ?>

	</body>

</html>