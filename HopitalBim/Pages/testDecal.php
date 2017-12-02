<?php
    include ('../Config/Menupage.php'); //menu de navigation
    include ('../Fonctions/RDVDemande.php'); // fonctions specifiques à demande RDV

    $req_CreneauSuivant = $auth_user->runQuery(" Select * FROM CreneauxInterventions
														WHERE InterventionsidIntervention = '4'
														AND date_rdv ='2017-12-18' 
														AND heure_rdv >'11:00'" ); 
    $req_CreneauSuivant->execute();
	$a_creneauSuiv= reqToArrayPlusligne($req_CreneauSuivant);
	$req_CreneauSuivant->closeCursor();
	Dumper($a_creneauSuiv);
    
    foreach($a_creneauSuiv["id_rdv"] as $k=>$v)
    {
        if ($a_creneauSuiv["statut"][$k]  == 'a')
        {
            
            break;
        }
        else
        {
            $a_creneauSuiv["heure_rdv"][$k] = heurePlus15($a_creneauSuiv["heure_rdv"][$k],'+15 minutes');
        }
    }
    echo ";;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;<br>";
    Dumper($a_creneauSuiv);
?>					