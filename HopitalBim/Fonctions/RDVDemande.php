<?php 	// Fonctions
    
	function ProchaineHeureArrondie() // Recuperer l'heure actuelle et de arrondie l'heure à la quinzaine 
    { 
        $current_time = strtotime(date('H:i')); // recupere l'heure actuelle sous format hh:mm
        $frac = 900;                            // pour definir le creneaux horaire :  15 min x 60 sec = 900 
        $r = $current_time % $frac;             // calcul le nombre de minutes necessaire a ajouter.
        $heureArrondie = date('H:i',($current_time + ($frac-$r))); // calcule combien de minute il faut ajouter pour passer au prochain creneaux de 15 mins
        return ($heureArrondie); // retourne la prochaine heure arrondie
    }

    function heurePlus15($h,$temps) // prend en entrée une string h:m et renvoye l'heure + 15minutes
	{
		$heure = strtotime($h);
		$heurePlus15 =date("H:i", strtotime( $temps,$heure));
		return ($heurePlus15);
	}

    function prochainCreneauxDispo($auth_user,$idInterv) // recherche le dernier creneau occupé ou le premier creneau annulé 
    {
	$req_infoDateHeure = $auth_user->runQuery(" SELECT MIN(dateR), MIN(heureR),MIN(statutR), MIN(idR)
                FROM  (
					SELECT MAX(dateR1) as dateR, MAX(heureR1) as heureR, statutR1 as statutR, idR1 as idR
					FROM  (
						(SELECT MAX(date_rdv) as dateR1, MAX(heure_rdv)  as heureR1, CreneauxInterventions.statut as statutR1, CreneauxInterventions.id_rdv as idR1       
						FROM CreneauxInterventions JOIN Interventions  JOIN Services 
						WHERE  CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
						AND Interventions.ServicesnomService = Services.nomService
						AND date_rdv = CURDATE() 
						AND heure_rdv > CURRENT_TIMESTAMP()
						AND InterventionsidIntervention = 5
						AND CreneauxInterventions.statut = 'p'
						AND heure_rdv >= (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = 5 AND Interventions.ServicesnomService = Services.nomService)
						AND heure_rdv < (SELECT horaire_fermeture FROM Interventions  JOIN Services WHERE idIntervention = 5  AND Interventions.ServicesnomService = Services.nomService)
					) 
					UNION
						(SELECT  MAX(date_rdv) as dateR1, MAX(heure_rdv)  as heureR1, CreneauxInterventions.statut as statutR1, CreneauxInterventions.id_rdv as idR1        
						FROM CreneauxInterventions JOIN Interventions  JOIN Services 
						WHERE  CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
						AND Interventions.ServicesnomService = Services.nomService
						AND date_rdv > CURDATE() 
						AND InterventionsidIntervention = 5
						AND CreneauxInterventions.statut = 'p'
						AND heure_rdv >= (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = 5 AND Interventions.ServicesnomService = Services.nomService)
						AND heure_rdv < (SELECT horaire_fermeture FROM Interventions  JOIN Services WHERE idIntervention = 5  AND Interventions.ServicesnomService = Services.nomService)
                ) )as d1
                UNION
					(SELECT MIN(date_rdv) as dateR, MIN(heure_rdv)  as heureR, CreneauxInterventions.statut as statutR, CreneauxInterventions.id_rdv as idR      
					FROM CreneauxInterventions JOIN Interventions  JOIN Services 
					WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
					AND Interventions.ServicesnomService = Services.nomService
					AND date_rdv = CURDATE()
					AND heure_rdv > CURRENT_TIMESTAMP() 
					AND CreneauxInterventions.statut = 'a'
					AND InterventionsidIntervention = 5
					AND heure_rdv >= (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = 5 AND Interventions.ServicesnomService = Services.nomService)
					AND heure_rdv < (SELECT horaire_fermeture FROM Interventions  JOIN Services WHERE idIntervention = 5  AND Interventions.ServicesnomService = Services.nomService)
                )
                UNION
					(SELECT MIN(date_rdv) as dateR, MIN(heure_rdv)  as heureR, CreneauxInterventions.statut as statutR, CreneauxInterventions.id_rdv as idR
					FROM CreneauxInterventions JOIN Interventions  JOIN Services 
					WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
					AND Interventions.ServicesnomService = Services.nomService
					AND date_rdv > CURDATE()
					AND CreneauxInterventions.statut = 'a'
					AND InterventionsidIntervention = 5
					AND heure_rdv >= (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = 5 AND Interventions.ServicesnomService = Services.nomService)
					AND heure_rdv < (SELECT horaire_fermeture FROM Interventions  JOIN Services WHERE idIntervention = 5  AND Interventions.ServicesnomService = Services.nomService)
                ) 
			) as d
		");
		$req_infoDateHeure->execute(array('idIntervention' => $idInterv)); // modifier variables
		$a_infoDateHeure = reqToArrayPlusAttASSO($req_infoDateHeure); // retourne : [ MIN(dateR), MIN(heureR), statutR, idR ] heure = dernier rdv prevu ou premier rdv annulé 
		$req_infoDateHeure->closeCursor();
        return ($a_infoDateHeure);
    }
    
    function CreneauxUrgent($auth_user, $nivUrg, $idInterv) // recherche le dernier creneau occupé ou le premier creneau annulé avec nivUrgence 
    {
        $req_infoDateHeureUrg = $auth_user->runQuery("SELECT MIN(dateR), MIN(heureR),min(statutR), min(idR)
                    FROM  (
                    SELECT MAX(dateR1) as dateR, MAX(heureR1) as heureR, statutR1 as statutR, idR1 as idR
                    FROM  (
                    (SELECT max(date_rdv) as dateR1, max(heure_rdv)  as heureR1, CreneauxInterventions.statut as statutR1, CreneauxInterventions.id_rdv as idR1       
                    FROM CreneauxInterventions JOIN Interventions  JOIN Services 
                    WHERE  CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
                    AND Interventions.ServicesnomService = Services.nomService
                    AND date_rdv = CURDATE() 
                    AND heure_rdv > CURRENT_TIMESTAMP()
                    AND InterventionsidIntervention = :idIntervention
                    AND CreneauxInterventions.statut = 'p'
                    AND CreneauxInterventions.niveauUrgence >= :niveauUrgence
                    AND heure_rdv >= (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = :idIntervention AND Interventions.ServicesnomService = Services.nomService)
                        ) 
                    UNION
                    (SELECT  max(date_rdv) as dateR1, max(heure_rdv)  as heureR1, CreneauxInterventions.statut as statutR1, CreneauxInterventions.id_rdv as idR1        
                    FROM CreneauxInterventions JOIN Interventions  JOIN Services 
                    WHERE  CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
                    AND Interventions.ServicesnomService = Services.nomService
                    AND date_rdv > CURDATE() 
                    AND InterventionsidIntervention = :idIntervention
                    AND CreneauxInterventions.statut = 'p'
                    AND CreneauxInterventions.niveauUrgence >= :niveauUrgence
                    AND heure_rdv >= (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = :idIntervention AND Interventions.ServicesnomService = Services.nomService)
                    ) )as d1
                    UNION
                    (SELECT MIN(date_rdv) as dateR, MIN(heure_rdv)  as heureR, CreneauxInterventions.statut as statutR, CreneauxInterventions.id_rdv as idR      
                    FROM CreneauxInterventions JOIN Interventions  JOIN Services 
                    WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
                    AND Interventions.ServicesnomService = Services.nomService
                    AND date_rdv = CURDATE() 
                    AND heure_rdv > CURRENT_TIMESTAMP() 
                    AND CreneauxInterventions.statut = 'a'
                    AND InterventionsidIntervention = :idIntervention
                    AND heure_rdv >= (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = :idIntervention AND Interventions.ServicesnomService = Services.nomService)
                    )
                    UNION
                    (SELECT MIN(date_rdv) as dateR, MIN(heure_rdv)  as heureR, CreneauxInterventions.statut as statutR, CreneauxInterventions.id_rdv as idR
                    FROM CreneauxInterventions JOIN Interventions  JOIN Services 
                    WHERE CreneauxInterventions.InterventionsidIntervention = Interventions.idIntervention
                    AND Interventions.ServicesnomService = Services.nomService
                    AND date_rdv > CURDATE() 
                    AND CreneauxInterventions.statut = 'a'
                    AND InterventionsidIntervention = :idIntervention
                    AND heure_rdv >= (SELECT horaire_ouverture FROM Interventions  JOIN Services WHERE idIntervention = :idIntervention AND Interventions.ServicesnomService = Services.nomService)
                    ) 
                ) as d
            ");
        $req_infoDateHeureUrg->execute(array( 'niveauUrgence'=> $nivUrg,
											 'idIntervention'=>$idInterv)); // modifier variables
		$a_infoDateHeureUrg = reqToArrayPlusAttASSO($req_infoDateHeureUrg); // retourne : [ MIN(dateR), MIN(heureR), statutR, idR ] heure = dernier rdv prevu ou premier rdv annulé 
		$req_infoDateHeureUrg->closeCursor();
        return ($a_infoDateHeureUrg);
    }
    
    function prochainCreneauxUrgent($auth_user,$niveauUrgence, $idIntervention )  // fonction qui test tous les niveaux d'urgence jusqu'a trouver un creneaux compatible
    {
        $a_infoDateHeureUrgence["MIN(dateR)"]=null; //initialisation
        for ($i=$niveauUrgence; $i>=0; $i--)
        {
            //echo "etape :".$i."<br>";
			if ($a_infoDateHeureUrgence["MIN(dateR)"] != null )
            {
                 return ($a_infoDateHeureUrgence);
            }
            else 
            {
                $a_infoDateHeureUrgence= CreneauxUrgent($auth_user, $niveauUrgence, $idIntervention);
            }
        }
    }
	
	//
	//function VerificationPathologie ($auth_user,$niveauUrgence ) 
	//{
	//	$recupUrgMaxMin=$auth_user->runQuery("SELECT niveauUrgenceMax, niveauUrgenceMin
	//									FROM InterventionsPatho  
	//									WHERE PathologiesidPatho =:idPatho 
	//									AND InterventionsidIntervention=:idIntervention "); 
	//	$recupUrgMaxMin->execute(array('idPatho'=>$idPatho,
	//								'idIntervention'=>$idIntervention));
	//
	//	$var=$recupUrgMaxMin->fetch(PDO::FETCH_ASSOC);
	//return ($var);
	//}
    
?>