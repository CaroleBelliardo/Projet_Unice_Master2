<?php
//-- Notification de Surbooking **********************************************************************************			
					// recup horraire de fin de service reel pour le jour ou l'intervention demandée est insérée
    function Eval_notif_Surbooking ($auth_user,$idIntervention,$a_infoDateHeureUrgence,$a_horaireFermeture)
    {
        $req_heureFinJour = $auth_user->runQuery(" SELECT MAX(heure_rdv)
                                                    FROM CreneauxInterventions
                                                    WHERE InterventionsidIntervention = :idIntervention
                                                    AND  date_rdv= :date   "); 
        $req_heureFinJour->execute(array("idIntervention" => $idIntervention,
                                         "date" => $a_infoDateHeureUrgence["heureR"]));
        $a_horaireFermeture["horaire_reel"] = $req_heureFinJour->fetchColumn();
        $req_heureFinJour->closeCursor();
        
        // Enregistre la notif
                
        if ($a_horaireFermeture["horaire_reel"]> $a_horaireFermeture["horaire_fermeture"]) 
        {
            $req_notifService = $auth_user->runQuery(" INSERT INTO Notifications (CreneauxInterventionsidRdv, ServicesnomService, indication)
                                                                VALUES ( :idCreneau, :service, 'Surbooking')"); // Renseigne les valeurs de priorité = par default :0
            $req_notifService->execute(array('service'=> $horraireFermeture["Services.nomService"]));
            $req_notifService->closeCursor();
        }
    }              
  
  
// ************************************** NOTIFICATIONS UTLISATEURS *********************************
	// -- TEST CAS D'UTILISATION "VERIFICATION" admin
		// recup id creneau
    function Eval_notif_incompUrgence ($auth_user,$niveauUrgence,$a_niveauUrgence)
    {
    	$req_idCreneau= $auth_user->runQuery(" SELECT MAX( id_rdv) FROM CreneauxInterventions"); // Renseigne les valeurs de priorité = par default :0
		$req_idCreneau->execute();
		$idCreneau = $req_idCreneau->fetchColumn();
		$req_idCreneau->closeCursor();
		
		// Enregistre la notif
		if ($niveauUrgence > $a_niveauUrgence["niveauUrgenceMax"])
		{
			$req_notif = $auth_user->runQuery(" INSERT INTO Notifications (CreneauxInterventionsidRdv, ServicesnomService, indication)
																VALUES ( :idCreneau, 'Informatique', 'Max')"); // Renseigne les valeurs de priorité = par default :0
			$req_notif->execute(array('idCreneau'=> $idCreneau));
			$req_notif->closeCursor();
		}
		elseif ($niveauUrgence < $a_niveauUrgence["niveauUrgenceMin"])
		{
			$req_notif = $auth_user->runQuery(" INSERT INTO Notifications (CreneauxInterventionsidRdv, ServicesnomService, indication)
																VALUES ( :idCreneau, 'Informatique', 'Min')"); // Renseigne les valeurs de priorité = par default :0
			$req_notif->execute(array(	'idCreneau'=> $idCreneau));
			$req_notif->closeCursor();
		} 
    }
                    
?>