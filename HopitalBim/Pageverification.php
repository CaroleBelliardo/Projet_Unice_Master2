<!--TODO :
- Supprimer la requete extractReq --=extractReq2
- requete = tableau
-->

<?php

	require_once("session.php");
	require_once("classe.Systeme.php");
//  $auth_user = new Systeme();
//	$user_id = $_SESSION['idEmploye'];
//	$stmt = $auth_user->runQuery("SELECT * FROM CompteUtilisateurs WHERE idEmploye=:user_name");
//	$stmt->execute(array(":user_name"=>$user_id));
//	$userRow=$stmt->fetch(PDO::FETCH_ASSOC); 
   
    try // connnection à la base de donnée
    {
        $bdd = new PDO('mysql:host=localhost;dbname=bdd;charset=utf8', 'root', '');
    }
    catch (Exception $e)
    {
            die("Erreur : " . $e->getMessage());
    }
    
	
	function Dumper ($var){ // affichage des valeurs des variables tableaux
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
	}
	
	function extractReq ($inReq,$manip){ //TODO : recupere les valeurs de la première variable = > a supprimant en creant le tableau avant 
		$a_temp=[];
		while ($temp =  $inReq-> fetch(PDO::FETCH_ASSOC))
		{
			$a_temp[$temp["ServicesnomService"]] = [$manip=>$temp["COUNT(*)"]];
		}
		return($a_temp);
	}   
	function extractReq2 ($intab,$inReq,$manip){ // recupère les valeurs des exp
		while ($temp2 =  $inReq-> fetch(PDO::FETCH_ASSOC))
		{
			$tempo=$intab[$temp2["ServicesnomService"]];
			array_push($tempo, [$manip=>$temp2["COUNT(*)"]]);
			$intab[$temp2["ServicesnomService"]] = $tempo;
		}
		return($intab);
	}
	function extractReq3 ($intab,$inReq){
		while ($temp2 =  $inReq-> fetch(PDO::FETCH_ASSOC))
		{
			$tempo=$intab[$temp2["ServicesnomService"]]; //stock le tableau des stats du service 
			$a_info= ["idEmploye" => $temp2["EmployesCompteUtilisateursidEmploye"], 
			"Patient" => $temp2["PatientnumSS"]];
			array_push($tempo,["Med_Patient-MultiUrgence"=>$a_info]);
			$intab[$temp2["ServicesnomService"]] = $tempo;
		}
		return($intab);
	}   
//****************************** REQUETE MySQL ******************************************
    $service =$bdd->query("SELECT nomService FROM Services"); // connaiter total interv meme si rdv = 0
// --- Entete = nb TOTAL : recupere toutes les lignes correspondant dans la bdd puis compte le nombre de ligne
    $totalInterv = $bdd-> query('SELECT * FROM CreneauxInterventions'); //total demandes tt services confondu
    $nb_totalInterv = $totalInterv->rowCount();
    $totalIntervUrg = $bdd-> query('SELECT * FROM CreneauxInterventions WHERE niveauUrgence != 0'); 
    $nb_totalIntervUrg = $totalIntervUrg->rowCount();
	$totalIncomp = $bdd-> query('SELECT * FROM CreneauxInterventions WHERE incompDecte != 0'); 
    $nb_totalIncomp =0;// $totalIncomp->rowCount();

// --- Detail PAR service
//
// Selectionne toute les intervention et nom de service d'un service # planning
    //$req_interParserv= $bdd-> prepare('SELECT interventionsidIntervention, ServicesnomService
    //                         FROM CreneauxInterventions NATURAL JOIN Interventions 
    //                         WHERE CreneauxInterventions.InterventionsidIntervention=
    //                         Interventions.idIntervention');
// Compte le nombre de ligne de la table creneau dont
//nom de service qui a fait la demande = ?

