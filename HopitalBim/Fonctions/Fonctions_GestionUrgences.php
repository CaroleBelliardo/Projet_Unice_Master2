<?php
    function gestionUrgence($auth_user,$idIntervention,$niveauUrgence, $a_infoDateHeure,$a_horaireService)
    {

    // Si la demande respect un certain delais (specifique a chaque niveau d'urgence) alors on ne perturbe pas le planning et on insert le rdv à la suite du planning ou a la place d'un rdv annule
    // Sinon on place le rdv à la suite des rendez vous de même niveau et on décale les suivants au risque de dépasser les horraires d'ouverture du service
   
        $now=ProchaineHeureArrondie();  // si rdv = urgent -> determine le delais relatif au niveau d'urgence
        $daydelais = date('Y-m-d');
        
        switch ($niveauUrgence) // fixe un delais selon niveau urgence
        {
            case 3:
                $heureDelais=heurePlus15($now,'+180 minutes');
                if ($heureDelais < $now)
                {
                    $dateDelais = date('Y-m-d', strtotime('+1 day'));
                }else {$dateDelais = date('Y-m-d');}
                break;//alors on insert à la suite
            
            case 2:
                $heureDelais=heurePlus15($now,'+360 minutes');
                if ($heureDelais < $now)
                {
                    $dateDelais = date('Y-m-d', strtotime('+1 day'));
                }else {$daydelais= date('Y-m-d');}
                break;
        
            case 1:
                $daydelais = date('Y-m-d', strtotime('+1 day'));
                $heureDelais=$now;
                break;
        }

		// delais pas respecté mais le service est fermé 
        if (($a_infoDateHeure["dateR"] >  $daydelais  )
            or (($a_infoDateHeure["dateR"] ==  $daydelais ) and  ($a_infoDateHeure["heureR"] > $heureDelais))) // si premier creneau dispo est hors delais on recherche un autre creneaux dont rdv < urgent et on decale les rendez-vous suivant
        { 
            $a_infoDateHeureUrgence=CreneauxUrgent($auth_user,$niveauUrgence,$idIntervention ); 

        //-- Recherche le dernier creneau dont niveau d'urgence >= au niveau d'urgence
            if (array_key_exists('heureR',$a_infoDateHeureUrgence))
            {
                //$a_infoDateHeureUrgence = ["dateR"=>" ", "heureR"=>" ",  "statutR"=>"p",    "niveauUrgenceR"=>$nivUrg];
                if ($a_infoDateHeureUrgence['statutR'] != 'a')
                { 
                    $a_infoDateHeure["heureR"]= heurePlus15($a_infoDateHeureUrgence["heureR"],'+15 minutes');
                    $a_infoDateHeure["dateR"]= $a_infoDateHeureUrgence["dateR"];	
                }
                else
                {
                    $a_infoDateHeure["heureR"]= $a_infoDateHeureUrgence["heureR"];
                    $a_infoDateHeure["dateR"]= $a_infoDateHeureUrgence["dateR"];	
                } 
            }
            else
            {
               if ($now > $a_horaireService['horaire_fermeture'])
               { 
                    $a_infoDateHeure['dateR']= date('Y-m-d', strtotime('+1 day')); /// $daydelais + 1 !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                    $a_infoDateHeure['heureR']= $a_horaireService['horaire_ouverture'];
               }
               elseif ($now <  $a_horaireService['horaire_ouverture'])
               { 
                    $a_infoDateHeure['dateR']= $daydelais; 
                    $a_infoDateHeure['heureR']= $a_horaireService['horaire_ouverture'];
               }
               else
               {
				   
                    $a_infoDateHeure['dateR']= $daydelais; 
                    $a_infoDateHeure['heureR']= $now;
                }
            }
	
            
            if ($a_infoDateHeure['heureR'] == '00:00')
            {
                $a_infoDateHeure['dateR']= date('Y-m-d', strtotime('+1 day'));
            }
			
        //-- Decale rdv suivant  SI niveau urgence != 0
            //recupere tous les creneaux suivant
            $req_CreneauSuivant = $auth_user->runQuery(" Select id_rdv, heure_rdv, statut FROM CreneauxInterventions 
                                                    WHERE InterventionsidIntervention = :idIntervention
                                                    AND date_rdv = :date
                                                    AND heure_rdv >= :heure" ); 
            $req_CreneauSuivant->execute(array('idIntervention'=> $idIntervention,
                                                'date'=> $a_infoDateHeure["dateR"] ,
                                                'heure'=> $a_infoDateHeure["heureR"]));
            
            $a_creneauSuiv= reqToArrayPlusligne($req_CreneauSuivant);
            $req_CreneauSuivant->closeCursor(); 
        
        // upDate toutes les heures suivantes 
            if (array_key_exists("id_rdv",$a_creneauSuiv))
            {
            $req_upDateHoraire = $auth_user->runQuery(" UPDATE CreneauxInterventions
                                                        SET heure_rdv = :newHeure,
                                                        niveauUrgence = (niveauUrgence + '1') 
                                                        WHERE id_rdv = :id_rdv" );
                foreach($a_creneauSuiv["id_rdv"] as $k=>$v) /////  ???  faire le while sur le fetch ??????????????????????????????????????
                {
                    
                    if ($a_creneauSuiv["statut"][$k]  == 'a')
                    {
                        break;
                    }
                    else
                    {
                        $newHeure= heurePlus15($a_creneauSuiv["heure_rdv"][$k],'+15 minutes');
                        $req_upDateHoraire->execute(array("id_rdv" => $a_creneauSuiv["id_rdv"][$k],
                                                          "newHeure" =>$newHeure
                                                          ));
                        $req_upDateHoraire->closeCursor();
                    }
                }
            }// fin d'instruction si dispo = hors delais
         // puis on test s'il y a surbooking	
        }
	
    return ($a_infoDateHeure);
    }
?>