//TODO : mettre toutes les requetes dans un tableau . et executer le query sur le tableau
    $req_nbDemandeParService = $bdd -> query ('SELECT Employes.ServicesnomService, COUNT(*)
            FROM CreneauxInterventions NATURAL JOIN Employes
            WHERE CreneauxInterventions.EmployesCompteUtilisateursidEmploye =
            Employes.CompteUtilisateursidEmploye
			GROUP BY ServicesnomService');
// Compte le nombre de ligne = niveau > 0 et service qui a fait la demande = service
    $req_nbDemandeURGParService=$bdd-> query('SELECT Employes.ServicesnomService, COUNT(*)
            FROM CreneauxInterventions NATURAL JOIN Employes
            WHERE CreneauxInterventions.EmployesCompteUtilisateursidEmploye = Employes.CompteUtilisateursidEmploye
            AND niveauUrgence != 0
            GROUP BY Employes.ServicesnomService');
    $req_nbDemandeINCParService=$bdd ->query('SELECT Employes.ServicesnomService, COUNT(*)
            FROM CreneauxInterventions NATURAL JOIN Employes
            WHERE CreneauxInterventions.EmployesCompteUtilisateursidEmploye = Employes.CompteUtilisateursidEmploye
            AND incompDetect !=0
            GROUP BY Employes.ServicesnomService');
// retourne liste (medecin + patient) pour lequels il a plus d'une demande ( ligne avec h ou j dif)
// avec niveau urgent
    $reqMedPatient = $bdd ->query ('SELECT ServicesnomService, EmployesCompteUtilisateursidEmploye, PatientnumSS
            FROM Employes Natural JOIN CreneauxInterventions t1
            WHERE niveauUrgence != 0
            AND EXISTS (
            SELECT *
            FROM CreneauxInterventions t2
            WHERE t1.dateRdv <> t2.dateRdv
            OR   t1.heure <> t2.heure
            AND   t1.PatientnumSS = t2.PatientnumSS
            AND t1.EmployesCompteUtilisateursidEmploye = t2.EmployesCompteUtilisateursidEmploye)
			Group by EmployesCompteUtilisateursidEmploye');
$test=extractReq($req_nbDemandeParService,"nb_Interventions");
$test2=extractReq2($test,$req_nbDemandeURGParService,"nb_InterventionsUrgentes");
//$test3=extractReq2($test,$req_nbDemandeURGParService,"IncompatibilitePatho");
$test4=extractReq3($test2,$reqMedPatient);
Dumper ($test4);

//echo ("\n***\n"); \\ MANQUE ATTRIBUT SUR TABLE *** 
//var_dump($req_nbDemandeINCParService->fetch());
//echo ("\n***\n");


// EX. A MODIFIER POUR CODE 
//$req = $bdd->prepare('SELECT nom, prix FROM jeux_video WHERE possesseur = ?  AND prix <= ? ORDER BY prix');
//$req->execute(array($_GET['possesseur'], $_GET['prix_max']));


//****************************   SERVICES ***********************************************
$a_services =array(); // recupère les noms de services
//$a_services['Total'] = array('intervention'=>$nb_totalInterv, 'urgence'=> $nb_totalIntervUrg,'incompatibilite'=> $nb_totalIncomp);
$a=array();
while ($Services =  $service-> fetchColumn())
	{
		$a_services[$Services] = "$Services";
		array_push ($a,$Services); 
	}


// **************************  AFFICHAGE PAGE ********************************************   
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
    <?php include ('./Config/Menupage.php');?> 
<table>
	<?php foreach ($a_services as $service)
	{
	?>	<tr>
		<?php foreach ($col as $test2[$service])
		{
			echo  $test2[$service][$col];
		?>
		</tr>
	}
	}
	
	 <th> <?php ?></th> 
</table>	






    <p>
    <?php
        foreach ($a_services as  $Services=>$value)
        {
		?>
        <strong>Services</strong> : <?php echo( $Services); ?><br
       </p>
    
    <?php
        }
$service->closeCursor(); // Termine le traitement de la requête
?>

<!---->-->
<!--//-->
<!--//-->
<!--//function html_table($data = array())-->
<!--//{-->
<!--//    $rows = array();-->
<!--//    foreach ($data as $row) {-->
<!--//        $cells = array();-->
<!--//        foreach ($row as $cell) {-->
<!--//            $cells[] = "<td>{$cell}</td>";-->
<!--//        }-->
<!--//        $rows[] = "<tr>" . implode('', $cells) . "</tr>";-->
<!--//    }-->
<!--//    return "<table class='hci-table'>" . implode('', $rows) . "</table>";-->
<!--//}-->
<!--//-->
<!--//-->
<!--//echo html_table($test2);-->
<!---->
<!--//-->
<!--//    function build_table($array){-->
<!--//    // start table-->
<!--//    $html = '<table>';-->
<!--//    // header row-->
<!--//    $html .= '<tr>';-->
<!--//    foreach($array[0] as $key=>$value){-->
<!--//            $html .= '<th>' . htmlspecialchars($key) . '</th>';-->
<!--//        }-->
<!--//    $html .= '</tr>';-->
<!--//-->
<!--//    // data rows-->
<!--//    foreach( $array as $key=>$value){-->
<!--//        $html .= '<tr>';-->
<!--//        foreach($value as $key2=>$value2){-->
<!--//            $html .= '<td>' . htmlspecialchars($value2) . '</td>';-->
<!--//        }-->
<!--//        $html .= '</tr>';-->
<!--//    }-->
<!--//-->
<!--//    // finish table and return it-->
<!--//-->
<!--//    $html .= '</table>';-->
<!--//    return $html;-->
<!--//}-->
<!--//-->
<!--//$array=$test2;-->
<!--<!--//echo build_table($array);-->

</body>
</html>
